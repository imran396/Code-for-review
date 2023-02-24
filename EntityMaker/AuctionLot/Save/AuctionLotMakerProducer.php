<?php
/**
 * Class for producing of Auction Lot entity
 *
 * SAM-3942: Auction Lot entity maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 21, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Save;

use AuctionLotItem;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;
use InvalidArgumentException;
use Sam\Auction\Date\StartEndPeriod\TimedAuctionDateAssignorAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\BulkGroup\Delete\LotBulkGroupRevokerCreateTrait;
use Sam\AuctionLot\BulkGroup\Load\LotBulkGroupLoaderAwareTrait;
use Sam\AuctionLot\BulkGroup\Save\Date\LotBulkGroupLotDateUpdaterCreateTrait;
use Sam\AuctionLot\Date\AuctionLotDatesUpdaterCreateTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Load\TimedItemLoaderAwareTrait;
use Sam\AuctionLot\LotNo\Fill\LotNoAutoFillerAwareTrait;
use Sam\AuctionLot\LotNo\Parse\LotNoParserCreateTrait;
use Sam\AuctionLot\Order\Reorder\AuctionLotReordererAwareTrait;
use Sam\Consignor\Commission\Edit\Dto\ConsignorCommissionFeeRelatedEntityDto;
use Sam\Consignor\Commission\Edit\Save\ConsignorCommissionFeeRelatedEntityProducerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerConfigDto;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerDtoHelperAwareTrait;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerInputDto;
use Sam\EntityMaker\Base\Common\ValueResolver;
use Sam\EntityMaker\Base\Save\BaseMakerProducer;
use Sam\EntityMaker\Base\Save\Internal\EntitySync\EntitySyncSavingIntegratorCreateTrait;
use Sam\EntitySync\Load\EntitySyncLoaderAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\TimedOnlineItem\TimedOnlineItemWriteRepositoryAwareTrait;

/**
 * @method AuctionLotMakerInputDto getInputDto()
 * @method AuctionLotMakerConfigDto getConfigDto()
 */
class AuctionLotMakerProducer extends BaseMakerProducer
{
    use AuctionLoaderAwareTrait;
    use AuctionLotDatesUpdaterCreateTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionLotMakerDtoHelperAwareTrait;
    use AuctionLotReordererAwareTrait;
    use ConsignorCommissionFeeRelatedEntityProducerCreateTrait;
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use DbConnectionTrait;
    use EntityFactoryCreateTrait;
    use EntitySyncLoaderAwareTrait;
    use EntitySyncSavingIntegratorCreateTrait;
    use LotBulkGroupLoaderAwareTrait;
    use LotBulkGroupLotDateUpdaterCreateTrait;
    use LotBulkGroupRevokerCreateTrait;
    use LotCategoryLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotNoAutoFillerAwareTrait;
    use LotNoParserCreateTrait;
    use SettingsManagerAwareTrait;
    use TimedAuctionDateAssignorAwareTrait;
    use TimedItemLoaderAwareTrait;
    use TimedOnlineItemWriteRepositoryAwareTrait;

    protected ?AuctionLotItem $auctionLot = null;
    /**
     * Is regular lot added to bulk group,
     * or Is bulk group of piecemeal lot changed.
     */
    protected bool $isBulkMasterIdChanged = false;
    /**
     * Should remove all lots from the lot bulk group, when current lot loses its Master role
     */
    protected bool $shouldRevokeBulkGroup = false;
    protected bool $isModified = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        AuctionLotMakerInputDto $inputDto,
        AuctionLotMakerConfigDto $configDto
    ): static {
        $this->setInputDto($inputDto);
        $this->setConfigDto($configDto);
        $this->getAuctionLotMakerDtoHelper()->construct($configDto->mode);
        return $this;
    }

    /**
     * Assign AuctionLot values from DTO object
     */
    protected function assignValues(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();

        $auctionLot = $this->getAuctionLot();
        $auctionId = (int)$inputDto->auctionId;
        $auction = $this->getAuctionLoader()->load($auctionId);

        $this->assignBulkGrouping();

        if (isset($inputDto->featured)) {
            $auctionLot->SampleLot = ValueResolver::new()->isTrue($inputDto->featured);
        }
        if (isset($inputDto->lotStatus)) {
            $auctionLot->LotStatusId = array_search($inputDto->lotStatus, Constants\Lot::$lotStatusNames, true);
        }
        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            if (isset($inputDto->lotFullNum)) {
                [
                    $auctionLot->LotNum,
                    $auctionLot->LotNumExt,
                    $auctionLot->LotNumPrefix
                ] = $this->createLotNoParser()
                    ->construct()
                    ->parse((string)$inputDto->lotFullNum)
                    ->toArray();
            }
        } else {
            $this->setIfAssign($auctionLot, 'lotNum');
            $this->setIfAssign($auctionLot, 'lotNumExt');
            $this->setIfAssign($auctionLot, 'lotNumPrefix');
        }
        if (!AuctionStatusPureChecker::new()->isTimed($configDto->auctionType)) {
            $this->setIfAssign($auctionLot, 'lotGroup', self::STRATEGY_SPECIFIC_NAME, 'GroupId');
        }

        if (isset($inputDto->buyNowAmount)) {
            $auctionLot->BuyNowAmount = null;
            if ($inputDto->buyNowAmount) {
                $auctionLot->BuyNowAmount = $configDto->mode->isSoap()
                    ? Cast::toFloat($inputDto->buyNowAmount)
                    : $this->getNumberFormatter()->parseMoney($inputDto->buyNowAmount);
            }
        }

        $this->setIfAssign($auctionLot, 'generalNote');
        $this->setIfAssign($auctionLot, 'noteToClerk');
        $this->setIfAssign($auctionLot, 'lotStatusId');
        $this->setIfAssign($auctionLot, 'order');
        $this->setIfAssign($auctionLot, 'quantity', self::STRATEGY_PARSE, ['precision' => Constants\Lot::LOT_QUANTITY_MAX_FRACTIONAL_DIGITS]);
        $this->setIfAssign($auctionLot, 'quantityDigits');
        $this->setIfAssign($auctionLot, 'seoUrl');
        $this->setIfAssign($auctionLot, 'termsAndConditions');
        $this->setIfAssign($auctionLot, 'textMsgNotified');
        $this->setIfAssign($auctionLot, 'trackCode');
        $this->setIfAssign($auctionLot, 'quantityXMoney', self::STRATEGY_BOOL);
        $this->setIfAssign($auctionLot, 'listingOnly', self::STRATEGY_BOOL);
        $this->setIfAssign($auctionLot, 'publishDate', self::STRATEGY_DATE_TIME_CONVERT_TO_UTC, 'timezone');
        $this->setIfAssign($auctionLot, 'startBiddingDate', self::STRATEGY_DATE_TIME_CONVERT_TO_UTC, 'timezone');
        $this->setIfAssign($auctionLot, 'endPrebiddingDate', self::STRATEGY_DATE_TIME_CONVERT_TO_UTC, 'timezone');
        $this->setIfAssign($auctionLot, 'unpublishDate', self::STRATEGY_DATE_TIME_CONVERT_TO_UTC, 'timezone');
        $this->setIfAssign($auctionLot, 'hpTaxSchemaId');
        $this->setIfAssign($auctionLot, 'bpTaxSchemaId');

        /**
         * SAM-6339: When creating a lot from an existing item (Add available items to auction, Soap create lot, csv upload using inventory number, etc TBD),
         * we copy over the two attributes as default into the lot level, unless they are explicitly defined for the lot level (via soap attributes, or csv column values)
         */
        if (
            !$auctionLot->Id
            && !isset($inputDto->quantity)
        ) {
            $lotItem = $this->getLotItemLoader()->load(Cast::toInt($inputDto->lotItemId));
            if ($lotItem) {
                $auctionLot->Quantity = $lotItem->Quantity;
                $auctionLot->QuantityDigits = $lotItem->QuantityDigits;
                $auctionLot->QuantityXMoney = $lotItem->QuantityXMoney;
            } else {
                log_error('Available lot item not found' . composeSuffix(['li' => $inputDto->lotItemId]));
            }
        }


        // Force enable QuantityXMoney if BuyNowSelectQuantity is enabled
        if (isset($inputDto->buyNowSelectQuantityEnabled)) {
            $auctionLot->BuyNowSelectQuantityEnabled = ValueResolver::new()->isTrue($inputDto->buyNowSelectQuantityEnabled);
            if ($auctionLot->BuyNowSelectQuantityEnabled) {
                $auctionLot->QuantityXMoney = true;
            }
        }

        if (AuctionStatusPureChecker::new()->isTimed($configDto->auctionType)) {
            if ($auctionLot->StartClosingDate != new DateTime($inputDto->startClosingDate, new DateTimeZone($inputDto->timezone ?: 'UTC'))) {
                $auctionLot->TextMsgNotified = false;
            }
            $this->setIfAssign($auctionLot, 'startClosingDate', self::STRATEGY_DATE_TIME_CONVERT_TO_UTC, 'timezone');

            if (!$configDto->extendAll) {
                $auctionLot->StartDate = $auctionLot->StartBiddingDate ?? $auction->StartBiddingDate ?? null;
                $auctionLot->EndDate = $auctionLot->StartClosingDate ?? $auction->StartClosingDate ?? null;
            } else {
                $auctionLot->StartDate = $auction->StartBiddingDate ?? null;
                $auctionLot->EndDate = $auction->StartClosingDate ?? null;
            }
        }

        $auctionLot->TimezoneId = $this->getTimezoneLoader()->loadByLocationOrCreatePersisted($inputDto->timezone ?: 'UTC')->Id;

        if (!$auctionLot->LotStatusId) {
            $auctionLot->toActive();
        }
    }

    /**
     * Is auctionLot modified
     * @return bool
     */
    public function isModified(): bool
    {
        return $this->isModified;
    }

    /**
     * Run necessary actions after AuctionLot was created or updated
     */
    protected function doPostUpdate(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $isNew = !$inputDto->id;
        $inputDto->id = $this->getAuctionLot()->Id;

        if (
            AuctionStatusPureChecker::new()->isHybrid($configDto->auctionType)
            && $this->isModified()
        ) {
            $auctionId = (int)$inputDto->auctionId;
            $auction = $this->getAuctionLoader()->load($auctionId);
            if (!$auction) {
                $message = "Available auction not found when post-update of auction lot producing" . composeSuffix(['a' => $auctionId]);
                // TODO: probably, we should throw exception in case like this instead of simple return
                // throw new CouldNotFindAuction($message)
                log_error($message);
                return;
            }

            $this->getAuctionLotReorderer()->reorder($auction, $configDto->editorUserId);
            $this->createAuctionLotDatesUpdater()->update($auctionId, $configDto->editorUserId);
        }
        if (AuctionStatusPureChecker::new()->isTimed($configDto->auctionType)) {
            $this->saveTimedItem();
            $this->refreshLotsDates();

            if ($this->shouldRevokeBulkGroup) {
                $this->createLotBulkGroupRevoker()
                    ->revokePiecemealLots($this->auctionLot->Id, $configDto->editorUserId);
            }
        }

        if ($isNew) {
            $this->createEntitySyncSavingIntegrator()->create($this);
        } else {
            $this->createEntitySyncSavingIntegrator()->update($this);
        }
        $this->updateConsignorCommissionFee();
    }

    /**
     * Perform Lot Bulk Grouping related assignments
     */
    protected function assignBulkGrouping(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if (!AuctionStatusPureChecker::new()->isTimed($configDto->auctionType)) {
            // lot bulk grouping feature is available for timed auctions only
            return;
        }

        $auctionLot = $this->getAuctionLot();
        /**
         * dto->bulkControl describes status of the lot in bulk grouping:
         * "" - no grouping, lot is regular
         * "MASTER" - for master-lot status of group
         * master's lot# - for piecemeal-lot status in group
         */
        // YV: SAM-6856. Why do we perform this code for empty ('') (string)$dto->bulkControl ? i think, we should not to do that.
        if (isset($inputDto->bulkControl)) {
            $bulkControlValue = (string)$inputDto->bulkControl;
            $isFlaggedAsMaster = $bulkControlValue === Constants\LotBulkGroup::LBGR_MASTER;
            $isFlaggedAsNone = $bulkControlValue === '';
            $isFlaggedAsPiecemeal = !$isFlaggedAsMaster && !$isFlaggedAsNone;
            $auctionId = (int)$inputDto->auctionId;
            $incomingMasterAuctionLotId = null;
            if (
                $isFlaggedAsPiecemeal
                && $bulkControlValue
            ) {
                /**
                 * TODO: IK, 2020-10: since in $bulkControlValue we pass lot#, we always should perform search in db for master auction lot.
                 * a) Why don't we perform this check in Validator?
                 * b) We should operate with ali.id in $bulkControlValue, because it is native for DB model (code should be based on DB model, not SOAP).
                 */
                $incomingMasterAuctionLotId = $this->getLotBulkGroupLoader()
                    ->detectMasterAuctionLotIdByLotNoConcatenated($bulkControlValue, $auctionId);
                if (!$incomingMasterAuctionLotId) {
                    // IK, 2020-10: This situation is weird, it is possible because of incorrect input data for lot# defined by $bulkControlValue
                    // Shouldn't we throw exception in this case?
                    log_errorBackTrace(
                        "Available master auction lot not found for lot that is or should become a piecemeal lot of the group"
                        . composeSuffix(['ali' => $auctionLot->Id])
                    );
                }
            }

            // This means lot has changed or added or left lot bulk group
            $this->isBulkMasterIdChanged = $auctionLot->BulkMasterId !== $incomingMasterAuctionLotId;

            /**
             * $this->isBulkMasterIdChanged = true may mean:
             * [a] either we've assigned regular lot to bulk group (bm: null => xx),
             * [b] or re-assigned piecemeal lot to another bulk group (bm: xx => yy),
             * [c] or removed piecemeal lot from bulk group (bm: xx => null),
             * [d] or we modified lot from regular one to master (bm: null, isMaster: no => yes),
             * [e] or we modified lot from piecemeal one to master (bm: xx => null, isMaster: no => yes),
             * [f] or we modified lot from master to regular lot (bm: null, isMaster: yes => no),
             * [g] or we modified lot from master to piecemeal lot of another bulk group (bm: null => yy, isMaster: yes => no).
             * [h] or nothing to do, when regular lot keeps its non-grouped role
             * [i] or nothing to do, when master lot keeps its master lot role
             * [j] or nothing to do, when piecemeal lot keeps its piecemeal lot role and its group isn't changed
             * TODO: test every case.
             * TODO: this logic should be extracted to internal module, should be tested separately, probably with help of ResultStatusCollector
             */

            if ($auctionLot->hasMasterRole()) {
                if ($isFlaggedAsMaster) {
                    // nothing to do [i]
                } elseif ($isFlaggedAsPiecemeal) {
                    if ($incomingMasterAuctionLotId) {
                        $auctionLot->toPiecemealOfBulkGroup($incomingMasterAuctionLotId); // [g]
                    }
                    $this->shouldRevokeBulkGroup = true;
                } elseif ($isFlaggedAsNone) {
                    $auctionLot->removeFromBulkGroup(); // [f]
                    $this->shouldRevokeBulkGroup = true;
                }
            } elseif ($auctionLot->hasPiecemealRole()) {
                if ($isFlaggedAsMaster) {
                    $auctionLot->toMasterOfBulkGroup(); // [e]
                } elseif ($isFlaggedAsPiecemeal) {
                    if (
                        $incomingMasterAuctionLotId
                        && $this->isBulkMasterIdChanged
                    ) {
                        $auctionLot->toPiecemealOfBulkGroup($incomingMasterAuctionLotId); // [b]
                    } else {
                        // nothing to do [j]
                    }
                } elseif ($isFlaggedAsNone) {
                    $auctionLot->removeFromBulkGroup(); // [c]
                }
            } else { // it is/was a regular non-grouped lot
                if ($isFlaggedAsMaster) {
                    $auctionLot->toMasterOfBulkGroup(); // [d]
                } elseif ($isFlaggedAsPiecemeal) {
                    $auctionLot->toPiecemealOfBulkGroup($incomingMasterAuctionLotId); // [a]
                } elseif ($isFlaggedAsNone) {
                    // nothing to do [h]
                }
            }
        }

        /**
         * IK, 2020-10: Added check for being value assigned, before modifying property.
         * I think, we should follow this pattern for all fields. I don't think, we should drop this value otherwise, it doesn't harm anything and may be restored.
         * This value has sense in case of master lot context only.
         */
        if (isset($inputDto->bulkWinBidDistribution)) {
            if ($auctionLot->hasMasterRole()) {
                $auctionLot->BulkMasterWinBidDistribution = (int)$inputDto->bulkWinBidDistribution;
            }
        }
    }

    /**
     * Refresh lots dates
     */
    protected function refreshLotsDates(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        // If it is in auction lot relationship, lot item auction type will inherits auction's auction type
        $auctionId = $inputDto->auctionId;

        $auctionDateAssignor = $this->getTimedAuctionDateAssignor()->setAuctionId(Cast::toInt($auctionId));
        if ($auctionDateAssignor->shouldUpdate()) {
            $auctionDateAssignor->updateDateFromLots($configDto->editorUserId);
        }

        $auctionLot = $this->getAuctionLot();
        if ($this->isBulkMasterIdChanged) {
            $masterAuctionLot = null;
            if ($auctionLot->hasPiecemealRole()) {
                $masterAuctionLot = $this->getAuctionLotLoader()->loadById($auctionLot->BulkMasterId);
            }
            // IK, 2020-10: When regular or piecemeal lot becomes master, why don't we update this new master dates?
            // code below does not have sense, because we can't refresh dates for bulk group, when there is a single lot in group (only master one)
            // TODO: remove commented code or fix
            // elseif ($auctionLot->isMasterOfBulkGroup()) {
            //     $masterAuctionLot = $auctionLot;
            // }
            $this->createLotBulkGroupLotDateUpdater()->updateByMasterAuctionLot($masterAuctionLot, $configDto->editorUserId);
        }
    }

    /**
     * Produce the AuctionLot entity
     * @return static
     */
    public function produce(): static
    {
        $this->assertInputDto();
        $inputDto = $this->getAuctionLotMakerDtoHelper()->prepareValues($this->getInputDto(), $this->getConfigDto());
        $this->setInputDto($inputDto);
        $this->populateDates();
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
            log_errorBackTrace("Rollback transaction, because auction lot save failed.");
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
        $isNew = !$inputDto->id;
        if ($isNew) {
            $this->getLotNoAutoFiller()
                ->enableCustomFieldAutoFill(false)
                ->fill($this->auctionLot);
        }
        if ($this->auctionLot->__Modified) {
            $this->isModified = true;
            $this->getAuctionLotItemWriteRepository()->saveWithModifier($this->auctionLot, $configDto->editorUserId);
        }
        $this->doPostUpdate();
    }

    /**
     * Get AuctionLot
     * @return AuctionLotItem
     */
    public function getAuctionLot(): AuctionLotItem
    {
        if ($this->auctionLot === null) {
            $this->auctionLot = $this->loadAuctionLotOrCreate();
        }
        return $this->auctionLot;
    }

    /**
     * Load or create AuctionLot depending on the AuctionLot ID
     * @return AuctionLotItem
     */
    protected function loadAuctionLotOrCreate(): AuctionLotItem
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();

        $auctionLotId = (int)$inputDto->id;
        if ($auctionLotId) {
            $auctionLot = $this->getAuctionLotLoader()->loadById($auctionLotId);
            if (!$auctionLot) {
                throw new InvalidArgumentException("Cannot load AuctionLot" . composeSuffix(['ali' => $auctionLotId]));
            }
        } else {
            $lotItem = $this->getLotItemLoader()->load(Cast::toInt($inputDto->lotItemId));
            $auctionLot = $this->createEntityFactory()->auctionLotItem();
            $auctionLot->AccountId = $configDto->serviceAccountId;
            $auctionLot->AuctionId = Cast::toInt($inputDto->auctionId);
            $auctionLot->BuyNowSelectQuantityEnabled = $lotItem && $lotItem->BuyNowSelectQuantityEnabled;
            $auctionLot->LotItemId = Cast::toInt($inputDto->lotItemId);
            $auctionLot->TimezoneId = $this->getSettingsManager()->getForSystem(Constants\Setting::TIMEZONE_ID);
        }
        return $auctionLot;
    }

    /**
     * Save timed item
     */
    protected function saveTimedItem(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $timedItem = $this->getTimedItemLoader()->loadOrCreate(
            (int)$inputDto->lotItemId,
            (int)$inputDto->auctionId
        );

        $this->setIfAssign($timedItem, 'noBidding', self::STRATEGY_BOOL);
        $this->setIfAssign($timedItem, 'bestOffer', self::STRATEGY_BOOL);

        // Bidding can be disabled if bestOffer or buyNowAmount entered
        if (
            isset($inputDto->bestOffer)
            || isset($inputDto->buyNowAmount)
        ) {
            $timedItem->NoBidding = $inputDto->bestOffer || $inputDto->buyNowAmount
                ? (bool)$inputDto->noBidding
                : false;
        }
        // Force disable bidding if BuyNowSelectQuantity is enabled
        if (isset($inputDto->buyNowSelectQuantityEnabled)) {
            $isBuyNowSelectQuantityEnabled = ValueResolver::new()->isTrue($inputDto->buyNowSelectQuantityEnabled);
            if ($isBuyNowSelectQuantityEnabled) {
                $timedItem->NoBidding = true;
            }
        }
        $this->getTimedOnlineItemWriteRepository()->saveWithModifier($timedItem, $configDto->editorUserId);
    }

    /**
     * Populate startClosingDate, timezone fields from auction dates if they are empty.
     * Set them to Dto
     */
    protected function populateDates(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        // Do not populate dates when startClosingDate specified explicitly
        if (
            !$inputDto->startClosingDate
            || $configDto->extendAll
        ) {
            $timezoneId = (int)$this->getSettingsManager()->getForSystem(Constants\Setting::TIMEZONE_ID);
            $startClosingDate = $this->getCurrentDateSys()->add(new DateInterval('P7D'));

            $auction = $this->getAuctionLoader()->load((int)$inputDto->auctionId);
            if (
                $auction
                && $auction->isTimedScheduled()
            ) {
                $startClosingDate = $this->getDateHelper()->convertUtcToTzById(
                    $auction->StartClosingDate,
                    $auction->TimezoneId
                );
                $timezoneId = $auction->TimezoneId;
            }

            $timezoneLocation = $this->getTimezoneLoader()->load($timezoneId, true)->Location ?? 'UTC';
            $inputDto->timezone = $timezoneLocation;
            $inputDto->startClosingDate = $startClosingDate->format('m/d/Y h:i A');
        }
        if (!$inputDto->timezone) {
            if ($inputDto->id) {
                $timezoneId = $this->loadAuctionLotOrCreate()->TimezoneId;
                $timezoneLocation = $this->getTimezoneLoader()->load($timezoneId, true)->Location ?? 'UTC';
            } else {
                $timezoneLocation = 'UTC';
            }
            $inputDto->timezone = $timezoneLocation;
        }
    }

    protected function updateConsignorCommissionFee(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();

        $auctionLot = $this->getAuctionLot();
        $commissionDto = ConsignorCommissionFeeRelatedEntityDto::new()->fromEntityMakerInputDto(
            $inputDto,
            'consignorCommissionId',
            'consignorCommissionRanges',
            'consignorCommissionCalculationMethod'
        );
        $auctionLot->ConsignorCommissionId = $this->createConsignorCommissionFeeRelatedEntityProducer()->update(
            $auctionLot->ConsignorCommissionId,
            $commissionDto,
            Constants\ConsignorCommissionFee::LEVEL_AUCTION_LOT,
            $auctionLot->Id,
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
        $auctionLot->ConsignorSoldFeeId = $this->createConsignorCommissionFeeRelatedEntityProducer()->update(
            $auctionLot->ConsignorSoldFeeId,
            $soldFeeDto,
            Constants\ConsignorCommissionFee::LEVEL_AUCTION_LOT,
            $auctionLot->Id,
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
        $auctionLot->ConsignorUnsoldFeeId = $this->createConsignorCommissionFeeRelatedEntityProducer()->update(
            $auctionLot->ConsignorUnsoldFeeId,
            $unsoldFeeDto,
            Constants\ConsignorCommissionFee::LEVEL_AUCTION_LOT,
            $auctionLot->Id,
            $configDto->editorUserId,
            $configDto->mode
        );
        $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $configDto->editorUserId);
    }
}
