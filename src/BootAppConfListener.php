<?php
declare(strict_types=1);

namespace HPlus\ChatPlugins;

use HPlus\ChatPlugins\ChatPlugins\ChatPluginsJson;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BeforeServerStart;
use Hyperf\HttpServer\Router\DispatcherFactory;
use Hyperf\HttpServer\Router\Handler;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Str;

class BootAppConfListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            BeforeServerStart::class,
        ];
    }

    public function process(object $event): void
    {
        $container = ApplicationContext::getContainer();
        $logger = $container->get(LoggerFactory::class)->get('swagger');
        $config = $container->get(ConfigInterface::class);
        if (!$config->get('swagger.enable')) {
            $logger->debug('swagger not enable');
            return;
        }
        $output = $config->get('swagger.output_file');
        if (!$output) {
            $logger->error('/config/autoload/swagger.php need set output_file');
            return;
        }
        $router = $container->get(DispatcherFactory::class)->getRouter('http');
        $data = $router->getData();
        $servers = $config->get('server.servers');
        if (count($servers) > 1 && !Str::contains($output, '{server}')) {
            $logger->warning('You have multiple serve, but your swagger.output_file not contains {server} var');
        }
        foreach ($servers as $server) {
            $swagger = new ChatPluginsJson($server['name']);
            #跳过非http的服务
            if ($server['name'] != 'http') {
                continue;
            }

            $ignore = $config->get('swagger.ignore', function ($controller, $action) {
                return false;
            });
            $plugins = [];
            array_walk_recursive($data, function ($item) use ($swagger, $ignore, &$plugins) {
                if ($item instanceof Handler && !($item->callback instanceof \Closure)) {
                    [$controller, $action] = $this->prepareHandler($item->callback);
                    if (!$ignore($controller, $action)&& $plugin = $swagger->addPath($controller, $action, $item->route)) {
                        $plugins[$plugin->getPluginId()][] = $plugin;
                    }
                }
            });

            /** @var ChatPluginBean $plugin */
            foreach ($plugins as $plugin_id => $plugin) {
                p($plugin);
                //
                // $outputFile = str_replace('{server}', $this->server, $outputFile);
                // file_put_contents($outputFile, json_encode($this->swagger, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                // print_r('Generate swagger.json success!' . PHP_EOL);
                //
                //

            }


            // $plugin->getAiPlugin();
            //

            // $swagger->save();
        }
    }

    protected function prepareHandler($handler): array
    {
        if (is_string($handler)) {
            if (strpos($handler, '@') !== false) {
                return explode('@', $handler);
            }
            return explode('::', $handler);
        }
        if (is_array($handler) && isset($handler[0], $handler[1])) {
            return $handler;
        }
        throw new \RuntimeException('Handler not exist.');
    }
}
