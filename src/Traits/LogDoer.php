<?php
namespace Webmachine\Logs\Traits;

trait LogDoer {
    
    /**
     * Agrega relación polimorfíca a modelo doer (ejemplo: usuario)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function logsDoer() {
        return $this->morphMany('Webmachine\Logs\Models\Log', 'doer');
    }    
}

