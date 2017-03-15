<?php
namespace Webmachine\Logs;

use Illuminate\Support\Facades\Facade;

class LogsFacade extends Facade {

    protected static function getFacadeAccessor() {
        return 'logs';
    }
}