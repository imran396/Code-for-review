<?php
/**
 * Render my items tab menu
 *
 * SAM-4400: Refactor zf view helpers to customized classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 20, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\ViewHelper;

use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\View\Base\HtmlRenderer;

/**
 * Class MyItemsTab
 * @package Sam\View\Responsive\ViewHelper
 */
class MyItemsTab extends CustomizableClass
{
    use EditorUserAwareTrait;
    use ParamFetcherForRouteAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;

    /** @var int[] */
    protected const AVAILABLE_MENU_ITEMS = [
        Constants\Url::P_ITEMS_BIDDING,
        Constants\Url::P_ITEMS_WATCHLIST,
        Constants\Url::P_ITEMS_WON,
        Constants\Url::P_ITEMS_NOT_WON,
        Constants\Url::P_ITEMS_CONSIGNED,
        Constants\Url::P_ITEMS_ALL
    ];

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
        return HtmlRenderer::new()->getTemplate(
            'my-items/my-items-tab.tpl.php',
            [
                'currentPage' => $this->getParamFetcherForRoute()->getActionName(),
                'menu' => $this->initMenu(),
            ],
            Ui::new()->constructWebResponsive()
        );
    }

    /**
     * Init menu
     * @return array<string, array<string>>
     */
    protected function initMenu(): array
    {
        return array_merge(
            [
                'bidding' => $this->buildMenuItem(Constants\Url::P_ITEMS_BIDDING),
                'watchlist' => $this->buildMenuItem(Constants\Url::P_ITEMS_WATCHLIST),
                'won' => $this->buildMenuItem(Constants\Url::P_ITEMS_WON),
                'not-won' => $this->buildMenuItem(Constants\Url::P_ITEMS_NOT_WON),
            ],
            $this->getEditorUserConsignorPrivilegeChecker()->isConsignor()
                ? ['consigned' => $this->buildMenuItem(Constants\Url::P_ITEMS_CONSIGNED)]
                : [],
            ['all' => $this->buildMenuItem(Constants\Url::P_ITEMS_ALL)]
        );
    }

    /**
     * @param int $urlType
     * @return string[]
     */
    protected function buildMenuItem(int $urlType): array
    {
        $menuItem = [
            'url' => '',
            'name' => ''
        ];
        if (in_array($urlType, self::AVAILABLE_MENU_ITEMS, true)) {
            $menuItem['url'] = $this->getUrlBuilder()->build(
                ZeroParamUrlConfig::new()->forWeb($urlType)
            );
            $menuItem['title'] = $this->findTranslationForMenuItem($urlType);
        }
        return $menuItem;
    }

    /**
     * @param int $urlType
     * @return string
     */
    protected function findTranslationForMenuItem(int $urlType): string
    {
        $translation = '';
        if ($urlType === Constants\Url::P_ITEMS_BIDDING) {
            $translation = $this->translate('MYITEMS_BIDDINGON_TAB');
        } elseif ($urlType === Constants\Url::P_ITEMS_WATCHLIST) {
            $translation = $this->translate('MYITEMS_WATCHLISTTITLE');
        } elseif ($urlType === Constants\Url::P_ITEMS_WON) {
            $translation = $this->translate('MYITEMS_ITEMSWONTITLE');
        } elseif ($urlType === Constants\Url::P_ITEMS_NOT_WON) {
            $translation = $this->translate('MYITEMS_ITEMSNOTWONTITLE');
        } elseif ($urlType === Constants\Url::P_ITEMS_CONSIGNED) {
            $translation = $this->translate('MYITEMS_CONSIGNEDTITLE');
        } elseif ($urlType === Constants\Url::P_ITEMS_ALL) {
            $translation = $this->translate('MYITEMS_ALLITEMSTITLE');
        }
        return $translation;
    }

    /**
     * @param string $translatableKey
     * @return string
     */
    private function translate(string $translatableKey): string
    {
        $output = $this->getTranslator()->translate($translatableKey, 'myitems');
        return $output;
    }
}
