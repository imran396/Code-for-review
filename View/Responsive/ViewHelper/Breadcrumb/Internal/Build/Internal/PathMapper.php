<?php
/**
 * SAM-4500: Front-end breadcrumb
 * https://bidpath.atlassian.net/browse/SAM-4500
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 08, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\ViewHelper\Breadcrumb\Internal\Build\Internal;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Web\ControllerAction\ControllerActionCollection;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class PathMapper
 * @package Sam\View\Responsive\ViewHelper\Breadcrumb
 */
class PathMapper extends CustomizableClass
{
    use SettingsManagerAwareTrait;
    use SystemAccountAwareTrait;

    // --- Incoming values ---

    protected ?int $landingType = null;
    protected CrumbBuilder $crumbBuilder;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $serviceAccountId
     * @param int $languageId
     * @return $this
     */
    public function construct(int $serviceAccountId, int $languageId): static
    {
        $this->crumbBuilder = CrumbBuilder::new()->construct($serviceAccountId, $languageId);
        return $this;
    }

    //Mapper

    /**
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param int|null $id invoiceId or SettlementId
     * @return ControllerActionCollection
     */
    public function buildControllerActionToBreadcrumbMappings(
        ?int $lotItemId = null,
        ?int $auctionId = null,
        ?int $id = null
    ): ControllerActionCollection {
        $landingType = $this->getLandingType();
        $crumbBuilder = $this->crumbBuilder;
        $mappingBreadCrumbs = [
            Constants\ResponsiveRoute::C_AUCTIONS => [

                Constants\ResponsiveRoute::AA_LIST => static function () use ($crumbBuilder): array {
                    $auctionListCrumb = $crumbBuilder->buildAuctionListCrumb(false);
                    return [$auctionListCrumb];
                },

                Constants\ResponsiveRoute::AA_CATALOG => static function () use ($auctionId, $crumbBuilder): array {
                    $auctionInfoCrumb = $crumbBuilder->buildAuctionCrumb($auctionId, Constants\SettingAuction::AUCTION_LINK_TO_INFO);
                    $auctionCatalogCrumb = $crumbBuilder->buildCatalogCrumb($auctionId, false);
                    return [$auctionInfoCrumb, $auctionCatalogCrumb];
                },

                Constants\ResponsiveRoute::AA_INFO => static function () use ($auctionId, $crumbBuilder): array {
                    $auctionCatalogCrumb = $crumbBuilder->buildCatalogCrumb($auctionId);
                    $auctionInfoCrumb = $crumbBuilder->buildAuctionCrumb($auctionId, null, false);
                    return [$auctionCatalogCrumb, $auctionInfoCrumb];
                },

                Constants\ResponsiveRoute::AA_LIVE_SALE => static function () use ($auctionId, $crumbBuilder): array {
                    $auctionCrumb = $crumbBuilder->buildAuctionCrumb($auctionId, Constants\SettingAuction::AUCTION_LINK_TO_CATALOG);
                    $auctionLiveSaleCrumb = $crumbBuilder->buildAuctionLiveSaleCrumb($auctionId, false);
                    return [$auctionCrumb, $auctionLiveSaleCrumb];
                },

                Constants\ResponsiveRoute::AA_TELL_FRIEND => static function () use (
                    $landingType,
                    $auctionId,
                    $lotItemId,
                    $crumbBuilder
                ): array {
                    $lotCrumb = $crumbBuilder->buildLotCrumb($lotItemId, $auctionId);
                    $tellFriendCrumb = $crumbBuilder->buildTellAFriendCrumb($lotItemId, $auctionId, false);
                    if ($landingType === Constants\SettingAuction::AUCTION_LINK_TO_INFO) {
                        $auctionCrumb = $crumbBuilder->buildAuctionCrumb($auctionId, Constants\SettingAuction::AUCTION_LINK_TO_INFO);
                        $auctionCatalogCrumb = $crumbBuilder->buildCatalogCrumb($auctionId);
                        return [$auctionCrumb, $auctionCatalogCrumb, $lotCrumb, $tellFriendCrumb];
                    }
                    $auctionCrumb = $crumbBuilder->buildAuctionCrumb($auctionId, Constants\SettingAuction::AUCTION_LINK_TO_CATALOG);
                    return [$auctionCrumb, $lotCrumb, $tellFriendCrumb];
                },

                Constants\ResponsiveRoute::AA_ASK_QUESTION => static function () use (
                    $landingType,
                    $auctionId,
                    $lotItemId,
                    $crumbBuilder
                ): array {
                    $askQuestionCrumb = $crumbBuilder->buildAskAQuestionCrumb($lotItemId, $auctionId, false);
                    $lotCrumb = $crumbBuilder->buildLotCrumb($lotItemId, $auctionId);
                    if ($landingType === Constants\SettingAuction::AUCTION_LINK_TO_INFO) {
                        $auctionCrumb = $crumbBuilder->buildAuctionCrumb($auctionId, Constants\SettingAuction::AUCTION_LINK_TO_INFO);
                        $auctionCatalogCrumb = $crumbBuilder->buildCatalogCrumb($auctionId);
                        return [$auctionCrumb, $auctionCatalogCrumb, $lotCrumb, $askQuestionCrumb];
                    }
                    $auctionCrumb = $crumbBuilder->buildAuctionCrumb($auctionId, Constants\SettingAuction::AUCTION_LINK_TO_CATALOG);
                    return [$auctionCrumb, $lotCrumb, $askQuestionCrumb];
                },

                Constants\ResponsiveRoute::AA_BIDDING_HISTORY => static function () use (
                    $landingType,
                    $auctionId,
                    $lotItemId,
                    $crumbBuilder
                ): array {
                    $biddingHistoryCrumb = $crumbBuilder->buildBiddingHistoryCrumb($lotItemId, $auctionId, false);
                    $lotCrumb = $crumbBuilder->buildLotCrumb($lotItemId, $auctionId);
                    if ($landingType === Constants\SettingAuction::AUCTION_LINK_TO_INFO) {
                        $auctionCrumb = $crumbBuilder->buildAuctionCrumb($auctionId, Constants\SettingAuction::AUCTION_LINK_TO_INFO);
                        $auctionCatalogCrumb = $crumbBuilder->buildCatalogCrumb($auctionId);
                        return [$auctionCrumb, $auctionCatalogCrumb, $lotCrumb, $biddingHistoryCrumb];
                    }
                    $auctionCrumb = $crumbBuilder->buildAuctionCrumb($auctionId, Constants\SettingAuction::AUCTION_LINK_TO_CATALOG);
                    return [$auctionCrumb, $lotCrumb, $biddingHistoryCrumb];
                },

                Constants\ResponsiveRoute::AA_ABSENTEE_BIDS => static function () use (
                    $landingType,
                    $auctionId,
                    $lotItemId,
                    $crumbBuilder
                ): array {
                    $absenteeBidsCrumb = $crumbBuilder->buildAbsenteeBidsCrumb($lotItemId, $auctionId, false);
                    $lotCrumb = $crumbBuilder->buildLotCrumb($lotItemId, $auctionId);
                    if ($landingType === Constants\SettingAuction::AUCTION_LINK_TO_INFO) {
                        $auctionCrumb = $crumbBuilder->buildAuctionCrumb($auctionId, Constants\SettingAuction::AUCTION_LINK_TO_INFO);
                        $auctionCatalogCrumb = $crumbBuilder->buildCatalogCrumb($auctionId);
                        return [$auctionCrumb, $auctionCatalogCrumb, $lotCrumb, $absenteeBidsCrumb];
                    }
                    $auctionCrumb = $crumbBuilder->buildAuctionCrumb($auctionId, Constants\SettingAuction::AUCTION_LINK_TO_CATALOG);
                    return [$auctionCrumb, $lotCrumb, $absenteeBidsCrumb];
                },
            ],
            Constants\ResponsiveRoute::C_LOT_DETAILS => [
                Constants\ResponsiveRoute::AACC_INDEX => static function () use (
                    $lotItemId,
                    $auctionId,
                    $landingType,
                    $crumbBuilder
                ): array {
                    $lotCrumb = $crumbBuilder->buildLotCrumb($lotItemId, $auctionId, false);
                    if ($landingType === Constants\SettingAuction::AUCTION_LINK_TO_INFO) {
                        if ($auctionId) {
                            $auctionCrumb = $crumbBuilder->buildAuctionCrumb($auctionId, Constants\SettingAuction::AUCTION_LINK_TO_INFO);
                            $auctionCatalogCrumb = $crumbBuilder->buildCatalogCrumb($auctionId);
                            return [$auctionCrumb, $auctionCatalogCrumb, $lotCrumb];
                        }
                        // Auction is unknown for un-assigned item
                        $auctionListCrumb = $crumbBuilder->buildAuctionListCrumb();
                        return [$auctionListCrumb, $lotCrumb];
                    }

                    if ($auctionId) {
                        $auctionCrumb = $crumbBuilder->buildAuctionCrumb($auctionId, Constants\SettingAuction::AUCTION_LINK_TO_CATALOG);
                        return [$auctionCrumb, $lotCrumb];
                    }
                    // Auction is unknown for un-assigned item
                    $auctionListCrumb = $crumbBuilder->buildAuctionListCrumb();
                    return [$auctionListCrumb, $lotCrumb];
                },
            ],

            Constants\ResponsiveRoute::C_ACCESS_ERROR => [
                Constants\ResponsiveRoute::AAE_INDEX => static function () use ($crumbBuilder): array {
                    $accessErrorCrumb = $crumbBuilder->buildAccessErrorCrumb(false);
                    return [$accessErrorCrumb];
                },
            ],

            Constants\ResponsiveRoute::C_ACCOUNTS => [
                Constants\ResponsiveRoute::AACC_INDEX => static function () use ($crumbBuilder): array {
                    $accountCrumb = $crumbBuilder->buildAccountCrumb(false);
                    return [$accountCrumb];
                },
            ],

            Constants\ResponsiveRoute::C_CHANGE_PASSWORD => [
                Constants\ResponsiveRoute::ACP_INDEX => static function () use ($crumbBuilder): array {
                    $changePasswordCrumb = $crumbBuilder->buildChangePasswordCrumb(false);
                    return [$changePasswordCrumb];
                },
            ],

            Constants\ResponsiveRoute::C_FORGOT_PASSWORD => [
                Constants\ResponsiveRoute::AFP_INDEX => static function () use ($crumbBuilder): array {
                    $forgotPasswordCrumb = $crumbBuilder->buildForgotPasswordCrumb(false);
                    return [$forgotPasswordCrumb];
                },
            ],

            Constants\ResponsiveRoute::C_FORGOT_USERNAME => [
                Constants\ResponsiveRoute::AFU_INDEX => static function () use ($crumbBuilder): array {
                    $forgotPasswordCrumb = $crumbBuilder->buildForgotUsernameCrumb(false);
                    return [$forgotPasswordCrumb];
                },
            ],

            Constants\ResponsiveRoute::C_LOGIN => [
                Constants\ResponsiveRoute::AL_INDEX => static function () use ($crumbBuilder): array {
                    $loginCrumb = $crumbBuilder->buildLoginCrumb(false);
                    return [$loginCrumb];
                },
            ],

            Constants\ResponsiveRoute::C_MY_ITEMS => [

                Constants\ResponsiveRoute::AIT_ALL => static function () use ($crumbBuilder): array {
                    $indexCrumb = $crumbBuilder->buildMyItemIndexCrumb();
                    $itemAllCrumb = $crumbBuilder->buildItemAllCrumb(false);
                    return [$indexCrumb, $itemAllCrumb];
                },

                Constants\ResponsiveRoute::AIT_BIDDING => static function () use ($crumbBuilder): array {
                    $indexCrumb = $crumbBuilder->buildMyItemIndexCrumb();
                    $biddingItemCrumb = $crumbBuilder->buildItemBiddingCrumb(false);
                    return [$indexCrumb, $biddingItemCrumb];
                },

                Constants\ResponsiveRoute::AIT_CONSIGNED => static function () use ($crumbBuilder): array {
                    $indexCrumb = $crumbBuilder->buildMyItemIndexCrumb();
                    $itemConsignedCrumb = $crumbBuilder->buildItemConsignedCrumb(false);
                    return [$indexCrumb, $itemConsignedCrumb];
                },

                Constants\ResponsiveRoute::AIT_NOT_WON => static function () use ($crumbBuilder): array {
                    $indexCrumb = $crumbBuilder->buildMyItemIndexCrumb();
                    $itemNotWonCrumb = $crumbBuilder->buildItemNotWonCrumb(false);
                    return [$indexCrumb, $itemNotWonCrumb];
                },

                Constants\ResponsiveRoute::AIT_WON => static function () use ($crumbBuilder): array {
                    $indexCrumb = $crumbBuilder->buildMyItemIndexCrumb();
                    $itemWonCrumb = $crumbBuilder->buildItemWonCrumb(false);
                    return [$indexCrumb, $itemWonCrumb];
                },

                Constants\ResponsiveRoute::AIT_WATCHLIST => static function () use ($crumbBuilder): array {
                    $indexCrumb = $crumbBuilder->buildMyItemIndexCrumb();
                    $watchListCrumb = $crumbBuilder->buildWatchListCrumb(false);
                    return [$indexCrumb, $watchListCrumb];
                },
            ],

            Constants\ResponsiveRoute::C_MY_ALERTS => [
                Constants\ResponsiveRoute::AALR_INDEX => static function () use ($crumbBuilder): array {
                    $alertCrumb = $crumbBuilder->buildAlertCrumb(false);
                    return [$alertCrumb];
                },
            ],

            Constants\ResponsiveRoute::C_PROFILE => [
                Constants\ResponsiveRoute::APR_VIEW => static function () use ($crumbBuilder): array {
                    $profileCrumb = $crumbBuilder->buildProfileCrumb(false);
                    return [$profileCrumb];
                },
            ],

            Constants\ResponsiveRoute::C_RESET_PASSWORD => [
                Constants\ResponsiveRoute::ARP_INDEX => static function () use ($crumbBuilder): array {
                    $resetPasswordCrumb = $crumbBuilder->buildResetPasswordCrumb(false);
                    return [$resetPasswordCrumb];
                },
            ],

            Constants\ResponsiveRoute::C_SEARCH => [
                Constants\ResponsiveRoute::ASRCH_INDEX => static function () use ($crumbBuilder): array {
                    $resetPasswordCrumb = $crumbBuilder->buildSearchCrumb(false);
                    return [$resetPasswordCrumb];
                },
            ],

            Constants\ResponsiveRoute::C_SIGNUP => [
                Constants\ResponsiveRoute::ASI_INDEX => static function () use ($crumbBuilder): array {
                    $resetSignUPCrumb = $crumbBuilder->buildSignUpCrumb(false);
                    return [$resetSignUPCrumb];
                },
            ],

            Constants\ResponsiveRoute::C_MY_INVOICES => [

                Constants\ResponsiveRoute::AINV_INDEX => static function () use ($crumbBuilder): array {
                    $invoiceCrumb = $crumbBuilder->buildMyInvoiceIndexCrumb(false);
                    return [$invoiceCrumb];
                },

                Constants\ResponsiveRoute::AINV_VIEW => static function () use ($id, $crumbBuilder): array {
                    $invoiceCrumb = $crumbBuilder->buildMyInvoiceIndexCrumb();
                    $invoiceViewCrumb = $crumbBuilder->buildMyInvoiceViewCrumb($id, false);
                    return [$invoiceCrumb, $invoiceViewCrumb];
                },
            ],

            Constants\ResponsiveRoute::C_STACKED_TAX_INVOICE => [
                Constants\ResponsiveRoute::AINV_INDEX => static function () use ($crumbBuilder): array {
                    $invoiceCrumb = $crumbBuilder->buildMyInvoiceIndexCrumb(false);
                    return [$invoiceCrumb];
                },

                Constants\ResponsiveRoute::ASTI_VIEW => static function () use ($crumbBuilder): array {
                    $invoiceCrumb = $crumbBuilder->buildMyInvoiceIndexCrumb();
                    $invoiceViewCrumb = $crumbBuilder->buildStackedTaxInvoiceViewCrumb();
                    return [$invoiceCrumb, $invoiceViewCrumb];
                },
            ],

            Constants\ResponsiveRoute::C_MY_SETTLEMENTS => [

                Constants\ResponsiveRoute::ASTL_INDEX => static function () use ($crumbBuilder): array {
                    $settlementsCrumb = $crumbBuilder->buildMySettlementsIndexCrumb(false);
                    return [$settlementsCrumb];
                },

                Constants\ResponsiveRoute::ASTL_VIEW => static function () use ($id, $crumbBuilder): array {
                    $settlementsCrumb = $crumbBuilder->buildMySettlementsIndexCrumb();
                    $settlementsViewCrumb = $crumbBuilder->buildMySettlementsViewCrumb($id, false);
                    return [$settlementsCrumb, $settlementsViewCrumb];
                },
            ],
        ];

        foreach (Constants\ResponsiveRoute::AR_ACTIONS as $action) {
            $mappingBreadCrumbs[Constants\ResponsiveRoute::C_REGISTER][$action] = static function () use (
                $auctionId,
                $crumbBuilder
            ): array {
                $auctionCrumb = $crumbBuilder->buildAuctionCrumb($auctionId, Constants\SettingAuction::AUCTION_LINK_TO_CATALOG);
                $registrationCrumb = $crumbBuilder->buildRegistrationCrumb($auctionId, false);
                return [$auctionCrumb, $registrationCrumb];
            };
        }

        return ControllerActionCollection::new()->construct($mappingBreadCrumbs);
    }

    /**
     * @return int
     */
    protected function getLandingType(): int
    {
        if ($this->landingType === null) {
            $this->landingType = (int)$this->getSettingsManager()->get(Constants\Setting::AUCTION_LINKS_TO, $this->getSystemAccountId());
        }
        return $this->landingType;
    }

    /**
     * @param int $landingType
     * @return $this
     * @interanl
     */
    public function setLandingType(int $landingType): static
    {
        $this->landingType = $landingType;
        return $this;
    }
}

