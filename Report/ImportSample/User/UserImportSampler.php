<?php

/**
 * SAM-4647: Refactor csv import sample builders
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/23/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\ImportSample\User;

use Sam\Auction\AuctionHelperAwareTrait;
use Sam\Core\Constants;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\CustomField\Base\Csv\CustomFieldCsvHelperCreateTrait;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\CustomField\User\Load\UserCustomFieldLoaderAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Report\ImportSample\ImportSamplerBase;
use UserCustField;

/**
 * Class UserImportSampler
 * @package Sam\Report\ImportSample\User
 */
class UserImportSampler extends ImportSamplerBase
{
    use AuctionHelperAwareTrait;
    use BaseCustomFieldHelperAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CustomFieldCsvHelperCreateTrait;
    use UserCustomFieldLoaderAwareTrait;

    protected int $bodyRowCount = 9;
    /** @var UserCustField[] */
    protected ?array $userCustomFields = null;
    protected ?string $outputFileName = 'user.csv';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        // TODO: use these constants, when user csv import will be adjusted
        // $yes = Constants\Csv\Common::TRUE;
        // $no = Constants\Csv\Common::FALSE;
        $yes = 'Y';
        $no = 'N';
        $userPureRenderer = UserPureRenderer::new();
        $ptWork = $userPureRenderer->makePhoneType(Constants\User::PT_WORK);
        $ptHome = $userPureRenderer->makePhoneType(Constants\User::PT_HOME);
        $ptMobile = $userPureRenderer->makePhoneType(Constants\User::PT_MOBILE);
        $rcSliding = Constants\BuyersPremium::RANGE_CALC_SLIDING;
        $rcTiered = Constants\BuyersPremium::RANGE_CALC_CUMULATIVE_TIERED;
        $taxHpInclusive = Constants\Csv\User::TAX_HP_INCLUSIVE;
        $frZero = Constants\ConsignorCommissionFee::FEE_REFERENCE_ZERO;
        $frHammerPrice = Constants\ConsignorCommissionFee::FEE_REFERENCE_HAMMER_PRICE;
        $frCurrentBid = Constants\ConsignorCommissionFee::FEE_REFERENCE_CURRENT_BID;

        // formatter:off
        /**
         * When array values - render each value per csv line, if not enough values in array, we render empty cell.
         * When single value - render the same value for each row.
         */
        $this->sampleValues = [
            Constants\Csv\User::USERNAME => ['bidder1', 'bidder2', 'bidder3', 'consignor1', 'consignor2', 'consignor3', 'staff1', 'staff2', 'staff3'],
            Constants\Csv\User::COMPANY_NAME => ['company1', 'company2', 'company3', 'company1', 'company2', 'company3', 'company1', 'company2', 'company3'],
            Constants\Csv\User::PASSWORD => 'password',
            Constants\Csv\User::EMAIL => [
                'bidder1@email.com',
                'bidder2@email.com',
                'bidder3@email.com',
                'consignor1@email.com',
                'consignor2@email.com',
                'consignor3@email.com',
                'staff1@email.com',
                'staff2@email.com',
                'staff3@email.com'
            ],
            Constants\Csv\User::FIRST_NAME => ['bidder1', 'bidder2', 'bidder3', 'consignor1', 'consignor2', 'consignor3', 'staff1', 'staff2', 'staff3'],
            Constants\Csv\User::LAST_NAME => ['one', 'two', 'three', 'one', 'two', 'three', 'one', 'two', 'three'],
            Constants\Csv\User::PHONE => '+1 201-555-5555',
            Constants\Csv\User::PHONE_TYPE => [$ptHome, $ptHome, $ptMobile, $ptMobile, $ptMobile, $ptWork, $ptWork, $ptWork, $ptWork],
            Constants\Csv\User::TEXT_ALERT => ['', '', $no, $yes, $no],
            Constants\Csv\User::CUSTOMER_NO => ['10001', '10002', '10003', '10004', '10005', '10006'],
            Constants\Csv\User::IDENTIFICATION_TYPE => UserPureRenderer::new()->makeIdentificationType(Constants\User::IDT_DRIVERSLICENSE),
            Constants\Csv\User::BUYER_SALES_TAX => [1, 2, 3, 4, 5, 6, 7, 8, 9],
            Constants\Csv\User::LOCATION => ['Location 1', 'Location 2', 'Location 3', 'Location 4', ''],
            Constants\Csv\User::LOCATION_ADDRESS => ['', '', '', '', 'Townsend St.'],
            Constants\Csv\User::LOCATION_COUNTRY => ['', '', '', '', 'US'],
            Constants\Csv\User::LOCATION_CITY => ['', '', '', '', 'San Francisco'],
            Constants\Csv\User::LOCATION_COUNTY => ['', '', '', '', 'San Francisco'],
            Constants\Csv\User::LOCATION_LOGO => ['', '', '', '', '1.png'],
            Constants\Csv\User::LOCATION_STATE => ['', '', '', '', 'CA'],
            Constants\Csv\User::LOCATION_ZIP => ['', '', '', '', '94107'],
            Constants\Csv\User::BILLING_CONTACT_TYPE => Constants\User::CONTACT_TYPE_ENUM[Constants\User::CT_WORK],
            Constants\Csv\User::BILLING_COMPANY_NAME => 'company',
            Constants\Csv\User::BILLING_FIRST_NAME => ['bidder1', 'bidder2', 'bidder3', 'consignor1', 'consignor2', 'consignor3', 'staff1', 'staff2', 'staff3'],
            Constants\Csv\User::BILLING_LAST_NAME => ['one', 'two', 'three', 'one', 'two', 'three', 'one', 'two', 'three'],
            Constants\Csv\User::BILLING_PHONE => '+1 201-555-5555',
            Constants\Csv\User::BILLING_FAX => '+1 201-555-5555',
            Constants\Csv\User::BILLING_COUNTRY => "United States",
            Constants\Csv\User::BILLING_ADDRESS => 'Townsend St.',
            Constants\Csv\User::BILLING_ADDRESS_2 => '',
            Constants\Csv\User::BILLING_ADDRESS_3 => '',
            Constants\Csv\User::BILLING_CITY => 'San Francisco',
            Constants\Csv\User::BILLING_STATE => 'CA',
            Constants\Csv\User::BILLING_ZIP => '94107',
            Constants\Csv\User::CC_TYPE => ['Visa', 'Amex'],
            Constants\Csv\User::CC_NUMBER => ['4012888888881881', '378282246310005'],
            Constants\Csv\User::CC_EXP_DATE => ['01-' . (date('Y') + 1), '02-' . (date('Y') + 2)],
            Constants\Csv\User::BANK_ROUTING_NO => '',
            Constants\Csv\User::BANK_ACCOUNT_NO => '',
            Constants\Csv\User::BANK_ACCOUNT_TYPE => '',
            Constants\Csv\User::BANK_NAME => '',
            Constants\Csv\User::BANK_ACCOUNT_NAME,
            Constants\Csv\User::SHIPPING_CONTACT_TYPE => Constants\User::CONTACT_TYPE_ENUM[Constants\User::CT_HOME],
            Constants\Csv\User::SHIPPING_COMPANY_NAME => 'company',
            Constants\Csv\User::SHIPPING_FIRST_NAME => ['bidder1', 'bidder2', 'bidder3', 'consignor1', 'consignor2', 'consignor3', 'staff1', 'staff2', 'staff3'],
            Constants\Csv\User::SHIPPING_LAST_NAME => ['one', 'two', 'three', 'one', 'two', 'three', 'one', 'two', 'three'],
            Constants\Csv\User::SHIPPING_PHONE => '+1 201-555-5555',
            Constants\Csv\User::SHIPPING_FAX => '+1 201-555-5555',
            Constants\Csv\User::SHIPPING_COUNTRY => "United States",
            Constants\Csv\User::SHIPPING_ADDRESS => 'Townsend St.',
            Constants\Csv\User::SHIPPING_ADDRESS_2 => '',
            Constants\Csv\User::SHIPPING_ADDRESS_3 => '',
            Constants\Csv\User::SHIPPING_CITY => 'San Francisco',
            Constants\Csv\User::SHIPPING_STATE => 'CA',
            Constants\Csv\User::SHIPPING_ZIP => '94107',
            Constants\Csv\User::HAS_CREDIT_CARD => [$yes, $yes, $no, $no, $no, $no, $no, $no, $no],
            Constants\Csv\User::IS_BIDDER => [$yes, $yes, $yes, $no, $no, $no, $no, $no, $no],
            Constants\Csv\User::HOUSE_BIDDER => [$yes, $no, $no, $no, $no, $no, $no, $no, $no],
            Constants\Csv\User::IS_PREFERRED_BIDDER => [$yes, $no, $no, $no, $no, $no, $no, $no, $no],
            Constants\Csv\User::FLAG => UserPureRenderer::new()->makeFlagCsv(Constants\User::FLAG_NONE),
            Constants\Csv\User::AGENT => [$yes, $no, $no, $no, $no, $no, $no, $no, $no],
            Constants\Csv\User::NEWSLETTER => [$yes, $no, $no, $no, $no, $no, $no, $no, $no],
            Constants\Csv\User::MAKE_PERMANENT_BIDDER_NO => [$yes, $no, $no, $no, $no, $no, $no, $no, $no],
            Constants\Csv\User::REFERRER => 'http://www.google.com/',
            // admin privileges
            Constants\Csv\User::IS_ADMIN => ['', $yes, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::MANAGE_AUCTIONS => ['', $yes, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::MANAGE_INVENTORY => ['', $yes, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::MANAGE_USERS => ['', $yes, $no, '', '', '', $no, $no, $no],
            Constants\Csv\User::MANAGE_INVOICES => ['', $yes, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::MANAGE_SETTLEMENTS => ['', $yes, $no, '', '', '', $no, $no, $no],
            Constants\Csv\User::MANAGE_SETTINGS => ['', $yes, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::MANAGE_CC_INFO => ['', $yes, $no, '', '', '', $no, $no, $no],
            Constants\Csv\User::SALES_STAFF => ['', $yes, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::REPORTS => ['', $yes, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::SUPERADMIN => ['', $no, $no, '', '', '', $no, $no, $no],
            Constants\Csv\User::SALES_COMMISSION_STEPDOWN => ['', $no, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::SALES_CONSIGNMENT_COMMISSION => ['', '', '1|1:2', '', '', '', '1|1:2', '1|1:2', '1|1:2'],
            Constants\Csv\User::MANAGE_ALL_AUCTIONS => ['', $no, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::DELETE_AUCTION => ['', $no, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::ARCHIVE_AUCTION => ['', $no, $no, '', '', '', $no, $no, $no],
            Constants\Csv\User::RESET_AUCTION => ['', $no, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::INFORMATION => ['', $no, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::PUBLISH => ['', $no, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::LOTS => ['', $no, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::AVAILABLE_LOTS => ['', $no, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::BIDDERS => ['', $no, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::REMAINING_USERS => ['', $no, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::RUN_LIVE_AUCTION => ['', $no, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::ASSISTANT_CLERK => ['', $no, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::PROJECTOR => ['', $no, $no, '', '', '', $no, $no, $no],
            Constants\Csv\User::BID_INCREMENTS => ['', $no, $no, '', '', '', $no, $no, $no],
            Constants\Csv\User::BUYERS_PREMIUM => ['', $no, $no, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::PERMISSIONS => ['', $no, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::CREATE_BIDDER => ['', $no, $yes, '', '', '', $yes, $yes, $yes],
            Constants\Csv\User::USER_PASSWORDS => $no,
            Constants\Csv\User::USER_PRIVILEGES => $no,
            Constants\Csv\User::DELETE_USER => $no,
            Constants\Csv\User::USER_BULK_EXPORT => $no,
            // consignor privileges
            Constants\Csv\User::CONSIGNOR_PRIVILEGES => [$no, $no, $yes, $yes, $yes, $yes, '', '', ''],
            Constants\Csv\User::CONSIGNOR_BUYER_TAX_PERCENTAGE => ['', '', 5, '', '', '', 5, 5, ''],
            Constants\Csv\User::CONSIGNOR_BUYER_TAX_HP => ['', '', 1, '', '', '', 1, 1, ''],
            Constants\Csv\User::CONSIGNOR_BUYER_TAX_BP => ['', '', 0, '', '', '', 0, 0, ''],
            Constants\Csv\User::CONSIGNOR_BUYER_TAX_SERVICES => ['', '', 0, '', '', '', 0, 0, ''],
            Constants\Csv\User::CONSIGNOR_TAX_PERCENTAGE => ['', '', 2, '', '', '', 2, 2, ''],
            Constants\Csv\User::CONSIGNOR_TAX_HP => ['', '', 1, '', '', '', 1, 1, ''],
            Constants\Csv\User::CONSIGNOR_TAX_HP_INCLUSIVE_OR_EXCLUSIVE => ['', '', $taxHpInclusive, '', '', '', $taxHpInclusive, $taxHpInclusive, ''],
            Constants\Csv\User::CONSIGNOR_TAX_COMMISSION => ['', '', 0, '', '', '', 0, 0, ''],
            Constants\Csv\User::CONSIGNOR_TAX_SERVICES => ['', '', 0, '', '', '', 0, 0, ''],
            Constants\Csv\User::PAYMENT_INFO => '',
            // buyer's premium live:
            Constants\Csv\User::BP_RANGES_LIVE => '2-0->|1000:5-2->|5000:0-7->',
            Constants\Csv\User::ADDITIONAL_BP_PERCENTAGE_FOR_INTERNET_BUYERS_LIVE => '2',
            Constants\Csv\User::BP_CALCULATION_LIVE => [$rcSliding, $rcTiered, $rcSliding, $rcTiered, $rcSliding, $rcTiered, $rcSliding, $rcTiered, $rcSliding],
            // buyer's premium timed
            Constants\Csv\User::BP_RANGES_TIMED => '30-0->|500:70-10->|1000:120-5->|2000:220-0->',
            Constants\Csv\User::BP_CALCULATION_TIMED => [$rcSliding, $rcTiered, $rcSliding, $rcTiered, $rcSliding, $rcTiered, $rcSliding, $rcTiered, $rcSliding],
            // consignor commission & fee
            Constants\Csv\User::CONSIGNOR_COMMISSION_RANGES => ['', '', '0:30-0->|500:70-10->', '0:30-0->|500:70-10->', '0:30-0->|500:70-10->', '0:30-0->|500:70-10->'],
            Constants\Csv\User::CONSIGNOR_COMMISSION_CALCULATION_METHOD => ['', '', $rcSliding, $rcTiered, $rcSliding, $rcTiered],
            Constants\Csv\User::CONSIGNOR_SOLD_FEE_RANGES => ['', '', '0:30-0->|500:70-10->', '0:30-0->|500:70-10->', '0:30-0->|500:70-10->', '0:30-0->|500:70-10->'],
            Constants\Csv\User::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD => ['', '', $rcSliding, $rcTiered, $rcSliding, $rcTiered],
            Constants\Csv\User::CONSIGNOR_SOLD_FEE_REFERENCE => ['', '', $frZero, $frCurrentBid, $frHammerPrice, $frZero],
            Constants\Csv\User::CONSIGNOR_UNSOLD_FEE_RANGES => ['', '', '0:30-0->|500:70-10->', '0:30-0->|500:70-10->', '0:30-0->|500:70-10->', '0:30-0->|500:70-10->'],
            Constants\Csv\User::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD => ['', '', $rcSliding, $rcTiered, $rcSliding, $rcTiered],
            Constants\Csv\User::CONSIGNOR_UNSOLD_FEE_REFERENCE => ['', '', $frZero, $frCurrentBid, $frCurrentBid, $frZero]
        ];
        if ($this->getAuctionHelper()->isHybridAuctionAvailable($this->getSystemAccountId())) {
            // buyer's premium hybrid
            $this->sampleValues += [
                Constants\Csv\User::BP_RANGES_HYBRID => '2-0->|1000:5-2->|5000:0-7->',
                Constants\Csv\User::ADDITIONAL_BP_PERCENTAGE_FOR_INTERNET_BUYERS_HYBRID => '2',
                Constants\Csv\User::BP_CALCULATION_HYBRID => [$rcSliding, $rcTiered, $rcSliding, $rcTiered, $rcSliding, $rcTiered, $rcSliding, $rcTiered, $rcSliding],
            ];
        }
        // formatter:on
        return $this;
    }

    /**
     * @param string[] $titles
     * @return static
     */
    public function setTitles(array $titles): static
    {
        // Downloadable, but not uploadable or modifiable columns
        $skipDownloadableTitles = [
            Constants\Csv\User::REGISTRATION_DATE,
            Constants\Csv\User::LAST_BID_DATE,
            Constants\Csv\User::LAST_WIN_DATE,
            Constants\Csv\User::LAST_INVOICE_DATE,
            Constants\Csv\User::LAST_PAYMENT_DATE,
            Constants\Csv\User::LAST_LOGIN_DATE,
        ];
        $titles = array_diff_key($titles, array_flip($skipDownloadableTitles));
        if (!$this->getAuctionHelper()->isHybridAuctionAvailable($this->getSystemAccountId())) {
            $skipHybridTitles = [
                Constants\Csv\User::BP_RANGES_HYBRID,
                Constants\Csv\User::ADDITIONAL_BP_PERCENTAGE_FOR_INTERNET_BUYERS_HYBRID,
                Constants\Csv\User::BP_CALCULATION_HYBRID,
            ];
            $titles = array_diff_key($titles, array_flip($skipHybridTitles));
        }
        parent::setTitles($titles);
        return $this;
    }

    /**
     * @return string
     */
    public function output(): string
    {
        $this->setTitles($this->cfg()->get('csv->admin->user')->toArray());
        $this->sendHttpHeader();
        $this->outputContent();
        return '';
    }

    /**
     * @return UserCustField[]
     */
    public function getUserCustomFields(): array
    {
        if ($this->userCustomFields === null) {
            $this->userCustomFields = $this->getUserCustomFieldLoader()->loadAllEditable([], true);
        }
        return $this->userCustomFields;
    }

    /**
     * @param UserCustField[] $userCustomFields
     * @return static
     */
    public function setUserCustomFields(array $userCustomFields): static
    {
        $this->userCustomFields = $userCustomFields;
        return $this;
    }

    /**
     * @return void
     */
    protected function outputContent(): void
    {
        echo $this->produceContent();
    }

    /**
     * @return string
     */
    protected function produceContent(): string
    {
        $titles = $this->getTitles();
        [$customFieldTitles, $customFieldValues] = $this->produceCustomFieldCsv();
        $allHeaderTitles = array_merge($titles, $customFieldTitles);
        $headerLine = $this->getReportTool()->makeLine($allHeaderTitles, $this->getEncoding());
        $contentRows[] = $headerLine;

        for ($i = 0; $i < $this->bodyRowCount; $i++) {
            $bodyRow = [];
            foreach (array_keys($titles) as $title) {
                $bodyRow[] = $this->produceValue($title, $i);
            }
            $bodyRow = array_merge($bodyRow, $customFieldValues);
            $bodyLine = $this->getReportTool()->makeLine($bodyRow, $this->getEncoding());
            $contentRows[] = $bodyLine;
        }

        $contentCsv = implode('', $contentRows);
        return $contentCsv;
    }

    /**
     * @return array
     */
    protected function produceCustomFieldCsv(): array
    {
        $userCustomFields = $this->getUserCustomFields();
        $customFieldValues = [];
        $customFieldTitles = [];
        $customFieldCsvHelper = $this->createCustomFieldCsvHelper();
        foreach ($userCustomFields as $userCustomField) {
            $customFieldTitles[] = $customFieldCsvHelper->makeCustomFieldColumnName($userCustomField);
            $value = '';
            if ($userCustomField->Type === Constants\CustomField::TYPE_INTEGER) {
                $value = 5;
            } elseif ($userCustomField->Type === Constants\CustomField::TYPE_DECIMAL) {
                $value = 250;
            } elseif ($userCustomField->Type === Constants\CustomField::TYPE_TEXT) {
                $value = 'text';
            } elseif ($userCustomField->Type === Constants\CustomField::TYPE_SELECT) {
                $values = $this->getBaseCustomFieldHelper()->extractDropdownOptionsFromString($userCustomField->Parameters);
                $value = count($values) ? array_shift($values) : '';
            } elseif ($userCustomField->Type === Constants\CustomField::TYPE_DATE) {
                $value = '1970-01-01 00:00:00';
            } elseif ($userCustomField->Type === Constants\CustomField::TYPE_FULLTEXT) {
                $value = 'fulltext';
            }
            $customFieldValues[] = $value;
        }
        return [$customFieldTitles, $customFieldValues];
    }
}
