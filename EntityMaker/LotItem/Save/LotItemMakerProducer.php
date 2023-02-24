<?php
/**
 * Class for producing of Lot entity
 *
 * SAM-8837: Lot item entity maker module structural adjustments for v3-5
 * SAM-4015: Auction Lot and Lot Item Entity Makers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 8, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Save;

use Exception;
use InvalidArgumentException;
use LotCategory;
use LotImage;
use LotItem;
use Sam\Bidding\BidIncrement\Save\BidIncrementProducerAwareTrait;
use Sam\BuyersPremium\Load\BuyersPremiumLoaderCreateTrait;
use Sam\Consignor\Commission\Edit\Dto\ConsignorCommissionFeeRelatedEntityDto;
use Sam\Consignor\Commission\Edit\Save\ConsignorCommissionFeeRelatedEntityProducerCreateTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\CustomField\Lot\Save\LotItemCustomFieldDataUpdaterCreateTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\EntityMaker\Base\Common\CustomFieldManagerAwareTrait;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\Base\Common\ValueResolver;
use Sam\EntityMaker\Base\Save\BaseMakerProducer;
use Sam\EntityMaker\Base\Save\Internal\EntitySync\EntitySyncSavingIntegratorCreateTrait;
use Sam\EntityMaker\LotCategory\Dto\LotCategoryMakerConfigDto;
use Sam\EntityMaker\LotCategory\Dto\LotCategoryMakerDtoFactory;
use Sam\EntityMaker\LotCategory\Dto\LotCategoryMakerInputDto;
use Sam\EntityMaker\LotCategory\Save\LotCategoryMakerProducerCreateTrait;
use Sam\EntityMaker\LotCategory\Validate\LotCategoryMakerValidatorCreateTrait;
use Sam\EntityMaker\LotItem\Common\LotItemMakerCustomFieldManager;
use Sam\EntityMaker\LotItem\Common\WinningAuction\WinningAuctionIdDetectorCreateTrait;
use Sam\EntityMaker\LotItem\Common\WinningAuction\WinningAuctionInput;
use Sam\EntityMaker\LotItem\Common\WinningBidder\WinningBidderIdDetectorCreateTrait;
use Sam\EntityMaker\LotItem\Common\WinningBidder\WinningBidderInput;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerConfigDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerDtoHelper;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerDtoHelperAwareTrait;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;
use Sam\EntityMaker\LotItem\Save\Internal\BuyersPremium\BuyersPremiumSaverCreateTrait;
use Sam\EntityMaker\LotItem\Save\Internal\BuyersPremium\BuyersPremiumSavingInput;
use Sam\EntityMaker\LotItem\Save\Internal\Consignor\LotItemConsignorUserProducerCreateTrait;
use Sam\EntityMaker\LotItem\Save\Internal\ItemNo\LotItemItemNoApplierCreateTrait;
use Sam\File\FolderManager;
use Sam\Invoice\Common\Load\InvoiceLoader;
use Sam\Invoice\Common\Validate\InvoiceExistenceChecker;
use Sam\Location\Load\LocationLoaderAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Category\LotLinker\LotCategoryLotLinkerAwareTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\SearchIndex\SearchIndexQueueCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotImage\LotImageWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;
use Sam\Tax\SamTaxCountryState\Delete\SamTaxCountryStateDeleterCreateTrait;
use Sam\Tax\SamTaxCountryState\Load\SamTaxCountryStateLoaderCreateTrait;
use Sam\Tax\SamTaxCountryState\Save\SamTaxCountryStateProducerCreateTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * @method LotItemMakerInputDto getInputDto()
 * @method LotItemMakerConfigDto getConfigDto()
 * @property LotItemMakerCustomFieldManager $customFieldManager
 */
class LotItemMakerProducer extends BaseMakerProducer
{
    use BidIncrementProducerAwareTrait;
    use BuyersPremiumLoaderCreateTrait;
    use BuyersPremiumSaverCreateTrait;
    use ConsignorCommissionFeeRelatedEntityProducerCreateTrait;
    use CurrentDateTrait;
    use CustomFieldManagerAwareTrait;
    use DateHelperAwareTrait;
    use DbConnectionTrait;
    use EntityFactoryCreateTrait;
    use EntitySyncSavingIntegratorCreateTrait;
    use LocationLoaderAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotCategoryLotLinkerAwareTrait;
    use LotCategoryMakerProducerCreateTrait;
    use LotCategoryMakerValidatorCreateTrait;
    use LotImageLoaderAwareTrait;
    use LotImageWriteRepositoryAwareTrait;
    use LotItemConsignorUserProducerCreateTrait;
    use LotItemCustomFieldDataUpdaterCreateTrait;
    use LotItemItemNoApplierCreateTrait;
    use LotItemLoaderAwareTrait;
    use LotItemMakerDtoHelperAwareTrait;
    use LotItemWriteRepositoryAwareTrait;
    use SamTaxCountryStateDeleterCreateTrait;
    use SamTaxCountryStateLoaderCreateTrait;
    use SamTaxCountryStateProducerCreateTrait;
    use SearchIndexQueueCreateTrait;
    use SettingsManagerAwareTrait;
    use UserLoaderAwareTrait;
    use WinningAuctionIdDetectorCreateTrait;
    use WinningBidderIdDetectorCreateTrait;

    /**
     * @var LotCategory[]|null
     */
    private ?array $categories = null;
    /**
     * @var int
     */
    private int $addedImages = 0;
    /**
     * @var int
     */
    private int $rejectedImages = 0;
    /**
     * @var LotItem|null
     */
    protected ?LotItem $lotItem = null;
    /**
     * @var bool
     */
    private bool $isModified = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotItemMakerInputDto $inputDto
     * @param LotItemMakerConfigDto $configDto
     * @return $this
     */
    public function construct(
        LotItemMakerInputDto $inputDto,
        LotItemMakerConfigDto $configDto
    ): static {
        $this->setInputDto($inputDto);
        $this->setConfigDto($configDto);
        $this->customFieldManager ??= LotItemMakerCustomFieldManager::new()->construct($inputDto, $configDto);
        $this->lotItemMakerDtoHelper ??= LotItemMakerDtoHelper::new()->constructLotItemMakerDtoHelper($configDto->mode, $this->customFieldManager);
        return $this;
    }

    /**
     * Produce the LotItem entity
     * @return static
     */
    public function produce(): static
    {
        $this->assertInputDto();
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
            log_errorBackTrace("Rollback transaction, because lot item save failed.");
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
        if ($this->lotItem->__Modified) {
            $this->isModified = true;
        }
        $this->getLotItemWriteRepository()->saveWithModifier($this->lotItem, $configDto->editorUserId);
        $isNew
            ? $this->doPostCreate()
            : $this->doPostUpdate();
    }

    /**
     * Get a number of added images
     * @return int
     */
    public function getAddedImageCount(): int
    {
        return $this->addedImages;
    }

    /**
     * Get a number of rejected images, if the number of images is more than cfg()->core->lot->image->perLotLimit
     * @return int
     */
    public function getRejectedImageCount(): int
    {
        return $this->rejectedImages;
    }

    /**
     * Get added custom field files names
     * @return string[]
     */
    public function getAddedCustomFieldFiles(): array
    {
        return $this->customFieldManager->getAddedFileNames();
    }

    /**
     * Get old custom field files names
     * @return string[]
     */
    public function getOldCustomFieldFiles(): array
    {
        return $this->customFieldManager->getOldFileNames();
    }

    /**
     * Get LotItem
     * @return LotItem
     */
    public function getLotItem(): LotItem
    {
        if ($this->lotItem === null) {
            $this->lotItem = $this->loadLotItemOrCreate();
        }
        return $this->lotItem;
    }

    /**
     * Is lotItem modified
     * @return bool
     */
    public function isModified(): bool
    {
        return $this->isModified;
    }

    /**
     * Load or create LotItem depending on the LotItem Id
     * @return LotItem
     */
    protected function loadLotItemOrCreate(): LotItem
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $lotItemId = (int)$inputDto->id;
        if ($lotItemId) {
            $lotItem = $this->getLotItemLoader()->load($lotItemId);
            if (!$lotItem) {
                throw new InvalidArgumentException("Cannot load Lot" . composeSuffix(['id' => $lotItemId]));
            }
        } else {
            $lotItem = $this->createEntityFactory()->lotItem();
            $lotItem->toActive();
            $lotItem->AccountId = $configDto->serviceAccountId;
        }
        return $lotItem;
    }

    /**
     * Run necessary actions after LotItem was updated
     */
    public function doPostCreate(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $inputDto->id = $this->getLotItem()->Id;

        $this->saveBuyersPremium(true);

        if (
            isset($inputDto->categoriesIds)
            || isset($inputDto->categoriesNames)
        ) {
            $this->getLotCategoryLotLinker()->assignCategoryForLot(
                $this->getCategoriesIds(),
                $this->lotItem->Id,
                $configDto->editorUserId
            );
        }

        $this->customFieldManager
            ->setInputDto($inputDto)
            ->setConfigDto($configDto)
            ->save();

        if (isset($inputDto->images)) {
            $this->createImages((array)$inputDto->images, 0, $configDto->editorUserId);
        }

        if ($inputDto->increments) {
            $this->getBidIncrementProducer()->bulkCreate(
                (array)$inputDto->increments,
                $configDto->serviceAccountId,
                $configDto->editorUserId,
                null,
                $this->lotItem->Id
            );
        }

        $this->createEntitySyncSavingIntegrator()->create($this);

        $this->saveItemTaxStates();
        $this->updateConsignorCommissionFee();
        $this->updateLocations();
    }

    /**
     * Run necessary actions after LotItem was created
     */
    public function doPostUpdate(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();

        $this->saveBuyersPremium();

        if (
            isset($inputDto->categoriesIds)
            || isset($inputDto->categoriesNames)
        ) {
            $this->updateCategories();
        }

        $customFieldManager = $this->customFieldManager
            ->setInputDto($inputDto)
            ->save();
        if ($customFieldManager->isModified()) {
            $this->addLotToContentQueue();
        }

        if (isset($inputDto->images)) {
            $this->updateImages();
        }

        if (isset($inputDto->increments)) {
            $this->getBidIncrementProducer()->bulkUpdate(
                (array)$inputDto->increments,
                $configDto->serviceAccountId,
                $configDto->editorUserId,
                $configDto->editorUserId,
                null,
                $this->lotItem->Id
            );
        }

        $this->createEntitySyncSavingIntegrator()->update($this);

        $this->saveItemTaxStates();
        $this->updateConsignorCommissionFee();
        $this->updateLocations();
    }

    /**
     * Add a lot to index_queue, cron\search_index_queued.php will generate search_index_fulltext.full_content/public_content
     * If lot is new LotItemObserver already has this logic
     */
    public function addLotToContentQueue(): void
    {
        if (InvoiceExistenceChecker::new()->existByLotItemId($this->lotItem->Id)) {
            $invoices = InvoiceLoader::new()->loadForLotItem($this->lotItem->Id);
            if ($invoices) {
                $this->createSearchIndexQueue()->add(Constants\Search::ENTITY_INVOICE, $invoices[0]->Id);
            }
        }
        $this->createSearchIndexQueue()->add(Constants\Search::ENTITY_LOT_ITEM, $this->lotItem->Id);
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
            $this->lotItem->Id,
            $this->lotItem->AccountId
        );
        $this->createBuyersPremiumSaver()->save($buyersPremiumSavingInput, $isCreate);
    }

    /**
     * Save lotItem tax states to sam_tax_country_states table, remove unsaved
     */
    protected function saveItemTaxStates(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $taxDefaultCountry = $this->lotItem->TaxDefaultCountry;

        if (
            !isset($inputDto->taxDefaultCountry)
            || !AddressChecker::new()->isUsaOrCanada((string)$taxDefaultCountry)
        ) {
            return;
        }

        $taxStates = $inputDto->taxStates ?: [];
        $lotItemId = $this->lotItem->Id;
        $taxStateSaved = [];
        foreach ($taxStates as $taxState) {
            $taxState = AddressRenderer::new()->normalizeState($taxDefaultCountry, $taxState);
            if ($taxState && $lotItemId) {
                $samTaxCountryState = $this->createSamTaxCountryStateLoader()
                    ->load($taxDefaultCountry, $taxState, null, null, $lotItemId);
                if (!$samTaxCountryState) {
                    $samTaxCountryStateProducer = $this->createSamTaxCountryStateProducer()
                        ->construct($taxDefaultCountry, $taxState, $configDto->editorUserId, null, null, $lotItemId);
                    $samTaxCountryState = $samTaxCountryStateProducer->add();
                }
                $taxStateSaved[] = $samTaxCountryState->Id;
            }
        }
        // Remove un-save country state
        $this->createSamTaxCountryStateDeleter()->delete(
            $taxStateSaved,
            $configDto->editorUserId,
            null,
            null,
            $lotItemId
        );
    }

    /**
     * Update lot categories
     */
    public function updateCategories(): void
    {
        $configDto = $this->getConfigDto();
        $oldCategoryIds = $this->getLotCategoryLoader()->loadIdsForLot($this->lotItem->Id);
        $newCategoryIds = $this->getCategoriesIds();

        // If categories are changed - strictly unassign old categories and assign new ones, because order is important
        /** @noinspection TypeUnsafeComparisonInspection - we compare arrays and don't care about order */
        if ($oldCategoryIds != $newCategoryIds) {
            $this->getLotCategoryLotLinker()->unassignCategoryFromLot($oldCategoryIds, $this->lotItem->Id, $configDto->editorUserId);
            $this->getLotCategoryLotLinker()->assignCategoryForLot(
                $newCategoryIds,
                $this->lotItem->Id,
                $configDto->editorUserId
            );
            $this->createLotItemCustomFieldDataUpdater()
                ->cleanUpNotLinkedByCategories($this->lotItem->Id, $configDto->editorUserId);
            $this->isModified = true;
        }
    }

    /**
     * Assign AuctionLot values from Dto object
     */
    public function assignValues(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $serviceAccountId = $configDto->serviceAccountId;
        $lotItem = $this->getLotItem();
        $lotItem->AccountId = $serviceAccountId;
        $this->setIfAssign($lotItem, 'active', self::STRATEGY_BOOL);
        $this->setIfAssign($lotItem, 'additionalBpInternet', self::STRATEGY_REMOVE_FORMAT);
        $this->setIfAssign($lotItem, 'bpRangeCalculation');
        $this->setIfAssign($lotItem, 'consignorId');
        $this->setIfAssign($lotItem, 'cost', self::STRATEGY_PARSE);
        $this->setIfAssign($lotItem, 'dateSold', self::STRATEGY_DATE_TIME);
        $this->setIfAssign($lotItem, 'description');
        $this->setIfAssign($lotItem, 'hammerPrice', self::STRATEGY_PARSE);
        $this->setIfAssign($lotItem, 'fbOgDescription');
        $this->setIfAssign($lotItem, 'fbOgImageUrl');
        $this->setIfAssign($lotItem, 'fbOgTitle');
        $this->setIfAssign($lotItem, 'highEstimate', self::STRATEGY_PARSE);
        $this->setIfAssign($lotItem, 'internetBid', self::STRATEGY_BOOL);
        $this->setIfAssign($lotItem, 'lotItemTaxArr', self::STRATEGY_BOOL);
        $this->setIfAssign($lotItem, 'lowEstimate', self::STRATEGY_PARSE);
        $this->setIfAssign($lotItem, 'onlyTaxBp', self::STRATEGY_BOOL);
        $this->setIfAssign($lotItem, 'returned', self::STRATEGY_BOOL);
        $this->setIfAssign($lotItem, 'notes');
        $this->setIfAssign($lotItem, 'reservePrice', self::STRATEGY_PARSE);
        $this->setIfAssign($lotItem, 'salesTax', self::STRATEGY_REMOVE_FORMAT);
        $this->setIfAssign($lotItem, 'seoMetaDescription');
        $this->setIfAssign($lotItem, 'seoMetaKeywords');
        $this->setIfAssign($lotItem, 'seoMetaTitle');
        $this->setIfAssign($lotItem, 'startingBid', self::STRATEGY_PARSE);
        $this->setIfAssign($lotItem, 'taxExempt', self::STRATEGY_BOOL);
        $this->setIfAssign($lotItem, 'warranty');
        $this->setIfAssign($lotItem, 'quantity', self::STRATEGY_PARSE, ['precision' => Constants\Lot::LOT_QUANTITY_MAX_FRACTIONAL_DIGITS]);
        $this->setIfAssign($lotItem, 'quantityDigits');
        $this->setIfAssign($lotItem, 'quantityXMoney', self::STRATEGY_BOOL);
        $this->setIfAssign($lotItem, 'buyNowSelectQuantityEnabled', self::STRATEGY_BOOL);
        $this->setIfAssign($lotItem, 'hpTaxSchemaId');
        $this->setIfAssign($lotItem, 'bpTaxSchemaId');
        if (isset($inputDto->taxDefaultCountry)) {
            $lotItem->TaxDefaultCountry = AddressRenderer::new()->normalizeCountry($inputDto->taxDefaultCountry);
        }

        if (isset($inputDto->bpRule)) {
            $bpRule = $this->createBuyersPremiumLoader()->loadNotDefault($inputDto->bpRule, $serviceAccountId);
            $lotItem->BuyersPremiumId = $bpRule->Id ?? null;
        }

        if (isset($inputDto->changes)) {
            if (
                !isset($inputDto->id)
                || $lotItem->Changes !== $inputDto->changes
            ) {
                $lotItem->Changes = $inputDto->changes;
                $lotItem->ChangesTimestamp = $this->getCurrentDateUtc();
            }
        }

        if (isset($inputDto->consignorName)) {
            $consignorName = trim((string)$inputDto->consignorName);
            $user = $this->createLotItemConsignorUserProducer()->loadConsignorUserOrCreate(
                $consignorName,
                $configDto->serviceAccountId,
                $configDto->editorUserId,
                $this->getLotItemMakerDtoHelper()->shouldAutoCreateConsignor()
            );
            $lotItem->ConsignorId = $user?->Id;
        }

        $lotItem = $this->createLotItemItemNoApplier()->apply($lotItem, $this->getInputDto());

        if (isset($inputDto->location)) {
            $lotItem->LocationId = is_numeric($inputDto->location)
                ? $this->getLocationLoader()->load((int)$inputDto->location)?->Id
                : $this->getLocationLoader()->loadByName($inputDto->location, $serviceAccountId)?->Id;
        }

        if (isset($inputDto->name)) {
            $lotItem->Name = TextTransformer::new()->cut((string)$inputDto->name, $this->cfg()->get('core->lot->name->lengthLimit'));
        }

        if (isset($inputDto->noTaxOos)) {
            $lotItem->NoTaxOos = ValueResolver::new()->isTrue((string)$inputDto->noTaxOos);
        } else {
            if (!$lotItem->Id) {
                $lotItem->NoTaxOos = (bool)$this->getSettingsManager()->get(Constants\Setting::DEFAULT_LOT_ITEM_NO_TAX_OOS, $lotItem->AccountId);
            }
        }

        if (
            $inputDto->id
            && isset($inputDto->replacementPrice)
            && $lotItem->ReplacementPrice !== (float)$inputDto->replacementPrice
        ) {
            $dateNow = $this->getDateHelper()->formattedDate(
                $this->getCurrentDateSys($serviceAccountId),
                $serviceAccountId
            );
            $lotItem->Notes .= "$dateNow Previous Amount: $lotItem->ReplacementPrice New Amount: $inputDto->replacementPrice,\n";
        }

        $this->setIfAssign($lotItem, 'replacementPrice', self::STRATEGY_PARSE);

        $winningAuctionInput = WinningAuctionInput::new()->fromDto($inputDto);
        if ($winningAuctionInput->isSet()) {
            $lotItem->AuctionId = $this->createAuctionSoldIdDetector()->detectFromInput(
                $winningAuctionInput,
                Cast::toInt($inputDto->syncNamespaceId),
                $serviceAccountId,
                true
            );
        }
        $winningBidderInput = WinningBidderInput::new()->fromDto($inputDto);
        if ($winningBidderInput->isSet()) {
            $lotItem->WinningBidderId = $this->createWinningBidderIdDetector()->detectFromInput(
                $winningBidderInput,
                Cast::toInt($inputDto->syncNamespaceId),
                $serviceAccountId,
                true
            );
        }

        // Related with namespace fields

        if (isset($inputDto->consignorSyncKey)) {
            $user = $this->getUserLoader()->loadBySyncKey(
                $inputDto->consignorSyncKey,
                Cast::toInt($inputDto->syncNamespaceId),
                $serviceAccountId,
                true
            );
            $lotItem->ConsignorId = $user->Id ?? null;
        }
    }

    /**
     * Create category
     * @param string $categoryName
     * @return LotCategory|null
     */
    protected function createCategory(string $categoryName): ?LotCategory
    {
        $configDto = $this->getConfigDto();

        if (!$this->getLotItemMakerDtoHelper()->shouldAutoCreateCategory()) {
            return null;
        }

        /**
         * @var LotCategoryMakerInputDto $lotCategoryInputDto
         * @var LotCategoryMakerConfigDto $lotCategoryConfigDto
         */
        [$lotCategoryInputDto, $lotCategoryConfigDto] = LotCategoryMakerDtoFactory::new()->createDtos(
            Mode::WEB_ADMIN,
            $configDto->editorUserId,
            $configDto->serviceAccountId,
            $configDto->systemAccountId
        );
        $lotCategoryInputDto->name = $categoryName;

        $lotCategoryMakerValidator = $this->createLotCategoryMakerValidator()
            ->construct($lotCategoryInputDto, $lotCategoryConfigDto);
        if (!$lotCategoryMakerValidator->validate()) {
            return null;
        }

        $lotCategoryMakerProducer = $this->createLotCategoryMakerProducer()
            ->construct($lotCategoryInputDto, $lotCategoryConfigDto);
        $lotCategoryMakerProducer->produce();
        return $lotCategoryMakerProducer->getLotCategory();
    }

    /**
     * Create lot images
     * @param string[] $images Array of image links
     * @param int $counter
     * @param int $editorUserId
     * @return LotImage[]
     */
    private function createImages(array $images, int $counter, int $editorUserId): array
    {
        $lotImages = [];
        foreach ($images as $image) {
            if ($counter < $this->cfg()->get('core->lot->image->perLotLimit') && $image) {
                $lotImage = $this->createEntityFactory()->lotImage();
                $lotImage->LotItemId = $this->lotItem->Id;
                $lotImage->ImageLink = $image;
                $lotImage->Order = $counter;
                $this->getLotImageWriteRepository()->saveWithModifier($lotImage, $editorUserId);
                $lotImages[] = $lotImage;
                $counter++;
                $this->addedImages++;
            } else {
                $this->rejectedImages++;
            }
        }
        return $lotImages;
    }

    /**
     * Update lot images
     */
    private function updateImages(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $resultImage = (array)$inputDto->images;

        // Change existing images order
        $counter = 0;
        $oldImages = $this->getLotImageLoader()->loadForLot($this->lotItem->Id);
        $imagePerLotLimit = $this->cfg()->get('core->lot->image->perLotLimit');
        $existingLotImages = [];
        foreach ($oldImages as $key => $lotImage) {
            $subFolder = substr((string)($lotImage->Id), 0, 4);
            $thumbDirPath = path()->qcodoImageAssets() . '/lot/' . $subFolder;
            $success = FolderManager::new()->rrmdir($thumbDirPath);
            if (!$success) {
                log_warning('Failed to remove lot image thumb: ' . $thumbDirPath);
            }
            foreach ($resultImage as $key2 => $img) {
                if (
                    $lotImage->LotItemId
                    && trim($lotImage->ImageLink) === trim($img)
                ) {
                    $lotImage->Order = $key2;
                    $this->getLotImageWriteRepository()->saveWithModifier($lotImage, $configDto->editorUserId);
                    $existingLotImages[] = $lotImage;
                    $counter++;
                    unset($oldImages[$key], $resultImage[$key2]);
                }

                if (!$img) {
                    unset($resultImage[$key2]);
                }
            }
        }

        /** @var LotImage[] $resultOldImages */
        $resultOldImages = [];
        foreach ($oldImages as $lotImage) {
            $resultOldImages[] = $lotImage;
        }

        foreach ($resultImage as $key => $img) {
            if ($counter < $imagePerLotLimit && $img) {
                if (isset($resultOldImages[$counter])) {
                    $resultOldImages[$counter]->LotItemId = $this->lotItem->Id;
                    $resultOldImages[$counter]->ImageLink = $img;
                    $resultOldImages[$counter]->Order = $key;
                    $this->getLotImageWriteRepository()->saveWithModifier($resultOldImages[$counter], $configDto->editorUserId);
                    unset($resultImage[$key], $resultOldImages[$counter]);
                    $counter++;
                } else {
                    break;
                }
                $this->addedImages++;
            } else {
                $this->rejectedImages++;
            }
        }

        foreach ($resultOldImages as $lotImage) {
            $this->getLotImageWriteRepository()->deleteWithModifier($lotImage, $configDto->editorUserId);
        }

        $newLotImages = $this->createImages($resultImage, $counter, $configDto->editorUserId);

        $currentLotImages = array_merge($newLotImages, $existingLotImages);
        foreach ($currentLotImages as $lotImage) {
            foreach ($resultImage as $key => $value) {
                if ($lotImage->ImageLink === $value) {
                    $lotImage->Order = $key;
                    $this->getLotImageWriteRepository()->saveWithModifier($lotImage, $configDto->editorUserId);
                }
            }
        }
    }

    protected function updateLocations(): void
    {
        $inputDto = $this->getInputDto();

        if (isset($inputDto->specificLocation)) {
            $location = $this->createLocationSavingIntegrator()->save($this, $inputDto->specificLocation, Constants\Location::TYPE_LOT_ITEM);
            $this->setEntityLocation(Constants\Location::TYPE_LOT_ITEM, $location);
        }

        $this->createLocationSavingIntegrator()->removeExcessCommonOrSpecificLocation(
            $this,
            'specificLocation',
            ['location'],
            Constants\Location::TYPE_LOT_ITEM,
            $this->lotItem,
            'LocationId',
        );
    }

    /**
     * Get lot categories by DTO categoriesIds or categoriesNames
     * Create new category if autoCreateCategory set to true
     * @return LotCategory[]
     */
    private function getCategories(): array
    {
        $inputDto = $this->getInputDto();
        if ($this->categories === null) {
            $categoriesNames = $inputDto->categoriesNames;

            $categories = [];
            if ($categoriesNames) {
                foreach ($categoriesNames as $categoryName) {
                    $categoryName = trim($categoryName);
                    if ($categoryName === '') {
                        continue;
                    }
                    $category = $this->getLotCategoryLoader()->loadByName($categoryName);
                    $category = $category ?: $this->createCategory($categoryName);
                    if ($category) {
                        $categories[] = $category;
                    }
                }
            }
            $this->categories = $categories;
        }
        return $this->categories;
    }

    /**
     * Get lot categories IDs
     * @return int[]
     */
    public function getCategoriesIds(): array
    {
        $inputDto = $this->getInputDto();
        $categoryIds = $inputDto->categoriesIds;
        if ($categoryIds) {
            return $categoryIds;
        }

        $categoriesIds = [];
        foreach ($this->getCategories() as $category) {
            $categoriesIds[] = $category->Id;
        }
        return array_unique($categoriesIds);
    }

    protected function updateConsignorCommissionFee(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();

        $lotItem = $this->getLotItem();
        $commissionDto = ConsignorCommissionFeeRelatedEntityDto::new()->fromEntityMakerInputDto(
            $inputDto,
            'consignorCommissionId',
            'consignorCommissionRanges',
            'consignorCommissionCalculationMethod'
        );
        $lotItem->ConsignorCommissionId = $this->createConsignorCommissionFeeRelatedEntityProducer()->update(
            $lotItem->ConsignorCommissionId,
            $commissionDto,
            Constants\ConsignorCommissionFee::LEVEL_LOT_ITEM,
            $lotItem->Id,
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
        $lotItem->ConsignorSoldFeeId = $this->createConsignorCommissionFeeRelatedEntityProducer()->update(
            $lotItem->ConsignorSoldFeeId,
            $soldFeeDto,
            Constants\ConsignorCommissionFee::LEVEL_LOT_ITEM,
            $lotItem->Id,
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
        $lotItem->ConsignorUnsoldFeeId = $this->createConsignorCommissionFeeRelatedEntityProducer()->update(
            $lotItem->ConsignorUnsoldFeeId,
            $unsoldFeeDto,
            Constants\ConsignorCommissionFee::LEVEL_LOT_ITEM,
            $lotItem->Id,
            $configDto->editorUserId,
            $configDto->mode
        );
        $this->getLotItemWriteRepository()->saveWithModifier($lotItem, $configDto->editorUserId);
    }
}
