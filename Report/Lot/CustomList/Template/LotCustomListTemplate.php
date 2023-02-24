<?php
/**
 * SAM-1264: WALMART - Custom lot reports
 * SAM-1376: Add auction fields to custom lot export
 * SAM-1372: Category level in custom lots report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Template.php 21585 2015-06-18 03:16:01Z SWB\bregidor $
 * @since           Sep 12, 2012
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\CustomList\Template;

use CustomLotsTemplateConfig;
use CustomLotsTemplateField;
use Laminas\Config\Config;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Order\LotCategoryOrdererAwareTrait;
use Sam\Report\Lot\CustomList\Template\Load\CustomLotsTemplateConfigLoaderCreateTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Storage\ReadRepository\Entity\CustomLotsTemplateField\CustomLotsTemplateFieldReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\CustomLotsTemplateConfig\CustomLotsTemplateConfigWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\CustomLotsTemplateField\CustomLotsTemplateFieldWriteRepositoryAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;
use User;

/**
 *  Class implements functionality for "Custom Lots" report templates configuration
 *
 * Class CustomListTemplate
 * @package Sam\Report\Lot\CustomList\Template
 * @property int $AccountId
 * @property User $User
 */
class LotCustomListTemplate extends CustomizableClass
{
    use AccountAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CustomLotsTemplateConfigLoaderCreateTrait;
    use CustomLotsTemplateConfigWriteRepositoryAwareTrait;
    use CustomLotsTemplateFieldReadRepositoryCreateTrait;
    use CustomLotsTemplateFieldWriteRepositoryAwareTrait;
    use LotCategoryOrdererAwareTrait;
    use LotCustomFieldLoaderCreateTrait;
    use UserAwareTrait;

    public const REPORT_CSV = 'csv';
    public const REPORT_HTML = 'html';

    protected AdminPrivilegeChecker $adminPrivilegeChecker;

    // Index max length is 30 chars
    /** @var string[] */
    protected array $basicFields = [
        'LotStatus' => 'Lot Status',
        'ItemNumber' => 'Item #',
        'ItemNumberExtension' => 'Item Ext',
        'Category' => 'Category',
        'CategoryTree' => 'Category Tree',
        'LotNumber' => 'Lot #',
        'Quantity' => 'Quantity',
        'QxM' => 'QxM',
        'LotName' => 'Lot Name',
        'LotDescription' => 'Lot Description',
        'Changes' => 'Changes',
        'Warranty' => 'Warranty',
        'ListingOnly' => 'Listing Only',
        'SpecialTerms' => 'Special T&C',
        'LowEst' => 'Low Est.',
        'HighEst' => 'High Est.',
        'StartingBid' => 'Starting Bid',
        'BuyNowPrice' => 'Buy Now Price',
        'Cost' => 'Cost',
        'Reserve' => 'Reserve',
        'Consignor' => 'Consignor',
        'CommissionPercent' => 'Commission %',
        'Commission' => 'Commission',
        'HammerPrice' => 'Hammer Price',
        'BuyersPremium' => 'Buyer\'s Premium',
        'AuctionId' => 'Auction ID',
        'AuctionNumber' => 'Auction #',
        'DateSold' => 'Date Sold',
        'WinningBidder' => 'Winning Bidder',
        'WinningBidderNum' => 'Winning Bidder #',
        'InternetBid' => 'Int.',
        'OnlyTaxBp' => 'Only Tax BP',
        'TaxPercent' => 'Tax(%)',
        'Tax' => 'Tax',
        'TaxInvoicePercent' => 'Tax(%) from Invoice',
        'TaxInvoice' => 'Tax from Invoice',
        'NoTaxOutside' => 'NoTaxOutside',
        'Returned' => 'Returned',
        'Featured' => 'Featured',
        'ClerkNote' => 'Clerk Note',
        'GeneralNote' => 'General Note',
        'LotImage' => 'Lot Image',
        'BulkControl' => 'Bulk Control',
        'WinningBidDistribution' => 'Winning Bid Distribution',
    ];
    /** @var string[] */
    protected array $winnerInfoFields = [
        'WinnerUsername' => 'Username',
        'WinnerEmail' => 'Email',
        'WinnerCustomerNumber' => 'Customer #',
        'WinnerPermanentBidderNumber' => 'Make permanent bidder number',
        'WinnerFirstName' => 'First name',
        'WinnerLastName' => 'Last name',
        'WinnerPhone' => 'Phone #',
        'WinnerPhoneType' => 'Phone Type',
        'WinnerIdentification' => 'Identification',
        'WinnerIdentificationType' => 'Identification Type',
        'WinnerCompanyName' => 'Company Name',
        'WinnerLocation' => 'Location',
        'WinnerFlag' => 'Flag',
        'WinnerReferrer' => 'Referrer',
        'WinnerReferrerHost' => 'Referrer Host',
    ];
    /** @var string[] */
    protected array $winnerBillingFields = [
        'WinnerBillingContactType' => 'Billing Contact Type',
        'WinnerBillingFirstName' => 'Billing First name',
        'WinnerBillingLastName' => 'Billing Last name',
        'WinnerBillingCompanyName' => 'Billing Company name',
        'WinnerBillingPhone' => 'Billing Phone',
        'WinnerBillingFax' => 'Billing Fax',
        'WinnerBillingCountry' => 'Billing Country',
        'WinnerBillingAddress' => 'Billing Address',
        'WinnerBillingAddress2' => 'Billing Address 2',
        'WinnerBillingAddress3' => 'Billing Address 3',
        'WinnerBillingCity' => 'Billing City',
        'WinnerBillingState' => 'Billing State/Province',
        'WinnerBillingZip' => 'Billing Zip/Postal code',
        'WinnerBillingAuthNetCim' => 'Billing CIM Profile ID',
        'WinnerBillingCcType' => 'Billing CC type',
        'WinnerBillingCcNumber' => 'Billing CC number',
        'WinnerBillingExpDate' => 'Billing Expiration date',
    ];
    /** @var string[] */
    protected array $winnerShippingFields = [
        'WinnerShippingContactType' => 'Shipping Contact Type',
        'WinnerShippingFirstName' => 'Shipping First name',
        'WinnerShippingLastName' => 'Shipping Last name',
        'WinnerShippingCompanyName' => 'Shipping Company name',
        'WinnerShippingPhone' => 'Shipping Phone',
        'WinnerShippingFax' => 'Shipping Fax',
        'WinnerShippingCountry' => 'Shipping Country',
        'WinnerShippingAddress' => 'Shipping Address',
        'WinnerShippingAddress2' => 'Shipping Address 2',
        'WinnerShippingAddress3' => 'Shipping Address 3',
        'WinnerShippingCity' => 'Shipping City',
        'WinnerShippingState' => 'Shipping State/Province',
        'WinnerShippingZip' => 'Shipping Zip/Postal code',
    ];
    /** @var string[] */
    protected array $auctionFields = [
        'AuctionType' => 'Auction type',
        'AuctionStartDate' => 'Start date/time',
        'AuctionStartClosingDate' => 'Start closing date/time',
        'AuctionEndDate' => 'End date/time',                 // Timed
        'AuctionClerkingStyle' => 'Clerking style',                // Live
        'AuctionName' => 'Name',
        'AuctionDescription' => 'Description',
        'AuctionTerms' => 'Terms and conditions',
        'AuctionInvoiceNotes' => 'Invoice notes',
        'AuctionShippingInfo' => 'Shipping info',
        'AuctionStreamDisplay' => 'Stream display',                // Live
        //        'AuctionStreamServer' => 'Stream server',                 // Live
        //        'AuctionStreamName' => 'Stream name',                   // Live
        'AuctionParcelChoice' => 'Enable parcel choice lots',     // Live
        'AuctionImage' => 'Image',
        'AuctionDefaultPostalCode' => 'Default lot ZIP/Postal code',
        'AuctionPublished' => 'Published',
        'AuctionTest' => 'Test Auction',
        'AuctionReverse' => 'Reverse Auction',               // Timed
        'AuctionEventType' => 'Event Type',                    // Timed
        'AuctionStaggerClosing' => 'Staggered Closing',             // Timed
        'AuctionLotsPerInterval' => 'Lots per interval',             // Timed
        'AuctionExcludeClosedLots' => 'Exclude closed lots',           // Timed
        'AuctionOnlyOngoingLots' => 'Only show ongoing lots',        // Timed
        'AuctionDateAssignmentStrategy' => 'Lot/Auction Timing',            // Timed
        'AuctionSaleGroup' => 'Sale Group',
        'AuctionCcThresholdDom' => 'Credit card threshold (Domestic)',
        'AuctionCcThresholdInt' => 'Credit card threshold (International)',
        'AuctionAuthAmount' => 'Authorization amount',
        'AuctionHeldIn' => 'Auction held in',
        'AuctionCurrency' => 'Currency',
        'AuctionAddCurrencies' => 'Additional currencies',
        'AuctionAuctioneer' => 'Auctioneer',
        'AuctionEmail' => 'Email',
        'AuctionLocation' => 'Location',
        'AuctionTaxPercent' => 'Auction Tax(%)',
        'AuctionPaymentTrackCode' => 'Payment tracking code',
        'AuctionLotOrderPri' => 'Default lot order (primary)',
        'AuctionLotOrderPriIgnStpWrd' => 'Ignore stop words (primary)',
        'AuctionLotOrderSec' => 'Default lot order (secondary)',
        'AuctionLotOrderSecIgnStpWrd' => 'Ignore stop words (secondary)',
        'AuctionLotOrderTer' => 'Default lot order (tertiary)',
        'AuctionLotOrderTerIgnStpWrd' => 'Ignore stop words (tertiary)',
        'AuctionLotOrderQua' => 'Default lot order (quaternary)',
        'AuctionLotOrderQuaIgnStpWrd' => 'Ignore stop words (quaternary)',
        'AuctionBlacklist' => 'Blacklist',
        'AuctionLotChangeConfirm' => 'Require lot change confirmation',
        'AuctionPopLotFromCategory' => 'Auto populate lot name from categories',
        'AuctionPopEmptyLotNum' => 'Auto populate empty lot numbers',
        'AuctionDefaultLotPeriod' => 'Default lot period',            // Timed
        'AuctionWavebidGuid' => 'Wavebid Auction Guid',
    ];

    /** @var string[] */
    protected array $invoiceFields = [
        'InvoiceNumber' => 'Invoice #',
    ];

    // Fields are not used for sorting
    /** @var string[] */
    protected array $fieldsNotSorted = [
        'BuyersPremium',
        'TaxPercent',
        'Tax',
        'LotImage',
        'AuctionImage',
        'AuctionAddCurrencies',
        'Commission',
        'CommissionPercent',
    ];

    // Numeric field must be formatted (corresponding to UsNumberFormatting value)
    /**
     * @var string[]
     */
    protected array $percentFields = [
        'AuctionTaxPercent',
        'CommissionPercent',
        'TaxInvoicePercent',
        'TaxPercent',
    ];
    /**
     * @var string[]
     */
    protected array $moneyFields = [
        'AuctionAuthAmount',
        'AuctionCcThresholdDom',
        'AuctionCcThresholdInt',
        'BuyNowPrice',
        'BuyersPremium',
        'Commission',
        'Cost',
        'HammerPrice',
        'HighEst',
        'LowEst',
        'Reserve',
        'StartingBid',
        'Tax',
        'TaxInvoice',
    ];

    /** @var string[] */
    protected array $accountInfoFields = [
        'Name' => 'Name',
        'CompanyName' => 'Company Name',
        'UrlDomain' => 'Url domain',
    ];

    // cache there all fields to avoid redundant reloading (we load custom fields from db)
    /** @var string[] */
    protected ?array $allFields = null;
    // cache there available (for user) fields
    /** @var string[] */
    protected ?array $availableFields = null;
    // cache there lot item custom fields
    /** @var string[] */
    protected ?array $lotCustomFields = null;

    /**
     * Class instantiation method
     * @return static or customized class extending LotCustomListTemplate
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
        $this->initBasicFields();
        $this->adminPrivilegeChecker = AdminPrivilegeChecker::new();
        return $this;
    }

    /**
     * We add 'CategoryLevel(\d+)' fields to basic field array,
     * their count defined by LotCategory_Manager::getMaxLevel() (starting from 0 level)
     */
    public function initBasicFields(): void
    {
        $basicFields = [];
        foreach ($this->getBasicFields() as $index => $name) {
            $basicFields[$index] = $name;
            if ($index === 'Category') {
                $maxLevel = $this->getLotCategoryOrderer()->getMaxLevel();
                for ($i = 0; $i <= $maxLevel; $i++) {
                    $basicFields['CategoryLevel' . $i] = 'Category_' . ($i + 1);
                }
            }
        }
        $this->basicFields = $basicFields;
    }

    /**
     * Return all fields array
     *
     * @param bool $isForceReload Reload all fields again
     * @return array                    Key: index, value: name
     */
    public function getAllFields(bool $isForceReload = false): array
    {
        if ($this->allFields !== null && !$isForceReload) {
            return $this->allFields;
        }

        $lotCustomFields = $this->getCustomFields($isForceReload);
        $this->allFields = array_merge(
            $this->getBasicFields(),
            $lotCustomFields,
            $this->winnerInfoFields,
            $this->winnerBillingFields,
            $this->winnerShippingFields,
            $this->auctionFields,
            $this->invoiceFields,
            $this->accountInfoFields
        );
        return $this->allFields;
    }

    /**
     * Return all available fields array
     *
     * @param bool $isForceReload Reload all fields again
     * @return array                    Key: index, value: name
     */
    public function getAvailableFields(bool $isForceReload = false): array
    {
        if ($this->availableFields !== null && !$isForceReload) {
            return $this->availableFields;
        }

        $this->availableFields = [];
        $customFields = $this->getCustomFields($isForceReload);
        $this->availableFields = array_merge($this->availableFields, $this->getBasicFields(), $customFields);

        if ($this->checkUserFieldsAvailable()) {
            $this->availableFields = array_merge(
                $this->availableFields,
                $this->winnerInfoFields,
                $this->winnerBillingFields,
                $this->winnerShippingFields
            );
        }

        if ($this->checkAuctionFieldsAvailable()) {
            $this->availableFields = array_merge($this->availableFields, $this->auctionFields);
        }

        if ($this->checkInvoiceFieldsAvailable()) {
            $this->availableFields = array_merge($this->availableFields, $this->invoiceFields);
        }

        if ($this->checkAccountInfoFieldsAvailable()) {
            $this->availableFields = array_merge($this->availableFields, $this->accountInfoFields);
        }

        return $this->availableFields;
    }

    /**
     * Return all field indexes in array
     *
     * @return array
     */
    public function getAllFieldIndexes(): array
    {
        return array_keys($this->getAllFields());
    }

    /**
     * Return available field indexes in array
     *
     * @return array
     */
    public function getAvailableFieldIndexes(): array
    {
        return array_keys($this->getAvailableFields());
    }

    /**
     * @return array
     */
    public function getBasicFields(): array
    {
        return $this->basicFields;
    }

    /**
     * @param bool $isForceReload
     * @return array
     */
    public function getCustomFields(bool $isForceReload = false): array
    {
        if ($this->lotCustomFields !== null && !$isForceReload) {
            return $this->lotCustomFields;
        }

        $this->lotCustomFields = [];
        $lotCustomFields = $this->createLotCustomFieldLoader()->loadAll();
        foreach ($lotCustomFields as $lotCustomField) {
            $this->lotCustomFields['fc' . $lotCustomField->Id] = $lotCustomField->Name;
        }
        return $this->lotCustomFields;
    }

    /**
     * Return winning bidder info fields or Null if not available due to privileges
     * @return string[]
     */
    public function getWinnerInfoFields(): array
    {
        return $this->winnerInfoFields;
    }

    /**
     * Return winning bidder billing fields or Null if not available due to privileges
     * @return string[]
     */
    public function getWinnerBillingFields(): array
    {
        return $this->winnerBillingFields;
    }

    /**
     * Return winning bidder shipping fields or Null if not available due to privileges
     * @return string[]
     */
    public function getWinnerShippingFields(): array
    {
        return $this->winnerShippingFields;
    }

    /**
     * Return winning bidder shipping fields or Null if not available due to privileges
     * @return string[]
     */
    public function getAuctionFields(): array
    {
        return $this->auctionFields;
    }

    /**
     * Return Invoice fields or Null if not available due to privileges
     * @return string[]
     */
    public function getInvoiceFields(): array
    {
        return $this->invoiceFields;
    }

    /**
     * Return Account Info fields or Null if not available due to privileges
     * @return string[]
     */
    public function getAccountInfoFields(): array
    {
        return $this->accountInfoFields;
    }

    /**
     * Return auction field indexes in array
     *
     * @return array
     */
    public function getAuctionFieldIndexes(): array
    {
        return array_keys($this->getAuctionFields());
    }

    /**
     * Check if Auction Info fields are available.
     * Current user must have 'Manage auctions' access rights
     *
     * @return bool
     */
    public function checkAuctionFieldsAvailable(): bool
    {
        $hasPrivilegeForManageAuctions = $this->adminPrivilegeChecker->hasPrivilegeForManageAuctions();
        return $hasPrivilegeForManageAuctions;
    }

    /**
     * Check if field related to Auction Info fields
     *
     * @param string $index
     * @return bool
     */
    public function isAuctionField(string $index): bool
    {
        return array_key_exists($index, $this->auctionFields);
    }

    /**
     * Check if user has privilege to access inventory page
     *
     * @return bool
     */
    public function checkInventoryPrivilege(): bool
    {
        $hasPrivilegeForManageInventory = $this->adminPrivilegeChecker->hasPrivilegeForManageInventory();
        return $hasPrivilegeForManageInventory;
    }

    /**
     * Check if Invoice fields are available.
     * Current user must have 'Manage invoices' access rights
     *
     * @return bool
     */
    public function checkInvoiceFieldsAvailable(): bool
    {
        $hasPrivilegeForManageInvoices = $this->adminPrivilegeChecker->hasPrivilegeForManageInvoices();
        return $hasPrivilegeForManageInvoices;
    }

    /**
     * Check if Winning bidder fields are available.
     * Current user must have 'Manage users' access rights
     *
     * @return bool
     */
    public function checkUserFieldsAvailable(): bool
    {
        $hasPrivilegeForManageUsers = $this->adminPrivilegeChecker->hasPrivilegeForManageUsers();
        return $hasPrivilegeForManageUsers;
    }

    /**
     * To see this fields user must have super admin privileges
     * @return bool
     */
    public function checkAccountInfoFieldsAvailable(): bool
    {
        $hasPrivilegeForSuperadmin = $this->adminPrivilegeChecker->hasPrivilegeForSuperadmin();
        $isAvailable = $this->cfg()->get('core->portal->enabled')
            && $hasPrivilegeForSuperadmin;
        return $isAvailable;
    }

    /**
     * Check if field used for sorting
     *
     * @param string $index
     * @return bool
     */
    public function isSortOrderField(string $index): bool
    {
        return !in_array($index, $this->fieldsNotSorted, true)
            && !str_starts_with($index, 'Category');
    }

    /**
     * Return array of fields, which can be used for report results sorting
     *
     * @return array    Key: index, value: name
     */
    public function getSortOrderFields(): array
    {
        $sortFields = [];
        $allFields = $this->getAvailableFields();
        foreach ($allFields as $index => $field) {
            if ($this->isSortOrderField($index)) {
                $sortFields[$index] = $field;
            }
        }
        return $sortFields;
    }

    /**
     * Return array of fields for default template
     *
     * @return array    Key: index, value: name
     */
    public function getDefaultFields(): array
    {
        $defaultFields = [];
        $allFields = $this->getAllFields();
        /** @var Config $defaultFieldIndexes */
        $defaultFieldIndexes = $this->cfg()->get('core->admin->report->customLots->fields');
        $defaultFieldIndexes = $defaultFieldIndexes->toArray();
        foreach ($defaultFieldIndexes as $index) {
            $defaultFields[$index] = $allFields[$index];
        }
        return $defaultFields;
    }

    /**
     * Return field index of default sorting
     *
     * @return string
     */
    public function getDefaultSortOrderField(): string
    {
        return $this->cfg()->get('core->admin->report->customLots->order->field');
    }

    /**
     * Return direction for default sorting 0 - Ascending, 1 - Descending
     *
     * @return string
     */
    public function getDefaultSortOrderDirection(): string
    {
        return $this->cfg()->get('core->admin->report->customLots->order->direction');
    }

    /**
     * Check if field is percent
     *
     * @param string $index
     * @return bool
     */
    public function isPercentField(string $index): bool
    {
        return in_array($index, $this->percentFields, true);
    }

    /**
     * Check if field is money
     *
     * @param string $index
     * @return bool
     */
    public function isMoneyField(string $index): bool
    {
        return in_array($index, $this->moneyFields, true);
    }

    /**
     * Load active fields for config id
     *
     * @param int $configId
     * @param bool $isOrderByName
     * @return CustomLotsTemplateField[]|array
     */
    public function loadFieldArray(int $configId, bool $isOrderByName = false): array
    {
        $customLotsTemplateFieldRepository = $this->createCustomLotsTemplateFieldReadRepository()
            ->filterConfigId($configId)
            ->filterActive(true);
        if ($isOrderByName) {
            $customLotsTemplateFieldRepository->orderByOrder()
                ->orderByName()
                ->orderById();
        }
        $fields = $customLotsTemplateFieldRepository->loadEntities();

        // exclude deleted fields
        $allFields = $this->getAllFields();
        foreach ($fields as $i => $field) {
            if (!array_key_exists($field->Index, $allFields)) {
                unset($fields[$i]);
            }
        }
        return $fields;
    }

    /**
     * Load all fields for config id
     *
     * @param int $configId
     * @return CustomLotsTemplateField[]
     */
    public function loadAllFields(int $configId): array
    {
        $customLotsTemplateFields = $this->createCustomLotsTemplateFieldReadRepository()
            ->filterConfigId($configId)
            ->loadEntities();
        $customLotsTemplateFields = ArrayHelper::indexEntities($customLotsTemplateFields, 'Index');
        return $customLotsTemplateFields;
    }

    /**
     * Delete template
     *
     * @param int $configId
     * @param int $editorUserId
     */
    public function delete(int $configId, int $editorUserId): void
    {
        $templateFields = $this->createCustomLotsTemplateFieldReadRepository()
            ->filterConfigId($configId)
            ->loadEntities();

        foreach ($templateFields as $templateField) {
            $templateField->Active = false;
            $this->getCustomLotsTemplateFieldWriteRepository()->saveWithModifier($templateField, $editorUserId);
        }

        $templateConfig = $this->createCustomLotsTemplateConfigLoader()->load($configId);
        if ($templateConfig) {
            $templateConfig->Active = false;
            $this->getCustomLotsTemplateConfigWriteRepository()->saveWithModifier($templateConfig, $editorUserId);
        }
    }

    /**
     * Return array of fields used in defined or default template config
     *
     * @param CustomLotsTemplateConfig|null $config Null for default config template
     * @param string $reportType Fields for html or csv report
     *                                            For HTML we show images in one column "Lot Image", they would be limited by $config->ImageSeparateColumns, if it is set
     *                                            For CSV we may show images in one column (All in One), or in separate columns, which count defined in $config->ImageSeparateColumns
     * @return array                                Key: index, value: name
     */
    public function getConfigFields(
        CustomLotsTemplateConfig $config = null,
        string $reportType = LotCustomListTemplate::REPORT_HTML
    ): array {
        $resultFields = [];
        if ($config) {
            $fields = $this->loadFieldArray($config->Id, true);

            // Exclude un-available fields
            $allAvailableFields = $this->getAvailableFields();
            foreach ($fields as $key => $field) {
                if (!array_key_exists($field->Index, $allAvailableFields)) {
                    unset($fields[$key]);
                }
            }

            foreach ($fields as $field) {
                if (
                    (
                        in_array($field->Index, ['Category', 'CategoryTree'])
                        || preg_match('/^CategoryLevel(\d+)$/', $field->Index)
                    )
                    && $config->CategoriesSeparateColumns > 0
                ) {
                    for ($i = 1; $i <= $config->CategoriesSeparateColumns; $i++) {
                        $resultFields[$field->Index . '_' . $i] = $field->Name . ' ' . $i;
                    }
                } elseif (
                    $reportType === self::REPORT_CSV
                    && $field->Index === 'LotImage'
                    && $config->ImageSeparateColumns > 0
                ) {
                    for ($i = 1; $i <= $config->ImageSeparateColumns; $i++) {
                        $resultFields[$field->Index . $i] = $field->Name . ' ' . $i;
                    }
                } else {
                    $resultFields[$field->Index] = $field->Name;
                }
            }
        } else {
            $resultFields = $this->getDefaultFields();
        }

        return $resultFields;
    }

    /**
     * Assign unset order values as max order + 1
     *
     * @param int $configId
     * @param int $editorUserId
     */
    public function assignFieldOrders(int $configId, int $editorUserId): void
    {
        $noOrderFields = $this->createCustomLotsTemplateFieldReadRepository()
            ->filterConfigId($configId)
            ->filterActive(true)
            ->filterOrder(0)
            ->loadEntities();

        if (count($noOrderFields) > 0) {
            $indexes = $this->getAllFieldIndexes();
            $nextOrder = $this->suggestNextOrder($configId);
            foreach ($indexes as $index) {
                foreach ($noOrderFields as $field) {
                    if ($index === $field->Index) {
                        $field->Order = $nextOrder;
                        $this->getCustomLotsTemplateFieldWriteRepository()->saveWithModifier($field, $editorUserId);
                        $nextOrder++;
                        continue 2;
                    }
                }
            }
        }
    }

    /**
     * Determine next available order value
     *
     * @param int $configId
     * @return int
     */
    public function suggestNextOrder(int $configId): int
    {
        $maxOrder = $this->createCustomLotsTemplateFieldReadRepository()
            ->filterConfigId($configId)
            ->select(['MAX(cltf.`order`) AS max_order'])
            ->loadRow();
        return $maxOrder ? floor($maxOrder['max_order']) + 1 : 1;
    }

    /**
     * Return sorting field index and direction.
     * We get values by template config or use defaults.
     * It is possible, that sorting field in config is not available for current user, that we would use the first field from available
     *
     * @param CustomLotsTemplateConfig|null $config
     * @param array|null $fieldIndexes // We use it to skip double loading
     * @return array(string, integer)
     */
    public function getSortOrderOptions(CustomLotsTemplateConfig $config = null, array $fieldIndexes = null): array
    {
        if ($fieldIndexes === null) {
            $configFields = $this->getConfigFields($config);
            $fieldIndexes = array_keys($configFields);
        }
        if ($config) {
            if (in_array($config->OrderFieldIndex, $fieldIndexes, true)) {
                $orderFieldIndex = $config->OrderFieldIndex;
                $orderFieldDirection = (int)$config->OrderFieldDirection;
            } else {    // Case, when sorting field is not among available fields for current user
                $orderFieldIndex = $fieldIndexes[0];
                $orderFieldDirection = 0;
            }
        } else {
            $orderFieldIndex = $this->getDefaultSortOrderField();
            $orderFieldDirection = (int)($this->getDefaultSortOrderDirection() === Constants\MySearch::DESC) ? 1 : 0;
        }
        return [$orderFieldIndex, $orderFieldDirection];
    }

    /**
     * Overwrite UserAwareTrait::setUser()
     * @param User $user
     * @return static
     */
    public function setUser($user): static
    {
        $this->adminPrivilegeChecker->initByUser($user);
        $this->getAccountAggregate()->setAccountId($user->AccountId);
        return $this;
    }
}
