<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Aws\S3\S3Client;

class VideoController extends Controller
{
    /**
     * Display a listing of videos.
     */
    public function index()
    {
        try {
            Log::info('Admin videos index - Fetching videos', [
                'total_videos_in_db' => Video::count(),
            ]);
            
            // Use leftJoin to safely handle missing categories
            $videos = Video::leftJoin('categories', 'videos.category_id', '=', 'categories.id')
                ->select('videos.*', 'categories.name as category_name')
                ->latest('videos.created_at')
                ->paginate(15);
            
            // Transform to ensure created_at is Carbon and handle category_name
            $videos->getCollection()->transform(function ($video) {
                try {
                    // Ensure category_name is set (from join or fallback)
                    if (empty($video->category_name)) {
                        $video->category_name = 'No Category';
                    }
                } catch (\Exception $e) {
                    Log::warning('Error processing category_name for video', [
                        'video_id' => $video->id,
                        'error' => $e->getMessage()
                    ]);
                    $video->category_name = 'No Category';
                }
                
                try {
                    if ($video->created_at && !($video->created_at instanceof \Carbon\Carbon)) {
                        $video->created_at = \Carbon\Carbon::parse($video->created_at);
                    }
                } catch (\Exception $e) {
                    Log::warning('Error parsing created_at for video', [
                        'video_id' => $video->id,
                        'created_at' => $video->created_at,
                        'error' => $e->getMessage()
                    ]);
                }
                
                return $video;
            });
            
            Log::info('Admin videos index - Videos fetched', [
                'paginated_count' => $videos->count(),
                'total_pages' => $videos->lastPage(),
                'current_page' => $videos->currentPage(),
            ]);
            
            return view('admin.videos.index', compact('videos'));
        } catch (\Exception $e) {
            Log::error('Error in admin videos index', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Fallback: try without category relation
            try {
                $videos = Video::latest('created_at')->paginate(15);
                $videos->getCollection()->transform(function ($video) {
                    try {
                        $video->category_name = 'No Category';
                    } catch (\Exception $e) {
                        $video->category_name = 'No Category';
                    }
                    
                    try {
                        if ($video->created_at && !($video->created_at instanceof \Carbon\Carbon)) {
                            $video->created_at = \Carbon\Carbon::parse($video->created_at);
                        }
                    } catch (\Exception $e) {
                        // Ignore parsing errors
                    }
                    
                    return $video;
                });
                return view('admin.videos.index', compact('videos'));
            } catch (\Exception $e2) {
                Log::error('Complete failure in admin videos index', [
                    'error' => $e2->getMessage(),
                    'file' => $e2->getFile(),
                    'line' => $e2->getLine(),
                ]);
                abort(500, 'Unable to load videos. Please check the logs.');
            }
        }
    }

    /**
     * Debug endpoint to check videos in database.
     */
    public function debug()
    {
        $videos = Video::with('category')->latest()->get();
        
        return response()->json([
            'total_videos' => Video::count(),
            'videos_fetched' => $videos->count(),
            'videos' => $videos->map(function($v) {
                return [
                    'id' => $v->id,
                    'title' => $v->title,
                    'video_path' => $v->video_path,
                    'category_id' => $v->category_id,
                    'category_name' => $v->category ? $v->category->name : 'NULL',
                    'has_category' => $v->category ? true : false,
                    'created_at' => $v->created_at->toDateTimeString(),
                ];
            })
        ]);
    }

    /**
     * Show the form for creating a new video.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.videos.create', compact('categories'));
    }

    /**
     * Store a newly created video.
     */
    public function store(Request $request)
{
    // #region agent log
    $logData = [
        'sessionId' => 'debug-session',
        'runId' => 'run1',
        'hypothesisId' => 'ALL',
        'location' => 'VideoController.php:154',
        'message' => 'store() method entry',
        'data' => [
            'has_video' => $request->hasFile('video'),
            'has_thumbnail' => $request->hasFile('thumbnail'),
        ],
        'timestamp' => time() * 1000
    ];
    $logFile = base_path('.cursor/debug.log');
    $logDir = base_path('.cursor');
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    $logEntry = json_encode($logData) . "\n";
    $writeResult = @file_put_contents($logFile, $logEntry, FILE_APPEND);
    Log::info('DEBUG: store() method entry', array_merge($logData['data'], ['log_write_result' => $writeResult, 'log_file' => $logFile]));
    // #endregion

    // Check PHP upload configuration before validation
    $uploadMaxSize = ini_get('upload_max_filesize');
    $postMaxSize = ini_get('post_max_size');
    $maxExecutionTime = ini_get('max_execution_time');
    $memoryLimit = ini_get('memory_limit');
    
    Log::info('PHP upload configuration', [
        'upload_max_filesize' => $uploadMaxSize,
        'post_max_size' => $postMaxSize,
        'max_execution_time' => $maxExecutionTime,
        'memory_limit' => $memoryLimit,
    ]);
    
    // Check if file was actually uploaded
    if (!$request->hasFile('video')) {
        Log::error('No video file in request');
        return back()
            ->withInput()
            ->with('error', 'No video file received. Please check PHP upload settings.');
    }
    
    $videoFile = $request->file('video');
    
    // Check for upload errors
    if ($videoFile->getError() !== UPLOAD_ERR_OK) {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize (' . $uploadMaxSize . ')',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload',
        ];
        
        $errorCode = $videoFile->getError();
        $errorMessage = $errorMessages[$errorCode] ?? 'Unknown upload error (code: ' . $errorCode . ')';
        
        Log::error('File upload error', [
            'error_code' => $errorCode,
            'error_message' => $errorMessage,
            'file_name' => $videoFile->getClientOriginalName(),
            'file_size' => $videoFile->getSize(),
        ]);
        
        return back()
            ->withInput()
            ->with('error', 'Upload failed: ' . $errorMessage);
    }
    
    // Check if file is valid
    if (!$videoFile->isValid()) {
        Log::error('Uploaded file is not valid', [
            'error' => $videoFile->getErrorMessage(),
            'file_name' => $videoFile->getClientOriginalName(),
        ]);
        
        return back()
            ->withInput()
            ->with('error', 'Uploaded file is not valid: ' . $videoFile->getErrorMessage());
    }

    // #region agent log
    $logEntry = json_encode([
        'sessionId' => 'debug-session',
        'runId' => 'run1',
        'hypothesisId' => 'ALL',
        'location' => 'VideoController.php:218',
        'message' => 'Before validation',
        'data' => [
            'title' => $request->input('title'),
            'category_id' => $request->input('category_id'),
            'video_file_name' => $request->hasFile('video') ? $request->file('video')->getClientOriginalName() : 'none',
            'video_file_size' => $request->hasFile('video') ? $request->file('video')->getSize() : 0,
        ],
        'timestamp' => time() * 1000
    ]) . "\n";
    @file_put_contents($logFile, $logEntry, FILE_APPEND);
    // #endregion

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'video' => 'required|file|mimes:mp4,mov,webm,avi|max:5242880',
        'thumbnail' => 'nullable|image|max:10240',
        'category_id' => 'required|exists:categories,id',
        'is_premium' => 'boolean',
    ]);

    // #region agent log
    $logEntry = json_encode([
        'sessionId' => 'debug-session',
        'runId' => 'run1',
        'hypothesisId' => 'ALL',
        'location' => 'VideoController.php:235',
        'message' => 'After validation, entering try block',
        'data' => ['validated' => true],
        'timestamp' => time() * 1000
    ]) . "\n";
    @file_put_contents($logFile, $logEntry, FILE_APPEND);
    // #endregion

    try {
        // #region agent log
        $logFile = base_path('.cursor/debug.log');
        $logEntry = json_encode([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'A',
            'location' => 'VideoController.php:227',
            'message' => 'Entering video upload try block',
            'data' => [
                'file_size' => $videoFile->getSize(),
                'real_path' => $videoFile->getRealPath(),
                'is_valid' => $videoFile->isValid(),
                'extension' => $videoFile->getClientOriginalExtension(),
            ],
            'timestamp' => time() * 1000
        ]) . "\n";
        @file_put_contents($logFile, $logEntry, FILE_APPEND);
        // #endregion

        // Check S3 configuration (without exposing secrets)
        // #region agent log
        $s3Config = config('filesystems.disks.s3');
        $s3ConfigData = [
            'driver' => $s3Config['driver'] ?? 'missing',
            'region' => $s3Config['region'] ?? 'missing',
            'bucket' => $s3Config['bucket'] ?? 'missing',
            'has_key' => !empty($s3Config['key']),
            'has_secret' => !empty($s3Config['secret']),
            'endpoint' => $s3Config['endpoint'] ?? 'not_set',
        ];
        $logEntry = json_encode([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'A',
            'location' => 'VideoController.php:245',
            'message' => 'S3 configuration check',
            'data' => $s3ConfigData,
            'timestamp' => time() * 1000
        ]) . "\n";
        @file_put_contents($logFile, $logEntry, FILE_APPEND);
        Log::info('DEBUG: S3 configuration check', $s3ConfigData);
        // #endregion

        // Test S3 connection before upload
        // #region agent log
        $s3ConnectionTestData = ['test_result' => false, 'error' => null];
        try {
            $testFile = 'test-connection-' . time() . '.txt';
            $testResult = Storage::disk('s3')->put($testFile, 'test');
            $s3ConnectionTestData = [
                'test_result' => $testResult,
                'test_result_type' => gettype($testResult),
                'connection_success' => ($testResult !== false),
            ];
            if ($testResult) {
                Storage::disk('s3')->delete($testFile);
            }
        } catch (\Exception $e) {
            $s3ConnectionTestData['error'] = $e->getMessage();
        }
        $logEntry = json_encode([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'D',
            'location' => 'VideoController.php:265',
            'message' => 'S3 connection test',
            'data' => $s3ConnectionTestData,
            'timestamp' => time() * 1000
        ]) . "\n";
        @file_put_contents($logFile, $logEntry, FILE_APPEND);
        Log::info('DEBUG: S3 connection test', $s3ConnectionTestData);
        error_log('DEBUG S3 connection test: ' . json_encode($s3ConnectionTestData));
        // #endregion

        // ✅ VIDEO UPLOAD
        $videoName = Str::uuid() . '.' . $videoFile->getClientOriginalExtension();
        $videoPath = 'videos/' . $videoName;

        Log::info('Starting S3 upload', [
            'filename' => $videoName,
            'full_path' => $videoPath,
            'file_size' => $videoFile->getSize(),
            'real_path' => $videoFile->getRealPath(),
            'is_valid' => $videoFile->isValid(),
        ]);

        // #region agent log
        $beforePutFileAsData = [
            'video_name' => $videoName,
            'video_path' => $videoPath,
            'directory' => 'videos',
            'file_exists' => file_exists($videoFile->getRealPath()),
            'file_readable' => is_readable($videoFile->getRealPath()),
        ];
        $logEntry = json_encode([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'B',
            'location' => 'VideoController.php:285',
            'message' => 'Before putFileAs call',
            'data' => $beforePutFileAsData,
            'timestamp' => time() * 1000
        ]) . "\n";
        @file_put_contents($logFile, $logEntry, FILE_APPEND);
        Log::info('DEBUG: Before putFileAs call', $beforePutFileAsData);
        // #endregion

        // Try putFileAs first
        // Temporarily enable throwing to see actual errors
        $s3Disk = Storage::disk('s3');
        $originalThrow = config('filesystems.disks.s3.throw', false);
        config(['filesystems.disks.s3.throw' => true]);
        
        try {
            $uploadedPath = $s3Disk->putFileAs(
                'videos',
                $videoFile,
                $videoName
            );
        } catch (\Exception $putFileAsException) {
            Log::error('DEBUG: putFileAs threw exception', [
                'error' => $putFileAsException->getMessage(),
                'file' => $putFileAsException->getFile(),
                'line' => $putFileAsException->getLine(),
                'trace' => substr($putFileAsException->getTraceAsString(), 0, 1000),
            ]);
            error_log('DEBUG putFileAs exception: ' . $putFileAsException->getMessage());
            $uploadedPath = false;
        } finally {
            // Restore original setting
            config(['filesystems.disks.s3.throw' => $originalThrow]);
        }

        // #region agent log
        $afterPutFileAsData = [
            'result' => $uploadedPath,
            'result_type' => gettype($uploadedPath),
            'is_false' => ($uploadedPath === false),
            'is_empty' => empty($uploadedPath),
            'is_string' => is_string($uploadedPath),
            'result_length' => is_string($uploadedPath) ? strlen($uploadedPath) : 0,
        ];
        $logEntry = json_encode([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'B',
            'location' => 'VideoController.php:295',
            'message' => 'After putFileAs call',
            'data' => $afterPutFileAsData,
            'timestamp' => time() * 1000
        ]) . "\n";
        @file_put_contents($logFile, $logEntry, FILE_APPEND);
        Log::info('DEBUG: After putFileAs call', $afterPutFileAsData);
        // #endregion

        Log::info('putFileAs result', [
            'result' => $uploadedPath,
            'type' => gettype($uploadedPath),
            'is_false' => ($uploadedPath === false),
            'is_empty' => empty($uploadedPath),
        ]);

        // If putFileAs returns false, try fallback with streaming upload
        if (!$uploadedPath || $uploadedPath === false) {
            Log::warning('putFileAs returned false, trying fallback method with streaming upload');
            
            // #region agent log
            $logEntry = json_encode([
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'C',
                'location' => 'VideoController.php:310',
                'message' => 'Entering fallback method',
                'data' => [
                    'file_size_bytes' => $videoFile->getSize(),
                    'file_size_mb' => round($videoFile->getSize() / (1024 * 1024), 2),
                    'memory_limit' => ini_get('memory_limit'),
                    'memory_usage' => memory_get_usage(true),
                ],
                'timestamp' => time() * 1000
            ]) . "\n";
            @file_put_contents($logFile, $logEntry, FILE_APPEND);
            // #endregion
            
            try {
                // Use S3 client directly with streaming for large files
                // Create S3 client directly using AWS SDK
                $s3Config = config('filesystems.disks.s3');
                
                // #region agent log
                $logFile = base_path('.cursor/debug.log');
                $logEntry = json_encode([
                    'sessionId' => 'debug-session',
                    'runId' => 'run1',
                    'hypothesisId' => 'A',
                    'location' => 'VideoController.php:470',
                    'message' => 'S3 config read from config()',
                    'data' => [
                        'bucket' => $s3Config['bucket'] ?? 'NOT_SET',
                        'bucket_type' => gettype($s3Config['bucket'] ?? null),
                        'bucket_empty' => empty($s3Config['bucket'] ?? null),
                        'bucket_is_null' => ($s3Config['bucket'] ?? null) === null,
                        'has_region' => !empty($s3Config['region'] ?? null),
                        'has_key' => !empty($s3Config['key'] ?? null),
                        'has_secret' => !empty($s3Config['secret'] ?? null),
                    ],
                    'timestamp' => time() * 1000
                ]) . "\n";
                @file_put_contents($logFile, $logEntry, FILE_APPEND);
                // #endregion
                
                $s3Client = new S3Client([
                    'version' => 'latest',
                    'region' => $s3Config['region'],
                    'credentials' => [
                        'key' => $s3Config['key'],
                        'secret' => $s3Config['secret'],
                    ],
                    'endpoint' => $s3Config['endpoint'] ?? null,
                    'use_path_style_endpoint' => $s3Config['use_path_style_endpoint'] ?? false,
                ]);
                $bucket = $s3Config['bucket'];
                
                // #region agent log
                $logEntry = json_encode([
                    'sessionId' => 'debug-session',
                    'runId' => 'run1',
                    'hypothesisId' => 'B',
                    'location' => 'VideoController.php:481',
                    'message' => 'Bucket value before putObject',
                    'data' => [
                        'bucket' => $bucket,
                        'bucket_type' => gettype($bucket),
                        'bucket_empty' => empty($bucket),
                        'bucket_is_null' => $bucket === null,
                        'bucket_length' => is_string($bucket) ? strlen($bucket) : 0,
                    ],
                    'timestamp' => time() * 1000
                ]) . "\n";
                @file_put_contents($logFile, $logEntry, FILE_APPEND);
                // #endregion
                
                // #region agent log
                $logEntry = json_encode([
                    'sessionId' => 'debug-session',
                    'runId' => 'run1',
                    'hypothesisId' => 'C',
                    'location' => 'VideoController.php:325',
                    'message' => 'Before streaming upload',
                    'data' => [
                        'real_path' => $videoFile->getRealPath(),
                        'file_exists' => file_exists($videoFile->getRealPath()),
                        'file_size' => $videoFile->getSize(),
                    ],
                    'timestamp' => time() * 1000
                ]) . "\n";
                @file_put_contents($logFile, $logEntry, FILE_APPEND);
                // #endregion

                // Open file as stream instead of loading into memory
                $fileStream = fopen($videoFile->getRealPath(), 'rb');
                
                if ($fileStream === false) {
                    throw new \Exception('Could not open video file for reading: ' . $videoFile->getRealPath());
                }
                
                Log::info('DEBUG: Starting S3 streaming upload', [
                    'video_path' => $videoPath,
                    'file_size' => $videoFile->getSize(),
                ]);
                
                // #region agent log
                $logEntry = json_encode([
                    'sessionId' => 'debug-session',
                    'runId' => 'run1',
                    'hypothesisId' => 'D',
                    'location' => 'VideoController.php:513',
                    'message' => 'About to call putObject',
                    'data' => [
                        'bucket' => $bucket,
                        'bucket_empty' => empty($bucket),
                        'video_path' => $videoPath,
                        'has_file_stream' => is_resource($fileStream),
                    ],
                    'timestamp' => time() * 1000
                ]) . "\n";
                @file_put_contents($logFile, $logEntry, FILE_APPEND);
                // #endregion
                
                // Use putObject with stream for large files
                $result = $s3Client->putObject([
                    'Bucket' => $bucket,
                    'Key' => $videoPath,
                    'Body' => $fileStream,
                    'ACL' => 'private',
                    'ContentType' => $videoFile->getMimeType(),
                ]);
                
                fclose($fileStream);
                
                // Check if upload was successful
                if (isset($result['ObjectURL']) || isset($result['ETag'])) {
                    $uploadedPath = $videoPath;
                    
                    // #region agent log
                    $logEntry = json_encode([
                        'sessionId' => 'debug-session',
                        'runId' => 'run1',
                        'hypothesisId' => 'E',
                        'location' => 'VideoController.php:360',
                        'message' => 'After streaming upload',
                        'data' => [
                            'result' => $uploadedPath,
                            'etag' => $result['ETag'] ?? 'N/A',
                        ],
                        'timestamp' => time() * 1000
                    ]) . "\n";
                    @file_put_contents($logFile, $logEntry, FILE_APPEND);
                    Log::info('DEBUG: After streaming upload', ['path' => $uploadedPath, 'etag' => $result['ETag'] ?? 'N/A']);
                    // #endregion
                    
                    Log::info('Streaming upload succeeded', ['path' => $uploadedPath]);
                } else {
                    throw new \Exception('S3 putObject did not return expected result');
                }
                
            } catch (\Exception $fallbackException) {
                // #region agent log
                $logEntry = json_encode([
                    'sessionId' => 'debug-session',
                    'runId' => 'run1',
                    'hypothesisId' => 'E',
                    'location' => 'VideoController.php:375',
                    'message' => 'Fallback exception caught',
                    'data' => [
                        'error' => $fallbackException->getMessage(),
                        'file' => $fallbackException->getFile(),
                        'line' => $fallbackException->getLine(),
                        'trace' => substr($fallbackException->getTraceAsString(), 0, 500),
                    ],
                    'timestamp' => time() * 1000
                ]) . "\n";
                @file_put_contents($logFile, $logEntry, FILE_APPEND);
                // #endregion

                Log::error('DEBUG: Streaming upload failed', [
                    'error' => $fallbackException->getMessage(),
                    'file' => $fallbackException->getFile(),
                    'line' => $fallbackException->getLine(),
                    'trace' => substr($fallbackException->getTraceAsString(), 0, 1000),
                ]);
                
                // Last resort: try multipart upload for very large files
                if ($videoFile->getSize() > 50 * 1024 * 1024) { // > 50MB
                    try {
                        Log::info('DEBUG: Attempting multipart upload for large file');
                        $uploadedPath = $this->uploadLargeFileToS3($videoFile, $videoPath);
                        if ($uploadedPath) {
                            Log::info('DEBUG: Multipart upload succeeded', ['path' => $uploadedPath]);
                        } else {
                            throw new \Exception('Multipart upload also failed');
                        }
                    } catch (\Exception $multipartException) {
                        Log::error('DEBUG: Multipart upload failed', [
                            'error' => $multipartException->getMessage(),
                            'file' => $multipartException->getFile(),
                            'line' => $multipartException->getLine(),
                        ]);
                        throw new \Exception('S3 video upload failed: ' . $fallbackException->getMessage() . ' | Multipart: ' . $multipartException->getMessage());
                    }
                } else {
                    throw new \Exception('S3 video upload failed: ' . $fallbackException->getMessage());
                }
            }
        } else {
            // Set visibility for putFileAs result
            try {
                Storage::disk('s3')->setVisibility($uploadedPath, 'private');
                Log::info('Video visibility set to private', ['path' => $uploadedPath]);
            } catch (\Exception $e) {
                Log::warning('Could not set video visibility', ['error' => $e->getMessage()]);
            }
        }

        $videoPath = $uploadedPath;

        // Verify file exists on S3
        try {
            $exists = Storage::disk('s3')->exists($videoPath);
            Log::info('S3 file verification', [
                'path' => $videoPath,
                'exists' => $exists,
            ]);
            
            if (!$exists) {
                Log::warning('Uploaded file not found on S3 immediately after upload', ['path' => $videoPath]);
                // Don't throw - might be a timing issue
            }
        } catch (\Exception $e) {
            Log::warning('Could not verify file on S3', ['error' => $e->getMessage()]);
            // Continue anyway
        }

        // ✅ THUMBNAIL
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumb = $request->file('thumbnail');
            $thumbName = Str::uuid() . '.' . $thumb->getClientOriginalExtension();
            $thumbPath = 'thumbnails/' . $thumbName;

            $uploadedThumb = Storage::disk('s3')->putFileAs(
                'thumbnails',
                $thumb,
                $thumbName
            );

            // Fallback for thumbnail too
            if (!$uploadedThumb || $uploadedThumb === false) {
                Log::warning('Thumbnail putFileAs returned false, trying fallback');
                try {
                    $thumbContents = file_get_contents($thumb->getRealPath());
                    if ($thumbContents !== false) {
                        $uploadedThumb = Storage::disk('s3')->put($thumbPath, $thumbContents, 'public');
                    } else {
                        Log::warning('Could not read thumbnail file', ['path' => $thumb->getRealPath()]);
                    }
                } catch (\Exception $e) {
                    Log::warning('Thumbnail fallback failed', ['error' => $e->getMessage()]);
                }
            } else {
                try {
                    Storage::disk('s3')->setVisibility($uploadedThumb, 'public');
                } catch (\Exception $e) {
                    Log::warning('Could not set thumbnail visibility', ['error' => $e->getMessage()]);
                }
            }

            $thumbnailPath = $uploadedThumb;
        }

        Log::info('Creating database record', [
            'video_path' => $videoPath,
            'thumbnail_path' => $thumbnailPath,
        ]);

        Video::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'video_path' => $videoPath,
            'thumbnail' => $thumbnailPath,
            'category_id' => (int) $validated['category_id'],
            'is_premium' => $request->boolean('is_premium'),
        ]);

        Log::info('Video created successfully in database');

        return redirect()
            ->route('dashboard')
            ->with('success', 'Video uploaded successfully');

    } catch (\Throwable $e) {
        Log::error('Video upload failed', [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        return back()
            ->withInput()
            ->with('error', 'Upload failed: ' . $e->getMessage());
    }
}

    /**
     * Show the form for editing the specified video.
     */
    public function edit(Video $video)
    {
        $categories = Category::all();
        return view('admin.videos.edit', compact('video', 'categories'));
    }

    /**
     * Update the specified video in storage.
     */
    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'is_premium' => 'boolean',
        ]);

        $video->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category_id' => (int) $validated['category_id'],
            'is_premium' => $request->boolean('is_premium'),
        ]);

        return redirect()
            ->route('admin.videos.index')
            ->with('success', 'Video updated successfully');
    }

    /**
     * Remove the specified video.
     */
    public function destroy(Video $video)
    {
        try {
            // Delete video file from S3
            if ($video->video_path && Storage::disk('s3')->exists($video->video_path)) {
                Storage::disk('s3')->delete($video->video_path);
            }

            // Delete thumbnail from S3 if exists
            if ($video->thumbnail && Storage::disk('s3')->exists($video->thumbnail)) {
                Storage::disk('s3')->delete($video->thumbnail);
            }

            // Delete database record
            $video->delete();

            return redirect()->route('admin.videos.index')
                ->with('success', 'Video deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete video: ' . $e->getMessage());
        }
    }

    /**
     * Upload large file to S3 using multipart upload
     */
    private function uploadLargeFileToS3($file, $s3Path)
    {
        // Create S3 client directly using AWS SDK
        $s3Config = config('filesystems.disks.s3');
        
        // #region agent log
        $logFile = base_path('.cursor/debug.log');
        $logEntry = json_encode([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'E',
            'location' => 'VideoController.php:730',
            'message' => 'S3 config in uploadLargeFileToS3',
            'data' => [
                'bucket' => $s3Config['bucket'] ?? 'NOT_SET',
                'bucket_type' => gettype($s3Config['bucket'] ?? null),
                'bucket_empty' => empty($s3Config['bucket'] ?? null),
                'bucket_is_null' => ($s3Config['bucket'] ?? null) === null,
            ],
            'timestamp' => time() * 1000
        ]) . "\n";
        @file_put_contents($logFile, $logEntry, FILE_APPEND);
        // #endregion
        
        $s3Client = new S3Client([
            'version' => 'latest',
            'region' => $s3Config['region'],
            'credentials' => [
                'key' => $s3Config['key'],
                'secret' => $s3Config['secret'],
            ],
            'endpoint' => $s3Config['endpoint'] ?? null,
            'use_path_style_endpoint' => $s3Config['use_path_style_endpoint'] ?? false,
        ]);
        $bucket = $s3Config['bucket'];
        
        // #region agent log
        $logEntry = json_encode([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'F',
            'location' => 'VideoController.php:741',
            'message' => 'Bucket value in uploadLargeFileToS3 before use',
            'data' => [
                'bucket' => $bucket,
                'bucket_type' => gettype($bucket),
                'bucket_empty' => empty($bucket),
                'bucket_is_null' => $bucket === null,
            ],
            'timestamp' => time() * 1000
        ]) . "\n";
        @file_put_contents($logFile, $logEntry, FILE_APPEND);
        // #endregion
        
        // For files > 100MB, use multipart upload
        $fileSize = $file->getSize();
        $partSize = 10 * 1024 * 1024; // 10MB parts
        
        if ($fileSize < 100 * 1024 * 1024) {
            // For files < 100MB, use regular streaming
            $fileStream = fopen($file->getRealPath(), 'rb');
            if ($fileStream === false) {
                return false;
            }
            
            // #region agent log
            $logEntry = json_encode([
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'G',
                'location' => 'VideoController.php:754',
                'message' => 'About to call putObject in uploadLargeFileToS3',
                'data' => [
                    'bucket' => $bucket,
                    'bucket_empty' => empty($bucket),
                    's3_path' => $s3Path,
                ],
                'timestamp' => time() * 1000
            ]) . "\n";
            @file_put_contents($logFile, $logEntry, FILE_APPEND);
            // #endregion
            
            $result = $s3Client->putObject([
                'Bucket' => $bucket,
                'Key' => $s3Path,
                'Body' => $fileStream,
                'ACL' => 'private',
                'ContentType' => $file->getMimeType(),
            ]);
            
            fclose($fileStream);
            return isset($result['ETag']) ? $s3Path : false;
        }
        
        // #region agent log
        $logEntry = json_encode([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'H',
            'location' => 'VideoController.php:767',
            'message' => 'About to call createMultipartUpload',
            'data' => [
                'bucket' => $bucket,
                'bucket_empty' => empty($bucket),
                's3_path' => $s3Path,
            ],
            'timestamp' => time() * 1000
        ]) . "\n";
        @file_put_contents($logFile, $logEntry, FILE_APPEND);
        // #endregion
        
        // Multipart upload for very large files
        $uploadId = $s3Client->createMultipartUpload([
            'Bucket' => $bucket,
            'Key' => $s3Path,
            'ACL' => 'private',
            'ContentType' => $file->getMimeType(),
        ])['UploadId'];
        
        $parts = [];
        $partNumber = 1;
        $fileHandle = fopen($file->getRealPath(), 'rb');
        
        if ($fileHandle === false) {
            return false;
        }
        
        try {
            while (!feof($fileHandle)) {
                $data = fread($fileHandle, $partSize);
                if ($data === false) {
                    break;
                }
                
                $result = $s3Client->uploadPart([
                    'Bucket' => $bucket,
                    'Key' => $s3Path,
                    'PartNumber' => $partNumber,
                    'UploadId' => $uploadId,
                    'Body' => $data,
                ]);
                
                $parts[] = [
                    'ETag' => $result['ETag'],
                    'PartNumber' => $partNumber,
                ];
                
                $partNumber++;
            }
            
            // Complete multipart upload
            $s3Client->completeMultipartUpload([
                'Bucket' => $bucket,
                'Key' => $s3Path,
                'UploadId' => $uploadId,
                'MultipartUpload' => ['Parts' => $parts],
            ]);
            
            return $s3Path;
            
        } catch (\Exception $e) {
            // Abort multipart upload on error
            try {
                $s3Client->abortMultipartUpload([
                    'Bucket' => $bucket,
                    'Key' => $s3Path,
                    'UploadId' => $uploadId,
                ]);
            } catch (\Exception $abortException) {
                Log::warning('Failed to abort multipart upload', ['error' => $abortException->getMessage()]);
            }
            
            throw $e;
        } finally {
            fclose($fileHandle);
        }
    }

    /**
     * Generate a presigned URL for video preview.
     */
    public function preview(Video $video)
    {
        try {
            if (!$video->video_path || !Storage::disk('s3')->exists($video->video_path)) {
                abort(404, 'Video not found');
            }

            // Generate presigned URL valid for 10 minutes
            $presignedUrl = Storage::disk('s3')->temporaryUrl(
                $video->video_path,
                now()->addMinutes(10)
            );

            return response()->json([
                'url' => $presignedUrl,
                'title' => $video->title,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to generate preview URL: ' . $e->getMessage()
            ], 500);
        }
    }
}
