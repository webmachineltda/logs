<?php
namespace Webmachineltda\Logs\Traits;

use Webmachineltda\Logs\LogsFacade as Logs;

trait LogTarget {
    
    public static function bootLogTarget() {
        static::created(function ($model) {
            Logs::created($model);
        });
        
        static::updated(function ($model) {
            Logs::updated($model);
        });        
    }
    
    /**
     * Agrega relación polimorfíca a modelo target
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */    
    public function logs() {
        return $this->morphMany('Webmachineltda\Logs\Models\Log', 'target');
    }    
}

