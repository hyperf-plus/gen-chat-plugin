<?php
declare(strict_types=1);

namespace HPlus\ChatPlugins;

use HPlus\ChatPlugins\Annotation\ChatPluginAnnotation;
use HPlus\ChatPlugins\ChatPlugins\ChatPluginsJson;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BootApplication;
use Hyperf\HttpServer\Router\DispatcherFactory;
use Hyperf\HttpServer\Router\Handler;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Context\ApplicationContext;
use Hyperf\Stringable\Str;

class BootAppConfListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            BootApplication::class,
        ];
    }

    public function process(object $event): void
    {
        $container = ApplicationContext::getContainer();
        $logger = $container->get(LoggerFactory::class)->get('plugins');
        $config = $container->get(ConfigInterface::class);


        if (!$config->get('plugins.enable')) {
            $logger->debug('swagger not enable');
            return;
        }

        $output = $config->get('plugins.output_dir');
        if (!$output) {
            $logger->error('/config/autoload/plugins.php need set output_file');
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

            $ignore = $config->get('plugins.ignore', function ($controller, $action) {
                return false;
            });



            $plugins = [];
            array_walk_recursive($data, function ($item) use ($swagger, $ignore, &$plugins) {
                if ($item instanceof Handler && !($item->callback instanceof \Closure)) {
                    [$controller, $action] = $this->prepareHandler($item->callback);
                    if (!$ignore($controller, $action) && $plugin = $swagger->addPath($controller, $action, $item->route)) {
                        if (empty($plugins[$plugin->getPluginId()])) {
                            $plugins[$plugin->getPluginId()] = [
                                'ai-plugin' => $plugin->getAiPlugin(),
                                'openai' => [],
                            ];
                        }
                        $paths = $plugins[$plugin->getPluginId()]['openai']['paths'] ?? [];
                        $plugins[$plugin->getPluginId()]['openai']['paths'] = array_merge($paths, $plugin->getPaths());
                        $plugins[$plugin->getPluginId()]['openai'] += $plugin->toArray();
                    }
                }
            });

            /** @var ChatPluginBean $plugin */
            foreach ($plugins as $plugin_id => $plugin) {
                $output_dir = trim($config->get('plugins.php.output_dir') ?: 'runtime/plugin', '/');
                $filename = sprintf('%s/%s/%s/openapi.json', BASE_PATH, $output_dir, $plugin_id);
                $dir = dirname($filename);
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                /** @var ChatPluginAnnotation $aiPlugin */
                $aiPlugin = $plugin['ai-plugin'];
                $openai = $plugin['openai'];

                file_put_contents($filename, json_encode($openai, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

                $filename = sprintf('%s/%s/%s/ai-plugin.json', BASE_PATH, $output_dir, $plugin_id);
                file_put_contents($filename, json_encode($aiPlugin->toArray(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                print_r('Generate ' . $plugin_id . ' success!' . PHP_EOL);
            }
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
