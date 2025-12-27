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

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'video' => 'required|file|mimes:mp4,mov,webm,avi|max:5242880',
        'thumbnail' => 'nullable|image|max:10240',
        'category_id' => 'required|exists:categories,id',
        'is_premium' => 'boolean',
    ]);

    try {
        // ✅ VIDEO UPLOAD
        $videoName = Str::uuid() . '.' . $videoFile->getClientOriginalExtension();

        $videoPath = Storage::disk('s3')->putFileAs(
            'videos',
            $videoFile,
            $videoName
        );

        if (!$videoPath) {
            throw new \Exception('S3 video upload failed');
        }

        // Set visibility
        try {
            Storage::disk('s3')->setVisibility($videoPath, 'private');
        } catch (\Exception $e) {
            Log::warning('Could not set video visibility', ['error' => $e->getMessage()]);
        }

        // ✅ THUMBNAIL
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumb = $request->file('thumbnail');
            $thumbName = Str::uuid() . '.' . $thumb->getClientOriginalExtension();

            $thumbnailPath = Storage::disk('s3')->putFileAs(
                'thumbnails',
                $thumb,
                $thumbName
            );
            
            if ($thumbnailPath) {
                try {
                    Storage::disk('s3')->setVisibility($thumbnailPath, 'public');
                } catch (\Exception $e) {
                    Log::warning('Could not set thumbnail visibility', ['error' => $e->getMessage()]);
                }
            }
        }

        Video::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'video_path' => $videoPath,
            'thumbnail' => $thumbnailPath,
            'category_id' => (int) $validated['category_id'],
            'is_premium' => $request->boolean('is_premium'),
        ]);

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
            ->with('error', 'Upload failed. Check logs.');
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

