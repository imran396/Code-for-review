<?php
/**
 * SAM-4642: Refactor "Bid history CSV" report
 * https://bidpath.atlassian.net/browse/SAM-4642
 *
 * @author        Vahagn Hovsepyan, Yura Vakulenko
 * @since         Mar 07, 2019
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Report\Lot\BidHistory;

use Auction;
use AuctionLotItem;
use BidTransaction;
use DateTime;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoaderCreateTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Bidding\BidTransaction\Load\BidTransactionLoaderCreateTrait;
use Sam\Billing\CreditCard\Load\CreditCardLoaderAwareTrait;
use Sam\Billing\Gate\Availability\BillingGateAvailabilityCheckerCreateTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Constants;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelper;
use Sam\Date\DateHelperAwareTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Load\UserLoader;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;
use Sam\User\Privilege\Validate\BidderPrivilegeChecker;
use User;
use UserBilling;
use UserShipping;

/**
 * Class LotBidHistoryReporter
 */
class LotBidHistoryReporter extends ReporterBase
{
    use AdminPrivilegeCheckerAwareTrait;
    use AuctionBidderLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use BidTransactionLoaderCreateTrait;
    use BidderNumPaddingAwareTrait;
    use BillingGateAvailabilityCheckerCreateTrait;
    use BlockCipherProviderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use CreditCardLoaderAwareTrait;
    use CurrencyLoaderAwareTrait;
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use FilePathHelperAwareTrait;
    use FilterAuctionAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotQuantityScaleLoaderCreateTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use TimezoneLoaderAwareTrait;

    /** @var bool|null */
    protected ?bool $hasPrivilegeForManageCcInfo = null;
    /** @var bool|null */
    protected ?bool $isAchPayment = null;
    protected ?int $editorUserId = null;

    /**
     * Ready for CSV output report columns titles. we need them to adjust 'Output body'
     * columns with 'Output titles' columns in case if 'Output body' columns less that in 'Output titles'.
     * Will be initialized in $this->outputTitles();
     * @var array
     */
    protected array $reportReadyTitles = [];

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
    public function construct(int $editorUserId, int $systemAccountId): LotBidHistoryReporter
    {
        $this->editorUserId = $editorUserId;
        $this->setSystemAccountId($systemAccountId);
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
            $filename = "auction_bid_history_{$dateIso}_{$saleNo}.csv";
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
     */
    public function enablePrivilegeForManageCcInfo(bool $hasPrivilegeForManageCcInfo): static
    {
        $this->hasPrivilegeForManageCcInfo = $hasPrivilegeForManageCcInfo;
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
     * @return string
     */
    protected function outputBody(): string
    {
        $output = '';
        $auctionId = $this->getFilterAuctionId();
        $auctionLotGenerator = $this->getAuctionLotLoader()
            ->orderByLotNum(true)
            ->yieldByAuctionId($auctionId);
        foreach ($auctionLotGenerator as $auctionLot) {
            $output .= $this->buildOutputBodyForAuctionLot($auctionLot, $auctionId);
        }
        return $output;
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @param $auctionId
     * @return string
     */
    protected function buildOutputBodyForAuctionLot(AuctionLotItem $auctionLot, $auctionId): string
    {
        if (!$this->validateAuctionLotItem($auctionLot)) {
            return '';
        }
        $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId, true);
        if (!$lotItem) {
            log_error(
                "Available lot item not found, when building bid history report"
                . composeSuffix(['ali' => $auctionLot->Id, 'li' => $auctionLot->LotItemId])
            );
            return '';
        }
        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId, true);
        if (!$auction) {
            log_error(
                "Available auction not found, when building bid history report"
                . composeSuffix(['ali' => $auctionLot->Id])
            );
            return '';
        }

        $lotNoRow = $this->makeLotNumber($auctionLot);
        $lotName = $this->getLotRenderer()->makeName($lotItem->Name, $auction->TestAuction);
        $lotQty = '';
        if ($auctionLot->Quantity !== null) {
            $quantityScale = $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale($auctionLot->LotItemId, $auctionLot->AuctionId);
            $lotQty = $this->getNumberFormatter()->formatNto($auctionLot->Quantity, $quantityScale);
        }
        $lotQtyXmoney = $auctionLot->QuantityXMoney ? 1 : 0;
        $saleNo = $this->getAuctionRenderer()->renderSaleNo($auction);
        $saleTitle = $this->getAuctionRenderer()->renderName($auction);

        $generalBodyRow = array_merge($lotNoRow, [$lotName, $lotQty, $lotQtyXmoney, $saleNo, $saleTitle]);

        $bodyLine = '';
        $bidTransactions = $this->createBidTransactionLoader()->load($lotItem->Id, $auction->Id);
        if (empty($bidTransactions)) {
            $bodyRow = array_merge($generalBodyRow, ['No Bids Placed']);
            $bodyRow = $this->adjustOutputBodyRowWithOutputTitles($bodyRow);
            $bodyLine = $this->makeLine($bodyRow);
        } else {
            foreach ($bidTransactions as $bidTransaction) {
                $bidTransactionUser = $this->getBidTransactionUser($bidTransaction, $auctionLot->Id);
                if (!$bidTransactionUser) {
                    continue;
                }
                $bodyRow = $this->makeBodyRowForBidTransaction($generalBodyRow, $bidTransaction, $bidTransactionUser, $auctionId, $auction);
                $bodyRow = $this->adjustOutputBodyRowWithOutputTitles($bodyRow);
                $bodyLine .= $this->makeLine($bodyRow);
            }
        }

        $output = $this->processOutput($bodyLine);
        return $output;
    }

    /**
     * @param array $generalBodyRow
     * @param BidTransaction $bidTransaction
     * @param User $bidTransactionUser
     * @param int $auctionId
     * @param Auction $auction
     * @return array
     */
    protected function makeBodyRowForBidTransaction(
        array $generalBodyRow,
        BidTransaction $bidTransaction,
        User $bidTransactionUser,
        int $auctionId,
        Auction $auction
    ): array {
        $bidTransactionCreatedOnDate = $this->getDateHelper()->convertUtcToSysByDateIso($bidTransaction->CreatedOn);
        $bidTransactionCreatedOnDateFormatted = $bidTransactionCreatedOnDate
            ? $this->getDateHelper()->formattedDate($bidTransactionCreatedOnDate, null, null, null, Constants\Date::US_DATE_HOUR_MINUTE_SEC)
            : '';
        $userLoader = $this->getUserLoader();
        $bid = $this->getNumberFormatter()->formatMoney($bidTransaction->Bid);
        $maxBid = $bidTransaction->MaxBid;
        $userBilling = $userLoader->loadUserBillingOrCreate($bidTransactionUser->Id, true);
        [
            $bidderNo,
            $username,
            $email,
            $customerNo,
            $isPreferredBidder,
            $dateReg,
            $firstName,
            $lastName,
            $buyersSalesTax,
            $applyTaxTo,
            $note,
            $phone,
            $ccType,
            $ccNumber,
            $ccExpDate,
            $bankRouting,
            $bankAcct,
            $bankAcctType,
            $bankName,
            $bankAcctName
        ] = $this->makeColumnsRelatedWithBidTransactionUser($bidTransaction, $auctionId, $auction, $bidTransactionUser, $userBilling, $userLoader);

        /*-------------------------------------
        * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
        * This might include adding, changing, or moving columns,
        * modifying header names,
        * modifying data or data format(s)
        *-------------------------------------*/

        $billingColumns = $this->makeBillingColumns($userBilling);
        $bodyRow = array_merge(
            $generalBodyRow,
            [
                $bidTransactionCreatedOnDateFormatted,
                $bid,
                $maxBid,
                $bidderNo,
                $username,
                $email,
                $firstName,
                $lastName,
                $phone,
                $customerNo,
                $buyersSalesTax,
                $applyTaxTo,
                $note,
            ],
            $billingColumns
        );

        if ($this->hasPrivilegeForManageCcInfo()) {
            $bodyRow = array_merge($bodyRow, [$ccType, $ccNumber, $ccExpDate]);
            if ($this->isAchPayment()) {
                $bodyRow = array_merge(
                    $bodyRow,
                    [$bankRouting, $bankAcct, $bankAcctType, $bankName, $bankAcctName]
                );
            } else {
                $bodyRow = array_merge($bodyRow, array_fill(0, 5, ''));
            }
        } else {
            $bodyRow = array_merge($bodyRow, array_fill(0, 8, ''));
        }

        $userShipping = $userLoader->loadUserShippingOrCreate($bidTransactionUser->Id, true);
        $shippingColumns = $this->makeShippingColumns($userShipping);
        $bodyRow = array_merge(
            $bodyRow,
            $shippingColumns,
            [
                $dateReg,
                $this->getReportTool()->renderBool($isPreferredBidder),
            ]
        );

        return $bodyRow;
    }

    /**
     * @param BidTransaction $bidTransaction
     * @param int $auctionId
     * @param Auction $auction
     * @param User $bidTransactionUser
     * @param UserBilling $userBilling
     * @param UserLoader $userLoader
     * @return array
     */
    protected function makeColumnsRelatedWithBidTransactionUser(
        BidTransaction $bidTransaction,
        int $auctionId,
        Auction $auction,
        User $bidTransactionUser,
        UserBilling $userBilling,
        UserLoader $userLoader
    ): array {
        $bidderNo = 'floor bidder';
        $auctionBidder = $this->getAuctionBidderLoader()->load($bidTransaction->UserId, $auctionId, true);
        if ($auctionBidder) {
            $bidderNo = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
        }
        $columnsRelatedWithUser = $this->makeColumnsRelatedWithUser($bidTransactionUser, $auction, $userLoader);

        $ccType = $ccNumber = $ccExpDate = '';
        $bankRouting = $bankAcct = $bankAcctType = $bankName = $bankAcctName = '';
        if ($this->hasPrivilegeForManageCcInfo()) {
            [$ccType, $ccNumber, $ccExpDate] = $this->makeCcColumns($userBilling);
            //add these columns if ACH is enabled
            if ($this->isAchPayment()) {
                [$bankRouting, $bankAcct, $bankAcctType, $bankName, $bankAcctName]
                    = $this->makeBankColumns($userBilling);
            }
        }

        $output = array_merge(
            [$bidderNo],
            $columnsRelatedWithUser,
            [$ccType, $ccNumber, $ccExpDate],
            [$bankRouting, $bankAcct, $bankAcctType, $bankName, $bankAcctName]
        );
        return $output;
    }

    /**
     * @param BidTransaction $bidTransaction
     * @param int $auctionLotId
     * @return User|null
     */
    protected function getBidTransactionUser(BidTransaction $bidTransaction, int $auctionLotId): ?User
    {
        if ($bidTransaction->UserId) {
            $user = $this->getUserLoader()->load($bidTransaction->UserId, true);
            if (!$user) {
                log_error(
                    "Available user not found for bid transaction of lot bid history csv report"
                    . composeSuffix(['u' => $bidTransaction->UserId, 'ali' => $auctionLotId])
                );
                return null;
            }
            return $user;
        }
        return null;
    }

    /**
     * @param User $user
     * @param Auction $auction
     * @param UserLoader $userLoader
     * @return array
     */
    protected function makeColumnsRelatedWithUser(User $user, Auction $auction, UserLoader $userLoader): array
    {
        $username = $user->Username;
        $email = $user->Email;
        $customerNo = $user->CustomerNo;
        $isPreferredBidder = BidderPrivilegeChecker::new()
            ->initByUserId($user->Id)
            ->hasPrivilegeForPreferred();
        $dateReg = '';
        if ($user->CreatedOn) {
            $dateReg = DateHelper::new()
                ->formattedDate(new DateTime($user->CreatedOn), $auction->AccountId);
        }
        //get the UserInfo
        $userInfo = $userLoader->loadUserInfoOrCreate($user->Id, true);
        $firstName = $userInfo->FirstName;
        $lastName = $userInfo->LastName;
        $buyersSalesTax = $userInfo->SalesTax;
        $applyTaxTo = UserPureRenderer::new()->makeTaxApplication($userInfo->TaxApplication);
        $note = $userInfo->Note;
        $phone = $userInfo->Phone;

        return [
            $username,
            $email,
            $customerNo,
            $isPreferredBidder,
            $dateReg,
            $firstName,
            $lastName,
            $buyersSalesTax,
            $applyTaxTo,
            $note,
            $phone
        ];
    }

    /**
     * @param UserBilling $userBilling
     * @return array
     */
    protected function makeBillingColumns(UserBilling $userBilling): array
    {
        $billCompany = $userBilling->CompanyName;
        $billFName = $userBilling->FirstName;
        $billLName = $userBilling->LastName;
        $billPhone = $userBilling->Phone;
        $billFax = $userBilling->Fax;
        $billAddress = $userBilling->Address;
        $billAddress2 = $userBilling->Address2;
        $billAddress3 = $userBilling->Address3;
        $billCity = $userBilling->City;
        $billZip = $userBilling->Zip;
        $billCountry = AddressRenderer::new()->countryName($userBilling->Country);
        $billState = AddressRenderer::new()->stateName($userBilling->State, $userBilling->Country);
        return [
            $billCompany,
            $billFName,
            $billLName,
            $billPhone,
            $billFax,
            $billCountry,
            $billAddress,
            $billAddress2,
            $billAddress3,
            $billCity,
            $billState,
            $billZip
        ];
    }

    /**
     * @param UserShipping $userShipping
     * @return array
     */
    protected function makeShippingColumns(UserShipping $userShipping): array
    {
        $shipCompany = $userShipping->CompanyName;
        $shipFName = $userShipping->FirstName;
        $shipLName = $userShipping->LastName;
        $shipPhone = $userShipping->Phone;
        $shipFax = $userShipping->Fax;
        $shipAddress = $userShipping->Address;
        $shipAddress2 = $userShipping->Address2;
        $shipAddress3 = $userShipping->Address3;
        $shipCity = $userShipping->City;
        $shipZip = $userShipping->Zip;
        $shipCountry = AddressRenderer::new()->countryName($userShipping->Country);
        $shipState = AddressRenderer::new()->stateName($userShipping->State, $userShipping->Country);
        return [
            $shipCompany,
            $shipFName,
            $shipLName,
            $shipPhone,
            $shipFax,
            $shipCountry,
            $shipAddress,
            $shipAddress2,
            $shipAddress3,
            $shipCity,
            $shipState,
            $shipZip
        ];
    }

    /**
     * @param UserBilling $userBilling
     * @return array
     */
    protected function makeCcColumns(UserBilling $userBilling): array
    {
        $ccNumber = '';
        if ($userBilling->CcNumber) {
            $ccNumber = $this->createBlockCipherProvider()->construct()->decrypt($userBilling->CcNumber);
        }
        $ccExpDate = $userBilling->CcExpDate;
        $ccType = '';
        $creditCard = $this->getCreditCardLoader()->load($userBilling->CcType);
        if (
            $userBilling->CcType
            && $creditCard
        ) {
            $ccType = $creditCard->Name;
        }
        return [$ccType, $ccNumber, $ccExpDate];
    }

    /**
     * @param UserBilling $userBilling
     * @return array
     */
    protected function makeBankColumns(UserBilling $userBilling): array
    {
        $bankTypes = Constants\BillingBank::ACCOUNT_TYPE_NAMES;
        $bankRouting = $userBilling->BankRoutingNumber;
        $bankAcct = $this->createBlockCipherProvider()->construct()->decrypt($userBilling->BankAccountNumber);
        $bankAcctType = '';
        if (
            $userBilling->BankAccountType
            && isset($bankTypes[$userBilling->BankAccountType])
        ) {
            $bankAcctType = $bankTypes[$userBilling->BankAccountType];
        }
        $bankName = $userBilling->BankName;
        $bankAcctName = $userBilling->BankAccountName;

        return [$bankRouting, $bankAcct, $bankAcctType, $bankName, $bankAcctName];
    }


    /**
     * @param AuctionLotItem $auctionLot
     * @return bool
     */
    protected function validateAuctionLotItem(AuctionLotItem $auctionLot): bool
    {
        $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId, true);
        if (!$lotItem) {
            $logInfo = composeSuffix(['li' => $auctionLot->LotItemId, 'ali' => $auctionLot->Id]);
            log_error("Available lot item not found for lot bid history csv report" . $logInfo);
            return false;
        }
        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId, true);
        if (!$auction) {
            $logInfo = composeSuffix(['a' => $auctionLot->AuctionId, 'ali' => $auctionLot->Id]);
            log_error("Available auction not found for lot bid history csv report" . $logInfo);
            return false;
        }
        return true;
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @return array
     */
    protected function makeLotNumber(AuctionLotItem $auctionLot): array
    {
        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            $lotNo = $this->getLotRenderer()
                ->makeLotNo($auctionLot->LotNum, $auctionLot->LotNumExt, $auctionLot->LotNumPrefix);
            $lotNoRow = [$lotNo];
        } else {
            $lotNoRow = [$auctionLot->LotNumPrefix, $auctionLot->LotNum, $auctionLot->LotNumExt];
        }
        return $lotNoRow;
    }

    /**
     * @return string
     */
    protected function outputTitles(): string
    {
        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            $titlesHeader = ["LotFull#"];
        } else {
            $titlesHeader = [
                "Lot# Prefix",
                "Lot#",
                "Lot# Ext.",
            ];
        }
        $currencySign = $this->getCurrencyLoader()->detectDefaultSign($this->getFilterAuctionId());
        $otherHeaders = [
            "Lot name",
            "Lot Qty",
            "Quantity x Money",
            "Auction#",
            "Auction name",
            "Timestamp",
            "Bid[" . $currencySign . "]",
            "MaxBid[" . $currencySign . "]",
            "Paddle #",                         // Lot Number
            "Username",
            "Email",
            "FirstName",
            "LastName",
            "Phone #",
            "Customer #",                       //basic needed for importable results
            "Buyer's Sales Tax (%)",
            "Apply Tax To",
            "Notes",                            //user info headers
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
            "Billing ZIP",                       //billing headers
            "CC Type",
            "CC Number",
            "CC Exp. Date",
            "Bank Routing #",
            "Bank Acct #",
            "Bank Acct Type",
            "Bank Name",
            "Bank Account Name",                  //billing-ach headers
            "Shipping Company name",
            "Shipping First name",
            "Shipping Last name",
            "Shipping Phone",
            "Shipping Fax",
            "Shipping Country",
            "Shipping Address",
            "Shipping Address Ln 2",
            "Shipping Address Ln 3",
            "Shipping City",
            "Shipping State",
            "Shipping ZIP",                      //shipping headers
            "Date Registered",                   //reg date and time
            "Preferred Bidder"                   //preferred bidder
        ];
        $titlesHeader = array_merge($titlesHeader, $otherHeaders);
        $this->reportReadyTitles = $titlesHeader;
        $headerLine = $this->makeLine($this->reportReadyTitles);

        return $this->processOutput($headerLine);
    }

    /**
     * @param array $bodyRow
     * @return array
     */
    protected function adjustOutputBodyRowWithOutputTitles(array $bodyRow): array
    {
        $adjustedBodyRow = $bodyRow;
        if (count($bodyRow) < count($this->reportReadyTitles)) {
            $amountBodyElements = count($bodyRow);
            $emptyColumns = array_fill($amountBodyElements, count($this->reportReadyTitles) - $amountBodyElements, '');
            $adjustedBodyRow = array_merge($bodyRow, $emptyColumns);
        }
        return $adjustedBodyRow;
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
}
