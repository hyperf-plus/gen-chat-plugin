<?php

return [
    'enable' => true,
    'output_dir' => BASE_PATH . '/runtime/plugin',
    'base_uri' => 'https://example.com',
    // 忽略的hook, 非必须 用于忽略符合条件的接口, 将不会输出到上定义的文件中
    'ignore' => function ($controller, $action) {
        return false;
    },
    'plugin' => [
        'schema_version' => 'v1',
        'auth' => [
            'type' => 'none',
        ],
        'logo_url' => '',
        'contact_email' => '',
        'legal_info_url' => '',
    ],
    // openapi 的基础配置
    'openapi' => [
        'openapi' => '3.1.0',
        'info' => [
            'title' => '请求',
            'description' => '',
            'version' => 'v1',
        ],
        'components' => [
            'schemas' => [],
        ],
    ],
];
