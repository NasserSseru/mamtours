<?php

return [
    'dsn' => env('SENTRY_LARAVEL_DSN'),

    // capture release as git sha
    'release' => env('APP_VERSION', '1.0.0'),

    // Capture environment
    'environment' => env('APP_ENV', 'production'),

    'breadcrumbs' => [
        // Capture Laravel logs in breadcrumbs
        'logs' => true,

        // Capture SQL queries in breadcrumbs
        'sql_queries' => true,

        // Capture bindings on SQL queries logged in breadcrumbs
        'sql_bindings' => true,

        // Capture queue job information in breadcrumbs
        'queue_info' => true,

        // Capture command information in breadcrumbs
        'command_info' => true,
    ],

    // @see: https://docs.sentry.io/platforms/php/guides/laravel/performance/instrumentation/automatic-instrumentation/
    'tracing' => [
        // Trace queue jobs
        'queue_job_transactions' => env('SENTRY_TRACE_QUEUE_ENABLED', false),
        'queue_jobs' => true,

        // Capture SQL queries information
        'sql_queries' => true,

        // Try to find out where the SQL query originated from and add it to the query spans
        'sql_origin' => true,

        // Capture views rendered
        'views' => true,

        // Indicates if the tracing integrations supplied by Sentry should be loaded
        'default_integrations' => true,

        // Indicates that requests without a matching route should be traced
        'missing_routes' => false,
    ],

    // @see: https://docs.sentry.io/platforms/php/guides/laravel/configuration/options/#send-default-pii
    'send_default_pii' => false,

    // @see: https://docs.sentry.io/platforms/php/guides/laravel/configuration/options/#traces-sample-rate
    'traces_sample_rate' => (float)(env('SENTRY_TRACES_SAMPLE_RATE', 0.0)),

    // @see: https://docs.sentry.io/platforms/php/guides/laravel/configuration/options/#profiles-sample-rate
    'profiles_sample_rate' => (float)(env('SENTRY_PROFILES_SAMPLE_RATE', 0.0)),

    // Scrub sensitive data before sending to Sentry
    'before_send' => function (\Sentry\Event $event): ?\Sentry\Event {
        // Scrub password fields
        if ($event->getRequest()) {
            $request = $event->getRequest();
            $data = $request['data'] ?? [];
            
            $sensitiveKeys = ['password', 'token', 'secret', 'api_key', 'access_token'];
            foreach ($sensitiveKeys as $key) {
                if (isset($data[$key])) {
                    $data[$key] = '[REDACTED]';
                }
            }
            
            $request['data'] = $data;
        }
        
        return $event;
    },
];
