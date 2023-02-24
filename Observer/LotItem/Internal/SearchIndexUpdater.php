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

namespace Sam\Observer\LotItem\Internal;

use LotItem;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Invoice\Common\Validate\InvoiceExistenceCheckerAwareTrait;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\SearchIndex\SearchIndexQueueCreateTrait;

/**
 * Class SearchIndexUpdater
 * @package Sam\Observer\LotItem
 * @internal
 */
class SearchIndexUpdater extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use InvoiceExistenceCheckerAwareTrait;
    use InvoiceLoaderAwareTrait;
    use SearchIndexQueueCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function onCreate(EntityObserverSubject $subject): void
    {
        /** @var LotItem $lotItem */
        $lotItem = $subject->getEntity();
        $this->createSearchIndexQueue()->add(Constants\Search::ENTITY_LOT_ITEM, $lotItem->Id);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        /** @var LotItem $lotItem */
        $lotItem = $subject->getEntity();
        if ($this->getInvoiceExistenceChecker()->existByLotItemId($lotItem->Id)) {
            $invoices = $this->getInvoiceLoader()->loadForLotItem($lotItem->Id);
            if ($invoices) {
                $this->createSearchIndexQueue()->add(Constants\Search::ENTITY_INVOICE, $invoices[0]->Id);
            }
        }
        $this->createSearchIndexQueue()->add(Constants\Search::ENTITY_LOT_ITEM, $lotItem->Id);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isAnyPropertyModified(['Active', 'Name', 'Description']);
    }
}
