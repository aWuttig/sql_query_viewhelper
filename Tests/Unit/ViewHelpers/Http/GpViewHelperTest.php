<?php

namespace Wuttig\AwViewHelper\Tests\Unit\ViewHelpers\Http;

use PHPUnit_Framework_MockObject_MockObject;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Fluid\Tests\Unit\ViewHelpers\ViewHelperBaseTestcase;
use Wuttig\AwViewHelper\ViewHelpers\Http\GpViewHelper;
use Wuttig\AwViewHelper\ViewHelpers\Sql\Query\ExecuteViewHelper;

/**
 * Class GpViewHelperTest
 * @package Wuttig\AwViewHelper\Tests\Unit\ViewHelpers\Http
 */
class GpViewHelperTest extends ViewHelperBaseTestcase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject|GpViewHelper
     */
    protected $viewHelper;

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();
        $this->viewHelper = $this->getMock(GpViewHelper::class, ['renderChildren']);
        $this->injectDependenciesIntoViewHelper($this->viewHelper);
        $this->viewHelper->initializeArguments();
    }

    /**
     * @test
     */
    public function renderReturnGetPostParameterWithoutMerged()
    {
        $arguments = [
            'name' => 'tx_foo_pi1.foo.bar'
        ];
        $_GET['tx_foo_pi1']['foo']['bar'] = 'get';
        $_POST['tx_foo_pi1']['foo']['bar'] = 'post';

        $this->viewHelper->setArguments($arguments);

        $result = $this->viewHelper->render();
        $this->assertEquals('post', $result);
    }

    /**
     * @test
     */
    public function renderReturnGetPostParameterWithMerged()
    {
        $arguments = [
            'name' => 'tx_foo_pi1',
            'merged' => true
        ];
        $_GET['tx_foo_pi1']['foo']['bar'] = 'get';
        $_POST['tx_foo_pi1']['foo']['bar'] = 'post';

        $this->viewHelper->setArguments($arguments);

        $result = $this->viewHelper->render();

        $expected = [
            'foo' => [
                'bar' => 'post'
            ]
        ];

        $this->assertArraySubset($expected, $result);
    }

    /**
     * @test
     */
    public function renderReturnGetPostParameterWithMergedAndPropertyPath()
    {
        $arguments = [
            'name' => 'tx_foo_pi1.foo',
            'merged' => true
        ];
        $_GET['tx_foo_pi1']['foo']['bar'] = 'get';
        $_POST['tx_foo_pi1']['foo']['bar'] = 'post';

        $this->viewHelper->setArguments($arguments);

        $result = $this->viewHelper->render();

        $expected = [
            'bar' => 'post'
        ];

        $this->assertArraySubset($expected, $result);
    }
}
