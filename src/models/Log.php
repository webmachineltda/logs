<?php

namespace Webmachineltda\Logs\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model {
    
    protected $table = 'logs';
    
    protected $fillable = ['user_id', 'description', 'properties'];
    
    protected $casts = [
        'properties' => 'array',
    ];    
    
    public $timestamps = false;

    
    public static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }    
    
    public function doer() {
        return $this->morphTo();
    }
    
    public function target() {
        return $this->morphTo();
    }   
    
}