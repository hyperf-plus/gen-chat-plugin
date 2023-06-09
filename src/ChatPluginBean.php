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

use HPlus\ChatPlugins\Annotation\ChatPluginAnnotation;

class ChatPluginBean
{
    public function __construct(
        protected array                 $data = [],
        protected string                $plugin_id = '',
        protected string                $openapi = '',
        protected string                $name = '',
        protected array                 $tags = [],
        protected array                 $info = [],
        protected array                 $servers = [],
        protected array                 $paths = [],
        protected ?ChatPluginAnnotation $aiPlugin = null,
    )
    {
        foreach ($data as $k => $v) {
            if (property_exists($this, $k)) {
                $this->{$k} = $v;
            }
        }
    }

    public function addTags(array $tags): void
    {
        $this->tags[] = $tags;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return ChatPluginAnnotation
     */
    public function getAiPlugin(): ChatPluginAnnotation
    {
        return $this->aiPlugin;
    }

    /**
     * @param ChatPluginAnnotation $aiPlugin
     */
    public function setAiPlugin(ChatPluginAnnotation $aiPlugin): void
    {
        $this->aiPlugin = $aiPlugin;
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
     * @param $path
     * @param $method
     * @param array $paths
     */
    public function addPath($path, $method, array $paths): void
    {
        $this->paths[$path][$method] = $paths;
    }

    public function addPathSecurity(mixed $path, string $method, array $array)
    {
        if (!isset($this->paths[$path][$method]['security'])) {
            $this->paths[$path][$method]['security'] = [];
        }
        $this->paths[$path][$method]['security'][] = $array;
    }

    /**
     * @return array
     */
    public function getServers(): array
    {
        return $this->servers;
    }

    /**
     * @param array $servers
     */
    public function setServers(array $servers): void
    {
        $this->servers = $servers;
    }

    /**
     * @return array
     */
    public function getInfo(): array
    {
        return $this->info;
    }

    /**
     * @param array $info
     */
    public function setInfo(array $info): void
    {
        $this->info = $info;
    }

    /**
     * @return string
     */
    public function getPluginId(): string
    {
        return $this->plugin_id;
    }

    /**
     * @param string $plugin_id
     */
    public function setPluginId(string $plugin_id): void
    {
        $this->plugin_id = $plugin_id;
    }

    public function toArray()
    {
        $items = [];
        foreach ($this as $k => $v) {
            if (in_array($k, ['data', 'aiPlugin', 'plugin_id'])) {
                continue;
            }
            $items[$k] = $v;
        }
        return $items;
    }

}
