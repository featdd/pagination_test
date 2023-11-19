<?php
declare(strict_types=1);

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

call_user_func(
    function () {
        ExtensionUtility::registerPlugin(
            'PaginationTest',
            'pagination',
            'Test Pagination',
            'ext-pagination_test',
            'special'
        );
    }
);
