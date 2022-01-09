<?php
namespace App\Traits;

use App\Models\Activity;

trait ActivityLog
{
	public function activities()
    {
        return $this->morphMany(Activity::class, 'activity');
    }

	public static function bootActivityLog()
    {
        static::created(function ($model){
            $model->recordActivity('Created');
        });

        static::deleting(function ($model) {
        	$model->activities()->delete();
        });

    }

    public function recordActivity($event)
    {
        $this->activities()->create([
            'user_id' => 1,
            'type' => $event.class_basename($this)
            // 'type' => $event.'_'.strtolower(class_basename($this))
        ]);
    }
}