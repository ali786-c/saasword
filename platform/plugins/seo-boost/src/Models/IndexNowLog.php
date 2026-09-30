<?php

namespace Botble\SeoBoost\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Casts\Attribute;

class IndexNowLog extends BaseModel
{
    protected $table = 'index_now_logs';

    protected $fillable = [
        'url',
        'status_code',
        'message',
        'is_manual',
    ];

    protected $casts = [
        'status_code' => 'int',
        'is_manual' => 'bool',
        'created_at' => 'datetime',
        'url' => SafeContent::class,
        'message' => SafeContent::class,
    ];

    protected function isSuccess(): Attribute
    {
        return Attribute::get(fn () => in_array($this->status_code, [200, 202, 204]));
    }
}
