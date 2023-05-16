<?php

declare (strict_types=1);
namespace HPlus\ChatPlugins;

use GuzzleHttp\Psr7\Stream;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Utils\ApplicationContext;
use Psr\Http\Message\StreamInterface;
#[\Hyperf\HttpServer\Annotation\AutoController(prefix: '/swagger')]
class ChatPlugin
{
    /**
     * @var ConfigInterface $config
     */
    protected $config;
    
    public function __construct()
    {
        $this->config = ApplicationContext::getContainer()->get(ConfigInterface::class);
    }
    
    public function plugin()
    {
        if (!$this->config->get('swagger.enable', false)) {
            return 'swagger not start';
        }
        $domain = $this->config->get('swagger.host', '');
        $url = $domain . '/swagger/api?s=' . time();
        $res = ApplicationContext::getContainer()->get(ResponseInterface::class);

        return $res->withBody(new SwooleStream(""))->withHeader('content-type', 'text/html; charset=utf8');
    }
    
    public function openai()
    {
        if (!$this->config->get('swagger.enable', false)) {
            return 'swagger not start';
        }
        $domain = $this->config->get('swagger.output_file', '');
        return json_decode(file_get_contents($domain), true);
    }
}