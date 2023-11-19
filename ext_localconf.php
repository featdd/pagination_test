<?php
declare(strict_types=1);

use Featdd\PaginationTest\Controller\TestController;
use Featdd\PaginationTest\MetaTag\PaginationMetaTagManager;
use Featdd\PaginationTest\Routing\ExtendedExtbasePluginEnhancer;
use TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

call_user_func(
    function () {
        ExtensionManagementUtility::addTypoScriptConstants(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:pagination_test/Configuration/TypoScript/constants.typoscript">'
        );

        ExtensionManagementUtility::addTypoScriptSetup(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:pagination_test/Configuration/TypoScript/setup.typoscript">'
        );

        ExtensionUtility::configurePlugin(
            'PaginationTest',
            'pagination',
            [TestController::class => 'list'],
            [TestController::class => ''],
            ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
        );

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['enhancers']['ExtendedExtbase'] = ExtendedExtbasePluginEnhancer::class;

        /** @var \TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry $metaTagManagerRegistry */
        $metaTagManagerRegistry = GeneralUtility::makeInstance(MetaTagManagerRegistry::class);
        $metaTagManagerRegistry->registerManager('pagination', PaginationMetaTagManager::class);
    }
);
