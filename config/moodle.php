<?php

return [

    'url' => env(
        'MOODLE_URL',
        'https://cv.utm.mx'
    ),

    'service' => env(
        'MOODLE_SERVICE',
        'moodle_mobile_app'
    ),

    'teacher_roles' => [
        'editingteacher',
        'teacher',
    ],

    'verify_ssl' => filter_var(
        env('MOODLE_VERIFY_SSL', true),
        FILTER_VALIDATE_BOOLEAN
    ),

];