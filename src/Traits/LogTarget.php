<?php
namespace Webmachine\Logs\Traits;

use Webmachine\Logs\LogsFacade as Logs;

trait LogTarget {
    
    public static function bootLogTarget() {
        Logs::setBannedProperties(self::getBannedLogProperties());
        
        static::created(function ($model) {
            Logs::created($model);
        });
        
        static::updated(function ($model) {
            Logs::updated($model);
        });

        static::deleted(function ($model) {
            Logs::deleted($model);
        });        
    }
    
    protected static function getBannedLogProperties() {
        return isset(static::$bannedLogProperties)? static::$bannedLogProperties : []; 
    }


    /**
     * Agrega relación polimorfíca a modelo target
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */    
    public function logs() {
        return $this->morphMany('Webmachine\Logs\Models\Log', 'target');
    }    
}

