<?php
/**
 * SAM-4770 : Refactor Auction Inc modules
 * https://bidpath.atlassian.net/browse/SAM-4770
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/3/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Shipping\AuctionInc;

use AuctionincStats;
use Invoice;
use InvoiceItem;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Application\Url\Build\Config\Invoice\AnySingleInvoiceUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Invoice\Render\InvoicePureRenderer;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceUserShippingLoaderCreateTrait;
use Sam\Location\Load\LocationLoaderAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionincStats\AuctionincStatsReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionincStats\AuctionincStatsWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class AuctionInc
 * @package Sam\Invoice\Common\Shipping\AuctionInc
 */
class AuctionInc extends CustomizableClass
{
    use AccountLoaderAwareTrait;
    use AuctionBidderLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionincStatsReadRepositoryCreateTrait;
    use AuctionincStatsWriteRepositoryAwareTrait;
    use BlockCipherProviderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use CurrencyLoaderAwareTrait;
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use InvoiceLoaderAwareTrait;
    use InvoiceUserShippingLoaderCreateTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use LocationLoaderAwareTrait;
    use LotCustomDataLoaderCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use SettingsManagerAwareTrait;
    use UrlBuilderAwareTrait;
    use UserLoaderAwareTrait;

    protected string $aucIncAccountId;
    protected string $aucIncMethod;
    protected string $aucIncBusinessId;
    protected string $serviceCode = '';
    protected string $calcMethod;
    protected string $packMethod;
    protected int $accountId;
    protected int $aucIncDhlAccessKey;
    protected int $aucIncDhlPostalCode;
    protected int $detailLevel = 2;
    protected int $aucIncWeightType;
    protected int $aucIncDimensionType;
    protected array $carriers = [];
    protected array $params;

    /**
     * Get instance of AuctionInc
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Initialize AuctionInc object
     * @param int $accountId optional account.id. Uses main account if null
     * @return static|false
     */
    public function construct(int $accountId): static|false
    {
        $this->calcMethod = $this->cfg()->get('core->shipping->auctionInc->calcMethod');
        $this->packMethod = $this->cfg()->get('core->shipping->auctionInc->packMethod');
        $requestorAccountId = $accountId;
        $apiAccountId = $this->getSettingsManager()->get(Constants\Setting::AUC_INC_ACCOUNT_ID, $accountId);
        if (trim($apiAccountId) === '') {
            log_warning(
                'AuctionInc AccountId is not set on sam account'
                . composeSuffix(['acc' => $accountId])
            );

            /* If AuctionInc AccountId is not set for the SAM account, but for the main SAM account,
             * and this account is enabled to use the main SAM account AuctionInc credentials (see 3.),
             * */
            $account = $this->getAccountLoader()->load($accountId);
            if (
                $account
                && $account->Id !== $this->cfg()->get('core->portal->mainAccountId')
            ) {
                if ($account->AuctionIncAllowed) {
                    log_warning(
                        'Account is allowed to use sam main account AuctionInc Account Settings'
                        . composeSuffix(['acc' => $accountId])
                    );
                    $accountId = $this->cfg()->get('core->portal->mainAccountId');
                    $apiAccountId = $this->getSettingsManager()->get(Constants\Setting::AUC_INC_ACCOUNT_ID, $accountId);
                    if (trim($apiAccountId) === '') {
                        log_warning('AuctionInc AccountId is not set on main account');
                        return false;
                    }

                    // Get all carriers enabled for this account
                    $allCarriers = array_keys(Constants\CarrierService::CARRIER_SERVICE_NAMES);
                    $carriers = [];
                    foreach ($allCarriers as $carrier) {
                        $carrierUCFirst = ucfirst(strtolower($carrier));
                        if (
                            $this->getSettingsManager()->get('AucInc' . $carrierUCFirst, $accountId)
                            && $this->getSettingsManager()->get('AucInc' . $carrierUCFirst, $requestorAccountId)
                        ) {
                            $carriers[] = $carrier;
                        }
                    }
                    log_debug('Available carriers: ' . implode(',', $carriers));
                    $this->carriers = $carriers;
                } else {
                    log_warning(
                        'Account is NOT allowed to use sam main account AuctionInc Account Settings'
                        . composeSuffix(['acc' => $accountId])
                    );
                    return false;
                }
            } else {
                return false;
            }
        }

        $this->accountId = $accountId;

        $sm = $this->getSettingsManager();
        $blockCipher = $this->createBlockCipherProvider()->construct();
        $aucIncAccountId = $sm->get(Constants\Setting::AUC_INC_ACCOUNT_ID, $this->accountId);
        $this->aucIncAccountId = $blockCipher->decrypt($aucIncAccountId);
        $aucIncBusinessId = $sm->get(Constants\Setting::AUC_INC_BUSINESS_ID, $this->accountId);
        $this->aucIncBusinessId = $blockCipher->decrypt($aucIncBusinessId);
        $this->aucIncMethod = $sm->get(Constants\Setting::AUC_INC_METHOD, $this->accountId);
        $this->aucIncWeightType = (int)$sm->getForMain(Constants\Setting::AUC_INC_WEIGHT_TYPE);
        $this->aucIncDimensionType = (int)$sm->getForMain(Constants\Setting::AUC_INC_DIMENSION_TYPE);
        $this->aucIncDhlAccessKey = (int)$sm->get(Constants\Setting::AUC_INC_DHL_ACCESS_KEY, $this->accountId);
        $this->aucIncDhlPostalCode = (int)$sm->get(Constants\Setting::AUC_INC_DHL_POSTAL_CODE, $this->accountId);

        $this->params = [];
        $this->params['DestinationAddress'] = [];
        $this->params['OriginAddressList'] = [];
        $this->params['ItemList'] = [];
        $this->params['CarrierList'] = [];
        $this->params['DetailLevel'] = $this->detailLevel;
        $this->params['AccountID'] = $this->aucIncAccountId;

        return $this;
    }

    /**
     * Get shipping rate for a list of items
     *
     * @param Invoice $invoice can be updated and persisted in this scope.
     * @param InvoiceItem[] $invoiceItems
     * @param int $editorUserId
     * @param bool $shouldReturnAll
     * @return array{'ShipRate'?: array<array>, 'ErrorList'?: array<array<string|int>>}
     */
    public function process(Invoice $invoice, array $invoiceItems, int $editorUserId, bool $shouldReturnAll = false): array
    {
        $this->createItemList($invoice, $invoiceItems, $editorUserId);

        if ($this->aucIncMethod === Constants\SettingShippingAuctionInc::AIM_SINGLE_SELLER) {
            $shipRates = $this->getItemShipRateSS();
        } else {
            $shipRates = $this->getItemShipRateXS();
        }
        if (!isset($shipRates['ErrorList'])) {
            $this->countAucIncRequest($this->accountId, $editorUserId);
            if ($shouldReturnAll) {
                return $shipRates;
            }
            $output = $this->responseRates($shipRates);
        } else {
            $output = $shipRates;
        }
        return $output;
    }

    /**
     * @param array $shipRates
     * @return array
     */
    protected function responseRates(array $shipRates): array
    {
        $shipRates['ShipRate'] = (array)($shipRates['ShipRate'] ?? []);
        usort($shipRates['ShipRate'], [$this, 'sortShipRates']);
        $serviceCode = trim($this->serviceCode);
        if ($serviceCode !== '') {
            $count = $this->issetInArray($serviceCode, $shipRates['ShipRate']);
            if ($count === false) {
                $shipRateItems = end($shipRates['ShipRate']);
            } else {
                $shipRateItems = $shipRates['ShipRate'][$count[0]];
            }
        } else {
            $shipRateItems = end($shipRates['ShipRate']);
        }
        return (array)$shipRateItems;
    }

    /**
     * Create list of items from InvoiceItem[]
     *
     * @param Invoice $invoice
     * @param InvoiceItem[] $invoiceItems
     * @param int $editorUserId
     * @return void
     */
    protected function createItemList(Invoice $invoice, array $invoiceItems, int $editorUserId): void
    {
        foreach ($invoiceItems as $invoiceItem) {
            $auction = $this->getAuctionLoader()->load($invoiceItem->AuctionId);
            $currency = $auction ? $this->getCurrencyLoader()->load($auction->Currency, true) : null;
            if (
                $currency
                && $currency->Code
            ) {
                $this->params['Currency'] = $currency->Code;
            } else {
                $this->params['Currency'] = $this->getCurrencyLoader()->loadPrimaryCurrency()->Code;
            }

            $userShipping = $this->getUserLoader()->loadUserShippingOrCreate($invoiceItem->WinningBidderId);
            $this->serviceCode = $userShipping->CarrierMethod;

            $auctionBidder = $this->getAuctionBidderLoader()->load(
                $invoiceItem->WinningBidderId,
                $invoiceItem->AuctionId,
                true
            );
            if (
                $auctionBidder
                && $auctionBidder->CarrierMethod
            ) {
                $this->serviceCode = $auctionBidder->CarrierMethod;
            }
            if ($this->serviceCode === Constants\CarrierService::M_PICKUP) {
                $invoice->ShippingNote = UserPureRenderer::new()
                    ->makeCarrierMethod(Constants\CarrierService::M_PICKUP);
                $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);
            } else {
                $lotItem = $this->getLotItemLoader()->load($invoiceItem->LotItemId);
                if (!$lotItem) {
                    log_error(
                        "Available lot item not found, when creating invoice item list"
                        . composeSuffix(
                            [
                                'li' => $invoiceItem->LotItemId,
                                'ii' => $invoiceItem->Id,
                                'i' => $invoiceItem->InvoiceId,
                            ]
                        )
                    );
                    continue;
                }

                $location = $this->getLocationLoader()->loadCommonOrSpecificLocation(Constants\Location::TYPE_LOT_ITEM, $lotItem, true);
                if (!$location) {
                    $location = $this->getLocationLoader()->loadCommonOrSpecificLocation(Constants\Location::TYPE_AUCTION_INVOICE, $auction, true);
                }

                if ($location !== null) {
                    if ($this->aucIncMethod === Constants\SettingShippingAuctionInc::AIM_UNLIMITED_SELLER) {
                        $originCode = $location->Name;
                        $this->addOriginAddress($location->Country, $location->Zip, $location->State, $originCode);
                    } else {
                        $originCode = '';
                    }
                } else {
                    $originCode = '';
                }

                if ($this->aucIncMethod === Constants\SettingShippingAuctionInc::AIM_UNLIMITED_SELLER) {
                    log_debug('User preferred service ' . $this->serviceCode);
                    $carrierResult = $this->issetInArrayKey(
                        $this->serviceCode,
                        Constants\CarrierService::CARRIER_SERVICE_NAMES
                    );
                    if ($carrierResult !== false) {
                        $carrierCode = $carrierResult[0];
                        $this->addCarrier($carrierCode, 'D');
                        $this->addService($carrierCode, $this->serviceCode);
                        log_debug('Setting carrier ' . $carrierCode . ' service ' . $this->serviceCode);
                    } else {
                        // in case no service preference was selected add all available carriers and services
                        foreach ($this->carriers as $carrierCode) {
                            log_debug('Adding carrier ' . $carrierCode);
                            $this->addCarrier($carrierCode, 'D');
                            foreach (Constants\CarrierService::CARRIER_SERVICE_NAMES[$carrierCode] as $serviceCode => $serviceName) {
                                log_debug('Adding service ' . $serviceName);
                                $this->addService($carrierCode, $serviceCode);
                            }
                        }
                    }
                }

                $invoiceUserShipping = $this->createInvoiceUserShippingLoader()->load($invoiceItem->InvoiceId);
                if ($invoiceUserShipping) {
                    $residential = $userShipping->ContactType === Constants\User::CT_HOME;
                    $this->setDestinationAddress(
                        $invoiceUserShipping->Country,
                        $invoiceUserShipping->Zip,
                        $invoiceUserShipping->State,
                        $residential
                    );
                }

                //conversion of values if custom field type decimal (number of digits)
                $weight = $this->findCustomFieldLotItemFloatMetrics(
                    $invoiceItem->LotItemId,
                    Constants\Setting::AUC_INC_WEIGHT_CUST_FIELD_ID
                );

                $length = $this->findCustomFieldLotItemFloatMetrics(
                    $invoiceItem->LotItemId,
                    Constants\Setting::AUC_INC_LENGTH_CUST_FIELD_ID
                );

                $width = $this->findCustomFieldLotItemFloatMetrics(
                    $invoiceItem->LotItemId,
                    Constants\Setting::AUC_INC_WIDTH_CUST_FIELD_ID
                );

                $height = $this->findCustomFieldLotItemFloatMetrics(
                    $invoiceItem->LotItemId,
                    Constants\Setting::AUC_INC_HEIGHT_CUST_FIELD_ID
                );

                $itemNo = $this->getLotRenderer()->renderItemNo($lotItem);
                $qty = 1;
                $wtUOM = $this->aucIncWeightType;
                $dimUOM = $this->aucIncDimensionType;
                $decVal = $invoiceItem->HammerPrice + $invoiceItem->BuyersPremium;

                $odServices = $this->serviceCode;
                $packMethod = $this->packMethod;

                if ($this->calcMethod === 'C') {
                    $this->addItemCalc(
                        $itemNo,
                        $qty,
                        $weight,
                        $wtUOM,
                        $length,
                        $width,
                        $height,
                        $dimUOM,
                        $decVal,
                        $packMethod
                    );

                    if ($originCode) {
                        $this->addItemOriginCode($originCode);
                    }
                    if ($odServices) {
                        $this->addItemOnDemandServices($odServices);
                    }
                }
            }
        }
    }

    /**
     * @param int $lotItemIdOfInvoiceItem
     * @param string $metricsParameter
     * @return float
     */
    private function findCustomFieldLotItemFloatMetrics(int $lotItemIdOfInvoiceItem, string $metricsParameter): float
    {
        $metricsValue = 0;
        $validMetrics = [
            Constants\Setting::AUC_INC_WEIGHT_CUST_FIELD_ID,
            Constants\Setting::AUC_INC_LENGTH_CUST_FIELD_ID,
            Constants\Setting::AUC_INC_WIDTH_CUST_FIELD_ID,
            Constants\Setting::AUC_INC_HEIGHT_CUST_FIELD_ID
        ];
        if (in_array($metricsParameter, $validMetrics, true)) {
            $metricsParameterId = $this->getSettingsManager()->getForMain($metricsParameter);
            $lotCustomFieldMetricParameter = $metricsParameterId
                ? $this->createLotCustomFieldLoader()->load($metricsParameterId)
                : null;
            if ($lotCustomFieldMetricParameter) {
                $lotCustomDataForMetricParameter = $this->createLotCustomDataLoader()
                    ->load($lotCustomFieldMetricParameter->Id, $lotItemIdOfInvoiceItem, true);
                if ($lotCustomDataForMetricParameter) {
                    if ($lotCustomDataForMetricParameter->Numeric !== null) {
                        $precision = (int)$lotCustomFieldMetricParameter->Parameters;
                        $metricsValue = $lotCustomDataForMetricParameter->calcDecimalValue($precision);
                    }
                }
            }
        }

        return $metricsValue;
    }

    /**
     * Add Origin Code for current Item
     * @param string $code - origin code
     * @access protected
     * @return void
     */
    protected function addItemOriginCode(string $code): void
    {
        $total = count($this->params['ItemList']);
        $current = $total - 1;
        $this->params['ItemList'][$current]['OriginCode'] = $code;
    }

    /**
     * Add Carrier
     * @param string $carrierCode - Carrier Code: UPS | USPS | DHL | FDX
     * @param string $entryPoint - Entry Point: E | D
     * @access protected
     * @return bool
     */
    protected function addCarrier(string $carrierCode, string $entryPoint = 'P'): bool
    {
        $code = strtoupper($carrierCode);
        if (!preg_match("/^(UPS|USPS|DHL|FEDEX)\$/", $code)) {
            return false;
        }

        $this->params['CarrierList'][$code] = [];
        $this->params['CarrierList'][$code]['EntryPoint'] = $entryPoint;

        if (
            $code === 'DHL'
            && (string)$this->aucIncDhlAccessKey !== ''
            && (string)$this->aucIncDhlPostalCode !== ''
        ) {
            $this->params['CarrierList'][$code]['AccessKey'] = $this->aucIncDhlAccessKey;
            $this->params['CarrierList'][$code]['PostalCode'] = $this->aucIncDhlPostalCode;
        }
        return true;
    }

    /**
     * Add Service
     * @param string $carrierCode - Carrier Code: UPS | USPS | DHL | FDX
     * @param string $serviceCode - Service Code
     * @param Bool $onDemand - OnDemand Flag
     * @access protected
     * @return bool
     */
    protected function addService(string $carrierCode, string $serviceCode, bool $onDemand = false): bool
    {
        $code = strtoupper($carrierCode);
        if (!preg_match("/^(UPS|USPS|DHL|FEDEX)\$/", $code)) {
            return false;
        }

        if (isset($this->params['CarrierList'][$code])) {
            if (!isset($this->params['CarrierList'][$code]['ServiceList'])) {
                $this->params['CarrierList'][$code]['ServiceList'] = [];
            }
            $this->params['CarrierList'][$code]['ServiceList'][] = [
                'Code' => strtoupper($serviceCode),
                'OnDemand' => $onDemand,
            ];
        }
        return true;
    }

    /**
     * Add Origin Address
     * @param string $postalCode - Postal Code
     * @param string $stateOrProvinceCode - State or Province Code (2 Letter)
     * @param string $countryCode - Country Code (2 Letter)
     * @param string $originCode - Origin Code
     * @access protected
     * @return void
     */
    protected function addOriginAddress(string $countryCode, string $postalCode, string $stateOrProvinceCode = '', string $originCode = ''): void
    {
        $hash = md5(strtoupper($countryCode) . '-' . strtoupper($postalCode) . '-' . strtoupper($stateOrProvinceCode) . '-' . $originCode);
        $this->params['OriginAddressList'][$hash] = [];
        $this->params['OriginAddressList'][$hash]['PostalCode'] = strtoupper($postalCode);
        $this->params['OriginAddressList'][$hash]['StateOrProvinceCode'] = strtoupper($stateOrProvinceCode);
        $this->params['OriginAddressList'][$hash]['CountryCode'] = strtoupper($countryCode);
        $this->params['OriginAddressList'][$hash]['OriginCode'] = $originCode;
    }

    /**
     * Set Destination Address
     * @param string $countryCode - Country Code (2 Letter)
     * @param string $postalCode - Postal Code
     * @param string $stateOrProvinceCode - State or Province Code (2 Letter)
     * @param Bool $residentialFlag - Residential Flag
     * @access protected
     */
    protected function setDestinationAddress(
        string $countryCode,
        string $postalCode,
        string $stateOrProvinceCode = '',
        bool $residentialFlag = false
    ): void {
        $this->params['DestinationAddress']['PostalCode'] = strtoupper($postalCode);
        $this->params['DestinationAddress']['StateOrProvinceCode'] = strtoupper($stateOrProvinceCode);
        $this->params['DestinationAddress']['CountryCode'] = strtoupper($countryCode);
        $this->params['DestinationAddress']['Residential'] = ($residentialFlag ? 1 : 0);
    }

    /**
     * Add Item with Calc Rates
     * @param string $refCode - Reference Item Code
     * @param Float $qty - Quantity
     * @param Float $weight - Length
     * @param int $wtUOM - Weight Units: Constants\AuctionInc::WEIGHT_UNIT_*
     * @param Float $length - Length
     * @param Float $width - Width
     * @param Float $height - Height
     * @param int $dimUOM - Dimensional Units: Constants\AuctionInc::DIMENSION_UNIT_*
     * @param Float $decVal - Declared Value
     * @param string $packMethod - Packing Method: T | S
     * @param int $lotSize
     * @access protected
     */
    protected function addItemCalc(
        string $refCode,
        float $qty,
        float $weight,
        int $wtUOM,
        float $length,
        float $width,
        float $height,
        int $dimUOM,
        float $decVal,
        string $packMethod,
        int $lotSize = 1
    ): void {
        $total = count($this->params['ItemList']);
        $this->params['ItemList'][$total] = [];
        $this->params['ItemList'][$total]['CalcMethod'] = $this->calcMethod;
        $this->params['ItemList'][$total]['RefCode'] = $refCode;
        $this->params['ItemList'][$total]['Quantity'] = (float)abs($qty);
        $this->params['ItemList'][$total]['LotSize'] = (float)abs($lotSize);
        $this->params['ItemList'][$total]['Length'] = (float)abs($length);
        $this->params['ItemList'][$total]['Width'] = (float)abs($width);
        $this->params['ItemList'][$total]['Height'] = (float)abs($height);
        $this->params['ItemList'][$total]['DimUOM'] = Constants\AuctionInc::DIMENSION_UOM_VALUE[$dimUOM];
        $this->params['ItemList'][$total]['Weight'] = (float)abs($weight);
        $this->params['ItemList'][$total]['WeightUOM'] = Constants\AuctionInc::WEIGHT_UOM_VALUE[$wtUOM];
        $this->params['ItemList'][$total]['DeclaredValue'] = (float)abs($decVal);
        $this->params['ItemList'][$total]['PackMethod'] = (!strcasecmp('S', $packMethod) ? 'S' : 'T');
    }

    /**
     * Enable On Demand Services for current Item
     * @param string $serviceCodeList - carrieer services codes
     * @return void
     * @access protected
     */
    protected function addItemOnDemandServices(string $serviceCodeList): void
    {
        $count = count($this->params['ItemList']);
        $current = $count - 1;
        $this->params['ItemList'][$current]['OnDemandServices'] = [];
        $serviceCodes = explode(",", $serviceCodeList);
        foreach ($serviceCodes as $key => $val) {
            $this->params['ItemList'][$current]['OnDemandServices'][$key] = trim($val);
        }
    }

    /**
     * Generates the GetItemShipRateSS XML and invokes the web service method
     * @return array
     */
    protected function getItemShipRateSS(): array
    {
        $reqXML = $this->makeGetItemShipRateSSXml();
        return $this->getItemShippingRate($reqXML);
    }

    /**
     * Generates the GetItemShipRateXS XML and invokes the web service method
     * @return array
     */
    protected function getItemShipRateXS(): array
    {
        $reqXML = $this->makeGetItemShipRateXSXml();
        return $this->getItemShippingRate($reqXML);
    }

    /**
     * Internal method used by the GetItemShipRate methods which does the actual call out to the web service
     * @param string $reqXML
     * @return array
     */
    protected function getItemShippingRate(string $reqXML): array
    {
        $shippingRateSocketPost = RateSocketPost::new();
        $post = $shippingRateSocketPost->post($reqXML, []);
        if ($post->hasResponseError()) {
            $shipRates = $this->createError(505, $post->getResponseErrorMessage());
            log_debug($post->getResponseErrorMessage());
        } else {
            $post->processResponse();
            $shRtPrsXml = RateParserXmlItem::new();
            $respXML = substr(
                $post->getRespContent(),
                strpos($post->getRespContent(), "<?"),
                strpos($post->getRespContent(), "</Envelope>")
            );
            $shipRates = $shRtPrsXml->parse($respXML);
            if (!$shipRates) {
                return [];
            }
        }
        return $shipRates;
    }

    /**
     * Creates an array that mimics thats generated by the API results
     * @param int $errorCode error #
     * @param string $errorMsg error message
     * @param string $severity Severity (CRITICAL, WARNING, NOTICE)
     * @return array
     */
    protected function createError(int $errorCode, string $errorMsg, string $severity = 'CRITICAL'): array
    {
        $error = ['ErrorList' => []];
        $error['ErrorList'][] = [
            'Code' => $errorCode,
            'Message' => $errorMsg,
            'Severity' => $severity,
        ];
        return $error;
    }

    /**
     * This function is used to create a xml request to Single Seller
     * @return string xml request
     * @access   protected
     */
    protected function makeGetItemShipRateSSXml(): string
    {
        $head = $this->makeXmlHeader();
        $body = '<Body><GetItemShipRateSS version="2.3">' .
            '<Currency>' . $this->params['Currency'] . '</Currency>' .
            '<DetailLevel>' . $this->params['DetailLevel'] . '</DetailLevel>' .
            $this->makeXmlDestination() .
            $this->makeXmlItemList() .
            '</GetItemShipRateSS></Body>';
        return ('<?xml version="1.0" encoding="utf-8" ?><Envelope>' . $head . $body . '</Envelope>');
    }

    /**
     * This function is used to create a xml request to Unlimited Sellers
     * @return string xml request
     * @access   protected
     */
    protected function makeGetItemShipRateXSXml(): string
    {
        $head = $this->makeXmlHeader();
        $body = '<Body><GetItemShipRateXS version="2.3">' .
            '<Currency>' . $this->params['Currency'] . '</Currency>' .
            '<DetailLevel>' . $this->params['DetailLevel'] . '</DetailLevel>' .
            $this->makeXmlDestination() .
            $this->makeXmlOrigin() .
            $this->makeXmlItemList() .
            $this->makeXmlCarrierList() .
            '</GetItemShipRateXS></Body>';
        return ('<?xml version="1.0" encoding="utf-8" ?><Envelope>' . $head . $body . '</Envelope>');
    }

    /**
     * Create a xml header
     * @return string
     * @access   protected
     */
    protected function makeXmlHeader(): string
    {
        $head = '<Header>' .
            "<AccountId>{$this->aucIncAccountId}</AccountId>" .
            (!empty($this->aucIncBusinessId) ? "<RefCode>{$this->aucIncBusinessId}</RefCode>" : '') .
            '</Header>';
        return $head;
    }

    /**
     * Create a xml header
     * @return string
     * @access   protected
     */
    protected function makeXmlDestination(): string
    {
        $xml = '';
        if ($this->params['DestinationAddress']) {
            $xml = '<DestinationAddress>';
            if (isset($this->params['DestinationAddress']['Residential'])) {
                $xml .= '<ResidentialDelivery>' . ($this->params['DestinationAddress']['Residential'] ? 'true'
                        : 'false') . '</ResidentialDelivery>';
            }
            if (isset($this->params['DestinationAddress']['CountryCode'])) {
                $xml .= '<CountryCode>' . $this->params['DestinationAddress']['CountryCode'] . '</CountryCode>';
            }
            if (isset($this->params['DestinationAddress']['StateOrProvinceCode'])) {
                $xml .= '<StateOrProvinceCode>' . $this->params['DestinationAddress']['StateOrProvinceCode'] . '</StateOrProvinceCode>';
            }
            if (isset($this->params['DestinationAddress']['PostalCode'])) {
                $xml .= '<PostalCode>' . $this->params['DestinationAddress']['PostalCode'] . '</PostalCode>';
            }
            $xml .= '</DestinationAddress>';
        }
        return $xml;
    }

    /**
     * Create a xml OriginAddressList to the Unlimited Sellers
     * @return string
     * @access   protected
     */
    protected function makeXmlOrigin(): string
    {
        $xml = '<OriginAddressList>';
        foreach ($this->params['OriginAddressList'] as $i => $origin) {
            $xml .= '<OriginAddress>' .
                '<OriginCode>' . $this->params['OriginAddressList'][$i]['OriginCode'] . '</OriginCode>' .
                '<CountryCode>' . $this->params['OriginAddressList'][$i]['CountryCode'] . '</CountryCode>' .
                '<StateOrProvinceCode>' . $this->params['OriginAddressList'][$i]['StateOrProvinceCode'] . '</StateOrProvinceCode>' .
                '<PostalCode>' . $this->params['OriginAddressList'][$i]['PostalCode'] . '</PostalCode>' .
                '</OriginAddress>';
        }
        $xml .= '</OriginAddressList>';
        return $xml;
    }

    /**
     * Create a xml itemList
     * @return string
     * @access   protected
     */
    protected function makeXmlItemList(): string
    {
        $total = count($this->params['ItemList']);
        $xml = '<ItemList>';
        for ($i = 0; $i < $total; $i++) {
            $xml .= '<Item>' .
                '<RefCode>' . $this->params['ItemList'][$i]['RefCode'] . '</RefCode>' .
                '<Quantity>' . $this->params['ItemList'][$i]['Quantity'] . '</Quantity>' .
                '<CalcMethod code="' . $this->params['ItemList'][$i]['CalcMethod'] . '">';
            if ($this->params['ItemList'][$i]['CalcMethod'] === 'C') {
                $xml .= '<CarrierCalcProps>' .
                    '<Weight>' . $this->params['ItemList'][$i]['Weight'] . '</Weight>' .
                    '<WeightUOM>' . $this->params['ItemList'][$i]['WeightUOM'] . '</WeightUOM>' .
                    '<Length>' . $this->params['ItemList'][$i]['Length'] . '</Length>' .
                    '<Width>' . $this->params['ItemList'][$i]['Width'] . '</Width>' .
                    '<Height>' . $this->params['ItemList'][$i]['Height'] . '</Height>' .
                    '<DimUOM>' . $this->params['ItemList'][$i]['DimUOM'] . '</DimUOM>' .
                    '<DeclaredValue>' . $this->params['ItemList'][$i]['DeclaredValue'] . '</DeclaredValue>' .
                    '<PackMethod>' . $this->params['ItemList'][$i]['PackMethod'] . '</PackMethod>' .
                    (isset($this->params['ItemList'][$i]['LotSize'])
                        ? '<LotSize>' . $this->params['ItemList'][$i]['LotSize'] . '</LotSize>' : '') .
                    (isset($this->params['ItemList'][$i]['OriginCode'])
                        ? '<OriginCode>' . $this->params['ItemList'][$i]['OriginCode'] . '</OriginCode>' : '');

                if (isset($this->params['ItemList'][$i]['OnDemandServices'])) {
                    $totalOnDemand = count($this->params['ItemList'][$i]['OnDemandServices']);
                    if ($totalOnDemand > 0) {
                        $xml .= '<OnDemandServices>';
                        for ($j = 0; $j < $totalOnDemand; $j++) {
                            $xml .= '<ODService>' . $this->params['ItemList'][$i]['OnDemandServices'][$j] . '</ODService>';
                        }
                        $xml .= '</OnDemandServices>';
                    }
                }

                if (isset($this->params['ItemList'][$i]['SpecialServices'])) {
                    $totalSpecialServices = count($this->params['ItemList'][$i]['SpecialServices']);
                    if ($totalSpecialServices > 0) {
                        $xml .= '<SpecialServices>';
                        for ($j = 0; $j < $totalSpecialServices; $j++) {
                            $xml .= '<' . $this->params['ItemList'][$i]['SpecialServices'][$j] . '>TRUE</'
                                . $this->params['ItemList'][$i]['SpecialServices'][$j] . '>';
                        }
                        $xml .= '</SpecialServices>';
                    }
                }
                $xml .= '</CarrierCalcProps>';
            }
            $xml .= '</CalcMethod>';
            $xml .= '</Item>';
        }
        $xml .= '</ItemList>';
        return $xml;
    }

    /**
     * Create a xml CarrierList to the Unlimited Sellers
     * @return string
     * @access   protected
     */
    protected function makeXmlCarrierList(): string
    {
        $xml = '<CarrierList>';
        foreach ($this->params['CarrierList'] as $key => $val) {
            // Make certain that we have both carrier and service codes defined
            if (isset($val['ServiceList']) && count($val['ServiceList']) > 0) {
                $xml .= '<Carrier code="' . $key . '">' .
                    '<EntryPoint>' . $val['EntryPoint'] . '</EntryPoint>';
                if ($key === 'DHL') {
                    $xml .= '<AccessKey>' . $val['AccessKey'] . '</AccessKey>' .
                        '<PostalCode>' . $val['PostalCode'] . '</PostalCode>';
                }

                $xml .= '<ServiceList>';
                for ($i = 0, $c = count($val['ServiceList']); $i < $c; $i++) {
                    $xml .= '<Service code="' . $val['ServiceList'][$i]['Code'] . '">';
                    $xml .= '<PkgMaxWeight></PkgMaxWeight>' .
                        '<PkgMaxLength></PkgMaxLength>' .
                        '<PkgMaxWidth></PkgMaxWidth>' .
                        '<PkgMaxHeight></PkgMaxHeight>';
                    if ($val['ServiceList'][$i]['OnDemand']) {
                        $xml .= '<OnDemand>true</OnDemand>';
                    }
                    $xml .= '</Service>';
                }
                $xml .= '</ServiceList>';
                $xml .= '</Carrier>';
            }
        }
        $xml .= '</CarrierList>';
        return $xml;
    }

    /**
     * @param array $a
     * @param array $b
     * @return int
     */
    public function sortShipRates(array $a, array $b): int
    {
        if ((string)$a['Rate'] === (string)$b['Rate']) {
            return 0;
        }
        return ($a['Rate'] > $b['Rate']) ? -1 : 1;
    }

    /**
     * @param string $search
     * @param array $array
     * @return array|false
     */
    protected function issetInArray(string $search, array $array): array|false
    {
        $inKeys = [];
        foreach ($array as $key => $value) {
            if (in_array($search, $value, true)) {
                $inKeys[] = $key;
            }
        }
        if (count($inKeys) > 0) {
            return $inKeys;
        }

        return false;
    }

    /**
     * @param string $search
     * @param array $array
     * @return array|false
     */
    protected function issetInArrayKey(string $search, array $array): array|false
    {
        $inKeys = [];
        foreach ($array as $key => $value) {
            foreach ($value as $k => $v) {
                if ($search === (string)$k) {
                    $inKeys[] = $key;
                }
            }
        }
        if (count($inKeys) > 0) {
            return $inKeys;
        }

        return false;
    }

    /**
     * Counter of API use for this SAM account and this day (UTC)
     * @param int $accountId
     * @param int $editorUserId
     * @return void
     */
    protected function countAucIncRequest(int $accountId, int $editorUserId): void
    {
        $dateIso = $this->getCurrentDateUtc()->format('Y-m-d');

        $aucIncStats = $this->createAuctionincStatsReadRepository()
            ->filterId($accountId)
            ->filterDate($dateIso)
            ->loadEntity();

        if ($aucIncStats instanceof AuctionincStats) {
            ++$aucIncStats->Count;
            $this->getAuctionincStatsWriteRepository()->saveWithModifier($aucIncStats, $editorUserId);
        } else {
            $aucIncStats = $this->createEntityFactory()->auctionincStats();
            $aucIncStats->AccountId = $accountId;
            $aucIncStats->Date = $this->getCurrentDateUtc();
            $aucIncStats->Count = 1;
            $this->getAuctionincStatsWriteRepository()->saveWithModifier($aucIncStats, $editorUserId);
        }
    }

    /**
     * @param InvoiceItem[] $invoiceItems
     * @param string $errorMessage
     * @return string
     */
    public function getError(array $invoiceItems, string $errorMessage): string
    {
        $err = '';
        $a = explode('[', $errorMessage);
        $urlBuilder = $this->getUrlBuilder();
        $url = $urlBuilder->build(
            AnySingleInvoiceUrlConfig::new()->forTemplateByType(Constants\Url::A_INVOICES_EDIT)
        );
        $template = '<a href="' . $url . '">#%s</a> %s';

        if (!isset($a[1])) {
            if ('error: Missing or malformed DestinationAddress.CountryCode' === $errorMessage) {
                $errorMessage = "Missing  destination address";
                $invoiceItem = $invoiceItems[0] ?? null;
                if ($invoiceItem) {
                    $invoice = $this->getInvoiceLoader()->load($invoiceItem->InvoiceId);
                    $invoiceNo = $invoice ? InvoicePureRenderer::new()->makeInvoiceNo($invoice->InvoiceNo) : '';
                    $err = sprintf($template, $invoiceItem->InvoiceId, $invoiceNo, $errorMessage);
                }
            } else {
                $err = $errorMessage;
            }
        } else {
            $errorMessages = explode(',', $errorMessage);
            [$invoiceItemId,] = explode(']', $a[1]);

            $invoiceItem = $invoiceItems[$invoiceItemId] ?? null;
            if ($invoiceItem) {
                $invoice = $this->getInvoiceLoader()->load($invoiceItem->InvoiceId);
                $invoiceNo = $invoice ? InvoicePureRenderer::new()->makeInvoiceNo($invoice->InvoiceNo) : '';
                if ('error: Missing or invalid weight specified' === $errorMessages[0]) {
                    $errorMessage = 'Weight missing';
                    $err = sprintf($template, $invoiceItem->InvoiceId, $invoiceNo, $errorMessage);
                } else {
                    $url = $urlBuilder->build(
                        AnySingleInvoiceUrlConfig::new()->forWeb(
                            Constants\Url::A_INVOICES_EDIT,
                            $invoiceItem->InvoiceId
                        )
                    );
                    $err = ' ' . $errorMessages[0] . ' <i><a href="' . $url . '" >#'
                        . $invoiceNo . ' ' . $invoiceItem->LotName . '</a></i>';
                }
            }
        }

        return $err;
    }

}
