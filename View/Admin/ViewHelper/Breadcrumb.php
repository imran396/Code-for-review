<?php
/**
 * Render breadcrumb
 *
 * SAM-4400: Refactor zf view helpers to customized classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 5, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper;

use Sam\Core\Service\CustomizableClass;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Web\ControllerAction\ControllerActionCollection;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\View\Base\HtmlRenderer;

/**
 * Class Breadcrumb
 * @package Sam\View\Admin\ViewHelper
 */
class Breadcrumb extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use ParamFetcherForRouteAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $breadcrumb = $this->getBreadcrumbFromConfig();
        if (!$breadcrumb) {
            return '';
        }

        return HtmlRenderer::new()->h1(
            $breadcrumb['uri']
                ? HtmlRenderer::new()->link($breadcrumb['uri'], $breadcrumb['name'])
                : $breadcrumb['name']
            ,
            ['class' => 'page-title']
        );
    }

    /**
     * Get breadcrumb from config
     * @return string[]
     */
    protected function getBreadcrumbFromConfig(): array
    {
        $controller = $this->getParamFetcherForRoute()->getControllerName();
        $actionAlias = $this->detectActionAlias();
        $config = $this->cfg()->get("breadcrumb->admin->{$controller}");
        if (!isset($config->{$actionAlias})) {
            return [];
        }
        $configItem = $config->{$actionAlias};
        return [
            'name' => $configItem->name,
            'uri' => $configItem->uri ?? null
        ];
    }

    /**
     * Convert edit actions without id to add actions or sub-page
     * @return string
     */
    protected function detectActionAlias(): string
    {
        $paramFetcherForRoute = $this->getParamFetcherForRoute();
        $controller = $paramFetcherForRoute->getControllerName();
        $action = $paramFetcherForRoute->getActionName();
        $correctionCollection = $this->buildCollectionOfActionAliasDetectors();
        if ($correctionCollection->has($controller, $action)) {
            $actionAliasDetector = $correctionCollection->get($controller, $action);
            return $actionAliasDetector();
        }
        return $action;
    }

    /**
     * Build "Controller-Action Collection" that stores static functions for detecting action alias,
     * that is used in breadcrumbs configuration of _configuration/breadcrumb.php.
     * Example: [ <controller> => [ <action alias> => detection function() ], ... ]
     * @return ControllerActionCollection
     */
    protected function buildCollectionOfActionAliasDetectors(): ControllerActionCollection
    {
        $paramFetcherForRoute = $this->getParamFetcherForRoute();
        $items = [
            Constants\AdminRoute::C_MANAGE_USERS => [
                Constants\AdminRoute::AMU_EDIT => static function () use ($paramFetcherForRoute): string {
                    return $paramFetcherForRoute->getIntPositive(Constants\UrlParam::R_ID)
                        ? Constants\AdminRoute::AMU_EDIT
                        : Constants\AdminRoute::AMU_CREATE;
                },
            ],

            Constants\AdminRoute::C_MANAGE_INVENTORY => [
                Constants\AdminRoute::AMIN_EDIT => static function () use ($paramFetcherForRoute): string {
                    return $paramFetcherForRoute->getIntPositive(Constants\UrlParam::R_LOT)
                        ? Constants\AdminRoute::AMIN_EDIT
                        : Constants\AdminRoute::AMIN_ADD;
                },
            ],

            Constants\AdminRoute::C_MANAGE_TRANSLATION => [
                Constants\AdminRoute::AMT_EDIT => static function () use ($paramFetcherForRoute): string {
                    return $paramFetcherForRoute->getString(Constants\UrlParam::R_SET);
                },
            ],

            Constants\AdminRoute::C_MANAGE_CUSTOM_TEMPLATE => [
                Constants\AdminRoute::AMCT_AUCTION_INFO => static function (): string {
                    return Constants\AdminRoute::AMCT_AUCTION_INFO;
                },
                Constants\AdminRoute::AMCT_AUCTION_CAPTION => static function (): string {
                    return Constants\AdminRoute::AMCT_AUCTION_CAPTION;
                },
                Constants\AdminRoute::AMCT_LOT_ITEM_DETAILS => static function (): string {
                    return Constants\AdminRoute::AMCT_LOT_ITEM_DETAILS;
                },
                Constants\AdminRoute::AMCT_RTB_DETAILS => static function (): string {
                    return Constants\AdminRoute::AMCT_RTB_DETAILS;
                },
            ],
        ];
        $caCollection = ControllerActionCollection::new()->construct($items);
        return $caCollection;
    }

}
