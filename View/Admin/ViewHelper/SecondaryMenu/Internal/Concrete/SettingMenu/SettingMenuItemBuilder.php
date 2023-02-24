<?php
/**
 * SAM-9573: Refactor admin secondary menu for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\SettingMenu;

use Sam\Application\Url\Build\Config\Base\OneStringParamUrlConfig;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Common\SecondaryMenuConstants;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\SettingMenu\Internal\Load\DataProviderCreateTrait;

/**
 * Class SettingsMenuItemProvider
 * @package Sam\View\Admin\ViewHelper\SettingsMenu
 */
class SettingMenuItemBuilder extends CustomizableClass
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
     * @param int $systemAccountId
     * @param int|null $editorUserId
     * @return array
     */
    public function build(int $systemAccountId, ?int $editorUserId): array
    {
        $adminTranslator = $this->getAdminTranslator();
        $dataProvider = $this->createDataProvider();
        $urlBuilder = $this->getUrlBuilder();

        $hasAccessToAccountManagement = $dataProvider->hasAccessToAccountManagement($editorUserId, $systemAccountId, true);
        $hasAccessToBuyerGroupManagement = $dataProvider->hasAccessToBuyerGroupManagement($editorUserId, $systemAccountId, true);
        $hasAccessToCustomFieldManagement = $dataProvider->hasAccessToCustomFieldManagement($editorUserId, $systemAccountId, true);
        $hasAccessToLotCategoryManagement = $dataProvider->hasAccessToLotCategoryManagement($editorUserId, $systemAccountId, true);
        $isStackedTaxFeature = $dataProvider->isStackedTaxFeature();

        $items = [
            $hasAccessToAccountManagement ? [
                'label' => $adminTranslator->trans('secondary_menu.settings.accounts.label'),
                'title' => $adminTranslator->trans('secondary_menu.settings.accounts.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_ACCOUNT_LIST)
                ),
            ] : null,
            [
                'label' => $adminTranslator->trans('secondary_menu.settings.auctioneers.label'),
                'title' => $adminTranslator->trans('secondary_menu.settings.auctioneers.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_AUCTIONEER_LIST)
                ),
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.settings.bid_increments.label'),
                'title' => $adminTranslator->trans('secondary_menu.settings.bid_increments.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_BID_INCREMENT_INDEX)
                ),
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.settings.buyers_premium.label'),
                'title' => $adminTranslator->trans('secondary_menu.settings.buyers_premium.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_BUYERS_PREMIUM_LIST)
                ),
            ],
            $hasAccessToBuyerGroupManagement
                ? [
                'label' => $adminTranslator->trans('secondary_menu.settings.buyer_groups.label'),
                'title' => $adminTranslator->trans('secondary_menu.settings.buyer_groups.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_BUYER_GROUP_LIST)
                ),
            ] : null,
            $hasAccessToLotCategoryManagement
                ? [
                'label' => $adminTranslator->trans('secondary_menu.settings.categories.label'),
                'title' => $adminTranslator->trans('secondary_menu.settings.categories.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_CATEGORY_INDEX)
                ),
            ] : null,
            $hasAccessToCustomFieldManagement
                ? [
                'label' => $adminTranslator->trans('secondary_menu.settings.custom_fields.label'),
                'title' => $adminTranslator->trans('secondary_menu.settings.custom_fields.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_CUSTOM_FIELDS_INDEX)
                ),
            ] : null,
            [
                'label' => $adminTranslator->trans('secondary_menu.settings.coupons.label'),
                'title' => $adminTranslator->trans('secondary_menu.settings.coupons.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_COUPON_LIST)
                ),
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.settings.custom_templates.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_CUSTOM_TEMPLATE_AUCTION_INFO)
                ),
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.settings.consignor_commission_fee.label'),
                'title' => $adminTranslator->trans('secondary_menu.settings.consignor_commission_fee.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_CONSIGNOR_COMMISSION_FEE)
                ),
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.settings.email_templates.label'),
                'title' => $adminTranslator->trans('secondary_menu.settings.email_templates.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_EMAIL_TEMPLATE_LIST)
                ),
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.settings.feeds.label'),
                'title' => $adminTranslator->trans('secondary_menu.settings.feeds.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_FEED_LIST)
                ),
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.settings.locations.label'),
                'title' => $adminTranslator->trans('secondary_menu.settings.locations.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_LOCATION_LIST)
                ),
                'subUrls' => [
                    $urlBuilder->build(
                        ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_LOCATION_CREATE)
                    ),
                    $urlBuilder->build(
                        OneStringParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_LOCATION_EDIT, '')
                    ),
                ],
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.settings.site_content.label'),
                'title' => $adminTranslator->trans('secondary_menu.settings.site_content.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_SITE_CONTENT_INDEX)
                ),
            ],
            $isStackedTaxFeature
                ? [
                'label' => $adminTranslator->trans('secondary_menu.settings.stacked_tax.label'),
                'title' => $adminTranslator->trans('secondary_menu.settings.stacked_tax.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_TAX_SCHEMA_LIST)
                ),
                'subUrls' => [
                    $urlBuilder->build(
                        ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_TAX_SCHEMA_LIST)
                    ),
                    $urlBuilder->build(
                        ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_TAX_DEFINITION_LIST)
                    ),
                    $urlBuilder->build(
                        ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_TAX_SCHEMA_CREATE)
                    ),
                    $urlBuilder->build(
                        OneStringParamUrlConfig::new()->forWeb(Constants\Url::A_TAX_SCHEMA_EDIT, '')
                    ),
                    $urlBuilder->build(
                        ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_TAX_DEFINITION_CREATE)
                    ),
                    $urlBuilder->build(
                        OneStringParamUrlConfig::new()->forWeb(Constants\Url::A_TAX_DEFINITION_EDIT, '')
                    ),
                ],
            ] : null,
            [
                'label' => $adminTranslator->trans('secondary_menu.settings.sync.label'),
                'title' => $adminTranslator->trans('secondary_menu.settings.sync.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_SYNC_LIST)
                ),
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.settings.system_parameters.label'),
                'title' => $adminTranslator->trans('secondary_menu.settings.system_parameters.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_SYSTEM_PARAMETER_INVOICING)
                ),
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.settings.translations.label'),
                'title' => $adminTranslator->trans('secondary_menu.settings.translations.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_TRANSLATION_EDIT_NOPARAM)
                ),
            ],
        ];
        return [
            'items' => array_values(array_filter($items)),
            'position' => SecondaryMenuConstants::POSITION_LEFT,
            'show-item-id' => true,
        ];
    }
}
