<?php
/**
 * SAM-4629: Refactor custom lot report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\CustomList\Media\PrintTable;

use Exception;
use LotItemCustField;
use Sam\Application\Url\Build\Config\Image\AuctionImageUrlConfig;
use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Application\Url\Domain\AccountDomainDetectorCreateTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\BuyersPremium\Calculate\BuyersPremiumCalculatorAwareTrait;
use Sam\Consignor\Commission\Calculate\CommissionFeeCalculator\ConsignorCommissionFeeCalculatorCreateTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Auction\Render\AuctionPureRenderer;
use Sam\Core\Constants;
use Sam\Core\CustomField\Decimal\CustomDataDecimalPureCalculator;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Entity\Model\AuctionLotItem\LotBulkGrouping\LotBulkGroupingRole;
use Sam\Core\Entity\Model\Invoice\Tax\InvoiceTaxPureCalculator;
use Sam\Core\Entity\Model\LotItem\SellInfo\LotSellInfoPureChecker;
use Sam\Core\Lot\Render\LotPureRenderer;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Currency\Load\AuctionCurrencyLoaderAwareTrait;
use Sam\CustomField\Lot\Help\LotCustomFieldHelperCreateTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Image\ImageHelper;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Invoice\Legacy\Calculate\Basic\LegacyInvoiceCalculatorAwareTrait;
use Sam\Lot\Category\Renderer\LotCategoryRendererAwareTrait;
use Sam\Lot\Image\Path\LotImagePathResolverCreateTrait;
use Sam\Lot\Render\Amount\LotAmountRendererFactoryCreateTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Lot\CustomList\Media\Base\LotCustomListRowBuilderHelperAwareTrait;
use Sam\Report\Lot\CustomList\Template\LotCustomListTemplate;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * This class contains methods for building rows of report
 *
 * Class LotCustomListPrintTableRowBuilder
 * @package Sam\Report\Lot\CustomList\Media\PrintTable
 */
class LotCustomListPrintTableRowBuilder extends CustomizableClass
{
    use AccountAwareTrait;
    use AccountDomainDetectorCreateTrait;
    use AuctionCurrencyLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use BidderNumPaddingAwareTrait;
    use BlockCipherProviderCreateTrait;
    use BuyersPremiumCalculatorAwareTrait;
    use ConfigRepositoryAwareTrait;
    use ConsignorCommissionFeeCalculatorCreateTrait;
    use DateHelperAwareTrait;
    use FileManagerCreateTrait;
    use LegacyInvoiceCalculatorAwareTrait;
    use LotAmountRendererFactoryCreateTrait;
    use LotCategoryRendererAwareTrait;
    use LotCustomFieldHelperCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotCustomListRowBuilderHelperAwareTrait;
    use LotImagePathResolverCreateTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use SettingsManagerAwareTrait;
    use UrlBuilderAwareTrait;

    private array $calculatedData = [];
    /** @var LotItemCustField[]|null */
    private ?array $lotCustomFields = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $rowData
     * @param array $fields
     * @return array
     * @throws Exception
     */
    public function buildRow(array $rowData, array $fields): array
    {
        $row = [];
        foreach ($fields as $field) {
            $row[$field] = $this->makeFieldValue($field, $rowData);
        }
        return $row;
    }

    /**
     * @param string $field
     * @param array $row
     * @return string
     * @throws Exception
     */
    public function makeFieldValue(string $field, array $row): string
    {
        $lotItemId = (int)$row['id'];
        $auctionId = (int)$row['auction_id'];
        $accountId = (int)$row['account_id'];
        $value = '';        // result field value
        switch ($field) {
            case 'ItemNumber':
                $value = $this->getLotRenderer()->makeItemNo($row['ItemNumber'], $row['item_num_ext']);
                break;

            case 'LotStatus':
                $value = LotPureRenderer::new()->makeLotStatus((int)$row['LotStatus'], (bool)$row['reverse']);
                break;

            case 'Category':
                $strCategories = '';
                $lotCategories = $this->getLotCustomListRowBuilderHelper()->getLotCategories($lotItemId);
                foreach ($lotCategories as $lotCategory) {
                    $strCategories .= $lotCategory->Name . '; ';
                }
                $value = rtrim($strCategories, '; ');
                break;

            case 'CategoryTree':
                //$strValue = Lot_Factory::GetCategoriesText($arr['id']);
                // Use cached category paths
                $categoryPath = '';
                $categoryPaths = $this->getLotCustomListRowBuilderHelper()->getLotCategoryPaths($lotItemId);
                foreach ($categoryPaths as $pathLotCategories) {
                    $categoryPath .= $this->getLotCategoryRenderer()->buildCategoryTreeText($pathLotCategories) . '; ';
                }
                $value = rtrim($categoryPath, '; ');
                break;

            case 'LotNumber':
                $value = $this->getLotRenderer()->makeLotNo($row['lot_num'], $row['lot_num_ext'], $row['lot_num_prefix']);
                break;

            case 'WinningBidderNum':
                $value = $this->getBidderNumberPadding()->clear($row['WinningBidderNum']);
                break;

            case 'ListingOnly':
                $value = $row['ListingOnly'];
                break;

            case 'CommissionPercent':
                $hammerPrice = Cast::toFloat($row['HammerPrice']);
                if (!LotSellInfoPureChecker::new()->isHammerPrice($hammerPrice)) {
                    $value = '';
                } elseif (Floating::lteq($hammerPrice, 0.)) {
                    $value = 0.;
                } else {
                    if (!isset($this->calculatedData[$lotItemId]['commission'])) {
                        $consignorCommissionId = $row['consignor_commission_id']
                            ?? $this->getSettingsManager()->get(Constants\Setting::CONSIGNOR_COMMISSION_ID, $accountId);
                        $consignmentCommission = 0.;
                        if ($consignorCommissionId) {
                            $consignmentCommission = $this->createConsignorCommissionFeeCalculator()
                                ->calculate($hammerPrice, (int)$consignorCommissionId);
                        }
                        $this->calculatedData[$lotItemId]['commission'] = $consignmentCommission;
                    } else {
                        $consignmentCommission = $this->calculatedData[$lotItemId]['commission'];
                    }
                    $fltCommissionPercent = $consignmentCommission / $hammerPrice * 100;
                    $value = $fltCommissionPercent;
                }
                break;

            case 'Commission':
                $hammerPrice = Cast::toFloat($row['HammerPrice']);
                if (!LotSellInfoPureChecker::new()->isHammerPrice($hammerPrice)) {
                    $value = '';
                } else {
                    if (!isset($this->calculatedData[$lotItemId]['commission'])) {
                        $consignorCommissionId = $row['consignor_commission_id']
                            ?? $this->getSettingsManager()->get(Constants\Setting::CONSIGNOR_COMMISSION_ID, $accountId);
                        $consignmentCommission = 0.;
                        if ($consignorCommissionId) {
                            $consignmentCommission = $this->createConsignorCommissionFeeCalculator()
                                ->calculate($hammerPrice, (int)$consignorCommissionId);
                        }
                        $this->calculatedData[$lotItemId]['commission'] = $consignmentCommission;
                    }
                    $value = $this->calculatedData[$lotItemId]['commission'];
                }
                break;

            case 'BuyersPremium':
                if (!isset($this->calculatedData[$lotItemId]['buyers_premium'])) {
                    $this->calculatedData[$lotItemId]['buyers_premium'] = $this->getBuyersPremiumCalculator()
                        ->calculate($lotItemId, $auctionId, (int)$row['winning_bidder_id'], $this->getAccountId());
                }
                $value = $this->calculatedData[$lotItemId]['buyers_premium'];
                break;

            case 'TaxPercent':
                if (!isset($this->calculatedData[$lotItemId]['tax_percent'])) {
                    $taxResult = $this->getLegacyInvoiceCalculator()->detectApplicableSalesTax(
                        (int)$row['winning_bidder_id'],
                        $lotItemId,
                        $auctionId
                    );
                    $this->calculatedData[$lotItemId]['tax_percent'] = $taxResult->percent;
                    $this->calculatedData[$lotItemId]['tax_application'] = $taxResult->application;
                }
                $value = $this->calculatedData[$lotItemId]['tax_percent'];
                break;

            case 'Tax':
                if (!isset($this->calculatedData[$lotItemId]['tax_percent'])) {
                    $taxResult = $this->getLegacyInvoiceCalculator()->detectApplicableSalesTax(
                        (int)$row['winning_bidder_id'],
                        $lotItemId,
                        $auctionId
                    );
                    $this->calculatedData[$lotItemId]['tax_percent'] = $taxResult->percent;
                    $this->calculatedData[$lotItemId]['tax_application'] = $taxResult->application;
                }
                if (!isset($this->calculatedData[$lotItemId]['buyers_premium'])) {
                    $this->calculatedData[$lotItemId]['buyers_premium'] = $this->getBuyersPremiumCalculator()
                        ->calculate($lotItemId, $auctionId, (int)$row['winning_bidder_id'], $this->getAccountId());
                }
                $salesTax = InvoiceTaxPureCalculator::new()->calcSalesTaxApplied(
                    (float)$row['HammerPrice'],
                    (float)$this->calculatedData[$lotItemId]['buyers_premium'],
                    (float)$this->calculatedData[$lotItemId]['tax_percent'],
                    (int)$this->calculatedData[$lotItemId]['tax_application']
                );
                $value = $salesTax;
                break;

            case 'AuctionNumber':
                $value = $this->getAuctionRenderer()->makeSaleNo($row['sale_num'], $row['sale_num_ext']);
                break;

            case 'DateSold':
                $strDateSold = '';
                if (!empty($row['DateSold'])) {
                    $dateSold = $this->getDateHelper()->convertUtcToSysByDateIso($row['DateSold']);
                    $strDateSold = $this->getDateHelper()->formattedDate($dateSold);
                }
                $value = $strDateSold;
                break;

            case 'LotImage':
                $imageUrlList = '';
                $lotImagePathResolver = $this->createLotImagePathResolver();
                $lotImages = $this->getLotCustomListRowBuilderHelper()->getLotImages($lotItemId);
                foreach ($lotImages as $lotImage) {
                    $fileName = $lotImage->ImageLink;
                    $imageFilePath = $lotImagePathResolver->makeLotImageRelativePath($accountId, $fileName);
                    if ($this->createFileManager()->exist($imageFilePath)) {
                        $defaultSize = ImageHelper::new()->detectSizeFromMapping();
                        $lotImageUrl = $this->getUrlBuilder()->build(
                            LotImageUrlConfig::new()->construct($lotImage->Id, $defaultSize, $accountId)
                        );
                        $imageUrlList .= $lotImageUrl . '|';
                    }
                }
                $value = rtrim($imageUrlList, '|');
                break;

            case 'BulkControl':
                $strBulkControl = '';
                $lotBulkGrouping = LotBulkGroupingRole::new()->fromDbRow($row);
                if ($lotBulkGrouping->isMaster()) {
                    $strBulkControl = Constants\LotBulkGroup::LBGR_MASTER;
                } elseif ($lotBulkGrouping->isPiecemeal()) {
                    $strBulkControl = $this->getLotRenderer()->makeItemNo($row['ItemNumber'], $row['item_num_ext']);
                }
                $value = $strBulkControl;
                break;

            case 'WinnerFlag':
                $value = UserPureRenderer::new()->makeFlag((int)$row['WinnerFlag']);
                break;

            case 'WinnerBillingCountry':
                $country = (string)$row['WinnerBillingCountry'];
                $country = AddressRenderer::new()->countryName($country);
                $value = $country;
                break;

            case 'WinnerBillingState':
                $state = (string)$row['WinnerBillingState'];
                $country = (string)$row['WinnerBillingCountry'];
                $value = AddressRenderer::new()->stateName($state, $country);
                break;

            case 'WinnerBillingCcNumber':
                $value = $this->createBlockCipherProvider()->construct()->decrypt($row['WinnerBillingCcNumber']);
                break;

            case 'WinnerShippingCountry':
                $country = (string)$row['WinnerShippingCountry'];
                $country = AddressRenderer::new()->countryName($country);
                $value = $country;
                break;

            case 'WinnerShippingState':
                $state = (string)$row['WinnerShippingState'];
                $country = (string)$row['WinnerShippingCountry'];
                $value = AddressRenderer::new()->stateName($state, $country);
                break;

            case 'AuctionStartDate':
                if ($auctionId) {
                    $value = $this->getDateHelper()->formatUtcDateIso($row['AuctionStartDate'], null, $row['auction_timezone_location']);
                }
                break;

            case 'AuctionStartClosingDate':
                if ($auctionId) {
                    $value = $this->getDateHelper()->formatUtcDateIso($row['AuctionStartClosingDate'], null, $row['auction_timezone_location']);
                }
                break;

            case 'AuctionEndDate':
                $auctionStatusPureChecker = AuctionStatusPureChecker::new();
                if (
                    $auctionId
                    && $auctionStatusPureChecker->isTimed($row['auction_type'])
                ) {
                    $value = $this->getDateHelper()->formatUtcDateIso($row['AuctionEndDate'], null, $row['auction_timezone_location']);
                }
                break;

            case 'AuctionDateAssignmentStrategy':
                $auctionStatusPureChecker = AuctionStatusPureChecker::new();
                if (
                    $auctionId
                    && $auctionStatusPureChecker->isTimed($row['auction_type'])
                    && array_key_exists($row['AuctionDateAssignmentStrategy'], Constants\Auction::$dateAssignmentStrategies)
                ) {
                    $value = Constants\Auction::$dateAssignmentStrategies[$row['AuctionDateAssignmentStrategy']];
                }
                break;

            case 'AuctionImage':
                if ($auctionId && (int)$row['auction_image_id']) {
                    $auctionImageId = (int)$row['auction_image_id'];
                    $size = ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->customLotPrintAuction'));
                    $value = $this->getUrlBuilder()->build(
                        AuctionImageUrlConfig::new()->construct($auctionImageId, $size, $accountId)
                    );
                }
                break;

            case 'AuctionHeldIn':
                if ($auctionId) {
                    $country = $row['AuctionHeldIn'];
                    $country = AddressRenderer::new()->countryName($country);
                    $value = $country;
                }
                break;

            case 'AuctionAddCurrencies':
                if ($auctionId) {
                    $currencyIds = $this->getAuctionCurrencyLoader()->loadCurrencyIds($auctionId, true);
                    $currencies = $this->getLotCustomListRowBuilderHelper()->getCurrencies($currencyIds);
                    $currencyLines = [];
                    foreach ($currencies as $currency) {
                        $currencyLines[] = $currency->Name . '(' . $currency->Sign . ')';
                    }
                    $value = implode(', ', $currencyLines);
                }
                break;

            case 'AuctionLotOrderPri':
                if ($auctionId) {
                    $value = AuctionPureRenderer::new()->makeOrderOptionName(
                        (int)$row['lot_order_primary_type'],
                        $row['lot_order_primary_cust_field_name']
                    );
                }
                break;

            case 'AuctionLotOrderSec':
                if ($auctionId) {
                    $value = AuctionPureRenderer::new()->makeOrderOptionName(
                        (int)$row['lot_order_secondary_type'],
                        $row['lot_order_secondary_cust_field_name']
                    );
                }
                break;

            case 'AuctionLotOrderTer':
                if ($auctionId) {
                    $value = AuctionPureRenderer::new()->makeOrderOptionName(
                        (int)$row['lot_order_tertiary_type'],
                        $row['lot_order_tertiary_cust_field_name']
                    );
                }
                break;

            case 'AuctionLotOrderQua':
                if ($auctionId) {
                    $value = AuctionPureRenderer::new()->makeOrderOptionName(
                        (int)$row['lot_order_quaternary_type'],
                        $row['lot_order_quaternary_cust_field_name']
                    );
                }
                break;

            case 'WinnerBillingContactType':
            case 'WinnerShippingContactType':
                $value = Constants\User::CONTACT_TYPE_ENUM[(int)$row[$field]] ?? '';
                break;

            case 'WinnerPhoneType':
                $value = UserPureRenderer::new()->makePhoneType((int)$row[$field]);
                break;

            case 'WinnerIdentificationType':
                $value = UserPureRenderer::new()->makeIdentificationType((int)$row[$field]);
                break;

            case 'Quantity':
                $value = $this->createLotAmountRendererFactory()
                    ->create($accountId)
                    ->makeQuantity(Cast::toFloat($row[$field]), (int)$row['quantity_scale']);
                break;
            case 'UrlDomain':
                $value = $this->createAccountDomainDetector()->detectByValues(
                    (int)$row['account_id'],
                    (string)$row['UrlDomain'],
                    (string)$row['account_name']
                );
                break;
            default:
                if (preg_match('/^fc(\d+)$/', $field, $matches)) {
                    $customFieldId = Cast::toInt($matches[1], Constants\Type::F_INT_POSITIVE);
                    $customFieldValue = $row['fc' . $customFieldId];

                    $lotCustomField = $this->getLotCustomField($customFieldId);
                    if (
                        !empty($customFieldValue)
                        && $lotCustomField
                    ) {
                        $strRenderFunction = $this->createLotCustomFieldHelper()->makeCustomMethodName($lotCustomField->Name, 'Render'); // SAM-1570
                        if (function_exists($strRenderFunction)) {
                            $value = $strRenderFunction(
                                $lotCustomField,
                                $customFieldValue,
                                $auctionId,
                                $this->getLotCustomListRowBuilderHelper()->getEncoding($this->getAccountId())
                            );
                        } else {
                            switch ($lotCustomField->Type) {
                                case Constants\CustomField::TYPE_DECIMAL:
                                    $precision = (int)$lotCustomField->Parameters;
                                    $realValue = CustomDataDecimalPureCalculator::new()->calcRealValue((int)$customFieldValue, $precision);
                                    $value = $this->getNumberFormatter()->format($realValue, $precision);
                                    break;
                                case Constants\CustomField::TYPE_DATE:
                                    $value = $this->getDateHelper()->formattedDateByTimestamp(
                                        (int)$customFieldValue,
                                        $this->getAccountId(),
                                        ''
                                    );
                                    break;
                                case Constants\CustomField::TYPE_INTEGER:
                                default:
                                    $value = $this->getNumberFormatter()->formatInteger($customFieldValue);
                                    break;
                            }
                        }
                    }
                } elseif (preg_match('/^Category_(\d+)$/', $field, $matches)) {
                    $colX = --$matches[1];
                    $lotCategories = $this->getLotCustomListRowBuilderHelper()->getLotCategories($lotItemId);
                    $value = isset($lotCategories[$colX]) ? $lotCategories[$colX]->Name : '';
                } elseif (preg_match('/^CategoryTree_(\d+)$/', $field, $matches)) {
                    $colX = --$matches[1];
                    $categoryPaths = $this->getLotCustomListRowBuilderHelper()->getLotCategoryPaths($lotItemId);
                    $value = isset($categoryPaths[$colX]) ?
                        $this->getLotCategoryRenderer()->buildCategoryTreeText($categoryPaths[$colX])
                        : '';
                } elseif (preg_match('/^CategoryLevel(\d+)$/', $field, $matches)) {
                    $level = $matches[1];
                    $categoryPaths = $this->getLotCustomListRowBuilderHelper()->getLotCategoryPaths($lotItemId);
                    $categoryPath = '';
                    foreach ($categoryPaths as $pathLotCategories) {
                        if (isset($pathLotCategories[$level])) {
                            $categoryPath .= $pathLotCategories[$level]->Name . '; ';
                        }
                    }
                    $value = rtrim($categoryPath, '; ');
                } elseif (preg_match('/^CategoryLevel(\d+)_(\d+)$/', $field, $matches)) {
                    $level = $matches[1];
                    $colX = --$matches[2];
                    $categoryPaths = $this->getLotCustomListRowBuilderHelper()->getLotCategoryPaths($lotItemId);
                    if (isset($categoryPaths[$colX][$level])) {
                        $value = $categoryPaths[$colX][$level]->Name;
                    }
                } elseif (preg_match('/^LotImage(\d+)$/', $field, $matches)) {
                    $imageX = --$matches[1];
                    $lotImageUrl = '';
                    $lotImages = $this->getLotCustomListRowBuilderHelper()->getLotImages($lotItemId);
                    if (isset($lotImages[$imageX])) {
                        $fileName = $lotImages[$imageX]->ImageLink;
                        $imageFilePath = $this->createLotImagePathResolver()->makeLotImageRelativePath($accountId, $fileName);
                        if ($this->createFileManager()->exist($imageFilePath)) {
                            $lotImageId = $lotImages[$imageX]->Id;
                            $defaultSize = ImageHelper::new()->detectSizeFromMapping();
                            $lotImageUrl = $this->getUrlBuilder()->build(
                                LotImageUrlConfig::new()->construct($lotImageId, $defaultSize, $accountId)
                            );
                        }
                    }
                    $value = $lotImageUrl;
                } else {
                    $value = $row[$field];
                }
                break;
        }

        if ($value !== '') {
            $lotCustomListTemplate = LotCustomListTemplate::new();
            if ($lotCustomListTemplate->isMoneyField($field)) {
                $value = $this->getNumberFormatter()->formatMoney($value, $accountId);
            } elseif ($lotCustomListTemplate->isPercentField($field)) {
                $value = $this->getNumberFormatter()->formatPercent($value, $accountId);
            }
        }

        return (string)$value;
    }

    /**
     * @param LotItemCustField[] $lotCustomFields
     * @return static
     */
    public function setLotCustomFields(array $lotCustomFields): static
    {
        $this->lotCustomFields = $lotCustomFields;
        return $this;
    }

    /**
     * @return LotItemCustField[]
     */
    private function getLotCustomFields(): array
    {
        if ($this->lotCustomFields === null) {
            $this->lotCustomFields = $this->createLotCustomFieldLoader()->loadAll();
        }
        return $this->lotCustomFields;
    }

    /**
     * @param int $customFieldId
     * @return LotItemCustField|null
     */
    private function getLotCustomField(int $customFieldId): ?LotItemCustField
    {
        foreach ($this->getLotCustomFields() as $lotCustomField) {
            if ($lotCustomField->Id === $customFieldId) {
                return $lotCustomField;
            }
        }
        return null;
    }
}
