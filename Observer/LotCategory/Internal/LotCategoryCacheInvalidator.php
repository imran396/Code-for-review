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

use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Category\Tree\LotCategoryFullTreeManagerAwareTrait;
use Sam\Lot\Category\Tree\LotCategoryTreeLotsQuantityManagerCreateTrait;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;

/**
 * Class LotCategoryCacheInvalidator
 * @package Sam\Observer\LotCategory\Internal
 */
class LotCategoryCacheInvalidator extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use LotCategoryFullTreeManagerAwareTrait;
    use LotCategoryTreeLotsQuantityManagerCreateTrait;

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
        $this->getLotCategoryFullTreeManager()->deleteCache();
        $this->createLotCategoryTreeLotsQuantityManager()->clearCache();
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        $this->getLotCategoryFullTreeManager()->deleteCache();
        if ($subject->isPropertyModified('Level')) {
            $this->createLotCategoryTreeLotsQuantityManager()->clearCache();
        }
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isModified();
    }
}
