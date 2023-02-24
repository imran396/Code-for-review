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

namespace Sam\Observer\InvoiceItem\Internal;

use InvoiceItem;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;

/**
 * Class LotItemInvoiceIdUpdater
 * @package Sam\Observer\InvoiceItem
 * @internal
 */
class LotItemInvoiceIdUpdater extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use LotItemLoaderAwareTrait;
    use LotItemWriteRepositoryAwareTrait;

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
        /** @var InvoiceItem $invoiceItem */
        $invoiceItem = $subject->getEntity();
        $lotItem = $this->getLotItemLoader()->load($invoiceItem->LotItemId);
        if (!$lotItem) {
            log_error(
                "Available lot item not found by invoice item reference"
                . composeSuffix(['ii' => $invoiceItem->Id, 'ii.li' => $invoiceItem->LotItemId])
            );
            return;
        }
        if ($lotItem->InvoiceId === null) {
            $lotItem->InvoiceId = $invoiceItem->InvoiceId;
            $this->getLotItemWriteRepository()->saveWithSystemModifier($lotItem);
        }
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        /** @var InvoiceItem $invoiceItem */
        $invoiceItem = $subject->getEntity();
        $lotItem = $this->getLotItemLoader()->load($invoiceItem->LotItemId);
        if (!$lotItem) {
            log_error(
                "Available lot item not found by invoice item reference"
                . composeSuffix(['ii' => $invoiceItem->Id, 'ii.li' => $invoiceItem->LotItemId])
            );
            return;
        }
        if ($invoiceItem->isDeleted()) {
            $lotItem->InvoiceId = null;
            $this->getLotItemWriteRepository()->saveWithSystemModifier($lotItem);
        }
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isPropertyModified('Active');
    }
}
