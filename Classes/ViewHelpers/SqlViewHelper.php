<?php

namespace Wuttig\SqlQueryViewHelper;

use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;


/**
 * Class SqlViewHelper
 * @package Wuttig\SqlQueryViewHelper
 */
class SqlViewHelper extends AbstractViewHelper
{
    /**
     *
     * @param string $name
     * @param string $query
     * @param string $table
     * @param string $fields
     * @param string $condition
     * @param string $offset
     * @param string $limit
     * @param string $orderBy
     * @param boolean $silent
     */
    public function render(
        $name = null,
        $query = null,
        $table = null,
        $fields = null,
        $condition = null,
        $offset = null,
        $limit = null,
        $orderBy = null,
        $silent = false
    ) {
        $databaseConnection = $this->getDatabaseConnection();

        if (!$query && !$table) {
            $query = $this->renderChildren();
        } else {
            if ($table && !$query) {
                $query = $databaseConnection->SELECTquery($fields, $table, $condition, '', $orderBy, $limit,
                    $offset);
            }
        }
        $result = $databaseConnection->sql($query);
        if (!$result) {
            if ($silent) {
                // important force-return here to avoid error messages caused by processing of $result
                return null;
            } else {
                return "<div>Invalid SQL query! Error was: " . mysql_error() . "</div>";
            }
        }
        $rows = array();
        while ($row = $databaseConnection->sql_fetch_assoc($result)) {
            array_push($rows, $row);
        }
        if (count($rows) == 0) {
            $value = '0';
        } else {
            if (count($rows) == 1) {
                $value = array_pop($rows);
                if (count($value) == 1) {
                    $value = array_pop($value);
                }
            } else {
                $value = $rows;
            }
        }
        if ($name === null) {
            if (!$silent) {
                return $value;
            }
        } else {
            if ($this->templateVariableContainer->exists($name)) {
                $this->templateVariableContainer->remove($name);
            }
            $this->templateVariableContainer->add($name, $value);
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