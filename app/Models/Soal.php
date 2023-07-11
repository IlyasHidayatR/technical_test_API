<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;
    protected $table = 'soals';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'content',
        'options',
        'correct_option'
    ];

    public function getCorrectAnswerCount()
    {
        $correctOption = $this->correct_option;
        $options = json_decode($this->options, true);
        $correctAnswerCount = 0;
        if (is_array($options) && isset($options[$correctOption])) {
            $correctAnswerCount = count(array_filter($options, function ($option, $index) use ($correctOption) {
                return $index === $correctOption;
            }, ARRAY_FILTER_USE_BOTH));
        }

        return $correctAnswerCount;
    }    

    public static function orderByCorrectAnswerCountDesc()
    {
        return Soal::all()->sortByDesc(function ($soal) {
            return $soal->getCorrectAnswerCount();
        });
    }

    public static function searchByTitle($searchString)
    {
        return Soal::where('title', 'like', '%' . $searchString . '%')->get();
    }
}
