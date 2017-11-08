<?php

namespace Wuttig\ViewHelper\ViewHelpers\Query;

use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;


/**
 * Class ExecuteViewHelper
 * @package Wuttig\ViewHelper\ViewHelpers\Query
 */
class ExecuteViewHelper extends AbstractViewHelper
{
    /**
     * @param null $query
     * @param null $table
     * @param null $fields
     * @param null $condition
     * @param null $offset
     * @param null $limit
     * @param null $orderBy
     * @param null $as
     * @return array|mixed|string
     */
    public function render(
        $query = null,
        $table = null,
        $fields = null,
        $condition = null,
        $offset = null,
        $limit = null,
        $orderBy = null,
        $as = null
    ) {
        $databaseConnection = $this->getDatabaseConnection();
        $rows = [];

        if (!$query && !$table) {
            $this->renderChildren();
        } else {
            $rows = $databaseConnection->exec_SELECTgetRows($fields, $table, $condition, '', $orderBy, $limit);
        }
        if ($as === null) {
            return $rows;
        } else {
            if ($this->templateVariableContainer->exists($as)) {
                $this->templateVariableContainer->remove($as);
            }
            $this->templateVariableContainer->add($as, $rows);
        }
    }

    /**
     * @return DatabaseConnection
     */
    public function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }

}