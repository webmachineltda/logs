<?php
namespace Webmachine\Logs;

use Illuminate\Support\Facades\Auth;
use Webmachine\Logs\Models\Log;

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
     * Log enabled
     * 
     * @var bool 
     */
    protected $logEnabled;
    
    public function __construct() {
        $this->doer = Auth::check()? Auth::user() : NULL;
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
        $this->properties = ['attributes' => $model->toArray()];
        $this->target = $model;
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
        $properties['attributes'] = $model->getDirty();
        $properties['old'] = array_intersect_key($model->getOriginal(), $model->getDirty());
        $this->properties = $properties;
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
        $log->properties = $this->clean_properties();
        return $log->save();        
    }
    
    /**
     * Clean properties, unset timestamps
     * 
     * @return array
     */
    protected function clean_properties() {
        unset($this->properties['attributes']['created_at'], $this->properties['attributes']['updated_at'], $this->properties['old']['updated_at']);
        return $this->properties;
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
}
