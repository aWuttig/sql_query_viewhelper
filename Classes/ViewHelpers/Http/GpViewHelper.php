<?php

namespace Wuttig\AwViewHelper\ViewHelpers\Http;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class GpViewHelper
 *
 * @package Wuttig\AwViewHelper\ViewHelpers\Http
 */
class GpViewHelper extends AbstractViewHelper
{
    /**
     *
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('name', 'string', 'The name of the GPVar', true);
        $this->registerArgument('merged', 'boolean', 'Use _GPMerged', false, false);
    }

    /**
     * @param string $name The name of the GPVar
     * @param bool $merged
     * @return array|mixed
     */
    public function render()
    {
        $name = $this->arguments['name'];
        $merged = $this->arguments['merged'];

        if (strpos($name, '.') !== false) {
            $segments = explode('.', $name);
            $variableRootName = array_shift($segments);
            if ($merged) {
                $result = ObjectAccess::getPropertyPath(
                    GeneralUtility::_GPmerged($variableRootName),
                    implode('.', $segments)
                );
            } else {
                $result = ObjectAccess::getPropertyPath(
                    GeneralUtility::_GP($variableRootName),
                    implode('.', $segments)
                );
            }
        } else {
            if ($merged) {
                $result = GeneralUtility::_GPmerged($name);
            } else {
                $result = GeneralUtility::_GP($name);
            }
        }
        return $result;
    }
}
