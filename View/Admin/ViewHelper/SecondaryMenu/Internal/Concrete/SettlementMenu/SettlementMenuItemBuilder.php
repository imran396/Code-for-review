<?php
/**
 * SAM-9795: Check Printing for Settlements: Implementation of html layout and view layer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           10-24, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\SettlementMenu;

use Sam\Application\Url\Build\Config\Settlement\AnySingleSettlementUrlConfig;
use Sam\Application\Url\Build\Config\Settlement\SettlementCheckListUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Common\SecondaryMenuConstants;

/**
 * Class SettlementMenuItemBuilder
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\SettlementMenu
 */
class SettlementMenuItemBuilder extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function build(?int $settlementId): array
    {
        $items = $this->buildMenuItems($settlementId);

        return [
            'items' => array_values(array_filter($items)),
            'position' => SecondaryMenuConstants::POSITION_LEFT,
        ];
    }

    protected function buildMenuItems(?int $settlementId): array
    {
        $urlBuilder = $this->getUrlBuilder();
        $adminTranslator = $this->getAdminTranslator();

        $items = [
            [
                'label' => $adminTranslator->trans('secondary_menu.settlement.info.label'),
                'title' => $adminTranslator->trans('secondary_menu.settlement.info.title'),
                'url' => $urlBuilder->build(
                    AnySingleSettlementUrlConfig::new()->forWeb(Constants\Url::A_SETTLEMENTS_VIEW, $settlementId)
                ),
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.settlement.checks.label'),
                'title' => $adminTranslator->trans('secondary_menu.settlement.checks.title'),
                'url' => $urlBuilder->build(
                    SettlementCheckListUrlConfig::new()->forWeb($settlementId)
                ),
            ],
        ];

        return $items;
    }
}
