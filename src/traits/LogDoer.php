<?php
namespace Webmachineltda\Logs\Traits;

trait LogDoer {
    
    /**
     * Agrega relación polimorfíca a modelo doer (ejemplo: usuario)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function logs() {
        return $this->morphMany('Webmachineltda\Logs\Models\Log', 'doer');
    }    
}

