<?php

namespace Botble\SeoBoost\Http\Requests;

use Botble\Support\Http\Requests\Request;

class SubmitUrlsRequest extends Request
{
    public function rules(): array
    {
        return [
            'urls' => ['required', 'string', 'max:65535'],
        ];
    }
}
