<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 16, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\LotCategory\Internal;

use LotCategory;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Category\Validate\LotCategoryExistenceCheckerAwareTrait;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\Storage\ReadRepository\Entity\LotCategory\LotCategoryReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotCategory\LotCategoryWriteRepositoryAwareTrait;

/**
 * Class ChildCategoriesCounter
 * @package Sam\Observer\LotCategory
 * @internal
 */
class ChildCountUpdater extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use LotCategoryExistenceCheckerAwareTrait;
    use LotCategoryReadRepositoryCreateTrait;
    use LotCategoryWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    public function onCreate(EntityObserverSubject $subject): void
    {
        $this->update($subject);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        $this->update($subject);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isModified();
    }

    /**
     * @param EntityObserverSubject $subject
     */
    protected function update(EntityObserverSubject $subject): void
    {
        $parentCategoryIds = $this->collectParentCategoryIds($subject);
        foreach ($parentCategoryIds as $categoryId) {
            $this->updateForCategory($categoryId);
        }
    }

    /**
     * @param int $categoryId
     */
    protected function updateForCategory(int $categoryId): void
    {
        $category = $this->createLotCategoryReadRepository()
            ->filterId($categoryId)
            ->loadEntity();
        $category->ChildCount = $this->getLotCategoryExistenceChecker()->countChildren($categoryId);
        $this->getLotCategoryWriteRepository()->saveWithSystemModifier($category);
    }

    /**
     * @param EntityObserverSubject $subject
     * @return array
     */
    protected function collectParentCategoryIds(EntityObserverSubject $subject): array
    {
        $parentCategoryIds = [];
        /** @var LotCategory $lotCategory */
        $lotCategory = $subject->getEntity();
        if (
            $lotCategory->ParentId
            && (
                !$lotCategory->Active
                || $subject->isPropertyModified('ParentId')
            )
        ) {
            $parentCategoryIds[] = $lotCategory->ParentId;
        }

        if (
            $subject->isPropertyModified('ParentId')
            && $subject->getOldPropertyValue('ParentId')
        ) {
            $parentCategoryIds[] = $subject->getOldPropertyValue('ParentId');
        }
        $parentCategoryIds = array_unique($parentCategoryIds);
        return $parentCategoryIds;
    }
}
