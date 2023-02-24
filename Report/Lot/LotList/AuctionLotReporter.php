<?php
/**
 * SAM-4641: Refactor lot "All data CSV" report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/22/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
 * This might include adding, changing, or moving columns, modifying header names, modifying data or data format
 */

namespace Sam\Report\Lot\LotList;

use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Load\AuctionCacheLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\AuctionLot\Load\AuctionLotCacheLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoaderCreateTrait;
use Sam\AuctionLot\StaggerClosing\StaggerClosingHelperCreateTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Bidding\BidTransaction\Load\BidTransactionLoaderCreateTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Constants;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Transform\Html\HtmlRenderer;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Location\Load\LocationLoaderAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Base\Csv\HtmlDecodeAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Report\Base\ReportRendererAwareTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Tax\SamTaxCountryState\Load\SamTaxCountryStateLoaderCreateTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;

/**
 * Class AuctionLotReporter
 * @package Sam\Report\Lot\LotList
 */
class AuctionLotReporter extends ReporterBase
{
    use AdminPrivilegeCheckerAwareTrait;
    use AuctionBidderLoaderAwareTrait;
    use AuctionCacheLoaderAwareTrait;
    use AuctionLotCacheLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use BidTransactionLoaderCreateTrait;
    use BidderNumPaddingAwareTrait;
    use BlockCipherProviderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use CurrencyLoaderAwareTrait;
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use FilePathHelperAwareTrait;
    use FilterAuctionAwareTrait;
    use HtmlDecodeAwareTrait;
    use LocationLoaderAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotQuantityScaleLoaderCreateTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use ReportRendererAwareTrait;
    use SamTaxCountryStateLoaderCreateTrait;
    use StaggerClosingHelperCreateTrait;
    use TimezoneLoaderAwareTrait;
    use UrlBuilderAwareTrait;

    protected ?DataLoader $dataLoader = null;
    protected ?bool $hasPrivilegeForManageCcInfo = null;
    protected ?int $editorUserId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $editorUserId
     * @param int $systemAccountId
     * @return $this
     */
    public function construct(int $editorUserId, int $systemAccountId): AuctionLotReporter
    {
        $this->editorUserId = $editorUserId;
        $this->setSystemAccountId($systemAccountId);
        return $this;
    }

    /**
     * @return DataLoader
     */
    public function getDataLoader(): DataLoader
    {
        if ($this->dataLoader === null) {
            $this->dataLoader = DataLoader::new();
        }
        return $this->dataLoader;
    }

    /**
     * @param DataLoader $dataLoader
     * @return static
     */
    public function setDataLoader(DataLoader $dataLoader): static
    {
        $this->dataLoader = $dataLoader;
        return $this;
    }

    /**
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $saleNo = $this->getAuctionRenderer()->renderSaleNo($this->getFilterAuction());
            $dateIso = $this->getCurrentDateUtc()->format('m-d-Y');
            $filename = "auction_lots_{$dateIso}_{$saleNo}.csv";
            $filename = $this->getFilePathHelper()->toFilename($filename);
            $this->outputFileName = $filename;
        }
        return $this->outputFileName;
    }

    /**
     * @return bool
     */
    public function hasPrivilegeForManageCcInfo(): bool
    {
        if ($this->hasPrivilegeForManageCcInfo === null) {
            $this->hasPrivilegeForManageCcInfo = $this->getAdminPrivilegeChecker()
                ->enableReadOnlyDb(true)
                ->initByUserId($this->editorUserId)
                ->hasPrivilegeForManageCcInfo();
        }
        return $this->hasPrivilegeForManageCcInfo;
    }

    /**
     * @param bool $hasPrivilegeForManageCcInfo
     * @return static
     * @noinspection PhpUnused
     */
    public function enablePrivilegeForManageCcInfo(bool $hasPrivilegeForManageCcInfo): static
    {
        $this->hasPrivilegeForManageCcInfo = $hasPrivilegeForManageCcInfo;
        return $this;
    }

    /**
     * @return string
     */
    protected function outputBody(): string
    {
        $output = '';
        $auction = $this->getFilterAuction();
        $addressChecker = AddressChecker::new();
        // TODO: apply LotList\DataSourceMysql
        $auctionLotGenerator = $this->getAuctionLotLoader()
            ->orderByLotNum(true)
            ->yieldByAuctionId($auction->Id);
        foreach ($auctionLotGenerator as $auctionLot) {
            $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId, true);
            if (!$lotItem) {
                continue;
            }
            $lotNumPrefix = $auctionLot->LotNumPrefix;
            $lotNum = $auctionLot->LotNum;
            $lotNumExt = $auctionLot->LotNumExt;
            $itemNum = $lotItem->ItemNum;
            $itemNumExt = $lotItem->ItemNumExt;
            $quantity = '';
            if ($auctionLot->Quantity !== null) {
                $quantityScale = $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale($auctionLot->LotItemId, $auctionLot->AuctionId);
                $quantity = $this->getNumberFormatter()->formatNto($auctionLot->Quantity, $quantityScale);
            }
            $terms = $auctionLot->TermsAndConditions;
            // Get the general note of every lot item in this sale
            $generalNote = $auctionLot->GeneralNote;

            $categoryList = '';
            $lotCategories = $this->getLotCategoryLoader()->loadForLot($lotItem->Id, true);
            foreach ($lotCategories as $lotCategory) {
                if ($categoryList !== '') {
                    $categoryList .= ';';
                }
                // Convert UTF-8 encoding to the set encoding for export in settings
                $categoryList .= $lotCategory->Name;
            }

            $location = $this->getLocationLoader()->loadCommonOrSpecificLocation(Constants\Location::TYPE_LOT_ITEM, $lotItem, true);
            // Convert UTF-8 encoding to the set encoding for export in settings
            $lotName = $this->getLotRenderer()->makeName($lotItem->Name, $auction->TestAuction);
            $lotDescription = $lotItem->Description;
            if ($this->isHtmlDecode()) {
                $lotDescription = HtmlRenderer::new()->decodeHtmlEntity($lotDescription);
            }
            $lotChanges = $lotItem->Changes;

            $startingBid = $lotItem->StartingBid;
            $incrementsAndAmounts = $this->getReportRenderer()->makeLotBidIncrements($lotItem->Id);
            $cost = $lotItem->Cost;
            $replacementPrice = $lotItem->ReplacementPrice;
            $hammerPriceFormatted = $lotItem->hasHammerPrice()
                ? $this->getNumberFormatter()->formatMoneyNto($lotItem->HammerPrice)
                : '';

            $winnerUsername = '';
            $paddle = '';
            $companyName = '';
            $firstName = '';
            $lastName = '';
            $winnerEmail = '';
            $phone = '';
            $winnerCustomerId = '';
            $referrer = '';
            $referrerHost = '';
            $taxCountry = AddressRenderer::new()->countryName($lotItem->TaxDefaultCountry);
            $taxStateList = '';
            if ($addressChecker->isUsa($lotItem->TaxDefaultCountry)) {
                $samTaxCountryStates = $this->createSamTaxCountryStateLoader()->loadStates(
                    $lotItem->TaxDefaultCountry,
                    null,
                    null,
                    $lotItem->Id
                );
                $taxStates = [];
                foreach ($samTaxCountryStates as $samTaxCountryState) {
                    $taxStates[] = AddressRenderer::new()->stateName($samTaxCountryState, $lotItem->TaxDefaultCountry);
                }
                $taxStateList = implode('|', array_filter($taxStates));
            }

            if ($lotItem->hasWinningBidder()) {
                // load the winning bidder info, if auction is already closed
                $winnerUser = $this->getUserLoader()->load($lotItem->WinningBidderId, true);
                if ($winnerUser) {
                    if ($lotItem->isSaleSoldAuctionLinkedWith($auction->Id)) {
                        $auctionBidder = $this->getAuctionBidderLoader()->load($winnerUser->Id, $lotItem->AuctionId, true);
                        $paddle = $auctionBidder
                            ? $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum)
                            : '';
                    }
                    $winnerUsername = $winnerUser->Username;
                    $winnerEmail = $winnerUser->Email;
                    $winnerCustomerId = $winnerUser->CustomerNo;
                }
            }

            if ($this->hasPrivilegeForManageCcInfo()) {
                $billShipInfo = array_fill(0, 35, '');
            } else {
                $billShipInfo = array_fill(0, 26, '');
            }

            $bidderUserId = $lotItem->WinningBidderId;
            $bidderUserInfo = $this->getUserLoader()->loadUserInfo($bidderUserId, true);
            if ($bidderUserInfo) {
                // Convert UTF-8 encoding to the set encoding for export in settings
                $companyName = $bidderUserInfo->CompanyName;
                $firstName = $bidderUserInfo->FirstName;
                $lastName = $bidderUserInfo->LastName;
                $referrer = $bidderUserInfo->Referrer;
                $referrerHost = $bidderUserInfo->ReferrerHost;
                $phone = $bidderUserInfo->Phone;
                $billShipInfoRow = $this->getDataLoader()
                    ->loadUserBillingShippingById($bidderUserInfo->UserId, $this->hasPrivilegeForManageCcInfo());
                if ($billShipInfoRow) {
                    $billShipInfo = $billShipInfoRow;
                    if (isset($billShipInfo['bill_cc_number'])) {
                        $billShipInfo['bill_cc_number'] = $this->createBlockCipherProvider()->construct()->decrypt($billShipInfo['bill_cc_number']);
                    }
                    if (isset($billShipInfo['bill_use_card'])) {
                        $billShipInfo['bill_use_card'] = $this->renderBool((bool)$billShipInfo['bill_use_card']);
                    }
                    if (isset($billShipInfo['bill_con_type'])) {
                        $billShipInfo['bill_con_type'] = Constants\User::CONTACT_TYPE_ENUM[(int)$billShipInfo['bill_con_type']] ?? '';
                    }
                    if (isset($billShipInfo['ship_con_type'])) {
                        $billShipInfo['ship_con_type'] = Constants\User::CONTACT_TYPE_ENUM[(int)$billShipInfo['ship_con_type']] ?? '';
                    }
                }
            }

            $internetBid = $this->renderBool($lotItem->InternetBid);
            $consignorUsername = '';
            $consignorCompany = '';
            $consignorUser = $this->getUserLoader()->load($lotItem->ConsignorId, true);
            if ($consignorUser) {
                $consignorUsername = $consignorUser->Username;
                $consignorUserInfo = $this->getUserLoader()->loadUserInfoOrCreate($consignorUser->Id, true);
                if ($consignorUserInfo->CompanyName !== '') {
                    $consignorCompany = $consignorUserInfo->CompanyName;
                }
            }

            $auctionLotCache = $this->getAuctionLotCacheLoader()->loadById($auctionLot->Id, true);
            $viewCount = $auctionLotCache->ViewCount ?? 0;
            $seoUrl = $auctionLotCache->SeoUrl ?? null;
            $isListingOnly = $auctionLot->ListingOnly;
            $catalogLotUrl = $this->getUrlBuilder()->build(
                ResponsiveLotDetailsUrlConfig::new()->forWeb(
                    $auctionLot->LotItemId,
                    $auctionLot->AuctionId,
                    $seoUrl
                )
            );

            $bodyRow = [];
            if ($this->cfg()->get('core->lot->itemNo->concatenated')) {
                $bodyRow[] = $this->getLotRenderer()->makeItemNo($itemNum, $itemNumExt);
            } else {
                $bodyRow = array_merge($bodyRow, [$itemNum, $itemNumExt]);
            }
            if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
                $bodyRow[] = $this->getLotRenderer()->makeLotNo($lotNum, $lotNumExt, $lotNumPrefix);
            } else {
                $bodyRow = array_merge($bodyRow, [$lotNumPrefix, $lotNum, $lotNumExt]);
            }

            if ($auction->isLiveOrHybrid()) {
                $groupId = $auctionLot->GroupId;
                $lowEstimate = $lotItem->LowEstimate;
                $highEstimate = $lotItem->HighEstimate;
                $reservePrice = $lotItem->ReservePrice;
                $absenteeBidCount = $auctionLotCache->BidCount ?? 0;
                $presaleMaxBid = ($absenteeBidCount && $auctionLotCache) ? $auctionLotCache->CurrentMaxBid : '';
                $bodyRow = array_merge(
                    $bodyRow,
                    [
                        $groupId,
                        $categoryList,
                        $lotName,
                        $lotDescription,
                        $quantity,
                        $isListingOnly,
                        $this->getNumberFormatter()->formatMoneyNto($lowEstimate),
                        $this->getNumberFormatter()->formatMoneyNto($highEstimate),
                        $this->getNumberFormatter()->formatMoneyNto($reservePrice),
                        $this->getNumberFormatter()->formatMoneyNto($startingBid),
                        $incrementsAndAmounts,
                        $this->getNumberFormatter()->formatMoneyNto($cost),
                        $this->getNumberFormatter()->formatMoneyNto($replacementPrice),
                        $hammerPriceFormatted,
                        $winnerUsername,
                        $companyName,
                        $winnerCustomerId,
                        $paddle,
                        $firstName,
                        $lastName,
                        $winnerEmail,
                        $phone,
                    ],
                    $billShipInfo,
                    [
                        $internetBid,
                        $this->getNumberFormatter()->formatMoneyNto($presaleMaxBid),
                        $absenteeBidCount,
                        $consignorUsername,
                        $consignorCompany,
                        $viewCount,
                        $catalogLotUrl,
                        $generalNote,
                        $referrer,
                        $referrerHost,
                        $terms,
                        $lotChanges,
                        $taxCountry,
                        $taxStateList,
                        $location->Name ?? '',
                        $location->Address ?? '',
                        $location->Country ?? '',
                        $location->City ?? '',
                        $location->County ?? '',
                        $location->Logo ?? '',
                        $location->State ?? '',
                        $location->Zip ?? '',
                    ]
                );
            } else {
                $dateFormat = 'm/d/Y g:i A';
                if (
                    $auction->ExtendAll
                    && $auctionLot->isActive()
                ) {
                    $timezone = $this->getTimezoneLoader()->load($auction->TimezoneId, true);
                    $timezoneLocation = $timezone->Location ?? null;
                    $startDateTz = $this->getDateHelper()->convertUtcToTzById($auction->StartDate, $auction->TimezoneId);
                    $startDateFormatted = $this->getDateHelper()
                        ->formattedDate($startDateTz, null, $timezoneLocation, null, $dateFormat);
                    $endDateTz = $this->getDateHelper()->convertUtcToTzById($auction->EndDate, $auction->TimezoneId);
                    if ($auction->StaggerClosing) {
                        $endDateTz = null;
                        $startClosingDateTz = $this->getDateHelper()->convertUtcToTzById($auction->StartClosingDate, $auction->TimezoneId);
                        if ($startClosingDateTz) {
                            $endDateTz = $this->createStaggerClosingHelper()
                                ->calcEndDate(
                                    $startClosingDateTz,
                                    $auction->LotsPerInterval,
                                    $auction->StaggerClosing,
                                    $auctionLot->Order
                                );
                        }
                    }
                    $endDateFormatted = $this->getDateHelper()
                        ->formattedDate($endDateTz, null, $timezoneLocation, null, $dateFormat);
                } else {
                    $timezone = $this->getTimezoneLoader()->load($auctionLot->TimezoneId, true);
                    $timezoneLocation = $timezone->Location ?? null;
                    $startDateFormatted = $this->getDateHelper()->formatUtcDate($auctionLot->StartDate, null, $timezoneLocation, null, $dateFormat);
                    $endDateFormatted = $this->getDateHelper()->formatUtcDate($auctionLot->EndDate, null, $timezoneLocation, null, $dateFormat);
                }

                $lowEstimate = $lotItem->LowEstimate;
                $highEstimate = $lotItem->HighEstimate;
                $reservePrice = $lotItem->ReservePrice;

                $lastBidTransaction = $this->createBidTransactionLoader()->loadLastActiveBid($lotItem->Id, $auction->Id);
                $currentBidAmount = $lastBidTransaction->Bid ?? null;
                $maxBid = $lastBidTransaction->MaxBid ?? null;

                $bodyRow = array_merge(
                    $bodyRow,
                    [
                        $categoryList,
                        $lotName,
                        $lotDescription,
                        $quantity,
                        $isListingOnly,
                        $startDateFormatted,
                        $endDateFormatted,
                        $this->getNumberFormatter()->formatMoneyNto($lowEstimate),
                        $this->getNumberFormatter()->formatMoneyNto($highEstimate),
                        $this->getNumberFormatter()->formatMoneyNto($reservePrice),
                        $this->getNumberFormatter()->formatMoneyNto($startingBid),
                        $incrementsAndAmounts,
                        $this->getNumberFormatter()->formatMoneyNto($cost),
                        $this->getNumberFormatter()->formatMoneyNto($replacementPrice),
                        $hammerPriceFormatted,
                        $winnerUsername,
                        $companyName,
                        $winnerCustomerId,
                        $paddle,
                        $firstName,
                        $lastName,
                        $winnerEmail,
                        $phone,
                    ],
                    $billShipInfo,
                    [
                        $currentBidAmount,
                        $maxBid,
                        $consignorUsername,
                        $consignorCompany,
                        $viewCount,
                        $catalogLotUrl,
                        $generalNote,
                        $referrer,
                        $referrerHost,
                        $terms,
                        $lotChanges,
                        $taxCountry,
                        $taxStateList,
                        $location->Name ?? '',
                        $location->Address ?? '',
                        $location->Country ?? '',
                        $location->City ?? '',
                        $location->County ?? '',
                        $location->Logo ?? '',
                        $location->State ?? '',
                        $location->Zip ?? '',
                    ]
                );
            }

            $bodyLine = $this->makeLine($bodyRow);
            $output .= $this->processOutput($bodyLine);
        }
        return $output;
    }

    /**
     * Output titles for csv header (string or echo)
     * @return string
     */
    protected function outputTitles(): string
    {
        $auction = $this->getFilterAuction();
        $saleNo = $this->getAuctionRenderer()->renderSaleNo($auction);
        $timezone = $this->getTimezoneLoader()->load($auction->TimezoneId, true);
        $location = $timezone->Location ?? null;
        $auctionDateFormatted = '';
        if (
            $auction->isLiveOrHybrid()
            || $auction->isTimedScheduled()
        ) {
            $auctionDateFormatted .= $this->getDateHelper()
                ->formatUtcDate($auction->detectScheduledStartDate(), null, $location, null, Constants\Date::US_DATE_TIME);
        }
        $currencySign = $this->getCurrencyLoader()->detectDefaultSign($auction->Id);

        if ($auction->isTimedScheduled()) {
            $auctionDateFormatted .= ' - ' . $this->getDateHelper()
                    ->formatUtcDate($auction->EndDate, null, $location, null, Constants\Date::US_DATE_TIME);
        }
        $headerLine = '"Sale no. ' . $saleNo . '","Auction Date ' . $auctionDateFormatted . '"' . "\n\n";
        $headerTitles = [];

        if ($this->hasPrivilegeForManageCcInfo()) {
            $billingCcInfoHeaders = [
                "BillingCcType",
                "BillingCcNumber",
                "BillingCcExpDate",
                "BillingUseCard?",
                "BillingBankRoutingNum",
                "BillingBankAccNumber",
                "BillingBankAccType",
                "BillingBankName",
                "BillingBankAccName",
            ];
        } else {
            $billingCcInfoHeaders = [];
        }

        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            $lotNoTitle = ["LotFullNumber"];
        } else {
            $lotNoTitle = ["LotNumberPrefix", "Lot #", "LotNumberExtension"];
        }

        if ($this->cfg()->get('core->lot->itemNo->concatenated')) {
            $itemNoTitle = ["ItemFullNumber"];
        } else {
            $itemNoTitle = ["Item #", "ItemNumberExtension"];
        }

        if ($auction->isLiveOrHybrid()) {
            $headerTitles = array_merge(
                $headerTitles,
                $itemNoTitle,
                $lotNoTitle,
                [
                    "GroupId",
                    "Category",
                    "Name",
                    "Description",
                    "Qty",
                    "Listing Only",
                    "Low est[" . $currencySign . "]",
                    "High est[" . $currencySign . "]",
                    "Reserve[" . $currencySign . "]",
                    "Start bid[" . $currencySign . "]",
                    "Increment",
                    "Cost[" . $currencySign . "]",
                    "Replacement Price[" . $currencySign . "]",
                    "Hammer Price[" . $currencySign . "]",
                    "Winner Username",
                    "Company Name",
                    "Customer No",
                    "Bidder#",
                    "First",
                    "Last",
                    "Email",
                    "Phone",
                    "ShippingCompanyName",
                    "ShippingFirstName",
                    "ShippingLastName",
                    "ShippingPhone",
                    "ShippingFax",
                    "ShippingCountry",
                    "ShippingAddress",
                    "ShippingAddressLn2",
                    "ShippingAddressLn3",
                    "ShippingCity",
                    "ShippingState",
                    "ShippingZip",
                    "ShippingContactType",
                    "BillingCompanyName",
                    "BillingFirstName",
                    "BillingLastName",
                    "BillingPhone",
                    "BillingFax",
                    "BillingCountry",
                    "BillingAddress",
                    "BillingAddressLn2",
                    "BillingAddressLn3",
                    "BillingCity",
                    "BillingState",
                    "BillingZip",
                    "BillingContactType",
                ],
                $billingCcInfoHeaders,
                [
                    "Int.",
                    "High presale bid[" . $currencySign . "]",
                    "# of presale bids",
                    "Consignor",
                    "Consignor Company",
                    "Views",
                    "Link",
                    "General Note",
                    "Referrer",
                    "Referrer host",
                    "T&C",
                    "Changes",
                    "ItemTaxCountry",
                    "ItemTaxStates",
                    "Location (named, mutually exclusive with detailed location)",
                    "Location address",
                    "Location country",
                    "Location city",
                    "Location county",
                    "Location logo",
                    "Location state",
                    "Location zip",
                ]
            ); // Header

        } else {
            $headerTitles = array_merge(
                $headerTitles,
                $itemNoTitle,
                $lotNoTitle,
                [
                    "Category",
                    "Name",
                    "Description",
                    "Qty",
                    "Listing Only",
                    "Start date/time",
                    "End date/time",
                    "Low Est[" . $currencySign . "]",
                    "High Est[" . $currencySign . "]",
                    "Reserve[" . $currencySign . "]",
                    "Start Bid[" . $currencySign . "]",
                    "Increment",
                    "Cost[" . $currencySign . "]",
                    "Replacement Price[" . $currencySign . "]",
                    "Hammer Price[" . $currencySign . "]",
                    "Winner Username",
                    "Company Name",
                    "Customer No",
                    "Bidder#",
                    "First",
                    "Last",
                    "Email",
                    "Phone",
                    "ShippingCompanyName",
                    "ShippingFirstName",
                    "ShippingLastName",
                    "ShippingPhone",
                    "ShippingFax",
                    "ShippingCountry",
                    "ShippingAddress",
                    "ShippingAddressLn2",
                    "ShippingAddressLn3",
                    "ShippingCity",
                    "ShippingState",
                    "ShippingZip",
                    "ShippingContactType",
                    "BillingCompanyName",
                    "BillingFirstName",
                    "BillingLastName",
                    "BillingPhone",
                    "BillingFax",
                    "BillingCountry",
                    "BillingAddress",
                    "BillingAddressLn2",
                    "BillingAddressLn3",
                    "BillingCity",
                    "BillingState",
                    "BillingZip",
                    "BillingContactType",
                ],
                $billingCcInfoHeaders,
                [
                    "Current Bid",
                    "Max Bid",
                    "Consignor",
                    "Consignor Company",
                    "Views",
                    "Link",
                    "General Note",
                    "Referrer",
                    "Referrer host",
                    "T&C",
                    "Changes",
                    "ItemTaxCountry",
                    "ItemTaxStates",
                    "Location (named, mutually exclusive with detailed location)",
                    "Location address",
                    "Location country",
                    "Location city",
                    "Location county",
                    "Location logo",
                    "Location state",
                    "Location zip",
                ]
            );
        }

        $headerLine .= $this->makeLine($headerTitles);

        return $this->processOutput($headerLine);
    }

    /**
     * @return bool
     */
    protected function validate(): bool
    {
        $this->errorMessage = null;
        $auction = $this->getFilterAuction();
        if ($auction === null) {
            // Unknown auction situation already processed at controller layer in allow() method
            $this->errorMessage = "Auction not found" . composeSuffix(['a' => $this->getFilterAuctionId()]);
        } elseif ($auction->isDeleted()) {
            $this->errorMessage = "Auction already deleted" . composeSuffix(['a' => $this->getFilterAuctionId()]);
        }
        $success = $this->errorMessage === null;
        return $success;
    }

    /**
     * @param bool $is
     * @return string
     */
    protected function renderBool(bool $is): string
    {
        return $this->getReportTool()->renderBool($is);
    }
}
