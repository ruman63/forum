<?php
namespace App;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        if (auth()->guest()) {
            return;
        }
        foreach (static::getEventsToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }
    
    public static function getEventsToRecord()
    {
        return ['created'];
    }

    public function recordActivity($event)
    {
        $this->activities()->create([
            'type' => $this->getActivityType($event),
            'user_id' => auth()->id(),
        ]);
    }

    public function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
}
