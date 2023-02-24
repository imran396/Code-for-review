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

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\SystemParameterMenu;

use Account;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\SystemParameterMenu\Internal\Load\DataProviderCreateTrait;

/**
 * Class SystemParameterMenuBuilder
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\SystemParameterMenu
 */
class SystemParameterMenuItemBuilder extends CustomizableClass
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
     * @param Account $systemAccount
     * @return array
     */
    public function build(Account $systemAccount): array
    {
        $hybridAuctionAvailable = $this->createDataProvider()->isHybridAuctionAvailable($systemAccount);
        $urlBuilder = $this->getUrlBuilder();
        $adminTranslator = $this->getAdminTranslator();
        $items = [
            [
                'label' => $adminTranslator->trans('secondary_menu.system_parameter.invoicing.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_SYSTEM_PARAMETER_INVOICING)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.system_parameter.payments.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_SYSTEM_PARAMETER_PAYMENT)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.system_parameter.layout_and_site_customization.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_SYSTEM_PARAMETER_LAYOUT_AND_SITE_CUSTOMIZATION)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.system_parameter.system_configuration.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_SYSTEM_PARAMETER_SYSTEM)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.system_parameter.admin_options.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_SYSTEM_PARAMETER_ADMIN_OPTION)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.system_parameter.users_options.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_SYSTEM_PARAMETER_USER_OPTION)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.system_parameter.auction.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_SYSTEM_PARAMETER_AUCTION)
                )
            ],
            [
                'label' => $hybridAuctionAvailable
                    ? $adminTranslator->trans('secondary_menu.system_parameter.live_or_hybrid_auctions.label')
                    : $adminTranslator->trans('secondary_menu.system_parameter.live_auctions.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_SYSTEM_PARAMETER_LIVE_HYBRID_AUCTION)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.system_parameter.timed_online_auction.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_SYSTEM_PARAMETER_TIMED_ONLINE_AUCTION)
                )
            ],
            $hybridAuctionAvailable
                ? [
                'label' => $adminTranslator->trans('secondary_menu.system_parameter.hybrid.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_SYSTEM_PARAMETER_HYBRID_AUCTION)
                )
            ]
                : null,
            [
                'label' => $adminTranslator->trans('secondary_menu.system_parameter.integrations.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_SYSTEM_PARAMETER_INTEGRATION)
                )
            ],
        ];
        return [
            'items' => array_values(array_filter($items)),
        ];
    }
}
