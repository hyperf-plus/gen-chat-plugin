<?php

return [
    'enable' => true,
    'output_dir' => BASE_PATH . '/runtime/plugin',
    'base_uri' => 'https://example.com',
    // 忽略的hook, 非必须 用于忽略符合条件的接口, 将不会输出到上定义的文件中
    'ignore' => function ($controller, $action) {
        return false;
    },
    // 自定义验证器错误码、错误描述字段
    'error_code' => 400,
    'http_status_code' => 400,
    'field_error_code' => 'code',
    'field_error_message' => 'message',
    // swagger 的基础配置
    'swagger' => [
        'swagger' => '2.0',
        'info' => [
            'description' => 'hyperf swagger api desc',
            'version' => '1.0.0',
            'title' => 'HYPERF API DOC',
        ],
        'host' => '', //默认空为当前目录
        'schemes' => ['http'],
        "securityDefinitions" => [
            "token" => [
                "type" => "apiKey",
                "name" => "token",
                "in" => "header"
            ]
        ],
    ],
];
