<?php

namespace Wuttig\AwViewHelper\Tests\Functional\ViewHelpers\Sql\Query;

use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Wuttig\AwViewHelper\ViewHelpers\Sql\Query\ExecuteViewHelper;

/**
 * Class ExecuteViewHelperTest
 * @package Wuttig\AwViewHelper\Tests\Functional\ViewHelpers\Sql\Query
 */
class ExecuteViewHelperTest extends FunctionalTestCase
{

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|ExecuteViewHelper
     */
    protected $viewHelper;

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();
        $this->viewHelper = $this->getMock(ExecuteViewHelper::class, ['renderChildren']);

//        $this->importDataSet('ntf://Database/pages.sql');
        $this->importDataSet('ntf://Database/pages.xml');
    }

    /**
     * @test
     */
    public function renderListOfQueryResults()
    {
        $arguments = [
            'table' => 'pages',
            'fields' => 'uid,title',
            'conditions' => [
                [
                    'field' => 'pid',
                    'expression' => '=',
                    'value' => 1,
                ]
            ],
            'as' => 'results'
        ];

        $this->viewHelper->setArguments($arguments);

        $this->assertSame(1, $this->getDatabaseConnection()->exec_SELECTcountRows('pages'));

//        $this->viewHelper->render();
    }
}
