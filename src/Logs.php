<?php
namespace Webmachine\Logs;

use Illuminate\Support\Facades\Auth;
use Webmachine\Logs\Models\Log;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Authenticated;

class Logs {
    
    /**
     * Log description
     * 
     * @var string 
     */
    protected $description;
    
    /**
     * Log properties
     * 
     * @var array 
     */
    protected $properties;
    
    /**
     * Log doer
     * 
     * @var Illuminate\Database\Eloquent\Model
     */
    protected $doer;

    /**
     * Log target
     * 
     * @var Illuminate\Database\Eloquent\Model
     */    
    protected $target;
    
    /**
     * Banned Log Properties
     * 
     * @var array 
     */
    protected $bannedLogProperties;

    /**
     * Log enabled
     * 
     * @var bool 
     */
    protected $logEnabled;
    
    public function __construct() {
        
        $this->doer = NULL;
        Event::listen(Authenticated::class, function ($event) {
            $this->doer = $event->user;
        });
        
        $this->properties = $this->target = NULL;
        $this->logEnabled = config('logs.enabled');
    }
    
    /**
     * Simple Log, only description
     * 
     * @param string $description
     * @return bool
     */
    public function insert($description) {
        $this->description = $description;
        return $this->log();
    }
    
    /**
     * Log on create
     * 
     * @param Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function created($model) {
        $this->description = $this->getDescriptionOr('created');
        $attributes = $this->array_except($model->toArray(), $this->bannedLogProperties); // removes $bannedLogProperties
        $this->properties = ['attributes' => $attributes];
        $this->target = $model;
        if (empty($this->properties['attributes'])) return;
        return $this->log();
    }

    /**
     * Log on update
     * 
     * @param Illuminate\Database\Eloquent\Model $model
     * @return bool
     */    
    public function updated($model) {
        $this->description = $this->getDescriptionOr('updated');
        $attributes = $this->array_except($model->getDirty(), $this->bannedLogProperties); // removes $bannedLogProperties
        $properties['attributes'] = $attributes;
        $properties['old'] = array_intersect_key($model->getOriginal(), $attributes);
        $this->properties = $properties;
        $this->target = $model;
        if (empty($this->properties['attributes'])) return; 
        return $this->log();
    }
    
    /**
     * Log on delete
     * 
     * @param Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function deleted($model) {
        $this->description = $this->getDescriptionOr('deleted');
        $this->target = $model;
        return $this->log();
    }    
    
    /**
     * Save Log
     * 
     * @return bool
     */
    protected function log() {
        if (!$this->logEnabled) return;       
        
        $log = new Log();
        if($this->doer != NULL) $log->doer()->associate($this->doer);
        if($this->target != NULL) $log->target()->associate($this->target);
        $log->description = $this->description;
        $log->properties = $this->properties;
        return $log->save();        
    }
    
    /**
     * set Banned Properties
     * 
     * @param array $banned_properties
     * @return void
     */
    public function setBannedProperties($banned_properties) {
        $this->bannedLogProperties = array_merge($banned_properties, ['created_at', 'updated_at']);
    }

    /**
     * Set Log descripction
     * 
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }
    
    /**
     * Get description or set default
     * 
     * @param string $default_description
     * @return string
     */
    protected function getDescriptionOr($default_description) {
        return $this->description?: $default_description;
    }

    /**
     * Get array without banned properties
     * 
     * @param array $array
     * @param array $banned_properties
     * @return array
     */
    protected function array_except($array, $banned_properties) {
        return array_diff_key($array, array_flip((array) $banned_properties));
    }
}
