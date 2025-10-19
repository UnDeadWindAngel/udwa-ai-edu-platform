<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id', 'slide_number', 'slide_type', 'title', 'content',
        'media_url', 'media_type', 'interactive_data', 'duration'
    ];

    protected $casts = [
        'interactive_data' => 'array',
        'duration' => 'integer'
    ];

    // Типы слайдов
    const TYPE_TEXT = 'text';
    const TYPE_VIDEO = 'video';
    const TYPE_IMAGE = 'image';
    const TYPE_QUIZ = 'quiz';
    const TYPE_CODE = 'code';
    const TYPE_INTERACTIVE = 'interactive';

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    // Методы проверки типа
    public function isText()
    {
        return $this->slide_type === self::TYPE_TEXT;
    }

    public function isVideo()
    {
        return $this->slide_type === self::TYPE_VIDEO;
    }

    public function isImage()
    {
        return $this->slide_type === self::TYPE_IMAGE;
    }

    public function isQuiz()
    {
        return $this->slide_type === self::TYPE_QUIZ;
    }

    public function isInteractive()
    {
        return in_array($this->slide_type, [self::TYPE_QUIZ, self::TYPE_CODE, self::TYPE_INTERACTIVE]);
    }

    public function hasMedia()
    {
        return !empty($this->media_url);
    }

    public function getMediaTypeCategory()
    {
        if (empty($this->media_type)) return null;
        return explode('/', $this->media_type)[0]; // video, image, audio
    }

    public function getNextSlide()
    {
        return $this->lesson->slides()
            ->where('slide_number', '>', $this->slide_number)
            ->orderBy('slide_number')
            ->first();
    }

    public function getPreviousSlide()
    {
        return $this->lesson->slides()
            ->where('slide_number', '<', $this->slide_number)
            ->orderBy('slide_number', 'desc')
            ->first();
    }
}
