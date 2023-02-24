<?php
/**
 * Observer for Invoice
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

namespace Sam\Observer\Invoice;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\Invoice\Internal\AuctionBidderCollectedUpdater;
use Sam\Observer\Invoice\Internal\AuctionCacheInvalidator;
use Sam\Observer\Invoice\Internal\SearchIndexUpdater;
use SplObserver;
use SplSubject;

/**
 * Class InvoiceObserver
 * @package Sam\Observer\Invoice
 */
class InvoiceObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * @var Invoice|null
     */
    protected ?Invoice $invoice = null;

    /**
     * Return an instance of InvoiceObserver
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
        if (!$subject instanceof Invoice) {
            log_warning('Subject not instance of Invoice: ' . get_class($subject));
            return;
        }
        $handlers = [
            AuctionBidderCollectedUpdater::new(),
            AuctionCacheInvalidator::new(),
            SearchIndexUpdater::new(),
        ];

        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
