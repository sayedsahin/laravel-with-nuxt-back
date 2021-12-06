<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Qirolab\Laravel\Reactions\Contracts\ReactableInterface;
use Qirolab\Laravel\Reactions\Traits\Reactable;

class Category extends Model implements ReactableInterface
{
    use HasFactory, Reactable;

    public static function booted()
    {
        static::deleting(function($category) {
            $category->reactions()->delete();
        });
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}
