<?php
/**
 * Help methods for Lot Category ordering
 * SAM-4040: Lot Category modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 12, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Order;

use ActionQueue;
use LotCategory;
use Sam\Account\Main\MainAccountDetectorCreateTrait;
use Sam\ActionQueue\ActionQueueManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Category\Tree\LotCategoryFullTreeManagerAwareTrait;
use Sam\Lot\Category\Validate\LotCategoryExistenceCheckerAwareTrait;
use Sam\Settings\Save\SettingsProducerCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\LotCategory\LotCategoryReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotCategory\LotCategoryWriteRepositoryAwareTrait;

/**
 * Class LotCategoryOrderer
 * @package Sam\Lot\Category
 */
class LotCategoryOrderer extends CustomizableClass
{
    use ActionQueueManagerAwareTrait;
    use ConfigRepositoryAwareTrait;
    use LotCategoryExistenceCheckerAwareTrait;
    use LotCategoryFullTreeManagerAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotCategoryOrdererAwareTrait;
    use LotCategoryReadRepositoryCreateTrait;
    use LotCategoryWriteRepositoryAwareTrait;
    use MainAccountDetectorCreateTrait;
    use SettingsManagerAwareTrait;
    use SettingsProducerCreateTrait;

    /**
     * @var LotCategory|null
     */
    protected ?LotCategory $targetLotCategory = null;

    /**
     * @var LotCategory|null
     */
    protected ?LotCategory $markerLotCategory = null;

    /**
     * @var int|null
     */
    protected ?int $parentId = null;

    private const POSITION_AFTER = 1;
    private const POSITION_BEFORE = 2;
    private const POSITION_FIRST = 3;
    private const POSITION_LAST = 4;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $lotCategoryId
     * @param int $afterId
     * @param int $editorUserId
     * @return void
     */
    public function placeAfter(int $lotCategoryId, int $afterId, int $editorUserId): void
    {
        $this->targetLotCategory = $this->getLotCategoryLoader()->load($lotCategoryId);
        $this->markerLotCategory = $this->getLotCategoryLoader()->load($afterId);
        $this->parentId = $this->markerLotCategory->ParentId ?? null;
        $position = self::POSITION_AFTER;
        $this->moveLotCategory($position, $editorUserId);
    }

    /**
     * @param int $lotCategoryId
     * @param int $beforeId
     * @param int $editorUserId
     * @return void
     */
    public function placeBefore(int $lotCategoryId, int $beforeId, int $editorUserId): void
    {
        $this->targetLotCategory = $this->getLotCategoryLoader()->load($lotCategoryId);
        $this->markerLotCategory = $this->getLotCategoryLoader()->load($beforeId);
        $this->parentId = $this->markerLotCategory->ParentId ?? null;
        $position = self::POSITION_BEFORE;
        $this->moveLotCategory($position, $editorUserId);
    }

    /**
     * @param int $lotCategoryId
     * @param int|null $parentId
     * @param int $editorUserId
     * @return void
     */
    public function placeFirst(int $lotCategoryId, ?int $parentId, int $editorUserId): void
    {
        $this->targetLotCategory = $this->getLotCategoryLoader()->load($lotCategoryId);
        $this->parentId = Cast::toInt($parentId, Constants\Type::F_INT_POSITIVE);
        $position = self::POSITION_FIRST;
        $this->moveLotCategory($position, $editorUserId);
    }

    /**
     * @param int $lotCategoryId
     * @param int|null $parentId
     * @param int $editorUserId
     * @return void
     */
    public function placeLast(int $lotCategoryId, ?int $parentId, int $editorUserId): void
    {
        $this->targetLotCategory = $this->getLotCategoryLoader()->load($lotCategoryId);
        $this->parentId = Cast::toInt($parentId, Constants\Type::F_INT_POSITIVE);
        $position = self::POSITION_LAST;
        $this->moveLotCategory($position, $editorUserId);
    }

    /**
     * @param LotCategory[] $lotCategories
     * @return LotCategory[]
     */
    protected function removeTargetCategoryFromArray(array $lotCategories): array
    {
        foreach ($lotCategories as $index => $lotCategory) {
            if ($this->targetLotCategory->Id === $lotCategory->Id) {
                unset($lotCategories[$index]);
            }
        }
        return array_values($lotCategories);
    }

    /**
     * @param LotCategory|null $parentCategory
     * @param int $editorUserId
     * @return void
     */
    public function applyLevel(?LotCategory $parentCategory, int $editorUserId): void
    {
        $level = $parentCategory->Level ?? -1;
        $parentId = $parentCategory->Id ?? null;
        $childCategoryArray = $this->getLotCategoryLoader()->loadChildrenOfCategoryId($parentId);
        foreach ($childCategoryArray as $childCategory) {
            $childCategory->Level = $level + 1;
            $this->getLotCategoryWriteRepository()->saveWithModifier($childCategory, $editorUserId);
            $this->applyLevel($childCategory, $editorUserId);
        }
    }

    /**
     * @param LotCategory[] $lotCategories
     * @param int $editorUserId
     * @return void
     */
    protected function applySiblingOrder(array $lotCategories, int $editorUserId): void
    {
        $siblingOrder = 1;
        foreach ($lotCategories as $lotCategory) {
            $lotCategory->SiblingOrder = $siblingOrder;
            $this->getLotCategoryWriteRepository()->saveWithModifier($lotCategory, $editorUserId);
            $siblingOrder++;
        }
    }

    /**
     * @param int $position
     * @param int $editorUserId
     * @return void
     */
    protected function moveLotCategory(int $position, int $editorUserId): void
    {
        if ($this->targetLotCategory) {
            $sortedLotCategories = [];
            $options['order']['SiblingOrder'] = true;
            if (
                $position === self::POSITION_AFTER
                || $position === self::POSITION_BEFORE
            ) {
                if ($this->markerLotCategory) {
                    $lotCategories = $this->getLotCategoryLoader()
                        ->loadChildrenOfCategoryId($this->markerLotCategory->ParentId, $options);
                    $lotCategories = $this->removeTargetCategoryFromArray($lotCategories);
                    $searchedIndex = null;
                    foreach ($lotCategories as $index => $lotCategory) {
                        if ($lotCategory->Id === $this->markerLotCategory->Id) {
                            $searchedIndex = $index;
                        }
                    }
                    if ($position === self::POSITION_AFTER) {
                        ++$searchedIndex;
                    }
                    $rightSplicedCategories = array_splice($lotCategories, $searchedIndex);
                    $sortedLotCategories = array_merge(
                        $lotCategories,
                        [$this->targetLotCategory],
                        $rightSplicedCategories
                    );
                }
            } else {
                if ($this->targetLotCategory->ParentId !== $this->parentId) {
                    $lotCategories = $this->getLotCategoryLoader()
                        ->loadChildrenOfCategoryId($this->targetLotCategory->ParentId, $options);
                    $lotCategories = $this->removeTargetCategoryFromArray($lotCategories);
                    $this->applySiblingOrder($lotCategories, $editorUserId);
                }
                $lotCategories = $this->getLotCategoryLoader()->loadChildrenOfCategoryId($this->parentId, $options);
                $lotCategories = $this->removeTargetCategoryFromArray($lotCategories);
                if ($position === self::POSITION_FIRST) {
                    $sortedLotCategories = array_merge([$this->targetLotCategory], $lotCategories);
                } else {
                    $sortedLotCategories = array_merge($lotCategories, [$this->targetLotCategory]);
                }
            }
            $this->applySiblingOrder($sortedLotCategories, $editorUserId);

            //Apply ParentId at the end to prevent Optimistic Locking error.
            $this->targetLotCategory->ParentId = $this->parentId;
            $this->getLotCategoryWriteRepository()->saveWithModifier($this->targetLotCategory, $editorUserId);

            $parentCategory = $this->lotCategoryLoader->load($this->parentId);
            $this->applyLevel($parentCategory, $editorUserId);
        }
    }

    /**
     * Set and save flag of availability to use global order numbers of lot categories
     * It also add global order update task into queue and remove from it.
     *
     * @param bool $isAvailable
     * @param int $editorUserId
     * @return void
     */
    public function saveGlobalOrderAvailable(bool $isAvailable, int $editorUserId): void
    {
        $isAvailable
            ? $this->removeFromActionQueueTaskForGlobalOrderUpdate($editorUserId)
            : $this->addToActionQueueTaskForGlobalOrderUpdate($editorUserId);
        $this->createSettingsProducer()->update(
            [
                Constants\Setting::LOT_CATEGORY_GLOBAL_ORDER_AVAILABLE => $isAvailable,
            ],
            $this->createMainAccountDetector()->id(),
            $editorUserId
        );
    }

    /**
     * Refresh global_order for lot categories
     * @param int $editorUserId
     * @return void
     */
    public function refreshGlobalOrders(int $editorUserId): void
    {
        $globalOrder = 0;
        $option = ['order' => ['SiblingOrder' => true]];
        $lotCategories = $this->getLotCategoryLoader()->loadCategoryWithDescendants(null, $option);
        foreach ($lotCategories as $lotCategory) {
            $lotCategory->GlobalOrder = ++$globalOrder;
            $this->getLotCategoryWriteRepository()->saveWithModifier($lotCategory, $editorUserId);
        }
        $this->saveGlobalOrderAvailable(true, $editorUserId);
    }

    /**
     * @return int Maximal possible level, they are numerated from 0
     */
    public function getMaxLevel(): int
    {
        return (int)$this->cfg()->get('core->lot->category->maxLevel');
    }

    /**
     * Add global order update task into ActionQueue
     *
     * @param int $editorUserId
     * @return void
     */
    public function addToActionQueueTaskForGlobalOrderUpdate(int $editorUserId): void
    {
        $actionQueueRecord = $this->getLotCategoryOrderer()->loadActionQueueTaskForGlobalOrderUpdate();
        $accountId = $this->cfg()->get('core->portal->mainAccountId');
        $data = serialize(['AccountId' => $accountId]);
        $actionQueueManager = $this->getActionQueueManager();
        if (
            !$actionQueueRecord
            || $actionQueueRecord->Attempts === $actionQueueRecord->MaxAttempts
        ) {
            $actionQueueManager->addToQueue(
                GlobalOrderUpdateActionHandler::class,
                $data,
                $editorUserId,
                Constants\ActionQueue::AQID_GLOBAL_ORDER_UPDATE,
                null,
                Constants\ActionQueue::HIGH
            );
        }
    }

    /**
     * Load ActionQueue record that was registered for updating global order numbers.
     * @return ActionQueue|null
     */
    public function loadActionQueueTaskForGlobalOrderUpdate(): ?ActionQueue
    {
        $actionQueueManager = $this->getActionQueueManager();
        $encodedIdentifier = $actionQueueManager->encodeIdentifier(Constants\ActionQueue::AQID_GLOBAL_ORDER_UPDATE);
        $actionQueueRecord = $actionQueueManager->loadByIdentifier($encodedIdentifier);
        return $actionQueueRecord;
    }

    /**
     * Remove global order update task from ActionQueue.
     * @param int $editorUserId
     * @return void
     */
    public function removeFromActionQueueTaskForGlobalOrderUpdate(int $editorUserId): void
    {
        $actionQueueRecord = $this->loadActionQueueTaskForGlobalOrderUpdate();
        if (!$actionQueueRecord) {
            return;
        }
        $this->getActionQueueManager()->removeFromQueue($actionQueueRecord, $editorUserId);
    }

    /**
     * Re-arrange all lot categories in alphabetical order and refresh global order numbers of categories,
     * so it wouldn't need to perform in back-ground task by cron.
     * @param int $editorUserId
     * @return bool true: if re-ordered successfully, false: if categories already were ordered alphabetically earlier;
     */
    public function orderAllByName(int $editorUserId): bool
    {
        $this->sortAlphabetically(null, $editorUserId);
        if ($this->isLotCategoryGlobalOrderAvailable()) {
            log_debug('No need to re-order, because categories already were ordered alphabetically');
            return false;
        }

        $this->refreshGlobalOrders($editorUserId);
        log_debug('Categories are re-ordered alphabetically and global order numbers are updated');
        return true;
    }

    /**
     * @param int|null $parentId
     * @param int $editorUserId
     * @return void
     */
    protected function sortAlphabetically(?int $parentId, int $editorUserId): void
    {
        $lotCategories = $this->getLotCategoryLoader()->loadAllChildren($parentId);
        if (count($lotCategories)) {
            usort(
                $lotCategories,
                static function ($a, $b) {
                    return strcasecmp($a->Name, $b->Name);
                }
            );
            $siblingOrder = 0;
            foreach ($lotCategories as $lotCategory) {
                $lotCategory->SiblingOrder = $siblingOrder;
                $siblingOrder++;
                $this->sortAlphabetically($lotCategory->Id, $editorUserId);
                $this->getLotCategoryWriteRepository()->saveWithModifier($lotCategory, $editorUserId);
            }
        }
    }

    public function orderAllBySiblingOrder(int $editorUserId): void
    {
        $globalOrder = 0;
        $siblingOrders = [];
        $prevLevel = 0;
        $option = ['order' => ['SiblingOrder' => true]];
        $lotCategories = $this->getLotCategoryLoader()->loadCategoryWithDescendants(null, $option);
        foreach ($lotCategories as $lotCategory) {
            $level = $lotCategory->Level;
            if (
                $prevLevel < $level
                || !isset($siblingOrders[$level])
            ) {
                $siblingOrders[$level] = 0;
            }
            $siblingOrders[$level]++;
            $lotCategory->SiblingOrder = $siblingOrders[$level];
            $lotCategory->GlobalOrder = ++$globalOrder;
            $this->getLotCategoryWriteRepository()->saveWithModifier($lotCategory, $editorUserId);
            $prevLevel = $level;
        }
    }

    /**
     * Return highest global_order
     *
     * @return int
     */
    public function suggestGlobalOrder(): int
    {
        $category = $this->createLotCategoryReadRepository()
            ->filterActive(true)
            ->orderByGlobalOrder(false)
            ->loadEntity();
        $globalOrder = $category->GlobalOrder ?? 0;
        $globalOrder++;
        return $globalOrder;
    }

    /**
     * Get child categories level depth
     *
     * @param int $lotCategoryId
     * @return int
     */
    public function getChildLevelDepth(int $lotCategoryId): int
    {
        /** @var LotCategory $processedCategory */
        $processedCategory = null;
        $shouldIterateAmongChildren = false;
        $childMaxLevel = null;
        $allCategories = $this->getLotCategoryFullTreeManager()->load();
        foreach ($allCategories as $lotCategory) {
            if ($shouldIterateAmongChildren) {
                $shouldIterateAmongChildren = $lotCategory->Level > $processedCategory->Level;
            }
            if (
                $shouldIterateAmongChildren
                && $lotCategory->Level > $childMaxLevel
            ) {
                $childMaxLevel = $lotCategory->Level;
            }
            if ($lotCategory->Id === $lotCategoryId) {
                $processedCategory = $lotCategory;
                $shouldIterateAmongChildren = true;
            }
        }
        $childLevelDepth = isset($childMaxLevel) ? $childMaxLevel - $processedCategory->Level : 0;
        return $childLevelDepth;
    }

    public function isLotCategoryGlobalOrderAvailable(): bool
    {
        return (bool)$this->getSettingsManager()->getForMain(Constants\Setting::LOT_CATEGORY_GLOBAL_ORDER_AVAILABLE);
    }
}
