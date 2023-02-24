<?php
/**
 * SAM-4617: Refactor Auction Bids report
 * https://bidpath.atlassian.net/browse/SAM-4617
 *
 * @author        Vahagn Hovsepyan
 * @since         Dec 10, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Report\Auction\AuctionBid;

use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Auction\Load\AuctionCacheLoaderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Load\TimedItemLoaderAwareTrait;
use Sam\AuctionLot\StaggerClosing\StaggerClosingHelperCreateTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Billing\CreditCard\Load\CreditCardLoaderAwareTrait;
use Sam\Billing\Gate\Availability\BillingGateAvailabilityCheckerCreateTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\AuctionBidReportFormConstants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Entity\Model\LotItem\SellInfo\LotSellInfoPureChecker;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Transform\Html\HtmlEntityTransformer;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;

/**
 * Class AuctionBidReporter
 */
class AuctionBidReporter extends ReporterBase
{
    use AccountLoaderAwareTrait;
    use AdminPrivilegeCheckerAwareTrait;
    use AuctionBidderLoaderAwareTrait;
    use AuctionCacheLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use BillingGateAvailabilityCheckerCreateTrait;
    use BlockCipherProviderCreateTrait;
    use CommonAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CreditCardLoaderAwareTrait;
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use DbConnectionTrait;
    use FilePathHelperAwareTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterDatePeriodAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotRendererAwareTrait;
    use StaggerClosingHelperCreateTrait;
    use TimedItemLoaderAwareTrait;
    use TimezoneLoaderAwareTrait;

    // bid types
    public const BT_BID_TRANSACTION = 'BT';
    public const BT_ABSENTEE_BID = 'AB';

    protected ?DataLoader $dataLoader = null;
    protected ?bool $hasPrivilegeForManageCcInfo = null;
    protected ?bool $isAchPayment = null;
    protected ?int $editorUserId = null;

    /**
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
    public function construct(int $editorUserId, int $systemAccountId): AuctionBidReporter
    {
        $this->editorUserId = $editorUserId;
        $this->setSystemAccountId($systemAccountId);
        return $this;
    }

    /**
     * @return bool
     */
    public function isAchPayment(): bool
    {
        if ($this->isAchPayment === null) {
            $this->isAchPayment = $this->createBillingGateAvailabilityChecker()
                ->isAchPaymentEnabled($this->getSystemAccountId());
        }
        return $this->isAchPayment;
    }

    /**
     * @param bool $isAchPayment
     * @return static
     */
    public function enableAchPayment(bool $isAchPayment): static
    {
        $this->isAchPayment = $isAchPayment;
        return $this;
    }

    /**
     * @return DataLoader
     */
    public function getDataLoader(): DataLoader
    {
        if ($this->dataLoader === null) {
            $this->dataLoader = DataLoader::new()
                ->filterAccountId($this->getFilterAccountId())
                ->filterAuctionId($this->getFilterAuctionId())
                ->enableDateFiltering($this->isDateFiltering())
                ->setDateRangeType($this->getDateRangeType());
            if ($this->isDateFiltering()) {
                $this->dataLoader
                    ->filterEndDateSysIso($this->getFilterEndDateSysIso())
                    ->filterStartDateSysIso($this->getFilterStartDateSysIso());
            }
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
     */
    public function enablePrivilegeForManageCcInfo(bool $hasPrivilegeForManageCcInfo): static
    {
        $this->hasPrivilegeForManageCcInfo = $hasPrivilegeForManageCcInfo;
        return $this;
    }

    /**
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $dateRangeType = $this->getDateRangeType();
            $header = $dateRangeType === AuctionBidReportFormConstants::DR_BID
                ? 'auction-chosen-bid-date' : 'auction-chosen-auction-date';
            $dateIso = $this->getCurrentDateUtc()->format('m-d-Y');
            $filename = "{$header}-{$dateIso}.csv";
            $filename = $this->getFilePathHelper()->toFilename($filename);
            $this->setOutputFileName($filename);
        }
        return $this->outputFileName;
    }

    /**
     * @return string
     */
    protected function outputBody(): string
    {
        $rows = $this->getDataLoader()->load();
        if (!count($rows)) {
            echo "No bids found!";
            return '';
        }

        $output = '';
        foreach ($rows as $row) {
            $bodyLine = $this->buildBodyLine($row);
            $output .= $this->processOutput($bodyLine);
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    protected function buildBodyLine(array $row): string
    {
        $lotItemId = (int)$row['lot_item_id'];
        $auctionId = (int)$row['auction_id'];
        $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $auctionId, true);
        if (!$auctionLot) {
            log_error(
                "Available auction lot not found for auction bids report"
                . composeSuffix(['li' => $lotItemId, 'a' => $auctionId])
            );
            return '';
        }
        $auction = $this->getAuctionLoader()->load($auctionId, true);
        if (!$auction) {
            log_error("Available auction not found for auction bids report" . composeSuffix(['a' => $auctionId]));
            return '';
        }

        $bidDate = $this->getDateHelper()->convertUtcToSysByDateIso($row['bid_date']);
        $bidDateFormatted = $bidDate ? $bidDate->format(Constants\Date::US_DATE_TIME) : '';

        $itemNo = $this->getLotRenderer()->makeItemNo($row['item_num'], $row['item_num_ext']);

        $lotCategoryNames = $this->getLotCategoryLoader()->loadNamesForLot($lotItemId, true);
        $categoryOutput = implode(',', $lotCategoryNames);

        $auctionName = $this->getAuctionRenderer()->makeName($row['auction_name'], (bool)$row['test_auction']);
        $saleNo = $this->getAuctionRenderer()->makeSaleNo($row['sale_num'], $row['sale_num_ext']);

        // initialize variables for fail safe.
        $auctionStartDateFormatted = '';
        $auctionEndDateFormatted = '';
        $lotStartDateFormatted = '';
        $lotEndDateFormatted = '';

        $account = $this->getAccountLoader()->load((int)$row['account_id'], true);
        $accountName = '';
        $accountCompany = '';
        if ($account) {
            $accountName = $account->Name;
            $accountCompany = $account->CompanyName;
        }

        $timezoneLoader = $this->getTimezoneLoader();
        $timezone = $timezoneLoader->load($auction->TimezoneId, true);
        $tzLocation = $timezone->Location ?? '';
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $auctionType = $row['auction_type'];

        /* Default values for live auction in columns
         * Auction start date, Auction end date,
         * Lot start date, Lot end date,
         */
        if ($auctionStatusPureChecker->isLiveOrHybrid($auctionType)) {
            $auctionStartDateFormatted
                = $auctionEndDateFormatted
                = $lotStartDateFormatted
                = $lotEndDateFormatted
                = $this->getDateHelper()
                ->formatUtcDate($auction->StartDate, $this->getSystemAccountId(), $tzLocation);
        } elseif ($auctionStatusPureChecker->isTimed($auctionType)) {
            if ($auctionStatusPureChecker->isTimedScheduled($auctionType, (int)$row['event_type'])) {
                $auctionStartDateFormatted = $this->getDateHelper()
                    ->formatUtcDate($auction->StartDate, $this->getSystemAccountId(), $tzLocation);
                $auctionEndDateFormatted = $this->getDateHelper()
                    ->formatUtcDate($auction->EndDate, $this->getSystemAccountId(), $tzLocation);
            }

            if (
                $auction->ExtendAll
                && $auctionLot->isActive()
            ) {
                $lotStartDateFormatted = $this->getDateHelper()
                    ->formatUtcDate($auction->StartBiddingDate, $this->getSystemAccountId(), $tzLocation);
                if ($auction->StaggerClosing) {
                    $lotEndDate = null;
                    if ($auction->StartClosingDate) {
                        $lotEndDate = $this->createStaggerClosingHelper()
                            ->calcEndDate(
                                $auction->StartClosingDate,
                                $auction->LotsPerInterval,
                                $auction->StaggerClosing,
                                $auctionLot->Order
                            );
                    }
                    $lotEndDateFormatted = $this->getDateHelper()
                        ->formatUtcDate($lotEndDate, $this->getSystemAccountId(), $tzLocation);
                } else {
                    $lotEndDateFormatted = $this->getDateHelper()
                        ->formatUtcDate($auction->EndDate, $this->getSystemAccountId(), $tzLocation);
                }
            } else {
                $timezone = $timezoneLoader->load($auctionLot->TimezoneId, true);
                $tzLocation = $timezone->Location ?? '';
                $lotStartDateFormatted = $this->getDateHelper()->formatUtcDateIso(
                    $row['lot_start_date'],
                    $this->getSystemAccountId(),
                    $tzLocation
                );
                $lotEndDateFormatted = $this->getDateHelper()->formatUtcDateIso(
                    $row['lot_end_date'],
                    $this->getSystemAccountId(),
                    $tzLocation
                );
            }
        }

        $bidType = '';
        $bidAmount = '';
        $winningBidText = 'No';

        if ($row['bid_type'] === self::BT_BID_TRANSACTION) {
            $bidAmount = (float)$row['bid'];
            if ($auctionStatusPureChecker->isTimed($auctionType)) {
                $bidType = 'Timed';
            } elseif ($auctionStatusPureChecker->isLiveOrHybrid($auctionType)) {
                if ($row['flr_bid']) {
                    $bidType = 'Floor';
                } elseif ($row['abs_bid']) {
                    $bidType = 'Live Absentee';
                } else {
                    $bidType = 'Floor';
                    if ((int)$row['user_id'] > 0) {
                        $auctionBidder = $this->getAuctionBidderLoader()->load((int)$row['user_id'], $auctionId, true);
                        if ($auctionBidder) {
                            $bidType = 'Internet Live';
                        }
                    }
                }
            }
        } elseif ($row['bid_type'] === self::BT_ABSENTEE_BID) {
            $bidAmount = (float)$row['max_bid'];
            $bidType = 'Absentee';
        }

        $lotName = $row['lot_name'];
        $lowEstimate = $row['low_estimate'];
        $highEstimate = $row['high_estimate'];
        $hammerPrice = Cast::toFloat($row['hammer_price']);
        if (
            LotSellInfoPureChecker::new()->isHammerPrice($hammerPrice)
            && Floating::eq($hammerPrice, $bidAmount)
            && $auctionId === (int)$row['winning_auction_id']
        ) {
            $winningBidText = 'Yes';
        }

        $username = $row['username'];
        $email = $row['email'];
        $customerNo = $row['customer_no'];
        $firstName = $row['first_name'];
        $lastName = $row['last_name'];
        $taxApplication = UserPureRenderer::new()->makeTaxApplication((int)$row['tax_application']);
        $note = HtmlEntityTransformer::new()->fromHtmlEntity(trim((string)$row['note']));
        $buyersSalesTax = $row['sales_tax'];
        $phone = $row['phone'];

        $userBilling = $this->getUserLoader()->loadUserBillingOrCreate((int)$row['user_id'], true);
        $billingCompany = $userBilling->CompanyName;
        $billingFirstName = $userBilling->FirstName;
        $billingLastName = $userBilling->LastName;
        $billingPhone = $userBilling->Phone;
        $billingFax = $userBilling->Fax;
        $billingCountry = AddressRenderer::new()->countryName($userBilling->Country);
        $billingAddress = $userBilling->Address;
        $billingAddress2 = $userBilling->Address2;
        $billingAddress3 = $userBilling->Address3;
        $billingCity = $userBilling->City;
        $billingState = AddressRenderer::new()->stateName($userBilling->State, $userBilling->Country);
        $billingZip = $userBilling->Zip;

        $ccType = $ccNumber = $ccExpDate = $bankRouting = $bankAccount
            = $bankAccountType = $bankName = $bankAccountName = '';
        if ($this->hasPrivilegeForManageCcInfo()) {
            $creditCard = $this->getCreditCardLoader()->load($userBilling->CcType, true);
            if (
                $userBilling->CcType
                && $creditCard
            ) {
                $ccType = $creditCard->Name;
            }

            if ($userBilling->CcNumber) {
                $ccNumber = $this->createBlockCipherProvider()->construct()->decrypt($userBilling->CcNumber);
            }

            $ccExpDate = $userBilling->CcExpDate;

            if ($this->isAchPayment()) {
                $bankRouting = $userBilling->BankRoutingNumber;
                $bankAccount = $this->createBlockCipherProvider()->construct()->decrypt($userBilling->BankAccountNumber);
                $bankAccountType = UserPureRenderer::new()->makeBankAccountType($userBilling->BankAccountType);
                $bankName = $userBilling->BankName;
                $bankAccountName = $userBilling->BankAccountName;
            }
        }

        $userShipping = $this->getUserLoader()->loadUserShippingOrCreate((int)$row['user_id'], true);
        $shippingCompany = $userShipping->CompanyName;
        $shippingFirstName = $userShipping->FirstName;
        $shippingLastName = $userShipping->LastName;
        $shippingPhone = $userShipping->Phone;
        $shippingFax = $userShipping->Fax;
        $shippingCountry = AddressRenderer::new()->countryName($userShipping->Country);
        $shippingAddress = $userShipping->Address;
        $shippingAddress2 = $userShipping->Address2;
        $shippingAddress3 = $userShipping->Address3;
        $shippingCity = $userShipping->City;
        $shippingState = AddressRenderer::new()->stateName($userShipping->State, $userShipping->Country);
        $shippingZip = $userShipping->Zip;

        $bodyRow = [
            $bidDateFormatted,
            $itemNo,
            $lotName,
            $categoryOutput,
            $auctionName,
            $saleNo,
            $bidType,
            $bidAmount,
            $winningBidText,
            $lowEstimate,
            $highEstimate,
            $hammerPrice,
            $username,
            $firstName,
            $lastName,
            $email,
            $phone,
            $customerNo,
            $buyersSalesTax,
            $taxApplication,
            $note,
            $billingCompany,
            $billingFirstName,
            $billingLastName,
            $billingPhone,
            $billingFax,
            $billingCountry,
            $billingAddress,
            $billingAddress2,
            $billingAddress3,
            $billingCity,
            $billingState,
            $billingZip,
        ];

        if ($this->hasPrivilegeForManageCcInfo()) {
            $bodyRow = array_merge($bodyRow, [$ccType, $ccNumber, $ccExpDate]);
            if ($this->isAchPayment()) {
                $bodyRow = array_merge(
                    $bodyRow,
                    [$bankRouting, $bankAccount, $bankAccountType, $bankName, $bankAccountName]
                );
            } else {
                $bodyRow = array_merge($bodyRow, array_fill(0, 5, ''));
            }
        } else {
            $bodyRow = array_merge($bodyRow, array_fill(0, 8, ''));
        }

        $bodyRow = array_merge(
            $bodyRow,
            [
                $shippingCompany,
                $shippingFirstName,
                $shippingLastName,
                $shippingPhone,
                $shippingFax,
                $shippingCountry,
                $shippingAddress,
                $shippingAddress2,
                $shippingAddress3,
                $shippingCity,
                $shippingState,
                $shippingZip,
                $auctionStartDateFormatted,
                $auctionEndDateFormatted,
                $lotStartDateFormatted,
                $lotEndDateFormatted,
            ]
        );

        if ($this->cfg()->get('core->portal->enabled')) {
            $bodyRow = array_merge($bodyRow, [$accountName, $accountCompany]);
        }

        $bodyLine = $this->makeLine($bodyRow);

        return $bodyLine;
    }

    /**
     * Output titles for csv header (string or echo)
     * @return string
     */
    protected function outputTitles(): string
    {
        // basic headers
        $headerTitles = [
            "Date/Time",
            "Item#",
            "Lot Name",
            "Category",
            "Auction Name",
            "Auction #",
            "Bid Type",
            "Bid Amount",
            "Winning Bid?",
            "Low Estimate",
            "High Estimate",
            "Hammer Price",
            "Username",
            "First Name",
            "Last Name",
            "Email",
            "Phone #",
            "Customer #",
            "Buyer's Sales Tax (%)",
            "Apply Tax To",
            "Notes",
            // billing headers
            "Billing Company name",
            "Billing First name",
            "Billing Last name",
            "Billing Phone",
            "Billing Fax",
            "Billing Country",
            "Billing Address",
            "Billing Address Ln 2",
            "Billing Address Ln 3",
            "Billing City",
            "Billing State",
            "Billing ZIP",
            "CC Type",
            "CC Number",
            "CC Exp. Date",
            "Bank Routing #",
            "Bank Acct #",
            "Bank Acct Type",
            "Bank Name",
            "Bank Account Name",
            // shipping info headers
            "Shipping Company Name",
            "Shipping First Name",
            "Shipping Last Name",
            "Shipping Phone",
            "Shipping Fax",
            "Shipping Country",
            "Shipping Address",
            "Shipping Address Ln 2",
            "Shipping Address Ln 3",
            "Shipping City",
            "Shipping State",
            "Shipping ZIP",
            "Auction Start Date",
            "Auction End Date",
            "Lot Start Date",
            "Lot End Date",
        ];
        // newly added columns
        if ($this->cfg()->get('core->portal->enabled')) {
            array_push($headerTitles, "Account Name", "Account Company");
        }

        $headerLine = $this->makeLine($headerTitles);

        return $this->processOutput($headerLine);
    }

    /**
     * @return bool
     */
    protected function validate(): bool
    {
        $errorMessages = [];
        if ($this->isDateFiltering()) {
            $startDateSysIso = $this->getFilterStartDateSysIso();
            $endDateSysIso = $this->getFilterEndDateSysIso();
            if (!strtotime($startDateSysIso)) {
                $errorMessages[] = 'Invalid start date';
            }
            if (!strtotime($endDateSysIso)) {
                $errorMessages[] = 'Invalid end date';
            }
            if (strtotime($startDateSysIso) > strtotime($endDateSysIso)) {
                $errorMessages[] = 'Start date is later than end date';
            }
            if (!$this->getDateRangeType()) {
                $errorMessages[] = "Filter date range not defined";
            }
        }
        $this->errorMessage = implode('; ', $errorMessages);
        $success = count($errorMessages) === 0;
        return $success;
    }
}
