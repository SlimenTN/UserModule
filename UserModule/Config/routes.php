<?php

return [
    'login' => [
        'pattern' => '/login',
        'command' => 'User_Default:login',
    ],
    'logout' => [
        'pattern' => '/logout',
        'command' => 'User_Default:logout',
    ],
    'forgot_password' => [
        'pattern' => '/forgot_password',
        'command' => 'User_Default:forgotPassword',
    ],
    'registration' => [
        'pattern' => '/registration',
        'command' => 'User_Default:registration',
    ],
    'profile' => [
        'pattern' => '/profile',
        'command' => 'User_Default:profile',
    ],
];