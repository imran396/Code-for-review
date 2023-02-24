<?php
/**
 * SAM-9573: Refactor admin secondary menu for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\CustomTemplateMenu;

use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class CustomTemplteMenuItemBuilder
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\CustomTemplateMenu
 */
class CustomTemplateMenuItemBuilder extends CustomizableClass
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
                'label' => $adminTranslator->trans('secondary_menu.custom_template.auction_info.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_CUSTOM_TEMPLATE_AUCTION_INFO)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.custom_template.auction_caption.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_CUSTOM_TEMPLATE_AUCTION_CAPTION)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.custom_template.lot_item_details.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_CUSTOM_TEMPLATE_LOT_ITEM_DETAILS)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.custom_template.rtb_details.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_CUSTOM_TEMPLATE_RTB_DETAILS)
                )
            ],
        ];
        return [
            'items' => $items,
        ];
    }
}
