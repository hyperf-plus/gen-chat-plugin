<?php

declare (strict_types=1);

namespace HPlus\ChatPlugins;

use Hyperf\Contract\ConfigInterface;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Utils\ApplicationContext;

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
        $output_dir = trim($this->config->get('plugins.php.output_dir') ?: 'runtime/plugin', '/');
        $filename = sprintf('%s/%s/%s/ai-plugin.json', BASE_PATH, $output_dir, $plugin_id);
        $data = file_get_contents($filename);
        return $this->response->json(json_decode($data, true));
    }

    #[GetMapping(path: '/{plugin_id}}/openapi.yaml')]
    public function openai(string $plugin_id)
    {
        $output_dir = trim($this->config->get('plugins.php.output_dir') ?: 'runtime/plugin', '/');
        $filename = sprintf('%s/%s/%s/openapi.yaml', BASE_PATH, $output_dir, $plugin_id);
        $data = file_get_contents($filename);
        return $this->response->withAddedHeader('content-type', 'text/yaml; charset=utf-8')
            ->withBody(new SwooleStream((string)$data));
    }
}
