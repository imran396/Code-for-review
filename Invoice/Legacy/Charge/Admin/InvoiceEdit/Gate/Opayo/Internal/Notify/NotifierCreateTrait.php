<?php
/**
 * SAM-10913: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Opayo invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 08, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Opayo\Internal\Notify;

trait NotifierCreateTrait
{
    /**
     * @var Notifier|null
     */
    protected ?Notifier $notifier = null;

    /**
     * @return Notifier
     */
    protected function createNotifier(): Notifier
    {
        return $this->notifier ?: Notifier::new();
    }

    /**
     * @param Notifier $notifier
     * @return $this
     * @internal
     */
    public function setNotifier(Notifier $notifier): static
    {
        $this->notifier = $notifier;
        return $this;
    }
}
