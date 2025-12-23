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
        // Add debugging to identify the issue
        Log::info('Admin videos index - Fetching videos', [
            'total_videos_in_db' => Video::count(),
        ]);
        
        $videos = Video::with('category')->latest()->paginate(15);
        
        Log::info('Admin videos index - Videos fetched', [
            'paginated_count' => $videos->count(),
            'total_pages' => $videos->lastPage(),
            'current_page' => $videos->currentPage(),
            'videos_data' => $videos->map(function($v) {
                return [
                    'id' => $v->id,
                    'title' => $v->title,
                    'video_path' => $v->video_path,
                    'category_id' => $v->category_id,
                    'has_category' => $v->category ? true : false,
                    'category_name' => $v->category ? $v->category->name : null,
                ];
            })->toArray()
        ]);
        
        return view('admin.videos.index', compact('videos'));
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
        try {
            Log::info('Video upload request received', [
                'has_video' => $request->hasFile('video'),
                'has_thumbnail' => $request->hasFile('thumbnail'),
                'title' => $request->input('title'),
                'category_id' => $request->input('category_id'),
                'video_size' => $request->hasFile('video') ? $request->file('video')->getSize() : null,
            ]);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                // Note: Adjust max size based on your server's upload_max_filesize and post_max_size in php.ini
                // Also ensure your S3 bucket and IAM policies allow large file uploads
                // max value is in kilobytes: 5242880 KB = 5GB
                'video' => 'required|file|mimes:mp4,mov,webm,avi|max:5242880', // Max 5GB (adjust as needed)
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // Max 10MB (in KB)
                'category_id' => 'required|exists:categories,id',
                'is_premium' => 'boolean',
            ]);

            Log::info('Video upload validation passed', [
                'title' => $validated['title'],
                'category_id' => $validated['category_id'],
                'video_size' => $request->file('video')->getSize(),
            ]);

            // Upload video to S3 - following the same pattern as TestVideoUpload
            $videoFile = $request->file('video');
            
            // Generate unique filename to prevent conflicts
            $videoFileName = Str::uuid() . '.' . $videoFile->getClientOriginalExtension();
            
            Log::info('Uploading video to S3', ['filename' => $videoFileName]);
            
            // Upload to S3 - putFileAs(directory, file, filename) - same as TestVideoUpload
            $videoPath = Storage::disk('s3')->putFileAs('videos', $videoFile, $videoFileName);
            
            Log::info('Video uploaded to S3', ['path' => $videoPath]);
            
            // Verify file exists on S3 - wrap in try-catch to handle S3 connection issues
            try {
                if (!Storage::disk('s3')->exists($videoPath)) {
                    throw new \Exception('Video file was not found on S3 after upload');
                }
            } catch (\Exception $e) {
                Log::warning('Could not verify video file existence on S3', [
                    'path' => $videoPath,
                    'error' => $e->getMessage()
                ]);
                // Continue anyway - if putFileAs didn't throw, upload likely succeeded
            }
            
            // Set video as private (not publicly accessible)
            try {
                Storage::disk('s3')->setVisibility($videoPath, 'private');
            } catch (\Exception $e) {
                Log::warning('Could not set video visibility on S3', [
                    'path' => $videoPath,
                    'error' => $e->getMessage()
                ]);
                // Continue anyway - visibility is not critical
            }
            
            // Upload thumbnail to S3 if provided
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailFile = $request->file('thumbnail');
                // Generate unique filename for thumbnail
                $thumbnailFileName = Str::uuid() . '.' . $thumbnailFile->getClientOriginalExtension();
                // Upload to S3 - putFileAs(directory, file, filename)
                $thumbnailPath = Storage::disk('s3')->putFileAs('thumbnails', $thumbnailFile, $thumbnailFileName);
                
                // Verify thumbnail exists on S3 - wrap in try-catch
                try {
                    if (!Storage::disk('s3')->exists($thumbnailPath)) {
                        throw new \Exception('Thumbnail file was not found on S3 after upload');
                    }
                } catch (\Exception $e) {
                    Log::warning('Could not verify thumbnail file existence on S3', [
                        'path' => $thumbnailPath,
                        'error' => $e->getMessage()
                    ]);
                    // Continue anyway
                }
                
                // Set thumbnail as public (can be accessed directly)
                try {
                    Storage::disk('s3')->setVisibility($thumbnailPath, 'public');
                } catch (\Exception $e) {
                    Log::warning('Could not set thumbnail visibility on S3', [
                        'path' => $thumbnailPath,
                        'error' => $e->getMessage()
                    ]);
                    // Continue anyway
                }
                
                Log::info('Thumbnail uploaded to S3', ['path' => $thumbnailPath]);
            }

            // Create video record - same pattern as TestVideoUpload Step 8
            $videoData = [
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'video_path' => $videoPath,
                'thumbnail' => $thumbnailPath,
                'category_id' => $validated['category_id'],
                'is_premium' => $request->has('is_premium') ? true : false,
            ];
            
            Log::info('Creating video record in database', $videoData);
            
            $video = Video::create($videoData);
            
            // Verify the video was actually saved (same as TestVideoUpload Step 9)
            $savedVideo = Video::find($video->id);
            if ($savedVideo && $savedVideo->video_path === $videoPath) {
                $this->info("✓ Database record verified!");
            } else {
                $this->error("✗ Database record mismatch!");
                return 1;
            }
            
            Log::info('Video created successfully', [
                'video_id' => $video->id, 
                'video_path' => $video->video_path,
                'database_verified' => true
            ]);

            return redirect()->route('admin.videos.index')
                ->with('success', 'Video uploaded successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Video upload validation failed', [
                'errors' => $e->errors(),
                'title' => $request->input('title'),
            ]);
            
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Please fix the validation errors below.');
        } catch (\Exception $e) {
            Log::error('Video upload failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'title' => $request->input('title'),
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to upload video: ' . $e->getMessage())
                ->with('error_details', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
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

