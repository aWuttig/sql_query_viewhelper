<?php
defined('TYPO3_MODE') || die();
$boot = function ($_EXTKEY) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Powermail Body Fluid Template');
};
$boot($_EXTKEY);
unset($boot);