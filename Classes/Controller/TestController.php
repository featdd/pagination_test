<?php
declare(strict_types=1);

namespace Featdd\PaginationTest\Controller;

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
use TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\PaginationInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class TestController extends ActionController
{
    // /**
    //  * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentNameException
    //  */
    // protected function initializeListAction(): void
    // {
    //     $queryParameters = $this->request->getQueryParams();
    //     $page = (int)($queryParameters['page'] ?? 1);
    //     /** @var \TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters $extbaseRequestParameters */
    //     $extbaseRequestParameters = $this->request->getAttribute('extbase');
    //
    //     $extbaseRequestParameters->setArgument('currentPage', $page);
    // }

    /**
     * @param int $currentPage
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function listAction(int $currentPage): ResponseInterface
    {
        $paginationItems = [];

        foreach (range(1, 70) as $index) {
            $paginationItems[] = ['title' => sprintf('Item %d', $index)];
        }

        $paginator = new ArrayPaginator($paginationItems, $currentPage, 5);
        $pagination = new NumberedPagination($paginator, 4);

        $this->setPaginationMetaTags($currentPage, $pagination);

        $this->view->assignMultiple([
            'paginator' => $paginator,
            'pagination' => $pagination,
        ]);

        return $this->htmlResponse();
    }

    /**
     * @param int $currentPage
     * @param \TYPO3\CMS\Core\Pagination\PaginationInterface $pagination
     */
    protected function setPaginationMetaTags(int $currentPage, PaginationInterface $pagination): void
    {
        /** @var \TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry $metaTagManagerRegistry */
        $metaTagManagerRegistry = GeneralUtility::makeInstance(MetaTagManagerRegistry::class);
        /** @var \TYPO3\CMS\Core\Routing\PageArguments $pageArguments */
        $pageArguments = $this->request->getAttribute('routing');

        if ($currentPage > 1) {
            $metaTagManagerRegistry
                ->getManagerForProperty('robots')
                ->addProperty('robots', 'noindex,follow', [], true);

            $metaTagManagerRegistry
                ->getManagerForProperty('prev')
                ->addProperty(
                    'prev',
                    $this->uriBuilder
                        ->reset()
                        ->setTargetPageUid($pageArguments->getPageId())
                        ->setArguments($currentPage - 1 === 1 ? [] : ['page' => $currentPage - 1])
                        ->uriFor('list')
                );
        }

        if ($pagination->getLastPageNumber() !== $currentPage) {
            $metaTagManagerRegistry
                ->getManagerForProperty('next')
                ->addProperty(
                    'next',
                    $this->uriBuilder
                        ->reset()
                        ->setTargetPageUid($pageArguments->getPageId())
                        ->setArguments(['page' => $currentPage + 1])
                        ->uriFor('list')
                );
        }
    }
}
