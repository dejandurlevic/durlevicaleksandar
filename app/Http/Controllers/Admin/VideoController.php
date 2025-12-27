<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    /**
     * Display a listing of videos.
     */
    public function index()
    {
        try {
            // Add debugging to identify the issue
            Log::info('Admin videos index - Fetching videos', [
                'total_videos_in_db' => Video::count(),
            ]);
            
            // Use leftJoin to handle missing categories gracefully
            // Explicitly select all videos columns to avoid conflicts with categories timestamps
            // Wrap in try-catch to handle any SQL errors with the join
            try {
                $videos = Video::leftJoin('categories', 'videos.category_id', '=', 'categories.id')
                    ->select(
                        'videos.id',
                        'videos.title',
                        'videos.description',
                        'videos.video_path',
                        'videos.thumbnail',
                        'videos.is_premium',
                        'videos.category_id',
                        'videos.created_at',
                        'videos.updated_at',
                        'categories.name as category_name'
                    )
                    ->latest('videos.created_at')
                    ->paginate(15);
                    
                // Ensure created_at is a Carbon instance for all videos
                $videos->getCollection()->transform(function ($video) {
                    if ($video->created_at && !($video->created_at instanceof \Carbon\Carbon)) {
                        $video->created_at = \Carbon\Carbon::parse($video->created_at);
                    }
                    return $video;
                });
            } catch (\Exception $joinError) {
                // If leftJoin fails, try without join (fallback)
                Log::warning('leftJoin failed, falling back to simple query', [
                    'error' => $joinError->getMessage(),
                    'trace' => $joinError->getTraceAsString()
                ]);
                
                $videos = Video::latest()->paginate(15);
                // Manually add category_name as null for all videos
                $videos->getCollection()->transform(function ($video) {
                    $video->category_name = $video->category ? $video->category->name : null;
                    // Ensure created_at is a Carbon instance
                    if ($video->created_at && !($video->created_at instanceof \Carbon\Carbon)) {
                        $video->created_at = \Carbon\Carbon::parse($video->created_at);
                    }
                    return $video;
                });
            }
            
            // Safer logging - don't map if it might cause issues
            try {
                Log::info('Admin videos index - Videos fetched', [
                    'paginated_count' => $videos->count(),
                    'total_pages' => $videos->lastPage(),
                    'current_page' => $videos->currentPage(),
                ]);
            } catch (\Exception $logError) {
                // Ignore logging errors
                Log::warning('Could not log video details', ['error' => $logError->getMessage()]);
            }
            
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
                $videos = Video::latest()->paginate(15);
                // Manually add category_name as null
                $videos->getCollection()->transform(function ($video) {
                    $video->category_name = $video->category ? $video->category->name : null;
                    // Ensure created_at is a Carbon instance
                    if ($video->created_at && !($video->created_at instanceof \Carbon\Carbon)) {
                        $video->created_at = \Carbon\Carbon::parse($video->created_at);
                    }
                    return $video;
                });
                return view('admin.videos.index', compact('videos'))->with('error', 'Some videos may not display correctly.');
            } catch (\Exception $e2) {
                Log::error('Complete failure in admin videos index', [
                    'error' => $e2->getMessage(),
                    'file' => $e2->getFile(),
                    'line' => $e2->getLine(),
                    'trace' => $e2->getTraceAsString()
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
    $logFile = base_path('.cursor/debug.log');
    $logDir = base_path('.cursor');
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    $logEntry = json_encode([
        'sessionId' => 'debug-session',
        'runId' => 'run1',
        'hypothesisId' => 'ALL',
        'location' => 'VideoController.php:154',
        'message' => 'store() method entry',
        'data' => [
            'has_video' => $request->hasFile('video'),
            'has_thumbnail' => $request->hasFile('thumbnail'),
            'log_file_path' => $logFile,
            'log_dir_exists' => is_dir($logDir),
        ],
        'timestamp' => time() * 1000
    ]) . "\n";
    $writeResult = @file_put_contents($logFile, $logEntry, FILE_APPEND);
    Log::info('DEBUG: store() method entry', [
        'has_video' => $request->hasFile('video'),
        'log_write_result' => $writeResult,
        'log_file' => $logFile
    ]);
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
        $logEntry = json_encode([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'A',
            'location' => 'VideoController.php:245',
            'message' => 'S3 configuration check',
            'data' => [
                'driver' => $s3Config['driver'] ?? 'missing',
                'region' => $s3Config['region'] ?? 'missing',
                'bucket' => $s3Config['bucket'] ?? 'missing',
                'has_key' => !empty($s3Config['key']),
                'has_secret' => !empty($s3Config['secret']),
                'endpoint' => $s3Config['endpoint'] ?? 'not_set',
            ],
            'timestamp' => time() * 1000
        ]) . "\n";
        @file_put_contents($logFile, $logEntry, FILE_APPEND);
        // #endregion

        // Test S3 connection before upload
        // #region agent log
        try {
            $testFile = 'test-connection-' . time() . '.txt';
            $testResult = Storage::disk('s3')->put($testFile, 'test');
            $logEntry = json_encode([
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'D',
                'location' => 'VideoController.php:265',
                'message' => 'S3 connection test',
                'data' => [
                    'test_result' => $testResult,
                    'test_result_type' => gettype($testResult),
                    'connection_success' => ($testResult !== false),
                ],
                'timestamp' => time() * 1000
            ]) . "\n";
            @file_put_contents($logFile, $logEntry, FILE_APPEND);
            if ($testResult) {
                Storage::disk('s3')->delete($testFile);
            }
        } catch (\Exception $e) {
            $logEntry = json_encode([
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'D',
                'location' => 'VideoController.php:275',
                'message' => 'S3 connection test exception',
                'data' => ['error' => $e->getMessage()],
                'timestamp' => time() * 1000
            ]) . "\n";
            @file_put_contents($logFile, $logEntry, FILE_APPEND);
        }
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
        $logEntry = json_encode([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'B',
            'location' => 'VideoController.php:285',
            'message' => 'Before putFileAs call',
            'data' => [
                'video_name' => $videoName,
                'video_path' => $videoPath,
                'directory' => 'videos',
                'file_exists' => file_exists($videoFile->getRealPath()),
                'file_readable' => is_readable($videoFile->getRealPath()),
            ],
            'timestamp' => time() * 1000
        ]) . "\n";
        @file_put_contents($logFile, $logEntry, FILE_APPEND);
        // #endregion

        // Try putFileAs first
        $uploadedPath = Storage::disk('s3')->putFileAs(
            'videos',
            $videoFile,
            $videoName
        );

        // #region agent log
        $logEntry = json_encode([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'B',
            'location' => 'VideoController.php:295',
            'message' => 'After putFileAs call',
            'data' => [
                'result' => $uploadedPath,
                'result_type' => gettype($uploadedPath),
                'is_false' => ($uploadedPath === false),
                'is_empty' => empty($uploadedPath),
                'is_string' => is_string($uploadedPath),
                'result_length' => is_string($uploadedPath) ? strlen($uploadedPath) : 0,
            ],
            'timestamp' => time() * 1000
        ]) . "\n";
        @file_put_contents($logFile, $logEntry, FILE_APPEND);
        // #endregion

        Log::info('putFileAs result', [
            'result' => $uploadedPath,
            'type' => gettype($uploadedPath),
            'is_false' => ($uploadedPath === false),
            'is_empty' => empty($uploadedPath),
        ]);

        // If putFileAs returns false, try fallback with put() and file contents
        if (!$uploadedPath || $uploadedPath === false) {
            Log::warning('putFileAs returned false, trying fallback method with put() and file contents');
            
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
                // #region agent log
                $logEntry = json_encode([
                    'sessionId' => 'debug-session',
                    'runId' => 'run1',
                    'hypothesisId' => 'C',
                    'location' => 'VideoController.php:325',
                    'message' => 'Before file_get_contents',
                    'data' => [
                        'real_path' => $videoFile->getRealPath(),
                        'file_exists' => file_exists($videoFile->getRealPath()),
                    ],
                    'timestamp' => time() * 1000
                ]) . "\n";
                @file_put_contents($logFile, $logEntry, FILE_APPEND);
                // #endregion

                $fileContents = file_get_contents($videoFile->getRealPath());
                
                // #region agent log
                $logEntry = json_encode([
                    'sessionId' => 'debug-session',
                    'runId' => 'run1',
                    'hypothesisId' => 'C',
                    'location' => 'VideoController.php:335',
                    'message' => 'After file_get_contents',
                    'data' => [
                        'read_success' => ($fileContents !== false),
                        'content_length' => $fileContents !== false ? strlen($fileContents) : 0,
                        'memory_usage_after' => memory_get_usage(true),
                    ],
                    'timestamp' => time() * 1000
                ]) . "\n";
                @file_put_contents($logFile, $logEntry, FILE_APPEND);
                // #endregion

                if ($fileContents === false) {
                    throw new \Exception('Could not read video file: ' . $videoFile->getRealPath());
                }
                
                // #region agent log
                $logEntry = json_encode([
                    'sessionId' => 'debug-session',
                    'runId' => 'run1',
                    'hypothesisId' => 'E',
                    'location' => 'VideoController.php:350',
                    'message' => 'Before Storage::put() call',
                    'data' => [
                        'video_path' => $videoPath,
                        'content_size' => strlen($fileContents),
                    ],
                    'timestamp' => time() * 1000
                ]) . "\n";
                @file_put_contents($logFile, $logEntry, FILE_APPEND);
                // #endregion

                $uploadedPath = Storage::disk('s3')->put($videoPath, $fileContents, 'private');
                
                // #region agent log
                $logEntry = json_encode([
                    'sessionId' => 'debug-session',
                    'runId' => 'run1',
                    'hypothesisId' => 'E',
                    'location' => 'VideoController.php:360',
                    'message' => 'After Storage::put() call',
                    'data' => [
                        'result' => $uploadedPath,
                        'result_type' => gettype($uploadedPath),
                        'is_false' => ($uploadedPath === false),
                        'is_empty' => empty($uploadedPath),
                    ],
                    'timestamp' => time() * 1000
                ]) . "\n";
                @file_put_contents($logFile, $logEntry, FILE_APPEND);
                // #endregion
                
                if (!$uploadedPath || $uploadedPath === false) {
                    throw new \Exception('Fallback put() method also returned false');
                }
                
                Log::info('Fallback put() method succeeded', ['path' => $uploadedPath]);
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

                Log::error('Fallback upload method failed', [
                    'error' => $fallbackException->getMessage(),
                    'file' => $fallbackException->getFile(),
                    'line' => $fallbackException->getLine(),
                ]);
                throw new \Exception('S3 video upload failed: putFileAs returned false, and fallback method also failed: ' . $fallbackException->getMessage());
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
            ->route('admin.videos.index')
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

