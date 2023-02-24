<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 17, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\LotItemCategory\Internal;

use LotItemCategory;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\SearchIndex\SearchIndexQueueCreateTrait;

/**
 * Class SearchIndexUpdater
 * @package Sam\Observer\LotItemCategory
 * @internal
 */
class SearchIndexUpdater extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use SearchIndexQueueCreateTrait;

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
        /** @var LotItemCategory $lotItemCategory */
        $lotItemCategory = $subject->getEntity();
        $this->createSearchIndexQueue()->add(Constants\Search::ENTITY_LOT_ITEM, $lotItemCategory->LotItemId);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        /** @var LotItemCategory $lotItemCategory */
        $lotItemCategory = $subject->getEntity();
        $this->createSearchIndexQueue()->add(Constants\Search::ENTITY_LOT_ITEM, $lotItemCategory->LotItemId);

        $oldLotItemId = $subject->getOldPropertyValue('LotItemId');
        if ($oldLotItemId) {
            $this->createSearchIndexQueue()->add(Constants\Search::ENTITY_LOT_ITEM, $oldLotItemId);
        }
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isPropertyModified('LotCategoryId');
    }
}
