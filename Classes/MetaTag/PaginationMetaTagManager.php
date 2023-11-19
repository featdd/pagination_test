<?php
declare(strict_types=1);

namespace Featdd\PaginationTest\MetaTag;

/***
 *
 * This file is part of the "Pagination Test" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2023 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use GeorgRinger\NumberedPagination\NumberedPagination;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\MetaTag\AbstractMetaTagManager;
use TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class PaginationMetaTagManager extends AbstractMetaTagManager
{
    /**
     * @var array[]
     */
    protected $handledProperties = [
        'prev' => [],
        'next' => [],
    ];

    /**
     * @param string $property
     * @return string
     */
    public function renderProperty(string $property): string
    {
        $property = strtolower($property);
        $metaTags = [];

        foreach ($this->getProperty($property) as $propertyItem) {
            $metaTags[] = sprintf('<link rel="%s" href="%s"/>', $property, $propertyItem['content']);
        }

        return implode(PHP_EOL, $metaTags);
    }
}
