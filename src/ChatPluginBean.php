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

class ChatPluginBean
{
    public function __construct(public string $name = '', public string $plugin_id= '', public array $paths = []) # 剩下的自己写
    {
    }

    /**
     * @return array
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * @param array $paths
     */
    public function setPaths(array $paths): void
    {
        $this->paths = $paths;
    }


    /**
     * @param array $paths
     */
    public function addPath($path,$method,array $paths): void
    {
        $this->paths[$path][$method] = $paths;
    }

    public function addPathConsumes(mixed $path, string $method, array $array)
    {
        if (!isset($this->paths[$path][$method]['consumes'])){
            $this->paths[$path][$method]['consumes'] = [];
        }
        $this->paths[$path][$method]['consumes'] = $array;

    }
    public function addPathSecurity(mixed $path, string $method, array $array)
    {
        if (!isset($this->paths[$path][$method]['security'])){
            $this->paths[$path][$method]['security'] = [];
        }
        $this->paths[$path][$method]['security'][] = $array;
    }
}
