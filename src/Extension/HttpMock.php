<?php
namespace Codeception\Extension;

use Codeception\Platform\Extension as CodeceptionExtension;
use Codeception\Extension;
use InterNations\Component\HttpMock\Matcher\ExtractorFactory;
use InterNations\Component\HttpMock\Server;
use InterNations\Component\HttpMock\MockBuilder;
use InterNations\Component\HttpMock\Matcher\MatcherFactory;
use Mcustiel\DependencyInjection\DependencyInjectionService;
use Codeception\Events;

class HttpMock extends CodeceptionExtension
{
    /**
     * @var array
     */
    private $defaults = [
        'port' => '28080',
        'host' => 'localhost'
    ];

    public static $events = [
        Events::SUITE_BEFORE => 'startHttpMock',
        Events::SUITE_AFTER  => 'stopHttpMock'
    ];
    
    /**
     * @var \InterNations\Component\HttpMock\Server
     */
    private $server;
    /**
     * @var \Mcustiel\DependencyInjection\DependencyInjectionService
     */
    private $diManager;

    public function __construct(array $config, array $options)
    {
        parent::__construct(array_merge($this->defaults, $config), $options);
        $this->diManager = new DependencyInjectionService();

    }



    public function startHttpMock()
    {
        echo "Starting http mock server on {$this->config['host']}:{$this->config['port']}" . PHP_EOL;
        $this->server = new Server($this->config['port'], $this->config['host']);
        $this->server->start();
        $this->setUpDependencyInjection();
    }

    private function setUpDependencyInjection()
    {
        $this->diManager->register(
            'httpMockServer',
            function () {
                return $this->server;
            }
        );
        $this->diManager->register(
            'httpMockBuilder',
            function () {
                return new MockBuilder(new MatcherFactory(), new ExtractorFactory());
            }
        );
    }

    public function stopHttpMock()
    {
        echo 'Stoping http mock server' . PHP_EOL;
        $this->server->stop();
    }
}
