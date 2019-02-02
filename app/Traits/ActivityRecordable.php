<?php

namespace App\Traits;

use App\Activity;

trait ActivityRecordable
{
    protected static function bootActivityRecordable()
    {
        if (auth()->guest()) {
            return;
        }

        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($item) use ($event) {
                $item->createActivity($event);
            });
        }

        static::deleted(function ($item) {
            $item->activity()->delete();
        });
    }

    protected function createActivity(string $event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getEventName($event),
        ]);
    }

    protected function getEventName(string $event)
    {
        return strtolower($event.'_'.class_basename($this));
    }

    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
}
