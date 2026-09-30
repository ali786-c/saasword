<?php

return [
    [
        'name' => 'SEO Boost',
        'flag' => 'seo-boost.index',
    ],
    [
        'name' => 'Settings',
        'flag' => 'seo-boost.settings',
        'parent_flag' => 'seo-boost.index',
    ],
    [
        'name' => 'Submit URLs',
        'flag' => 'seo-boost.submit',
        'parent_flag' => 'seo-boost.index',
    ],
    [
        'name' => 'Submission History',
        'flag' => 'seo-boost.logs',
        'parent_flag' => 'seo-boost.index',
    ],
];
