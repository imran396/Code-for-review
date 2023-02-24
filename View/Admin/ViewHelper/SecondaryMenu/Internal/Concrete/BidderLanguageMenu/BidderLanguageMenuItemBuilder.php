<?php
/**
 * SAM-9573: Refactor admin secondary menu for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BidderLanguageMenu;

use Sam\Application\Url\Build\Config\Setting\TranslationEditUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class BidderLanguageMenuItemBuilder
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BidderLanguageMenu
 */
class BidderLanguageMenuItemBuilder extends CustomizableClass
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
     * @param int|null $languageId
     * @return array
     */
    public function build(?int $languageId): array
    {
        $adminTranslator = $this->getAdminTranslator();
        $urlBuilder = $this->getUrlBuilder();
        $items = [
            [
                'label' => $adminTranslator->trans('secondary_menu.bidder_language.main_menu.label'),
                'url' => $urlBuilder->build(
                    TranslationEditUrlConfig::new()->forWeb(TranslationEditUrlConfig::SECTION_MAINMENU, $languageId)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.bidder_language.auctions.label'),
                'url' => $urlBuilder->build(
                    TranslationEditUrlConfig::new()->forWeb(TranslationEditUrlConfig::SECTION_AUCTIONS, $languageId)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.bidder_language.catalog.label'),
                'url' => $urlBuilder->build(
                    TranslationEditUrlConfig::new()->forWeb(TranslationEditUrlConfig::SECTION_CATALOG, $languageId)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.bidder_language.search.label'),
                'url' => $urlBuilder->build(
                    TranslationEditUrlConfig::new()->forWeb(TranslationEditUrlConfig::SECTION_SEARCH, $languageId)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.bidder_language.item.label'),
                'url' => $urlBuilder->build(
                    TranslationEditUrlConfig::new()->forWeb(TranslationEditUrlConfig::SECTION_ITEM, $languageId)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.bidder_language.invoices.label'),
                'url' => $urlBuilder->build(
                    TranslationEditUrlConfig::new()->forWeb(TranslationEditUrlConfig::SECTION_MYINVOICES, $languageId)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.bidder_language.my_items.label'),
                'url' => $urlBuilder->build(
                    TranslationEditUrlConfig::new()->forWeb(TranslationEditUrlConfig::SECTION_MYITEMS, $languageId)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.bidder_language.settlements.label'),
                'url' => $urlBuilder->build(
                    TranslationEditUrlConfig::new()->forWeb(TranslationEditUrlConfig::SECTION_MYSETTLEMENTS, $languageId)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.bidder_language.user_details.label'),
                'url' => $urlBuilder->build(
                    TranslationEditUrlConfig::new()->forWeb(TranslationEditUrlConfig::SECTION_USER, $languageId)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.bidder_language.login.label'),
                'url' => $urlBuilder->build(
                    TranslationEditUrlConfig::new()->forWeb(TranslationEditUrlConfig::SECTION_LOGIN, $languageId)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.bidder_language.bidder_client.label'),
                'url' => $urlBuilder->build(
                    TranslationEditUrlConfig::new()->forWeb(TranslationEditUrlConfig::SECTION_BIDDERCLIENT, $languageId)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.bidder_language.hybrid_client.label'),
                'url' => $urlBuilder->build(
                    TranslationEditUrlConfig::new()->forWeb(TranslationEditUrlConfig::SECTION_HYBRIDCLIENT, $languageId)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.bidder_language.general.label'),
                'url' => $urlBuilder->build(
                    TranslationEditUrlConfig::new()->forWeb(TranslationEditUrlConfig::SECTION_GENERAL, $languageId)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.bidder_language.popups.label'),
                'url' => $urlBuilder->build(
                    TranslationEditUrlConfig::new()->forWeb(TranslationEditUrlConfig::SECTION_POPUPS, $languageId)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.bidder_language.lot_item_custom_fields.label'),
                'url' => $urlBuilder->build(
                    TranslationEditUrlConfig::new()->forWeb(TranslationEditUrlConfig::SECTION_CUSTOMFIELDS, $languageId)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.bidder_language.user_custom_fields.label'),
                'url' => $urlBuilder->build(
                    TranslationEditUrlConfig::new()->forWeb(TranslationEditUrlConfig::SECTION_USERCUSTOMFIELDS, $languageId)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.bidder_language.auction_custom_fields.label'),
                'url' => $urlBuilder->build(
                    TranslationEditUrlConfig::new()->forWeb(TranslationEditUrlConfig::SECTION_AUCTIONCUSTOMFIELDS, $languageId)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.bidder_language.auction_details.label'),
                'url' => $urlBuilder->build(
                    TranslationEditUrlConfig::new()->forWeb(TranslationEditUrlConfig::SECTION_AUCTION_DETAILS, $languageId)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.bidder_language.lot_details.label'),
                'url' => $urlBuilder->build(
                    TranslationEditUrlConfig::new()->forWeb(TranslationEditUrlConfig::SECTION_LOT_DETAILS, $languageId)
                )
            ],
        ];
        return [
            'items' => $items,
        ];
    }
}
