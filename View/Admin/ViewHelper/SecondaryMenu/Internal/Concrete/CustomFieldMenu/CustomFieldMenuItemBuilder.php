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

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\CustomFieldMenu;

use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class CustomFieldMenuItemBuilder
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\CustomFieldMenu
 */
class CustomFieldMenuItemBuilder extends CustomizableClass
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
        $adminTranslator = $this->getAdminTranslator();
        $urlBuilder = $this->getUrlBuilder();
        $items = [
            [
                'label' => $adminTranslator->trans('secondary_menu.custom_field.lot_item.label'),
                'subUrls' => [
                    $urlBuilder->build(
                        ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_CUSTOM_FIELD_CREATE_LOT_ITEM)
                    ),
                ],
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_CUSTOM_FIELD_LIST_LOT_ITEM)
                ),
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.custom_field.user.label'),
                'subUrls' => [
                    $urlBuilder->build(
                        ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_CUSTOM_FIELD_CREATE_USER)
                    ),
                ],
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_CUSTOM_FIELD_LIST_USER)
                ),
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.custom_field.auction.label'),
                'subUrls' => [
                    $urlBuilder->build(
                        ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_CUSTOM_FIELD_CREATE_AUCTION)
                    ),
                ],
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_CUSTOM_FIELD_LIST_AUCTION)
                ),
            ],
        ];
        return [
            'items' => $items,
        ];
    }

}
