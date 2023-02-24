<?php
/**
 * Class for producing of Lot Category entity
 *
 * SAM-8856: Lot category entity-maker module structural adjustments for v3-5
 * SAM-4048: LotCategory entity maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 5, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotCategory\Save;

use Exception;
use LotCategory;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Date\CurrentDateTrait;
use Sam\EntityMaker\Base\Common\ValueResolver;
use Sam\EntityMaker\Base\Save\BaseMakerProducer;
use Sam\EntityMaker\LotCategory\Common\LotCategoryMakerCustomFieldManager;
use Sam\EntityMaker\LotCategory\Dto\LotCategoryMakerConfigDto;
use Sam\EntityMaker\LotCategory\Dto\LotCategoryMakerDtoHelperAwareTrait;
use Sam\EntityMaker\LotCategory\Dto\LotCategoryMakerInputDto;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Category\Order\LotCategoryOrdererAwareTrait;
use Sam\Lot\Category\Tree\LotCategoryFullTreeManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotCategory\LotCategoryWriteRepositoryAwareTrait;

/**
 * Class LotCategoryMakerProducer
 * @package Sam\EntityMaker\LotCategory
 *
 * @method LotCategoryMakerInputDto getInputDto()
 * @method LotCategoryMakerConfigDto getConfigDto()
 */
class LotCategoryMakerProducer extends BaseMakerProducer
{
    use CurrentDateTrait;
    use DbConnectionTrait;
    use EntityFactoryCreateTrait;
    use LotCategoryFullTreeManagerAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotCategoryMakerDtoHelperAwareTrait;
    use LotCategoryOrdererAwareTrait;
    use LotCategoryWriteRepositoryAwareTrait;

    /**
     * @var LotCategory|null
     */
    protected ?LotCategory $lotCategory = null;

    /**
     * @var LotCategoryMakerCustomFieldManager
     */
    protected LotCategoryMakerCustomFieldManager $customFieldManager;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotCategoryMakerInputDto $inputDto
     * @param LotCategoryMakerConfigDto $configDto
     * @return $this
     */
    public function construct(
        LotCategoryMakerInputDto $inputDto,
        LotCategoryMakerConfigDto $configDto
    ): static {
        $this->setInputDto($inputDto);
        $this->setConfigDto($configDto);
        $this->customFieldManager = LotCategoryMakerCustomFieldManager::new()->construct($inputDto, $configDto);
        $this->getLotCategoryMakerDtoHelper()->construct($configDto->mode);
        return $this;
    }

    /**
     * @return void
     */
    protected function assignValues(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $lotCategory = $this->getLotCategory();
        if (isset($inputDto->name)) {
            $lotCategory->Name = trim($inputDto->name);
        }

        if (isset($inputDto->parentId)) {
            $lotCategory->ParentId = Cast::toInt($inputDto->parentId);
        }

        if (isset($inputDto->buyNowAmount)) {
            $buyNowAmount = $configDto->mode->isSoap()
                ? $inputDto->buyNowAmount
                : $this->getNumberFormatter()->parse($inputDto->buyNowAmount);
            $lotCategory->BuyNowAmount = trim((string)$inputDto->buyNowAmount) === '' ? null : $buyNowAmount;
        }

        if (isset($inputDto->startingBid)) {
            $startingBid = $configDto->mode->isSoap()
                ? $inputDto->startingBid
                : $this->getNumberFormatter()->parse($inputDto->startingBid);
            $lotCategory->StartingBid = trim((string)$inputDto->startingBid) === '' ? null : $startingBid;
        }

        if (isset($inputDto->imageLink)) {
            $lotCategory->ImageLink = trim((string)$inputDto->imageLink);
        }

        if (isset($inputDto->consignmentCommission)) {
            $consignmentCommission = $configDto->mode->isSoap()
                ? $inputDto->consignmentCommission
                : $this->getNumberFormatter()->parsePercent($inputDto->consignmentCommission);
            $lotCategory->ConsignmentCommission = trim((string)$inputDto->consignmentCommission) === '' ? null : $consignmentCommission;
        }

        $this->setIfAssign($lotCategory, 'hideEmptyFields', self::STRATEGY_BOOL);
        $this->setIfAssign($lotCategory, 'quantityDigits', self::STRATEGY_PARSE);

        if (
            isset($inputDto->active)
            && $inputDto->active === 'N'
        ) {
            $lotCategory->Active = false;
        } else {
            $lotCategory->Active = true;
        }

        if (isset($inputDto->parentName)) {
            $parentCategory = $this->getLotCategoryLoader()->loadByName((string)$inputDto->parentName);
            if ($parentCategory) {
                $lotCategory->ParentId = $parentCategory->Id;
            }
        }

        $level = 0;
        if ($lotCategory->ParentId) {
            $parentCategory = $this->getLotCategoryLoader()->load($lotCategory->ParentId);
            $level = $parentCategory->Level + 1;
        }
        $lotCategory->Level = $level;
    }

    /**
     * @return void
     */
    public function produce(): void
    {
        $this->assertInputDto();
        $inputDto = $this->getLotCategoryMakerDtoHelper()->prepareValues($this->getInputDto(), $this->getConfigDto());
        $this->setInputDto($inputDto);
        $this->assignValues();
        $this->atomicSave();
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
            log_errorBackTrace("Rollback transaction, because lot category save failed.");
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
        $lotCategory = $this->getLotCategory();
        $isNew = !$inputDto->id;
        if ($isNew) {
            $lotCategory->GlobalOrder = $this->getLotCategoryOrderer()->suggestGlobalOrder();
        }
        $this->getLotCategoryFullTreeManager()->deleteCache();
        $this->getLotCategoryWriteRepository()->saveWithModifier($lotCategory, $configDto->editorUserId);
        $this->lotCategory = $lotCategory;
        $this->doPostUpdate();
    }

    /**
     * Run necessary actions after LotCategory was created
     * @return void
     */
    protected function doPostUpdate(): void
    {
        $configDto = $this->getConfigDto();
        $this->customFieldManager->saveCustomFields($this->getLotCategory()->Id, $configDto->editorUserId);
        $this->reorderCategories();
        $this->getLotCategoryOrderer()->applyLevel($this->getLotCategory(), $configDto->editorUserId);
    }

    /**
     * Get LotCategory
     * @return LotCategory
     */
    public function getLotCategory(): LotCategory
    {
        if ($this->lotCategory === null) {
            $this->lotCategory = $this->loadLotCategoryOrCreate();
        }
        return $this->lotCategory;
    }

    /**
     * Load or create LotCategory depending on the LotCategory Id
     * @return LotCategory
     */
    protected function loadLotCategoryOrCreate(): LotCategory
    {
        $inputDto = $this->getInputDto();
        $lotCategoryId = (int)$inputDto->id;
        if ($lotCategoryId) {
            $lotCategory = $this->getLotCategoryLoader()->load($lotCategoryId);
            if ($lotCategory !== null) {
                return $lotCategory;
            }
            log_debug("Cannot load LotCategory" . composeSuffix(['id' => $lotCategoryId]));
        }
        $lotCategory = $this->createEntityFactory()->lotCategory();
        $lotCategory->Active = true;
        return $lotCategory;
    }

    /**
     * Reorder categories
     */
    protected function reorderCategories(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $lotCategory = $this->getLotCategory();
        if (ValueResolver::new()->isTrue($inputDto->first)) {
            $this->getLotCategoryOrderer()->placeFirst($lotCategory->Id, $lotCategory->ParentId, $configDto->editorUserId);
        } elseif (ValueResolver::new()->isTrue($inputDto->last)) {
            $this->getLotCategoryOrderer()->placeLast($lotCategory->Id, $lotCategory->ParentId, $configDto->editorUserId);
        } elseif (isset($inputDto->beforeName)) {
            $name = (string)$inputDto->beforeName;
            $beforeLotCategory = $this->getLotCategoryLoader()->loadByName($name);
            if (!$beforeLotCategory) {
                log_error('Available lot category (before) not found by name' . composeSuffix(['name' => $name]));
                return;
            }
            $this->getLotCategoryOrderer()->placeBefore($lotCategory->Id, $beforeLotCategory->Id, $configDto->editorUserId);
        } elseif (isset($inputDto->afterName)) {
            $name = (string)$inputDto->afterName;
            $afterLotCategory = $this->getLotCategoryLoader()->loadByName($name);
            if (!$afterLotCategory) {
                log_error('Available lot category (after) not found by name' . composeSuffix(['name' => $name]));
                return;
            }
            $this->getLotCategoryOrderer()->placeAfter($lotCategory->Id, $afterLotCategory->Id, $configDto->editorUserId);
        } elseif (isset($inputDto->beforeId)) {
            $beforeLotCategoryId = (int)$inputDto->beforeId;
            $beforeLotCategory = $this->getLotCategoryLoader()->load($beforeLotCategoryId);
            if (!$beforeLotCategory) {
                log_error('Available lot category (before) not found by id' . composeSuffix(['lc' => $beforeLotCategoryId]));
                return;
            }
            $this->getLotCategoryOrderer()->placeBefore($lotCategory->Id, $beforeLotCategory->Id, $configDto->editorUserId);
        } elseif (isset($inputDto->afterId)) {
            $afterLotCategoryId = (int)$inputDto->afterId;
            $afterLotCategory = $this->getLotCategoryLoader()->load($afterLotCategoryId);
            if (!$afterLotCategory) {
                log_error('Available lot category (after) not found by id' . composeSuffix(['lc' => $afterLotCategoryId]));
                return;
            }
            $this->getLotCategoryOrderer()->placeAfter($lotCategory->Id, $afterLotCategory->Id, $configDto->editorUserId);
        }
    }
}
