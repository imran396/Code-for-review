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
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\SearchIndex\SearchIndexQueueCreateTrait;

/**
 * Class SearchIndexUpdater
 * @package Sam\Observer\LotCategory
 * @internal
 */
class SearchIndexUpdater extends CustomizableClass implements EntityUpdateObserverHandlerInterface
{
    use LotItemLoaderAwareTrait;
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
    public function onUpdate(EntityObserverSubject $subject): void
    {
        /** @var LotCategory $lotCategory */
        $lotCategory = $subject->getEntity();
        $lotItemIdArray = $this->getLotItemLoader()->loadIdsByCategory($lotCategory->Id);
        $this->createSearchIndexQueue()->add(Constants\Search::ENTITY_LOT_ITEM, $lotItemIdArray);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isAnyPropertyModified(['Active', 'Name']);
    }
}
