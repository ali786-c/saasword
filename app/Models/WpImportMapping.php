<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Tracks which WordPress objects were imported into which local records,
 * making the migration re-runnable and powering the 301 redirect table.
 */
class WpImportMapping extends Model
{
    protected $table = 'wp_import_mapping';

    protected $fillable = [
        'wp_type',
        'wp_id',
        'wp_slug',
        'local_id',
        'local_type',
        'status',
        'error',
    ];
}
