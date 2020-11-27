<?php

/*
 * This file is part of the godruoyi/ocr.
 *
 * (c) Godruoyi <gmail@godruoyi.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default client
    |--------------------------------------------------------------------------
    |
    | 指定一个默认的 client 名称，其值需要在下列的 drivers 数组中配置。
    |
    */
    'default' => 'aliyun',

    /*
    |--------------------------------------------------------------------------
    | Client 配置
    |--------------------------------------------------------------------------
    |
    | Client 配置信息，包括基本密钥等；注意目前 aliyun 暂只支持 appcode 方式。
    |
    */
    'drivers' => [
        'aliyun' => [
            'appcode'    => '',
            'secret_id'  => '',
            'secret_key' => '',
        ],

        'baidu' => [
            'access_key' => '',
            'secret_key' => '',
        ],

        'tencent' => [
            'secret_id'  => '',
            'secret_key' => '',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 日志配置
    |--------------------------------------------------------------------------
    |
    | 基于 Monolog，可用的日志驱动有： "single", "daily", "slack", "syslog",
    | "errorlog", "monolog", "custom", "stack"
    |
    */
    'log'     => [
        'enable'   => true,
        'default'  => 'stack',
        'channels' => [
            'daily' => [
                'name'   => 'OCR',
                'driver' => 'daily',
                'path'   => '/tmp/ocr.log',
                'level'  => 'debug',
                'days'   => 14,
            ],
        ],
    ],
];