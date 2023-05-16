<?php

declare (strict_types=1);

namespace HPlus\ChatPlugins;

use Hyperf\Contract\ConfigInterface;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Utils\ApplicationContext;

#[\Hyperf\HttpServer\Annotation\Controller(prefix: '/')]
class ChatPlugin
{
    protected ConfigInterface $config;

    protected ResponseInterface $response;

    public function __construct()
    {
        $this->config = ApplicationContext::getContainer()->get(ConfigInterface::class);
        $this->response = ApplicationContext::getContainer()->get(ResponseInterface::class);
    }

    #[\Hyperf\HttpServer\Annotation\GetMapping(path: '/{plugin_id}/.well-known/ai-plugin.json')]
    public function plugin(string $plugin_id)
    {
        $json = file_get_contents(BASE_PATH . '/runtime/plugin/' . $plugin_id . '/ai-plugin.json');
        return $this->response->json(json_decode($json, true));
    }

    #[\Hyperf\HttpServer\Annotation\GetMapping(path: '/{plugin_id}}/openapi.yaml')]
    public function openai(string $plugin_id)
    {
        $data = file_get_contents(BASE_PATH . '/runtime/plugin/' . $plugin_id . '/openapi.yaml');
        /** @var ResponseInterface $response */
        return $this->response->withAddedHeader('content-type', 'text/yaml; charset=utf-8')
            ->withBody(new SwooleStream((string)$data));
    }
}
