<?php

return [

    // news api api configurations
    'newsapi'   => [
        'api_url'               => 'https://newsapi.org/v2/top-headlines',
        'api_key'               => env('NEWS_API_KEY', 'e6c1e09b72374a06a34697932bae5493'),
        'api_parameter_name'    => 'apiKey',
        'other_paremeters'      => ['language' => 'us'],
    ],

    // the guardian api configurations
    'theguardianapi'   => [
        'api_url'               => 'https://content.guardianapis.com/search',
        'api_key'               => env('GUARDIAN_API_KEY', 'b047d2a9-4bd7-4702-b4cf-0a9a4cd53487'),
        'api_parameter_name'    => 'api-key',
    ],


    // newyork times api configurations
    'newyorktimesapi'   => [
        'api_url'               => 'https://api.nytimes.com/svc/search/v2/articlesearch.json',
        'api_key'               => env('NYTIMES_API_KEY', 'NzwVvIUBCfWQBV8sPV6zX5w5MycwfCln'),
        'api_parameter_name'    => 'api-key',
    ]

];