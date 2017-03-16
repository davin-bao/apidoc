<?php

return [
    // 每次请求都重新生成 json
    "generateAlways" => true,

    'app_list'=> [
        //应用名称
        'apiv1' => [
            //标题
            'title' => '域名系统1 API文档',
            //请求的 API HOST
            'api_host' => 'http://apiv1.local.com/',
            // 扫描带有注释的目录
            'scan_dir' => dirname(dirname(__FILE__)) . '/examples',
            // 不进行扫描的目录
            "excludes" => [],
        ],
        //应用名称
        'apiv2' => [
            //标题
            'title' => '域名系统API文档',
            //请求的 API HOST
            'api_host' => 'http://apiv2.local.com/',
            // 扫描带有注释的目录
            'scan_dir' => dirname(dirname(__FILE__)) . '/examples2',
            // 不进行扫描的目录
            "excludes" => [],
        ]
    ],
];