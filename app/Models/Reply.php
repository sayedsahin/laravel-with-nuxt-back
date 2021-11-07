<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Qirolab\Laravel\Reactions\Contracts\ReactableInterface;
use Qirolab\Laravel\Reactions\Traits\Reactable;

class Reply extends Model implements ReactableInterface
{
    use HasFactory, Reactable;

    protected $fillable = ['body', 'topic_id', 'user_id'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }    

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
