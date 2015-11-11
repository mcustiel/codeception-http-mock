<?php
namespace Codeception\Module;

use Codeception\Module as CodeceptionModule;
use Codeception\TestCase;
use Codeception\Module;
use Codeception\Lib\ModuleContainer;
use Mcustiel\DependencyInjection\DependencyInjectionService;

class HttpMock extends CodeceptionModule
{
    private $diManager;

    public function __construct(ModuleContainer $moduleContainer, $config = null)
    {
        parent::__construct($moduleContainer, $config);
        $this->diManager = new DependencyInjectionService();
    }

    /**
     * {@inheritDoc}
     * @see \Codeception\Module::_before()
     */
    public function _before(TestCase $testCase)
    {
        parent::_before($testCase);
    }

    /**
     * @return \InterNations\Component\HttpMock\MockBuilder
     */
    public function expectRequest()
    {
        return $this->diManager->get('httpMockBuilder');
    }

    public function doNotExpectAnyOtherRequest()
    {
        $this->diManager->get('httpMockServer')->setUp(
            $this->diManager->get('httpMockBuilder')->flushExpectations()
        );
    }

    /**
     * {@inheritDoc}
     * @see \Codeception\Module::_after()
     */
    public function _after(TestCase $testCase)
    {
        parent::_after($testCase);
        $error = (string)$this->diManager->get('httpMockServer')->getIncrementalErrorOutput();
        if ($error != '') {
            throw new \Exception(
                'HTTP mock server standard error output should be empty but is: ' .
                var_export($error, true)
            );
        };
    }
}
