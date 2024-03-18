<?php

namespace App\Traits;

use App\Scopes\Deleted;

trait HasDeletedScope {
    public static function bootHasDeletedScope() {
        static::addGlobalScope(new Deleted());
    }
}