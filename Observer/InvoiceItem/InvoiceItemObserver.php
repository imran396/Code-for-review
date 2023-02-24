<?php
/**
 * Observer for InvoiceItem
 *
 * SAM-1652: Admin dashboard
 *
 * @author        Igors Kotlevskis
 * @package       com.swb.sam2
 * @version       SVN: $Id$
 * @since         Aug 28, 2013
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\InvoiceItem;

use InvoiceItem;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\InvoiceItem\Internal\AuctionBidderCollectedUpdater;
use Sam\Observer\InvoiceItem\Internal\AuctionCacheInvalidator;
use Sam\Observer\InvoiceItem\Internal\LotItemInvoiceIdUpdater;
use SplObserver;
use SplSubject;

/**
 * Class InvoiceItemObserver
 * @package Sam\Observer\InvoiceItem
 */
class InvoiceItemObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of InvoiceItemObserver
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param SplSubject $subject
     */
    public function update(SplSubject $subject): void
    {
        if (!$subject instanceof InvoiceItem) {
            log_warning('Subject not instance of InvoiceItem: ' . get_class($subject));
            return;
        }
        $handlers = [
            AuctionBidderCollectedUpdater::new(),
            AuctionCacheInvalidator::new(),
            LotItemInvoiceIdUpdater::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
