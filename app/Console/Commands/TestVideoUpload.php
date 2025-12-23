<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Video;

class TestVideoUpload extends Command
{
    protected $signature = 'test:video-upload {video_path}';
    protected $description = 'Test video upload to S3';

    public function handle()
    {
        $videoPath = $this->argument('video_path');
        
        if (!file_exists($videoPath)) {
            $this->error("Video file not found: {$videoPath}");
            return 1;
        }

        $this->info("Starting video upload test...");
        $this->info("Video file: {$videoPath}");
        $this->info("File size: " . filesize($videoPath) . " bytes");

        try {
            // Step 1: Check S3 connection
            $this->info("\n[Step 1] Testing S3 connection...");
            $testFile = 'test-connection-' . time() . '.txt';
            Storage::disk('s3')->put($testFile, 'test');
            $this->info("✓ S3 connection successful");
            Storage::disk('s3')->delete($testFile);

            // Step 2: Create a test video file object
            $this->info("\n[Step 2] Preparing video file...");
            $videoFile = new \Illuminate\Http\UploadedFile(
                $videoPath,
                basename($videoPath),
                mime_content_type($videoPath),
                null,
                true
            );
            $this->info("✓ Video file prepared");

            // Step 3: Generate filename
            $this->info("\n[Step 3] Generating filename...");
            $videoFileName = Str::uuid() . '.' . $videoFile->getClientOriginalExtension();
            $this->info("Filename: {$videoFileName}");

            // Step 4: Upload to S3
            $this->info("\n[Step 4] Uploading to S3...");
            $s3Path = Storage::disk('s3')->putFileAs('videos', $videoFile, $videoFileName);
            $this->info("✓ Upload successful!");
            $this->info("S3 Path: {$s3Path}");

            // Step 5: Verify file exists on S3
            $this->info("\n[Step 5] Verifying file on S3...");
            if (Storage::disk('s3')->exists($s3Path)) {
                $this->info("✓ File exists on S3");
                $size = Storage::disk('s3')->size($s3Path);
                $this->info("File size on S3: {$size} bytes");
            } else {
                $this->error("✗ File NOT found on S3!");
                return 1;
            }

            // Step 6: Set visibility
            $this->info("\n[Step 6] Setting visibility to private...");
            Storage::disk('s3')->setVisibility($s3Path, 'private');
            $this->info("✓ Visibility set");

            // Step 7: Get a category for testing
            $this->info("\n[Step 7] Getting category...");
            $category = Category::first();
            if (!$category) {
                $this->error("✗ No categories found in database!");
                return 1;
            }
            $this->info("Category: {$category->name} (ID: {$category->id})");

            // Step 8: Create database record
            $this->info("\n[Step 8] Creating database record...");
            $video = Video::create([
                'title' => 'Test Video - ' . now()->format('Y-m-d H:i:s'),
                'description' => 'Test upload',
                'video_path' => $s3Path,
                'thumbnail' => null,
                'category_id' => $category->id,
                'is_premium' => false,
            ]);
            $this->info("✓ Database record created!");
            $this->info("Video ID: {$video->id}");
            $this->info("Video Path in DB: {$video->video_path}");

            // Step 9: Verify database record
            $this->info("\n[Step 9] Verifying database record...");
            $savedVideo = Video::find($video->id);
            if ($savedVideo && $savedVideo->video_path === $s3Path) {
                $this->info("✓ Database record verified!");
            } else {
                $this->error("✗ Database record mismatch!");
                return 1;
            }

            $this->info("\n✅ All tests passed!");
            $this->info("You can now check the admin panel to see if the video appears.");
            $this->info("Video ID: {$video->id}");

            return 0;
        } catch (\Exception $e) {
            $this->error("\n✗ Error occurred:");
            $this->error($e->getMessage());
            $this->error("\nStack trace:");
            $this->error($e->getTraceAsString());
            
            Log::error('Test video upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return 1;
        }
    }
}



