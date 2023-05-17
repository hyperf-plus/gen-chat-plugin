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

use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\ConfigInterface;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Contract\ResponseInterface;

#[\Hyperf\HttpServer\Annotation\Controller]
class ChatPlugin
{
    protected ConfigInterface $config;

    protected ResponseInterface $response;

    public function __construct()
    {
        $this->config = ApplicationContext::getContainer()->get(ConfigInterface::class);
        $this->response = ApplicationContext::getContainer()->get(ResponseInterface::class);
    }

    #[GetMapping(path: '/{plugin_id}/.well-known/ai-plugin.json')]
    public function plugin(string $plugin_id)
    {
        $output_dir = $this->config->get('plugins.output_dir') ?: 'runtime/plugin';
        $output_dir = str_replace(BASE_PATH, '', $output_dir);
        $output_dir = trim($output_dir, '/');
        $filename = sprintf('%s/%s/%s/ai-plugin.json', BASE_PATH, $output_dir, $plugin_id);
        $data = file_get_contents($filename);
        return $this->response->json(json_decode($data, true));
    }

    #[GetMapping(path: '/{plugin_id}/openapi.json')]
    public function openai(string $plugin_id)
    {
        $output_dir = $this->config->get('plugins.output_dir') ?: 'runtime/plugin';
        $output_dir = str_replace(BASE_PATH, '', $output_dir);
        $output_dir = trim($output_dir, '/');
        $filename = sprintf('%s/%s/%s/openapi.json', BASE_PATH, $output_dir, $plugin_id);
        $data = file_get_contents($filename);
        return $this->response->withAddedHeader('content-type', 'text/yaml; charset=utf-8')
            ->withBody(new SwooleStream((string)$data));
    }
}
