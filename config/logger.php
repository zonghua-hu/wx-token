<?php

use WecarSwoole\Util\File;

return [
    'debug' => [
        'file' => File::join(STORAGE_ROOT, 'logs/info.log'),
    ],
    'info' => [
        'file' => File::join(STORAGE_ROOT, 'logs/info.log'),
    ],
    'warning' => [
        'file' => File::join(STORAGE_ROOT, 'logs/warning.log'),
    ],
    'error' => [
        'file' => File::join(STORAGE_ROOT, 'logs/error.log'),
    ],
    'critical' => [
        'mailer' => [
            'driver' => 'default',
            'subject' => '喂车告警',
            'to' => [
            ]
        ],
        'file' => File::join(STORAGE_ROOT, 'logs/error.log'),
    ],
    'emergency' => [
        'mailer' => [
            'driver' => 'default',
            'subject' => '喂车告警',
            'to' => [
                'zonghua.hu@weicheche.cn'=>'胡宗华'
            ]
        ],
        'file' => File::join(STORAGE_ROOT, 'logs/error.log'),
        'sms' => [
            '13636807794'=>'胡宗华'
        ]
    ],
];
