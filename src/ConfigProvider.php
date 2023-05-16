<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace HPlus\ChatPlugins;

class ConfigProvider
{

    public function __invoke(): array
    {

        return [
            'commands' => [
            ],
            'dependencies' => [
            ],
            'listeners' => [
                BootAppConfListener::class,
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'plugins',
                    'description' => 'hyperf-plugins',
                    'source' => __DIR__ . '/../publish/plugins.php',
                    'destination' => BASE_PATH . '/config/autoload/plugins.php',
                ],
            ],
        ];
    }
}
