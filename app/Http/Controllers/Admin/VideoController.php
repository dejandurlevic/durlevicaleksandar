<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    /**
     * Display a listing of videos.
     */
    public function index()
    {
        $videos = Video::with('category')->latest()->paginate(15);
        return view('admin.videos.index', compact('videos'));
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

        try {
            // Upload video to S3
            $videoFile = $request->file('video');
            // Generate unique filename to prevent conflicts
            $videoFileName = 'videos/' . Str::uuid() . '.' . $videoFile->getClientOriginalExtension();
            // Upload to S3 and get the path
            $videoPath = Storage::disk('s3')->putFileAs('', $videoFile, $videoFileName);
            // Set video as private (not publicly accessible)
            Storage::disk('s3')->setVisibility($videoPath, 'private');
            
            // Upload thumbnail to S3 if provided
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailFile = $request->file('thumbnail');
                // Generate unique filename for thumbnail
                $thumbnailFileName = 'thumbnails/' . Str::uuid() . '.' . $thumbnailFile->getClientOriginalExtension();
                // Upload to S3 and get the path
                $thumbnailPath = Storage::disk('s3')->putFileAs('', $thumbnailFile, $thumbnailFileName);
                // Set thumbnail as public (can be accessed directly)
                Storage::disk('s3')->setVisibility($thumbnailPath, 'public');
            }

            // Create video record
            Video::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'video_path' => $videoPath,
                'thumbnail' => $thumbnailPath,
                'category_id' => $validated['category_id'],
                'is_premium' => $request->has('is_premium') ? true : false,
            ]);

            return redirect()->route('admin.videos.index')
                ->with('success', 'Video uploaded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to upload video: ' . $e->getMessage());
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

