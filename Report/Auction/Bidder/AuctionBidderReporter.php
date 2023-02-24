<?php
/**
 * SAM-4618: Refactor auction bidder report
 * https://bidpath.atlassian.net/browse/SAM-4618
 *
 * @author        Vahagn Hovsepyan
 * @since         Nov 29, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Report\Auction\Bidder;

use DateTime;
use Sam\Auction\Register\AuctionRegistrationManagerAwareTrait;
use Sam\Billing\CreditCard\Load\CreditCardLoaderAwareTrait;
use Sam\Billing\Gate\Availability\BillingGateAvailabilityCheckerCreateTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Transform\Html\HtmlEntityTransformer;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Calculate\UserDateDetectorAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;
use Sam\User\Privilege\Validate\BidderPrivilegeCheckerAwareTrait;
use Sam\User\Privilege\Validate\RoleCheckerAwareTrait;
use UserCustData;
use UserCustField;

/**
 * Class AuctionBidderReporter
 */
class AuctionBidderReporter extends ReporterBase
{
    use AdminPrivilegeCheckerAwareTrait;
    use AuctionRegistrationManagerAwareTrait;
    use BidderPrivilegeCheckerAwareTrait;
    use BillingGateAvailabilityCheckerCreateTrait;
    use BlockCipherProviderCreateTrait;
    use CreditCardLoaderAwareTrait;
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use DbConnectionTrait;
    use EntityFactoryCreateTrait;
    use FilePathHelperAwareTrait;
    use FilterAuctionAwareTrait;
    use NumberFormatterAwareTrait;
    use RoleCheckerAwareTrait;
    use UserDateDetectorAwareTrait;

    protected ?DataLoader $dataLoader = null;
    protected ?bool $hasPrivilegeForManageCcInfo = null;
    protected ?bool $isAchPayment = null;
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
    public function construct(int $editorUserId, int $systemAccountId): AuctionBidderReporter
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
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $dateIso = $this->getCurrentDateUtc()->format('m-d-Y');
            $filename = "auction-bidders-{$dateIso}.csv";
            $filename = $this->getFilePathHelper()->toFilename($filename);
            $this->setOutputFileName($filename);
        }
        return $this->outputFileName;
    }

    /**
     * @override
     * Overwrite method default value by auction's account
     * @return int
     */
    public function getSystemAccountId(): int
    {
        if ($this->systemAccountId === null) {
            $this->systemAccountId = $this->getFilterAuction()->AccountId;
        }
        return $this->systemAccountId;
    }

    /**
     * @return DataLoader
     */
    public function getDataLoader(): DataLoader
    {
        if ($this->dataLoader === null) {
            $this->dataLoader = DataLoader::new()
                ->filterAuctionId($this->getFilterAuctionId());
        }
        return $this->dataLoader;
    }

    /**
     * @return string
     */
    protected function outputBody(): string
    {
        $startTs = microtime(true);
        $output = '';
        $userCustomFields = $this->getDataLoader()->loadUserCustomFields();
        $userCustomFieldIds = [];
        foreach ($userCustomFields as $userCustomField) {
            $userCustomFieldIds[] = $userCustomField->Id;
        }

        $total = $this->getDataLoader()->countTotal();
        if ($total) {
            $processed = 0;
            $iterationStartTs = microtime(true);
            $auctionBidderRepository = $this->getDataLoader()->prepareRepository();
            while ($rows = $auctionBidderRepository->loadRows()) {
                $userIds = ArrayCast::arrayColumnInt($rows, 'user_id');

                $users = $this->getDataLoader()->loadUsersByIds($userIds);
                $lastUsersBidDates = $this->getDataLoader()->loadAllLastUsersBidDates($userIds);
                $lastUsersWinDates = $this->getDataLoader()->loadAllLastUsersWinDates($userIds);
                $lastUsersPaymentDates = $this->getDataLoader()->loadAllLastUserPaymentDates($userIds);
                $dateHelper = $this->getDateHelper();

                foreach ($rows as $row) {
                    $bidderNum = '';
                    if ($row['bidder_num']) {
                        $bidderNum = $row['bidder_num'];
                    }

                    $user = $users[(int)$row['user_id']] ?? null;

                    if ($user) {
                        $username = $user->Username;
                        $email = $user->Email;

                        $customerNo = $user->CustomerNo;
                        $usePermanentBidderno = $user->UsePermanentBidderno;

                        $registerDateFormatted = '';
                        if ($row['registered_on']) {
                            $registerDate = $dateHelper->convertUtcToSysByDateIso($row['registered_on']);
                            $registerDateFormatted = $dateHelper->formattedDate($registerDate);
                        }

                        $regDateSysFormatted = '';
                        $lastInvoiceDateSysFormatted = '';
                        $lastBidDateSysFormatted = '';
                        $lastWinDateSysFormatted = '';
                        $lastPaymentDateSysFormatted = '';
                        //get last bid date
                        $lastBidDateUtcIso = $lastUsersBidDates[$user->Id] ?? '';
                        //get last winning date
                        $lastWinDateUtcIso = $lastUsersWinDates[$user->Id] ?? '';

                        //get last invoice date
                        $lastInvoiceDateSys = $this->getUserDateDetector()->detectLastInvoiceDateSys($user->Id);
                        //get last invoice or settlement payment date 2016-11-07 19:24:19
                        $lastPaymentDateUtcIso = $lastUsersPaymentDates[$user->Id] ?? '';

                        //get last login date
                        $lastLoginDateSysFormatted = '';

                        $isBidder = $this->getRoleChecker()->isBidder($user->Id, true);
                        $isPreferredBidder = $this->getBidderPrivilegeChecker()
                            ->initByUserId($user->Id)
                            ->hasPrivilegeForPreferred();

                        if ($user->CreatedOn) {
                            $regDateSys = $dateHelper->convertUtcToSysByDateIso($user->CreatedOn);
                            $regDateSysFormatted = $dateHelper->formattedDate($regDateSys);
                        }
                        if ($lastBidDateUtcIso) {
                            $lastBidDateSys = $this->getDateHelper()->convertUtcToSysByDateIso($lastBidDateUtcIso);
                            $lastBidDateSysFormatted = $dateHelper->formattedDate($lastBidDateSys);
                        }
                        if ($lastWinDateUtcIso) {
                            $lastWinDateSys = $this->getDateHelper()->convertUtcToSysByDateIso($lastWinDateUtcIso);
                            $lastWinDateSysFormatted = $dateHelper->formattedDate($lastWinDateSys);
                        }
                        if ($lastInvoiceDateSys) {
                            $lastInvoiceDateSysFormatted = $dateHelper->formattedDate($lastInvoiceDateSys);
                        }
                        if ($lastPaymentDateUtcIso) {
                            $lastPaymentDateSys = $this->getDateHelper()->convertUtcToSysByDateIso($lastPaymentDateUtcIso);
                            $lastPaymentDateSysFormatted = $dateHelper->formattedDate($lastPaymentDateSys);
                        }
                        if ($user->LogDate) {
                            $loginDateSys = $dateHelper->convertUtcToSys($user->LogDate);
                            $lastLoginDateSysFormatted = $dateHelper->formattedDate($loginDateSys);
                        }

                        $ccType = $ccNumber = $ccExpDate = $bankRouting = $bankAccountNumber = $bankAccountType
                            = $bankName = $bankAccountName = '';

                        $companyName = $row['company_name'];
                        $firstName = $row['first_name'];
                        $lastName = $row['last_name'];
                        $buyersSalesTax = $row['sales_tax'];
                        $applyTaxTo = UserPureRenderer::new()->makeTaxApplication((int)$row['tax_application']);
                        $note = HtmlEntityTransformer::new()->fromHtmlEntity(trim((string)$row['note']));
                        $phone = $row['phone'];
                        $isNewsLetter = (bool)$row['news_letter'];
                        $referrer = $row['referrer'];
                        $referrerHost = $row['referrer_host'];

                        // Billing info
                        $billingContactType = Constants\User::CONTACT_TYPE_ENUM[(int)$row['bill_contact_type']] ?? '';
                        $billingCompany = $row['bill_company_name'];
                        $billingFirstName = $row['bill_first_name'];
                        $billingLastName = $row['bill_last_name'];
                        $billingPhone = $row['bill_phone'];
                        $billingFax = $row['bill_fax'];
                        $billingCountry = AddressRenderer::new()->countryName((string)$row['bill_country']);
                        $billingAddress = $row['bill_address'];
                        $billingAddress2 = $row['bill_address2'];
                        $billingAddress3 = $row['bill_address3'];
                        $billingCity = $row['bill_city'];
                        $billingState = AddressRenderer::new()->stateName((string)$row['bill_state'], (string)$row['bill_country']);
                        $billingZip = $row['bill_zip'];

                        if ($this->hasPrivilegeForManageCcInfo()) {
                            $creditCard = $this->getCreditCardLoader()->load((int)$row['cc_type']);
                            if ($row['cc_type'] && $creditCard) {
                                $ccType = $creditCard->Name;
                            }
                            if ($row['cc_number']) {
                                $ccNumber = $this->createBlockCipherProvider()->construct()->decrypt($row['cc_number']);
                            }
                            $ccExpDate = $row['cc_exp_date'];

                            //add these columns if ACH is enabled
                            if ($this->isAchPayment()) {
                                $bankRouting = $row['bank_routing_number'];
                                $bankAccountNumber = $this->createBlockCipherProvider()->construct()->decrypt($row['bank_account_number']);
                                $bankAccountType = UserPureRenderer::new()->makeBankAccountType($row['bank_account_type']);
                                $bankName = $row['bank_name'];
                                $bankAccountName = $row['bank_account_name'];
                            }
                        }

                        $isCcNumber = (string)$row['cc_number'] !== ''
                            && (string)$row['cc_exp_date'] !== '';

                        // Shipping info
                        $shippingContactType = Constants\User::CONTACT_TYPE_ENUM[(int)$row['ship_contact_type']] ?? '';
                        $shippingCompany = $row['ship_company_name'];
                        $shippingFirstName = $row['ship_first_name'];
                        $shippingLastName = $row['ship_last_name'];
                        $shippingPhone = $row['ship_phone'];
                        $shippingFax = $row['ship_fax'];
                        $shippingCountry = AddressRenderer::new()->countryName((string)$row['ship_country']);
                        $shippingAddress = $row['ship_address'];
                        $shippingAddress2 = $row['ship_address2'];
                        $shippingAddress3 = $row['ship_address3'];
                        $shippingCity = $row['ship_city'];
                        $shippingState = AddressRenderer::new()->stateName((string)$row['ship_state'], (string)$row['ship_country']);
                        $shippingZip = $row['ship_zip'];

                        //get Auction Bidder Options Selection

                        $auctionRegistrationManager = $this->getAuctionRegistrationManager();
                        $auctionRegistrationManager->construct((int)$row['user_id'], (int)$row['auction_id'], $this->editorUserId);
                        $biddersOptions = $auctionRegistrationManager->getBiddersOptions();
                        $bidderOptionList = '';
                        foreach ($biddersOptions as [$name, $option]) {
                            $bidderOptionList .= $name . ':' . ' ' . $option . ' |';
                        }

                        $bodyRow = [
                            $bidderNum,
                            $username,
                            $companyName,
                            $email,
                            $firstName,
                            $lastName,
                            $phone,
                            $customerNo,
                            $this->getNumberFormatter()->formatPercent($buyersSalesTax),
                            $applyTaxTo,
                            $note,
                            $billingContactType,
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
                            $bankRow = $this->isAchPayment()
                                ? [
                                    $bankRouting,
                                    $bankAccountNumber,
                                    $bankAccountType,
                                    $bankName,
                                    $bankAccountName,
                                ]
                                : array_fill(0, 5, '');
                            $bodyRow = array_merge($bodyRow, $bankRow);
                        } else {
                            $bodyRow = array_merge($bodyRow, array_fill(0, 8, ''));
                        }

                        $bodyRow = array_merge(
                            $bodyRow,
                            [
                                $shippingContactType,
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
                                $registerDateFormatted,
                                $regDateSysFormatted,
                                $lastBidDateSysFormatted,
                                $lastWinDateSysFormatted,
                                $lastInvoiceDateSysFormatted,
                                $lastPaymentDateSysFormatted,
                                $lastLoginDateSysFormatted,
                                $this->getReportTool()->renderBool($isCcNumber),
                                $this->getReportTool()->renderBool($isBidder),
                                $this->getReportTool()->renderBool($isPreferredBidder),
                                $this->getReportTool()->renderBool($isNewsLetter),
                                $this->getReportTool()->renderBool($usePermanentBidderno),
                                $referrer,
                                $referrerHost,
                            ]
                        );

                        $userCustomDatas = $this->getDataLoader()->loadUserCustomData($user->Id, $userCustomFieldIds);
                        //write out the custom fields values
                        $userCustomDataValues = [];
                        foreach ($userCustomFields as $userCustomField) {
                            $userCustomData = $this->createUserCustomDataEntity($userCustomDatas, $userCustomField, $user->Id);
                            $userCustomDataValue = $this->renderUserCustomFieldValue($userCustomField, $userCustomData);
                            $userCustomDataValues[] = $userCustomDataValue;
                        }
                        $bodyRow = array_merge($bodyRow, $userCustomDataValues);

                        $addedByUsername = $row['addedby_username'];
                        $bodyRow = array_merge($bodyRow, [$addedByUsername, $bidderOptionList]);

                        $bodyLine = $this->makeLine($bodyRow);
                        $output .= $this->processOutput($bodyLine);
                    }
                }
                $processed += count($rows);
                $iterationElapsedTime = round(microtime(true) - $iterationStartTs, 2);
                log_debug(
                    "Auction bidder CSV export processed {$processed} of {$total} records, "
                    . "elapsed {$iterationElapsedTime} sec"
                );
                $iterationStartTs = microtime(true);
            }
        }

        $elapsedTime = round(microtime(true) - $startTs, 2);
        log_info(
            "Auction bidder CSV export generating completed"
            . composeSuffix(
                [
                    'elapsed time' => "{$elapsedTime} sec",
                    'auth.u' => $this->editorUserId,
                ]
            )
        );
        return $output;
    }

    /**
     * @return string
     */
    protected function outputTitles(): string
    {
        //column headers  basic needed for importable results
        $headerTitles = [
            "Bidder #",
            "Username",
            "CompanyName",
            "Email",
            "FirstName",
            "LastName",
            "Phone #",
            "Customer #",
            //user info headers
            "Buyer's Sales Tax (%)",
            "Apply Tax To",
            "Notes",
            //billing headers
            "Billing Contact type",
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
            //billing-ach headers
            "Bank Routing #",
            "Bank Acct #",
            "Bank Acct Type",
            "Bank Name",
            "Bank Account Name",
            //shipping headers
            "Shipping Contact type",
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
            "Shipping ZIP",
            //reg date and time
            "Auction Registration Date",
            "Registration Date",
            "Last Bid Date",
            "Last Win Date",
            "Last Invoice Date",
            "Last Payment Date",
            "Last Login Date",
            "Credit Card?",
            "Bidder?",
            //preferred bidder
            "Preferred Bidder?",
            "Newsletter?",
            "Make permanent bidder number?",
            "Referrer",
            "Referrer Host",
        ];
        // custom fields headers
        $userCustomFields = $this->getDataLoader()->loadUserCustomFields();

        foreach ($userCustomFields ?: [] as $userCustomField) {
            $headerTitles[] = $userCustomField->Name;
        }
        //user added by
        array_push($headerTitles, "User added by", "Bidder Option Selection");

        $headerLine = $this->makeLine($headerTitles);

        return $this->processOutput($headerLine);
    }

    /**
     * @param array $rows
     * @param UserCustField $userCustomField
     * @param int $userId
     * @return UserCustData
     */
    protected function createUserCustomDataEntity(array $rows, UserCustField $userCustomField, int $userId): UserCustData
    {
        $userCustomData = $this->createEntityFactory()->userCustData();
        if (!isset($rows[$userCustomField->Id])) {
            $userCustomData->UserId = $userId;
            $userCustomData->UserCustFieldId = $userCustomField->Id;
            $userCustomData->Active = true;
            $parameters = $userCustomField->Parameters;
            if (in_array(
                $userCustomField->Type,
                [Constants\CustomField::TYPE_INTEGER, Constants\CustomField::TYPE_CHECKBOX],
                true
            )
            ) {
                if (ctype_digit($parameters)) {
                    $userCustomData->Numeric = (int)$parameters;
                }
            } elseif (in_array(
                $userCustomField->Type,
                [
                    Constants\CustomField::TYPE_TEXT,
                    Constants\CustomField::TYPE_FULLTEXT,
                    Constants\CustomField::TYPE_PASSWORD,
                ],
                true
            )
            ) {
                $userCustomData->Text = $parameters;
            }
        } else {
            $userCustomData->UserId = $rows[$userCustomField->Id]->UserId;
            $userCustomData->UserCustFieldId = $rows[$userCustomField->Id]->UserCustFieldId;
            $userCustomData->Numeric = $rows[$userCustomField->Id]->Numeric;
            $userCustomData->Text = $rows[$userCustomField->Id]->Text;
            $userCustomData->Active = $rows[$userCustomField->Id]->Active;
            $userCustomData->Encrypted = $rows[$userCustomField->Id]->Encrypted;
            if ($userCustomData->Encrypted) {
                $userCustomData->Text = $this->createBlockCipherProvider()->construct()->decrypt($userCustomData->Text);
            }
        }
        return $userCustomData;
    }

    /**
     * @param UserCustField $userCustomField
     * @param UserCustData $userCustomData
     * @return string
     */
    protected function renderUserCustomFieldValue(UserCustField $userCustomField, UserCustData $userCustomData): string
    {
        $output = '';
        switch ($userCustomField->Type) {
            case Constants\CustomField::TYPE_INTEGER:
            case Constants\CustomField::TYPE_CHECKBOX:
                if ($userCustomData->Numeric !== null) {
                    $output = $this->getNumberFormatter()->formatInteger($userCustomData->Numeric);
                }
                break;
            case Constants\CustomField::TYPE_DECIMAL:
                if ($userCustomData->Numeric !== null) {
                    $precision = (int)$userCustomField->Parameters;
                    $realValue = $userCustomData->calcDecimalValue($precision);
                    $output = $this->getNumberFormatter()->format($realValue, $precision);
                }
                break;
            case Constants\CustomField::TYPE_TEXT:
            case Constants\CustomField::TYPE_SELECT:
            case Constants\CustomField::TYPE_FULLTEXT:
            case Constants\CustomField::TYPE_PASSWORD:
            case Constants\CustomField::TYPE_LABEL:
                $output = $userCustomData->Text;
                break;
            case Constants\CustomField::TYPE_DATE:
                if ($userCustomData->Numeric !== null) {
                    $output = (new DateTime())
                        ->setTimestamp($userCustomData->Numeric)
                        ->format(Constants\Date::ISO);
                }
                break;
        }
        return $output;
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
