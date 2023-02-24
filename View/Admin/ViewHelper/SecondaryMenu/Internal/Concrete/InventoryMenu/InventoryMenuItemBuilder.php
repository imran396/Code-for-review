<?php
/**
 * SAM-9573: Refactor admin secondary menu for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\InventoryMenu;

use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Common\SecondaryMenuConstants;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\InventoryMenu\Internal\Load\DataProviderCreateTrait;

/**
 * Class InventoryMenuItemBuilder
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\InventoryMenu
 */
class InventoryMenuItemBuilder extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use DataProviderCreateTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array
     */
    public function build(): array
    {
        $adminTranslator = $this->getAdminTranslator();
        $isBarcode = $this->createDataProvider()->isBarcode();
        $urlBuilder = $this->getUrlBuilder();
        $items = [
            [
                'label' => $adminTranslator->trans('secondary_menu.inventory.items.label'),
                'subUrls' => [
                    $urlBuilder->build(
                        ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_INVENTORY_LOT_CREATE)
                    ),
                ],
                'title' => $adminTranslator->trans('secondary_menu.inventory.items.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_INVENTORY_ITEMS)
                ),
            ],
            $isBarcode
                ? [
                'label' => $adminTranslator->trans('secondary_menu.inventory.barcode_operations.label'),
                'title' => $adminTranslator->trans('secondary_menu.inventory.barcode_operations.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_INVENTORY_BARCODE_OPERATIONS_NOPARAM)
                )
            ]
                : null,
        ];
        return [
            'items' => array_values(array_filter($items)),
            'position' => SecondaryMenuConstants::POSITION_LEFT,
        ];
    }

}
