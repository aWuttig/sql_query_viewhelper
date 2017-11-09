<?php

namespace Wuttig\AwViewHelper\ViewHelpers\Sql\Query;

use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class ExecuteViewHelper
 * @package Wuttig\AwViewHelper\ViewHelpers\Sql\Query
 */
class ExecuteViewHelper extends AbstractViewHelper
{
    /**
     *
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('table', 'string', 'The table to SELECT', true);
        $this->registerArgument('fields', 'string', 'The fields to SELECT', true);
        $this->registerArgument('conditions', 'array', 'WHERE conditions');
        $this->registerArgument('orderBy', 'string', 'ORDER_BY');
        $this->registerArgument('limit', 'integer', '');
        $this->registerArgument('as', 'string', '', true);
    }

    /**
     * @return string
     */
    public function render()
    {
        $table = $this->arguments['table'];
        $fields = $this->arguments['fields'];
        $conditions = $this->arguments['conditions'];
        $orderBy = $this->arguments['orderBy'];
        $limit = $this->arguments['limit'];
        $as = $this->arguments['as'];

        $databaseConnection = $this->getDatabaseConnection();


        $whereClause = '';
        foreach ($conditions as $condition) {
            $operator = isset($condition['operator']) ?  $condition['operator'] : '';
            $conditionString = vsprintf(
                '%s %s %s %s ',
                [
                    $operator,
                    $condition['field'],
                    $condition['expression'],
                    $condition['value']
                ]
            );

            $whereClause .= $conditionString;
        }

        $rows = $databaseConnection->exec_SELECTgetRows($fields, $table, $whereClause, '', $orderBy, $limit);

        if (true === $this->templateVariableContainer->exists($as)) {
            $this->templateVariableContainer->remove($as);
        }
        $this->templateVariableContainer->add(
            $as,
            $rows
        );
        $content = $this->renderChildren();
        $this->templateVariableContainer->remove($as);
        return $content;
    }

    /**
     * @return DatabaseConnection
     */
    public function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
