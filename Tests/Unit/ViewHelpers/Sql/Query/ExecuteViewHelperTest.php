<?php

namespace Wuttig\AwViewHelper\Tests\Unit\ViewHelpers\Sql\Query;

use PHPUnit_Framework_MockObject_MockObject;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Fluid\Tests\Unit\ViewHelpers\ViewHelperBaseTestcase;
use Wuttig\AwViewHelper\ViewHelpers\Sql\Query\ExecuteViewHelper;

/**
 * Class ExecuteViewHelperTest
 * @package Wuttig\AwViewHelper\Tests\Unit\ViewHelpers\Sql\Query
 */
class ExecuteViewHelperTest extends ViewHelperBaseTestcase
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
        $this->injectDependenciesIntoViewHelper($this->viewHelper);
        $this->viewHelper->initializeArguments();
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

        $GLOBALS['TYPO3_DB'] = $this->getMockBuilder(DatabaseConnection::class)->setMethods(
            [
                'fullQuoteStr',
                'exec_SELECTquery',
                'sql_fetch_assoc'
            ]
        )
            ->disableOriginalConstructor()->getMock();
        $GLOBALS['TYPO3_DB']
            ->expects($this->any())
            ->method('fullQuoteStr')
            ->willReturnArgument(0);

        $rows = [
            ['uid' => 1, 'title' => 'foo'],
            ['uid' => 2, 'title' => 'bar']
        ];

        $GLOBALS['TYPO3_DB']
            ->expects($this->any())
            ->method('exec_SELECTgetRows')
            ->with('uid,title', 'pages', ' pid = 1 ')
            ->willReturn($rows);

        $this->viewHelper->render();
    }
}
