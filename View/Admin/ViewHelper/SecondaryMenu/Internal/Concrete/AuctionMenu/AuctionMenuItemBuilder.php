<?php
/**
 * SAM-9573: Refactor admin secondary menu for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 2, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\AuctionMenu;

use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Common\SecondaryMenuConstants;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\AuctionMenu\Internal\Load\DataProviderCreateTrait;

/**
 * Class AuctionMenuItemBuilder
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\AuctionMenu
 */
class AuctionMenuItemBuilder extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use ApplicationRedirectorCreateTrait;
    use ConfigRepositoryAwareTrait;
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
     * @param int|null $auctionId
     * @param int|null $editorUserId
     * @return array
     */
    public function build(?int $auctionId, ?int $editorUserId): array
    {
        $adminTranslator = $this->getAdminTranslator();
        $dataProvider = $this->createDataProvider();
        $auction = $dataProvider->loadAuction($auctionId);
        $isBidIncrementsAllowed = !$auction->isLive()
            || !$auction->isAdvancedClerking();
        $isLive = $auction->isLive();
        $isHybrid = $auction->isHybrid();
        $isEncode = $this->cfg()->get('core->vendor->bidpathStreaming->enabled')
            && array_key_exists($auction->StreamDisplay, Constants\Auction::$streamDisplayCoded);
        $isStackedTaxDesignationForInvoice = $dataProvider->isStackedTaxDesignationForInvoice($auction->AccountId);

        $urlBuilder = $this->getUrlBuilder();
        $menuItems = [
            $dataProvider->isEditAllowed($editorUserId)
                ? [
                'label' => $adminTranslator->trans('secondary_menu.auction.info.label'),
                'title' => $adminTranslator->trans('secondary_menu.auction.info.title'),
                'url' => $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_EDIT, $auctionId)
                ),
            ] : null,
            $dataProvider->isLotsAllowed($editorUserId)
                ? [
                'label' => $adminTranslator->trans('secondary_menu.auction.lots.label'),
                'subUrls' => [
                    $urlBuilder->build(
                        AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_LOT_EDIT_NOPARAM, $auctionId)
                    ),
                ],
                'title' => $adminTranslator->trans('secondary_menu.auction.lots.title'),
                'url' => $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_LOT_LIST, $auctionId)
                ),
            ] : null,
            $dataProvider->isBiddersAllowed($editorUserId)
                ? [
                'label' => $adminTranslator->trans('secondary_menu.auction.bidders.label'),
                'title' => $adminTranslator->trans('secondary_menu.auction.bidders.title'),
                'url' => $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_BIDDER_LIST, $auctionId)
                ),
            ] : null,
            $isLive && $dataProvider->isRunAllowed($editorUserId)
                ? [
                'label' => $adminTranslator->trans('secondary_menu.auction.run_live_auction.label'),
                'title' => $adminTranslator->trans('secondary_menu.auction.run_live_auction.title'),
                'url' => $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_RUN, $auctionId)
                ),
            ] : null,
            $isLive && $dataProvider->isAuctioneerAllowed($editorUserId)
                ? [
                'label' => $adminTranslator->trans('secondary_menu.auction.auctioneer.label'),
                'title' => $adminTranslator->trans('secondary_menu.auction.auctioneer.title'),
                'url' => $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_AUCTIONEER, $auctionId)
                ),
            ] : null,
            ($isLive || $isHybrid) && $dataProvider->isProjectorAllowed($editorUserId)
                ? [
                'label' => $adminTranslator->trans('secondary_menu.auction.projector.label'),
                'title' => $adminTranslator->trans('secondary_menu.auction.projector.title'),
                'url' => $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_PROJECTOR, $auctionId)
                ),
            ] : null,
            ($isLive || $isHybrid) && $isEncode
                ? [
                'label' => $adminTranslator->trans('secondary_menu.auction.encode.label'),
                'title' => $adminTranslator->trans('secondary_menu.auction.encode.title'),
                'url' => $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_ENCODE, $auctionId)
                ),
            ] : null,
            ($isLive || $isHybrid)
                ? [
                'label' => $adminTranslator->trans('secondary_menu.auction.phone_bidders.label'),
                'title' => $adminTranslator->trans('secondary_menu.auction.phone_bidders.title'),
                'url' => $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_PHONE_BIDDERS, $auctionId)
                ),
            ] : null,
            $isHybrid && $dataProvider->isRunAllowed($editorUserId)
                ? [
                'label' => $adminTranslator->trans('secondary_menu.auction.manual_clerking.label'),
                'title' => $adminTranslator->trans('secondary_menu.auction.manual_clerking.title'),
                'url' => $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_RUN, $auctionId)
                ),
            ] : null,
            $isBidIncrementsAllowed && $dataProvider->isBidIncrementsAllowed($editorUserId)
                ? [
                'label' => $adminTranslator->trans('secondary_menu.auction.increments.label'),
                'title' => $adminTranslator->trans('secondary_menu.auction.increments.title'),
                'url' => $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_BID_INCREMENT, $auctionId)
                ),
            ] : null,
            $dataProvider->isBuyersPremiumAllowed($editorUserId)
                ? [
                'label' => $adminTranslator->trans('secondary_menu.auction.buyers_premium.label'),
                'title' => $adminTranslator->trans('secondary_menu.auction.buyers_premium.title'),
                'url' => $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_BUYERS_PREMIUM, $auctionId)
                ),
            ] : null,
            $dataProvider->isPermissionsAllowed($editorUserId)
                ? [
                'label' => $adminTranslator->trans('secondary_menu.auction.permissions.label'),
                'title' => $adminTranslator->trans('secondary_menu.auction.permissions.title'),
                'url' => $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_PERMISSION, $auctionId)
                ),
            ] : null,
            $dataProvider->isSettingsAllowed($editorUserId)
                ? [
                'label' => $adminTranslator->trans('secondary_menu.auction.settings.label'),
                'title' => $adminTranslator->trans('secondary_menu.auction.settings.title'),
                'url' => $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_SETTINGS, $auctionId)
                ),
            ] : null,
            $dataProvider->isSmsAllowed($editorUserId)
                ? [
                'label' => $adminTranslator->trans('secondary_menu.auction.sms.label'),
                'title' => $adminTranslator->trans('secondary_menu.auction.sms.title'),
                'url' => $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_SMS, $auctionId)
                ),
            ] : null,
            $dataProvider->isEmailAllowed($editorUserId)
                ? [
                'label' => $adminTranslator->trans('secondary_menu.auction.email.label'),
                'title' => $adminTranslator->trans('secondary_menu.auction.email.title'),
                'url' => $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_EMAIL, $auctionId)
                ),
            ] : null,
            $dataProvider->isShowImportAllowed($editorUserId)
                ? [
                'label' => $adminTranslator->trans('secondary_menu.auction.image_import.label'),
                'title' => $adminTranslator->trans('secondary_menu.auction.image_import.title'),
                'url' => $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_LOT_IMAGES_SHOW_IMPORT, $auctionId)
                ),
            ] : null,
            $dataProvider->isEnterBidsAllowed($editorUserId)
                ? [
                'label' => $adminTranslator->trans('secondary_menu.auction.enter_bids.label'),
                'title' => $adminTranslator->trans('secondary_menu.auction.enter_bids.title'),
                'url' => $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_ENTER_BIDS, $auctionId)
                ),
            ] : null,
            ($dataProvider->isAuctionInvoiceAllowed($editorUserId)
                && !$isStackedTaxDesignationForInvoice)
                ? [
                'label' => $adminTranslator->trans('secondary_menu.auction.invoices.label'),
                'title' => $adminTranslator->trans('secondary_menu.auction.invoices.title'),
                'url' => $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_INVOICE, $auctionId)
                ),
            ] : null,
            ($dataProvider->isAuctionInvoiceAllowed($editorUserId)
                && $isStackedTaxDesignationForInvoice)
                ? [
                'label' => $adminTranslator->trans('secondary_menu.auction.invoices.label'),
                'title' => $adminTranslator->trans('secondary_menu.auction.invoices.title'),
                'url' => $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_STACKED_TAX_INVOICE, $auctionId)
                ),
            ] : null,
            $dataProvider->isAuctionReportsAllowed($editorUserId)
                ? [
                'label' => $adminTranslator->trans('secondary_menu.auction.reports.label'),
                'title' => $adminTranslator->trans('secondary_menu.auction.reports.title'),
                'url' => $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_REPORTS, $auctionId)
                ),
            ] : null,
        ];
        $output = [
            'class' => 'tab-auction-lots',
            'items' => array_values(array_filter($menuItems)),
            'position' => SecondaryMenuConstants::POSITION_LEFT,
        ];
        return $output;
    }
}
