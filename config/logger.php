<?php

use WecarSwoole\Util\File;
use function WecarSwoole\Config\apollo;

return [
    'debug' => [
        'file' => File::join(STORAGE_ROOT, 'storage/logs/debug.log'),
    ],
    'info' => [
        'file' => File::join(STORAGE_ROOT, 'storage/logs/info.log'),
    ],
    'warning' => [
        'file' => File::join(STORAGE_ROOT, 'storage/logs/warning.log'),
    ],
    'error' => [
        'file' => File::join(STORAGE_ROOT, 'storage/logs/error.log'),
    ],

    'critical' => [
        'mailer' => [
            'driver' => 'default',
            'subject' => '喂车告警',
            'to' => [
                'to' => json_decode(apollo('application', 'logger.emails'), true) ?: []
            ]
        ],
        'file' => File::join(STORAGE_ROOT, 'storage/logs/error.log'),
    ],
    'emergency' => [
        'mailer' => [
            'driver' => 'default',
            'subject' => '喂车告警',
            'to' => [
                'zonghua.hu@weicheche.cn' => '胡宗华'
            ]
        ],
        'file' => File::join(STORAGE_ROOT, 'storage/logs/error.log'),
        'sms' => [
            '13636807794' => '胡宗华'
        ]
    ],
];
