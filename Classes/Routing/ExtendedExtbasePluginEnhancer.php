<?php
declare(strict_types=1);

namespace Featdd\PaginationTest\Routing;

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

use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Routing\Route;
use TYPO3\CMS\Extbase\Routing\ExtbasePluginEnhancer;

class ExtendedExtbasePluginEnhancer extends ExtbasePluginEnhancer
{
    /**
     * @param \TYPO3\CMS\Core\Routing\Route $route
     * @param array $results
     * @param array $remainingQueryParameters
     * @return \TYPO3\CMS\Core\Routing\PageArguments
     */
    public function buildResult(Route $route, array $results, array $remainingQueryParameters = []): PageArguments
    {
        $pageArguments = parent::buildResult($route, $results, $remainingQueryParameters);

        $queryArguments = $route->getOption('_queryArguments');
        $routeArguments = $pageArguments->getRouteArguments();
        $staticArguments = $pageArguments->getStaticArguments();

        foreach ($queryArguments as $queryArgument => $queryParameterName) {
            $queryParameter = $remainingQueryParameters[$queryParameterName] ?? $route->getDefault($queryArgument);

            if (!empty($queryParameter)) {
                $routeArguments[$this->namespace][$queryArgument] = $queryParameter;
                $staticArguments[$this->namespace][$queryArgument] = $queryParameter;
                unset($remainingQueryParameters[$queryParameterName]);
            }
        }

        return new PageArguments(
            $pageArguments->getPageId(),
            $pageArguments->getPageType(),
            $routeArguments,
            $staticArguments,
            $remainingQueryParameters
        );
    }

    /**
     * @param \TYPO3\CMS\Core\Routing\Route $defaultPageRoute
     * @param array $configuration
     * @return \TYPO3\CMS\Core\Routing\Route
     */
    protected function getVariant(Route $defaultPageRoute, array $configuration): Route
    {
        $route = parent::getVariant($defaultPageRoute, $configuration);
        $queryArguments = $configuration['_queryArguments'] ?? [];
        unset($configuration['_queryArguments']);

        if (!empty($this->configuration['queryDefaults'])) {
            $route->addDefaults($this->configuration['queryDefaults']);
        }

        $route->setOption('_queryArguments', $queryArguments);

        if (
            $defaultPageRoute->getPath() === $route->getPath() ||
            $defaultPageRoute->getPath() === rtrim($route->getPath(), '/')
        ) {
            $route->setPath(rtrim($route->getPath(), '/'));
            $defaultPageRoute->setDefaults($route->getDefaults());
            $defaultPageRoute->setOptions($route->getOptions());
            $defaultPageRoute->setAspects($route->getAspects());
        }

        return $route;
    }
}
