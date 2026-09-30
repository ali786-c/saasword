<?php

return [
    [
        'name' => 'SEO Boost',
        'flag' => 'seo-boost.index',
    ],
    [
        'name' => 'Submit URLs',
        'flag' => 'seo-boost.submit',
        'parent_flag' => 'seo-boost.index',
    ],
    [
        'name' => 'SEO Boost',
        'flag' => 'seo-boost.settings',
        'parent_flag' => 'settings.others',
    ],
];
