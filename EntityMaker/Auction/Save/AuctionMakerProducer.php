<?php
/**
 * Class for producing of Auction entity
 *
 * SAM-8840: Auction entity-maker module structural adjustments for v3-5
 * SAM-4241 Auction Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 4, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Save;

use Auction;
use DateTime;
use DateTimeZone;
use Exception;
use InvalidArgumentException;
use Sam\Auction\AuctionHelperAwareTrait;
use Sam\Auction\Date\AuctionEndDateDetectorCreateTrait;
use Sam\Auction\Image\Save\AuctionImageProducerCreateTrait;
use Sam\Auction\Load\AuctionDynamicLoaderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\SaleNo\Parse\SaleNoParserCreateTrait;
use Sam\Auction\SaleNo\SaleNoAdviserAwareTrait;
use Sam\AuctionLot\Date\AuctionLotDatesUpdaterCreateTrait;
use Sam\AuctionLot\Order\Reorder\AuctionLotReordererAwareTrait;
use Sam\Bidding\BidIncrement\Delete\BidIncrementDeleterAwareTrait;
use Sam\Bidding\BidIncrement\Load\BidIncrementLoaderAwareTrait;
use Sam\Bidding\BidIncrement\Save\BidIncrementProducerAwareTrait;
use Sam\Bidding\CurrentBid\HighBidDetectorCreateTrait;
use Sam\BuyersPremium\Load\BuyersPremiumLoaderCreateTrait;
use Sam\Consignor\Commission\Edit\Dto\ConsignorCommissionFeeRelatedEntityDto;
use Sam\Consignor\Commission\Edit\Save\ConsignorCommissionFeeRelatedEntityProducerCreateTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Currency\Save\AuctionCurrencyProducerAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\EntityMaker\Auction\Common\AuctionMakerCustomFieldManager;
use Sam\EntityMaker\Auction\Dto\AuctionMakerConfigDto;
use Sam\EntityMaker\Auction\Dto\AuctionMakerDtoHelperAwareTrait;
use Sam\EntityMaker\Auction\Dto\AuctionMakerInputDto;
use Sam\EntityMaker\Auction\Init\AuctionMakerDefaultsInitializer;
use Sam\EntityMaker\Auction\Save\Internal\BuyersPremium\BuyersPremiumSaverCreateTrait;
use Sam\EntityMaker\Auction\Save\Internal\BuyersPremium\BuyersPremiumSavingInput;
use Sam\EntityMaker\Base\Common\CustomFieldManagerAwareTrait;
use Sam\EntityMaker\Base\Common\ValueResolver;
use Sam\EntityMaker\Base\Save\BaseMakerProducer;
use Sam\EntityMaker\Base\Save\Internal\EntitySync\EntitySyncSavingIntegratorCreateTrait;
use Sam\Location\Load\LocationLoaderAwareTrait;
use Sam\Rtb\Increment\Validate\AdvancedClerkingIncrementExistenceCheckerCreateTrait;
use Sam\Rtb\Pool\Auction\Save\AuctionRtbdUpdaterAwareTrait;
use Sam\Rtb\Pool\Discovery\Search\AuctionRtbdAdviserCreateTrait;
use Sam\Rtb\Pool\Feature\RtbdPoolFeatureAvailabilityValidatorAwareTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\WebClient\AuctionStateResyncerAwareTrait;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionDynamic\AuctionDynamicWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrentIncrement\RtbCurrentIncrementWriteRepositoryAwareTrait;
use Sam\Tax\SamTaxCountryState\Delete\SamTaxCountryStateDeleterCreateTrait;
use Sam\Tax\SamTaxCountryState\Load\SamTaxCountryStateLoaderCreateTrait;
use Sam\Tax\SamTaxCountryState\Save\SamTaxCountryStateProducerCreateTrait;

/**
 * @method AuctionMakerInputDto getInputDto()
 * @method AuctionMakerConfigDto getConfigDto()
 */
class AuctionMakerProducer extends BaseMakerProducer
{
    use AdvancedClerkingIncrementExistenceCheckerCreateTrait;
    use AuctionCurrencyProducerAwareTrait;
    use AuctionDynamicLoaderAwareTrait;
    use AuctionDynamicWriteRepositoryAwareTrait;
    use AuctionEndDateDetectorCreateTrait;
    use AuctionHelperAwareTrait;
    use AuctionImageProducerCreateTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotDatesUpdaterCreateTrait;
    use AuctionLotReordererAwareTrait;
    use AuctionMakerDtoHelperAwareTrait;
    use AuctionRtbdAdviserCreateTrait;
    use AuctionRtbdUpdaterAwareTrait;
    use AuctionStateResyncerAwareTrait;
    use AuctionWriteRepositoryAwareTrait;
    use BidIncrementDeleterAwareTrait;
    use BidIncrementLoaderAwareTrait;
    use BidIncrementProducerAwareTrait;
    use BuyersPremiumLoaderCreateTrait;
    use BuyersPremiumSaverCreateTrait;
    use ConsignorCommissionFeeRelatedEntityProducerCreateTrait;
    use CurrencyLoaderAwareTrait;
    use CurrentDateTrait;
    use CustomFieldManagerAwareTrait;
    use DbConnectionTrait;
    use EntityFactoryCreateTrait;
    use EntitySyncSavingIntegratorCreateTrait;
    use HighBidDetectorCreateTrait;
    use LocationLoaderAwareTrait;
    use RtbCurrentIncrementWriteRepositoryAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use RtbdPoolFeatureAvailabilityValidatorAwareTrait;
    use SaleNoAdviserAwareTrait;
    use SaleNoParserCreateTrait;
    use SamTaxCountryStateDeleterCreateTrait;
    use SamTaxCountryStateLoaderCreateTrait;
    use SamTaxCountryStateProducerCreateTrait;

    /** @var string[] */
    protected const ORDER_FIELDS = [
        'ConcatenateLotOrderColumns',
        'LotOrderPrimaryType',
        'LotOrderPrimaryCustFieldId',
        'LotOrderPrimaryIgnoreStopWords',
        'LotOrderSecondaryType',
        'LotOrderSecondaryCustFieldId',
        'LotOrderSecondaryIgnoreStopWords',
        'LotOrderTertiaryType',
        'LotOrderTertiaryCustFieldId',
        'LotOrderTertiaryIgnoreStopWords',
        'LotOrderQuaternaryType',
        'LotOrderQuaternaryCustFieldId',
        'LotOrderQuaternaryIgnoreStopWords',
    ];

    protected ?Auction $auction = null;
    protected ?Auction $sourceAuction = null;
    /**
     * Effective field that has influence to decision-making
     * @var string
     */
    protected string $auctionType = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AuctionMakerInputDto $inputDto
     * @param AuctionMakerConfigDto $configDto
     * @return $this
     */
    public function construct(
        AuctionMakerInputDto $inputDto,
        AuctionMakerConfigDto $configDto
    ): static {
        $this->setInputDto($inputDto);
        $this->setConfigDto($configDto);
        $this->customFieldManager = AuctionMakerCustomFieldManager::new()->construct($inputDto, $configDto);
        $this->getAuctionMakerDtoHelper()->construct($configDto->mode);
        /**
         * IK, 2022-08-29: $this->initEffectiveFields(); must be called in produce(), because we must keep construct() without expensive DB calls.
         * Now it is placed in construct(), because $this->auctionType is used in assignValues() that is called in unit test.
         * assignValues() must not be public and must not be called in unit test directly. Its logic should be verified indirectly by tests of produce().
         */
        $this->initEffectiveFields();
        return $this;
    }

    /**
     * Produce the Auction entity
     * @return static
     */
    public function produce(): static
    {
        $this->assertInputDto();
        $inputDto = $this->getAuctionMakerDtoHelper()->prepareValues($this->getInputDto(), $this->getConfigDto());
        $this->setInputDto($inputDto);

        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();

        $isNew = !$inputDto->id;

        if ($isNew) {
            // Set defaults values for new auction
            $this->auction = AuctionMakerDefaultsInitializer::new()
                ->construct()
                ->create($configDto->serviceAccountId, $this->auctionType);
        }
        $this->assignValues();
        $this->atomicSave();
        return $this;
    }

    /**
     * Atomic persist data.
     * @throws Exception
     */
    protected function atomicSave(): void
    {
        $this->transactionBegin();
        try {
            $this->save();
        } catch (Exception $e) {
            log_errorBackTrace("Rollback transaction, because auction save failed.");
            $this->transactionRollback();
            throw $e;
        }
        $this->transactionCommit();
    }

    /**
     * Persist data.
     */
    protected function save(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $this->getAuctionWriteRepository()->saveWithModifier($this->auction, $configDto->editorUserId);
        $isNew = !$inputDto->id;
        if ($isNew) {
            $this->doPostCreate();
        } else {
            $this->doPostUpdate();
        }
    }

    /**
     * Get Auction
     * @return Auction
     */
    public function getAuction(): Auction
    {
        if ($this->auction === null) {
            $this->auction = $this->loadAuctionOrCreate();
        }
        return $this->auction;
    }

    /**
     * Load or create Auction depending on the Auction Id
     * @return Auction
     */
    protected function loadAuctionOrCreate(): Auction
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $auctionId = (int)$inputDto->id;
        if ($auctionId) {
            $auction = $this->getAuctionLoader()->load($auctionId);
            if (!$auction) {
                throw new InvalidArgumentException('Cannot load Auction' . composeSuffix(['id' => $inputDto->id]));
            }
        } else {
            $auction = AuctionMakerDefaultsInitializer::new()
                ->construct()
                ->create($configDto->serviceAccountId);
        }
        return $auction;
    }

    /**
     * Run necessary actions after Auction was updated
     */
    public function doPostCreate(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $inputDto->id = $this->getAuction()->Id;

        if (isset($inputDto->additionalCurrencies)) {
            $this->getAuctionCurrencyProducer()->create(
                (array)$inputDto->additionalCurrencies,
                $this->auction->Id,
                $this->auction->Currency,
                $configDto->editorUserId
            );
        }

        $this->saveBuyersPremium(true);

        $this->customFieldManager
            ->setInputDto($inputDto)
            ->save();

        if ($inputDto->image) {
            $this->createAuctionImageProducer()->save(
                $inputDto->image,
                $this->auction->Id,
                $configDto->serviceAccountId,
                $configDto->editorUserId
            );
        }

        if (isset($inputDto->increments)) {
            $this->getBidIncrementProducer()->bulkCreate(
                $inputDto->increments,
                $configDto->serviceAccountId,
                $configDto->editorUserId,
                $this->auction->Id
            );
        }

        $this->createEntitySyncSavingIntegrator()->create($this);

        if (AuctionStatusPureChecker::new()->isLiveOrHybrid($this->auctionType)) {
            $this->createRtbIncrements();
        }

        $this->updateLocations();
        $this->updateConsignorCommissionFee();
        $this->createAuctionRtbd();
        $this->updateTaxStates();
    }

    /**
     * Run necessary actions after Auction was created
     */
    public function doPostUpdate(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();

        $this->saveBuyersPremium();

        if ($inputDto->additionalCurrencies !== null) {
            $this->getAuctionCurrencyProducer()->update(
                (array)$inputDto->additionalCurrencies,
                $this->auction->Id,
                $this->auction->Currency,
                $configDto->editorUserId
            );
        }

        $this->customFieldManager
            ->setInputDto($inputDto)
            ->save();
        if ($inputDto->image) {
            $this->createAuctionImageProducer()->save(
                $inputDto->image,
                $this->auction->Id,
                $configDto->serviceAccountId,
                $configDto->editorUserId
            );
        }

        if ($inputDto->increments !== null) {
            $this->getBidIncrementProducer()->bulkUpdate(
                $inputDto->increments,
                $configDto->serviceAccountId,
                $configDto->editorUserId,
                $configDto->editorUserId,
                $this->auction->Id
            );
        }

        $this->createEntitySyncSavingIntegrator()->update($this);

        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isLive($this->auctionType)) {
            if (
                $inputDto->clerkingStyle === Constants\Auction::$clerkingStyleNames[Constants\Auction::CS_ADVANCED]
                || $auctionStatusPureChecker->isAdvancedClerking($inputDto->clerkingStyleId)
            ) {
                $this->getBidIncrementDeleter()->deleteForAuction($this->auction->Id);
            }
            if ($this->isModified('ClerkingStyle')) {
                $this->updateRtbCurrent();
            }
        }

        if ($this->needReorderLots()) {
            $this->getAuctionLotReorderer()->reorder($this->getAuction(), $configDto->editorUserId);
        }
        $this->updateLocations();
        $this->updateLotsDates();
        $this->updateAuctionRtbd();
        $this->updateAuctionDynamic();
        $this->updateConsignorCommissionFee();
        $this->updateTaxStates();
    }

    /**
     * Assign Auction values from Dto object
     */
    public function assignValues(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();

        $auction = $this->getAuction();
        $this->sourceAuction = clone $auction;

        if ($auction->Id) {
            if (!isset($inputDto->timezone)) {
                $timezone = $this->getTimezoneLoader()->load($auction->TimezoneId);
                if (!$timezone) {
                    log_error('Invalid timezone ' . composeSuffix(['a' => $auction->Id, 'TimezoneId' => $auction->TimezoneId]));
                }
                $inputDto->timezone = $timezone->Location ?? 'UTC';
            }
        }

        $this->setIfAssign($auction, 'auctionAuctioneerId');
        $this->setIfAssign($auction, 'auctionCatalogAccess');
        $this->setIfAssign($auction, 'auctionInfoAccess');
        $this->setIfAssign($auction, 'auctionInfoLink');
        $this->setIfAssign($auction, 'auctionStatusId');
        $this->setIfAssign($auction, 'auctionType');
        $this->setIfAssign($auction, 'auctionVisibilityAccess');
        $this->setIfAssign($auction, 'authorizationAmount', self::STRATEGY_REMOVE_FORMAT);
        $this->setIfAssign($auction, 'autoPopulateEmptyLotNum', self::STRATEGY_BOOL);
        $this->setIfAssign($auction, 'autoPopulateLotFromCategory', self::STRATEGY_BOOL);
        $this->setIfAssign($auction, 'biddingPaused', self::STRATEGY_BOOL);
        $this->setIfAssign($auction, 'blacklistPhrase');
        $this->setIfAssign($auction, 'bpRangeCalculation');
        $this->setIfAssign($auction, 'bpTaxSchemaId');
        $this->setIfAssign($auction, 'ccThresholdDomestic', self::STRATEGY_REMOVE_FORMAT);
        $this->setIfAssign($auction, 'ccThresholdInternational', self::STRATEGY_REMOVE_FORMAT);
        $this->setIfAssign($auction, 'concatenateLotOrderColumns', self::STRATEGY_BOOL);
        $this->setIfAssign($auction, 'defaultLotPostalCode');
        $this->setIfAssign($auction, 'description');
        $this->setIfAssign($auction, 'email');
        $this->setIfAssign($auction, 'eventId');
        $this->setIfAssign($auction, 'eventLocationId');
        $this->setIfAssign($auction, 'excludeClosedLots', self::STRATEGY_BOOL);
        $this->setIfAssign($auction, 'hpTaxSchemaId');
        $this->setIfAssign($auction, 'fbOgDescription');
        $this->setIfAssign($auction, 'fbOgImageUrl');
        $this->setIfAssign($auction, 'fbOgTitle');
        $this->setIfAssign($auction, 'hideUnsoldLots', self::STRATEGY_BOOL);
        $this->setIfAssign($auction, 'invoiceNotes');
        $this->setIfAssign($auction, 'liveViewAccess');
        $this->setIfAssign($auction, 'invoiceLocationId');
        $this->setIfAssign($auction, 'lotBiddingHistoryAccess');
        $this->setIfAssign($auction, 'lotBiddingInfoAccess');
        $this->setIfAssign($auction, 'lotDetailsAccess');
        $this->setIfAssign($auction, 'lotOrderPrimaryCustFieldId');
        $this->setIfAssign($auction, 'lotOrderPrimaryType');
        $this->setIfAssign($auction, 'lotOrderQuaternaryCustFieldId');
        $this->setIfAssign($auction, 'lotOrderQuaternaryType');
        $this->setIfAssign($auction, 'lotOrderSecondaryCustFieldId');
        $this->setIfAssign($auction, 'lotOrderSecondaryType');
        $this->setIfAssign($auction, 'lotOrderTertiaryCustFieldId');
        $this->setIfAssign($auction, 'lotOrderTertiaryType');
        $this->setIfAssign($auction, 'lotStartingBidAccess');
        $this->setIfAssign($auction, 'lotWinningBidAccess');
        $this->setIfAssign($auction, 'manualBidderApprovalOnly', self::STRATEGY_BOOL);
        $this->setIfAssign($auction, 'name');
        $this->setIfAssign($auction, 'paymentTrackingCode');
        $this->setIfAssign($auction, 'requireLotChangeConfirmation', self::STRATEGY_BOOL);
        $this->setIfAssign($auction, 'servicesTaxSchemaId');
        $this->setIfAssign($auction, 'saleGroup');
        $this->setIfAssign($auction, 'seoMetaDescription');
        $this->setIfAssign($auction, 'seoMetaKeywords');
        $this->setIfAssign($auction, 'seoMetaTitle');
        $this->setIfAssign($auction, 'shippingInfo');
        $this->setIfAssign($auction, 'simultaneous', self::STRATEGY_BOOL);
        $this->setIfAssign($auction, 'taxPercent', self::STRATEGY_REMOVE_FORMAT);
        $this->setIfAssign($auction, 'termsAndConditions');
        $this->setIfAssign($auction, 'textMsgNotification');
        $this->setIfAssign($auction, 'wavebidAuctionGuid');
        if (isset($inputDto->auctionHeldIn)) {
            $auction->AuctionHeldIn = AddressRenderer::new()->normalizeCountry($inputDto->auctionHeldIn);
        }
        if (isset($inputDto->taxDefaultCountry)) {
            $auction->TaxDefaultCountry = AddressRenderer::new()->normalizeCountry($inputDto->taxDefaultCountry);
        }

        // Not editable
        if (!$auction->Id) {
            $this->setIfAssign($auction, 'listingOnly', self::STRATEGY_BOOL);
            $this->setIfAssign($auction, 'testAuction', self::STRATEGY_BOOL);
        }

        if (isset($inputDto->auctionStatus)) {
            $auction->AuctionStatusId = array_search(
                $inputDto->auctionStatus,
                Constants\Auction::$auctionStatusNames,
                true
            );
        }
        if (isset($inputDto->bpRule)) {
            $bpRule = $this->createBuyersPremiumLoader()->loadNotDefault($inputDto->bpRule, $configDto->serviceAccountId);
            $auction->BuyersPremiumId = $bpRule->Id ?? null;
        }
        if (isset($inputDto->bpRuleId)) {
            $auction->BuyersPremiumId = Cast::toInt($inputDto->bpRuleId);
        }
        if (isset($inputDto->currency)) {
            $currency = $this->getCurrencyLoader()->loadByName($inputDto->currency);
            $auction->Currency = $currency->Id ?? null;
        }
        if (isset($inputDto->currencyId)) {
            $auction->Currency = Cast::toInt($inputDto->currencyId);
        }
        if (isset($inputDto->invoiceLocation)) {
            $location = $this->getLocationLoader()->loadByName($inputDto->invoiceLocation, $configDto->serviceAccountId);
            $auction->InvoiceLocationId = $location->Id ?? null;
        }
        if (isset($inputDto->eventLocation)) {
            $location = $this->getLocationLoader()->loadByName($inputDto->eventLocation, $configDto->serviceAccountId);
            $auction->EventLocationId = $location->Id ?? null;
        }

        //TODO: IM: Remove deprecated
        $this->setIfAssign($auction, 'locationId', self::STRATEGY_SPECIFIC_NAME, 'invoiceLocationId');
        if (isset($inputDto->location)) {
            $location = $this->getLocationLoader()->loadByName($inputDto->location, $configDto->serviceAccountId);
            $auction->InvoiceLocationId = $location->Id ?? null;
        }

        $this->setIfAssign($auction, 'lotOrderPrimaryCustField', self::STRATEGY_LOT_ITEM_CUSTOM_FIELD);
        $this->setIfAssign($auction, 'lotOrderSecondaryCustField', self::STRATEGY_LOT_ITEM_CUSTOM_FIELD);
        $this->setIfAssign($auction, 'lotOrderTertiaryCustField', self::STRATEGY_LOT_ITEM_CUSTOM_FIELD);
        $this->setIfAssign($auction, 'lotOrderQuaternaryCustField', self::STRATEGY_LOT_ITEM_CUSTOM_FIELD);
        $this->setIfAssign($auction, 'lotOrderPrimaryIgnoreStopWords', self::STRATEGY_BOOL);
        $this->setIfAssign($auction, 'lotOrderSecondaryIgnoreStopWords', self::STRATEGY_BOOL);
        $this->setIfAssign($auction, 'lotOrderTertiaryIgnoreStopWords', self::STRATEGY_BOOL);
        $this->setIfAssign($auction, 'lotOrderQuaternaryIgnoreStopWords', self::STRATEGY_BOOL);
        $this->setIfAssign($auction, 'maxOutstanding', self::STRATEGY_REMOVE_FORMAT);
        $this->setIfAssign($auction, 'postAucImportPremium', self::STRATEGY_REMOVE_FORMAT);
        if ($this->cfg()->get('core->auction->saleNo->concatenated')) {
            if (isset($inputDto->saleFullNo)) {
                [$auction->SaleNum, $auction->SaleNumExt] = $this->createSaleNoParser()
                    ->construct()
                    ->parse($inputDto->saleFullNo)
                    ->toArray();
            }
        } else {
            $this->setIfAssign($auction, 'saleNum');
            $this->setIfAssign($auction, 'saleNumExt');
        }
        if (!$auction->SaleNum) {
            $auction->SaleNum = $this->getSaleNoAdviser()->suggest($configDto->serviceAccountId);
        }
        if (!$this->isAuctionTimedOngoing()) {
            $this->setIfAssign($auction, 'startClosingDate', self::STRATEGY_DATE_TIME_CONVERT_TO_UTC, 'timezone');
        }
        if (isset($inputDto->timezone)) {
            $auction->TimezoneId = $this->getTimezoneLoader()->loadByLocationOrCreatePersisted($inputDto->timezone)->Id;
        }
        $this->setIfAssign($auction, 'publishDate', self::STRATEGY_DATE_TIME_CONVERT_TO_UTC, 'timezone');
        $this->setIfAssign($auction, 'unpublishDate', self::STRATEGY_DATE_TIME_CONVERT_TO_UTC, 'timezone');
        $this->setIfAssign($auction, 'startRegisterDate', self::STRATEGY_DATE_TIME_CONVERT_TO_UTC, 'timezone');
        $this->setIfAssign($auction, 'endRegisterDate', self::STRATEGY_DATE_TIME_CONVERT_TO_UTC, 'timezone');
        $this->setIfAssign($auction, 'startBiddingDate', self::STRATEGY_DATE_TIME_CONVERT_TO_UTC, 'timezone');
        if (AuctionStatusPureChecker::new()->isHybrid($this->auctionType)) {
            $this->setIfAssign($auction, 'allowBiddingDuringStartGap', self::STRATEGY_BOOL);
            $this->setIfAssign($auction, 'extendTime');
            $this->setIfAssign($auction, 'lotStartGapTime');
            $auction->toSimpleClerking();
        }
        if (AuctionStatusPureChecker::new()->isLive($this->auctionType)) {
            $this->setIfAssign(
                $auction,
                'clerkingStyle',
                self::STRATEGY_ARRAY_SEARCH,
                Constants\Auction::$clerkingStyleNames
            );
            if (isset($inputDto->clerkingStyleId)) {
                $auction->ClerkingStyle = $inputDto->clerkingStyleId;
            }
            if (isset($inputDto->liveEndDate)) {
                $timezone = $inputDto->timezone;
                $liveEndDate = $inputDto->liveEndDate;
                $auction->EndDate = new DateTime($liveEndDate, new DateTimeZone($timezone));
                $auction->EndDate = $auction->EndDate->setTimezone(new DateTimeZone('UTC'));
            }
        }
        if (AuctionStatusPureChecker::new()->isLiveOrHybrid($this->auctionType)) {
            $this->setIfAssign($auction, 'aboveReserve', self::STRATEGY_BOOL);
            $this->setIfAssign($auction, 'aboveStartingBid', self::STRATEGY_BOOL);
            $this->setIfAssign($auction, 'additionalBpInternet', self::STRATEGY_REMOVE_FORMAT);
            $this->setIfAssign($auction, 'absenteeBidsDisplay', self::STRATEGY_ARRAY_SEARCH, Constants\SettingAuction::ABSENTEE_BID_DISPLAY_SOAP_VALUES);
            $this->setIfAssign($auction, 'biddingConsoleAccessDate', self::STRATEGY_DATE_TIME_CONVERT_TO_UTC, 'timezone');
            $this->setIfAssign($auction, 'endPrebiddingDate', self::STRATEGY_DATE_TIME_CONVERT_TO_UTC, 'timezone');
            $this->setIfAssign($auction, 'noLowerMaxbid', self::STRATEGY_BOOL);
            $this->setIfAssign($auction, 'notifyAbsenteeBidders', self::STRATEGY_BOOL);
            $this->setIfAssign($auction, 'notifyXLots');
            $this->setIfAssign($auction, 'parcelChoice', self::STRATEGY_BOOL);
            $this->setIfAssign($auction, 'streamDisplay', self::STRATEGY_ARRAY_SEARCH, $this->getAuctionHelper()->getStreamDisplayNames());
            $this->setIfAssign($auction, 'streamDisplayValue', self::STRATEGY_SPECIFIC_NAME, 'streamDisplay');
            $this->setIfAssign($auction, 'suggestedStartingBid', self::STRATEGY_BOOL);
        }
        if (AuctionStatusPureChecker::new()->isTimed($this->auctionType)) {
            $this->setIfAssign($auction, 'allowForceBid', self::STRATEGY_BOOL);
            $this->setIfAssign($auction, 'defaultLotPeriod');
            $this->setIfAssign($auction, 'eventType', self::STRATEGY_ARRAY_SEARCH, Constants\Auction::$eventTypeNames);
            $this->setIfAssign($auction, 'extendAll', self::STRATEGY_BOOL);
            $this->setIfAssign($auction, 'extendFromCurrentTime', self::STRATEGY_BOOL);
            $this->setIfAssign($auction, 'extendTime');
            $this->setIfAssign($auction, 'lotsPerInterval');
            $this->setIfAssign($auction, 'nextBidButton', self::STRATEGY_BOOL);
            $this->setIfAssign($auction, 'notifyXMinutes');
            $this->setIfAssign($auction, 'notShowUpcomingLots', self::STRATEGY_BOOL);
            $this->setIfAssign($auction, 'onlyOngoingLots', self::STRATEGY_BOOL);
            $this->setIfAssign($auction, 'reverse', self::STRATEGY_BOOL);
            $this->setIfAssign($auction, 'staggerClosing');
            $this->setIfAssign($auction, 'takeMaxBidsUnderReserve', self::STRATEGY_BOOL);

            if (isset($inputDto->eventTypeId)) {
                $auction->EventType = Cast::toInt($inputDto->eventTypeId);
            }
            if ($auction->EventType === null) {
                $auction->toTimedScheduled();
            }
            if ($this->isAuctionTimedScheduled()) {
                $this->setIfAssign($auction, 'dateAssignmentStrategy');
                if ($auction->ExtendAll) {
                    $auction->toAuctionToItemsDateAssignment();
                }
                if (
                    $auction->isAuctionToItemsDateAssignment()
                    && ValueResolver::new()->isTrue($inputDto->updateAuctionItemDate)
                ) {
                    $this->createAuctionLotDatesUpdater()->update($auction->Id, $configDto->editorUserId);
                }
            }
            if ($this->isAuctionTimedOngoing()) {
                $auction->toStarted();
            }
        }
        if ($this->cfg()->get('core->lot->reserveNotice->enabled')) {
            $this->setIfAssign($auction, 'reserveNotMetNotice', self::STRATEGY_BOOL);
            $this->setIfAssign($auction, 'reserveMetNotice', self::STRATEGY_BOOL);
        }

        // Published backward compatibility
        if (
            $inputDto->published !== null
            && $inputDto->publishDate === null
        ) {
            if (ValueResolver::new()->isTrue($inputDto->published)) {
                $auction->PublishDate = $this->getCurrentDateUtc();
            } else {
                $auction->PublishDate = null;
            }
        }

        if (
            !$auction->isClosed()
            && !$auction->isArchived()
            && $this->isEndDateRelatedFieldModified($auction)
        ) {
            $auction->EndDate = $this->createAuctionEndDateDetector()->detect($auction);
        }
    }

    /**
     * @param bool $isCreate false - when creating new entity, true - when updating existing one.
     */
    protected function saveBuyersPremium(bool $isCreate = false): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $buyersPremiumSavingInput = BuyersPremiumSavingInput::new()->construct(
            $configDto->mode,
            $inputDto->buyersPremiumString,
            $inputDto->buyersPremiumDataRows,
            $configDto->editorUserId,
            $this->auction->Id,
            $this->auction->AccountId
        );
        $this->createBuyersPremiumSaver()->save($buyersPremiumSavingInput, $isCreate);
    }

    /**
     * Create rtb_current_increment records
     */
    protected function createRtbIncrements(): void
    {
        $configDto = $this->getConfigDto();
        $bidIncrements = $this->getBidIncrementLoader()
            ->loadAll($configDto->serviceAccountId, $this->getAuction()->AuctionType);
        foreach ($bidIncrements as $bidIncrement) {
            if (!$this->createAdvancedClerkingIncrementExistenceChecker()
                ->existInAuctionByIncrement($this->getAuction()->Id, $bidIncrement->Increment)
            ) {
                $rtbCurrentIncrement = $this->createEntityFactory()->rtbCurrentIncrement();
                $rtbCurrentIncrement->AuctionId = $this->getAuction()->Id;
                $rtbCurrentIncrement->Increment = $bidIncrement->Increment;
                $this->getRtbCurrentIncrementWriteRepository()->saveWithModifier($rtbCurrentIncrement, $configDto->editorUserId);
            }
        }
    }

    /**
     * @param string $field
     * @return bool
     */
    protected function isModified(string $field): bool
    {
        if (
            $this->sourceAuction->{$field} instanceof DateTime
            || $this->auction->{$field} instanceof DateTime
        ) {
            $isModified = $this->sourceAuction->{$field} != $this->auction->{$field};
        } else {
            $isModified = $this->sourceAuction->{$field} !== $this->auction->{$field};
        }
        return $isModified;
    }

    /**
     * Need reorder lots if order fields have changed
     * @return bool
     */
    protected function needReorderLots(): bool
    {
        foreach (self::ORDER_FIELDS as $orderField) {
            if ($this->isModified($orderField)) {
                return true;
            }
        }
        return false;
    }

    protected function updateLocations(): void
    {
        $inputDto = $this->getInputDto();

        if (isset($inputDto->specificEventLocation)) {
            $eventLocation = $this->createLocationSavingIntegrator()->save($this, $inputDto->specificEventLocation, Constants\Location::TYPE_AUCTION_EVENT);
            $this->setEntityLocation(Constants\Location::TYPE_AUCTION_EVENT, $eventLocation);
        }

        if (isset($inputDto->specificInvoiceLocation)) {
            $invoiceLocation = $this->createLocationSavingIntegrator()->save($this, $inputDto->specificInvoiceLocation, Constants\Location::TYPE_AUCTION_INVOICE);
            $this->setEntityLocation(Constants\Location::TYPE_AUCTION_INVOICE, $invoiceLocation);
        }

        $this->createLocationSavingIntegrator()->removeExcessCommonOrSpecificLocation(
            $this,
            'specificEventLocation',
            ['eventLocation', 'eventLocationId'],
            Constants\Location::TYPE_AUCTION_EVENT,
            $this->auction,
            'EventLocationId',
        );

        $this->createLocationSavingIntegrator()->removeExcessCommonOrSpecificLocation(
            $this,
            'specificInvoiceLocation',
            ['invoiceLocation', 'invoiceLocationId'],
            Constants\Location::TYPE_AUCTION_INVOICE,
            $this->auction,
            'InvoiceLocationId',
        );
    }

    /**
     * Update lots dates if necessary
     */
    protected function updateLotsDates(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if (
            $this->isExtendAllTurnedOff()
            || $this->isDateAssigmentStrategyChangedToAuctionToItems()
            || AuctionStatusPureChecker::new()->isHybrid($this->auctionType)
        ) {
            $this->createAuctionLotDatesUpdater()->update($this->auction->Id, $configDto->editorUserId);
        }
    }

    /**
     * @return bool
     */
    protected function isAuctionTimedOngoing(): bool
    {
        return AuctionStatusPureChecker::new()->isTimedOngoing($this->getInputDto()->auctionType, $this->detectEventType());
    }

    /**
     * @return bool
     */
    protected function isAuctionTimedScheduled(): bool
    {
        return AuctionStatusPureChecker::new()->isTimedScheduled($this->getInputDto()->auctionType, $this->detectEventType());
    }

    protected function detectEventType(): ?int
    {
        $inputDto = $this->getInputDto();
        if (!AuctionStatusPureChecker::new()->isTimed($this->auctionType)) {
            return null;
        }

        if (isset($inputDto->eventType)) {
            $eventType = (int)array_search($inputDto->eventType, Constants\Auction::$eventTypeNames, true);
        } elseif (isset($inputDto->eventTypeId)) {
            $eventType = (int)$inputDto->eventTypeId;
        } else {
            $eventType = $this->getAuction()->EventType;
        }
        return $eventType;
    }

    /**
     * @return bool
     */
    protected function isExtendAllTurnedOff(): bool
    {
        return $this->isAuctionTimedOngoing()
            && $this->isModified('ExtendAll')
            && !$this->auction->ExtendAll;
    }

    /**
     * @return bool
     */
    protected function isDateAssigmentStrategyChangedToAuctionToItems(): bool
    {
        return $this->isAuctionTimedScheduled()
            && $this->isModified('DateAssignmentStrategy')
            && $this->auction->isAuctionToItemsDateAssignment();
    }

    /**
     * Create or update rtb_current record
     */
    protected function updateRtbCurrent(): void
    {
        $configDto = $this->getConfigDto();
        $rtbCurrent = $this->getRtbGeneralHelper()->loadRtbCurrentOrCreate($this->auction);
        $currentBidAmount = $this->createHighBidDetector()
            ->detectAmount($rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        if (!$currentBidAmount) {
            $rtbCurrent->DefaultIncrement = null;
            $rtbCurrent->AskBid = null;
            $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $configDto->editorUserId);
            $this->getAuctionStateResyncer()->setAuctionId($rtbCurrent->AuctionId)->notifyRtbd();
        }
    }

    /**
     * Create auction_rtbd record
     */
    protected function createAuctionRtbd(): void
    {
        if ($this->getRtbdPoolFeatureAvailabilityValidator()->isAvailableForAuction($this->auction)) {
            $inputDto = $this->getInputDto();
            $rtbdName = (string)$inputDto->rtbdName;
            if ($rtbdName === '') {
                // Detect rtbd instance, if it was not set
                $rtbdName = $this->createAuctionRtbdAdviser()
                    ->setAuction($this->auction)
                    ->suggestName();
            }
            $this->getAuctionRtbdUpdater()
                ->setAuction($this->auction)
                ->setRtbdName($rtbdName)
                ->update();
        }
    }

    protected function updateTaxStates(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $country = AddressRenderer::new()->normalizeCountry($inputDto->taxDefaultCountry);

        if (
            !isset($inputDto->taxDefaultCountry)
            || !AddressChecker::new()->isUsaOrCanada($country)
        ) {
            return;
        }
        $auctionId = $inputDto->id;
        $states = $this->getInputDto()->taxStates;

        $taxStates = [];
        foreach ((array)$states as $state) {
            $state = AddressRenderer::new()->normalizeState($country, $state);
            $countryState = $this->createSamTaxCountryStateLoader()->load(
                $country,
                $state,
                null,
                $auctionId
            );
            if (!$countryState) {
                $countryState = $this->createSamTaxCountryStateProducer()->construct(
                    $country,
                    $state,
                    $configDto->editorUserId,
                    null,
                    $auctionId
                )->add();
            }
            $taxStates[] = $countryState->Id;
        }
        // Remove un-save country states
        $this->createSamTaxCountryStateDeleter()->delete(
            $taxStates,
            $configDto->editorUserId,
            null,
            $auctionId
        );
    }

    /**
     * Update auction_rtbd record
     */
    protected function updateAuctionRtbd(): void
    {
        if ($this->getRtbdPoolFeatureAvailabilityValidator()->isAvailableForAuction($this->auction)) {
            $inputDto = $this->getInputDto();
            $this->getAuctionRtbdUpdater()
                ->setAuction($this->auction)
                ->setRtbdName((string)$inputDto->rtbdName)
                ->update();
        }
    }

    /**
     * Update auction dynamic data
     */
    protected function updateAuctionDynamic(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if (
            AuctionStatusPureChecker::new()->isTimed($this->auctionType)
            && $this->isModified('StartClosingDate')
        ) {
            $auctionDynamic = $this->getAuctionDynamicLoader()->load($this->auction->Id);
            if ($auctionDynamic) {
                $auctionDynamic->ExtendAllStartClosingDate = $this->auction->StartClosingDate;
                $this->getAuctionDynamicWriteRepository()->saveWithModifier($auctionDynamic, $configDto->editorUserId);
                $this->auction->EndDate = $this->createAuctionEndDateDetector()->detect($this->auction);
                $this->getAuctionWriteRepository()->saveWithModifier($this->auction, $configDto->editorUserId);
            }
        }
    }

    protected function updateConsignorCommissionFee(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();

        $auction = $this->getAuction();
        $commissionDto = ConsignorCommissionFeeRelatedEntityDto::new()->fromEntityMakerInputDto(
            $inputDto,
            'consignorCommissionId',
            'consignorCommissionRanges',
            'consignorCommissionCalculationMethod'
        );
        $auction->ConsignorCommissionId = $this->createConsignorCommissionFeeRelatedEntityProducer()->update(
            $auction->ConsignorCommissionId,
            $commissionDto,
            Constants\ConsignorCommissionFee::LEVEL_AUCTION,
            $auction->Id,
            $configDto->editorUserId,
            $configDto->mode
        );

        $soldFeeDto = ConsignorCommissionFeeRelatedEntityDto::new()->fromEntityMakerInputDto(
            $inputDto,
            'consignorSoldFeeId',
            'consignorSoldFeeRanges',
            'consignorSoldFeeCalculationMethod',
            'consignorSoldFeeReference'
        );
        $auction->ConsignorSoldFeeId = $this->createConsignorCommissionFeeRelatedEntityProducer()->update(
            $auction->ConsignorSoldFeeId,
            $soldFeeDto,
            Constants\ConsignorCommissionFee::LEVEL_AUCTION,
            $auction->Id,
            $configDto->editorUserId,
            $configDto->mode
        );

        $unsoldFeeDto = ConsignorCommissionFeeRelatedEntityDto::new()->fromEntityMakerInputDto(
            $inputDto,
            'consignorUnsoldFeeId',
            'consignorUnsoldFeeRanges',
            'consignorUnsoldFeeCalculationMethod',
            'consignorUnsoldFeeReference'
        );
        $auction->ConsignorUnsoldFeeId = $this->createConsignorCommissionFeeRelatedEntityProducer()->update(
            $auction->ConsignorUnsoldFeeId,
            $unsoldFeeDto,
            Constants\ConsignorCommissionFee::LEVEL_AUCTION,
            $auction->Id,
            $configDto->editorUserId,
            $configDto->mode
        );
        $this->getAuctionWriteRepository()->saveWithModifier($auction, $configDto->editorUserId);
    }

    /**
     * @param Auction $auction
     * @return bool if we need to update end date
     */
    private function isEndDateRelatedFieldModified(Auction $auction): bool
    {
        $modifiedProperties = array_keys($auction->__Modified);
        if ($this->auction->isTimed()) {
            return (bool)array_intersect(['StartClosingDate', 'StaggerClosing', 'LotsPerInterval', 'ExtendAll'], $modifiedProperties);
        }
        if ($this->auction->isHybrid()) {
            return (bool)array_intersect(['StartClosingDate', 'LotStartGapTime', 'ExtendTime'], $modifiedProperties);
        }
        return false;
    }

    /**
     * Initialize fields that participate in decision-making of this service.
     * E.g. set auctionType from db for the existing auction editing, because it should present in input for the existing auction.
     * @return void
     */
    protected function initEffectiveFields(): void
    {
        $inputDto = $this->getInputDto();
        if ($inputDto->id) {
            $auction = $this->getAuction();
            $this->auctionType = $auction->AuctionType;
        } else {
            $this->auctionType = $inputDto->auctionType;
        }
    }
}
