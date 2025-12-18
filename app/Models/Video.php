<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'video_path',
        'thumbnail',
        'is_premium',
        'category_id',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
    ];

    /**
     * Get the category that owns the video.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}




















