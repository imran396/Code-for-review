<?php
/**
 * SAM-9573: Refactor admin secondary menu for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 1, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BarcodeOperationMenu;

use Sam\Application\Url\Build\Config\Base\OneStringParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class BarcodeOperationMenuItemBuilder
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Inernal\Concrete\BarcodeOperationMenu
 */
class BarcodeOperationMenuItemBuilder extends CustomizableClass
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

    /**
     * @return array
     */
    public function build(): array
    {
        return [
            'items' => [
                [
                    'label' => $this->getAdminTranslator()->trans('secondary_menu.barcode_operation.item_pickup.label'),
                    'url' => $this->getUrlBuilder()->build(
                        OneStringParamUrlConfig::new()->forWeb(
                            Constants\Url::A_INVENTORY_BARCODE_OPERATIONS,
                            'item-pickup'
                        )
                    )
                ],
            ],
        ];
    }

}
