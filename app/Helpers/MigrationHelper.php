<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class MigrationHelper
{
    /**
     * Run a raw SQL statement only if not in testing environment.
     */
    public static function safeStatement(string $sql): void
    {
        if (!App::environment('testing')) {
            DB::statement($sql);
        }
    }
}
