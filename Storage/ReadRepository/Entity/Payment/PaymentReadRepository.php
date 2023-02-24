<?php
/**
 * SAM-3724 Payment related repositories  https://bidpath.atlassian.net/browse/SAM-3724
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           17 May, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of Payment filtered by criteria
 * $paymentRepository = \Sam\Storage\ReadRepository\Entity\Payment\PaymentReadRepository::new()
 *     ->filterTranCode($tranCodes)          // single value passed as argument
 *     ->filterId($ids);   // array passed as argument
 *
 * $isFound = $paymentRepository->exist();
 * $count = $paymentRepository->count();
 * $payment = $paymentRepository->loadEntities();
 *
 * // Sample2. Load single Payment
 * $paymentRepository = \Sam\Storage\ReadRepository\Entity\Payment\PaymentReadRepository::new()
 *     ->filterId(1);
 * $payment = $paymentRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\Payment;

use Sam\Core\Constants;

/**
 * Class PaymentReadRepository
 * @package Sam\Storage\ReadRepository\Entity\Payment
 */
class PaymentReadRepository extends AbstractPaymentReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'invoice' => 'JOIN invoice i ON pmnt.tran_id = i.id AND pmnt.tran_type = "' . Constants\Payment::TT_INVOICE . '"',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join 'invoice' table
     * @return static
     */
    public function joinInvoice(): static
    {
        $this->join('invoice');
        return $this;
    }

    /**
     * Define filtering by invoice.exclude_in_threshold
     * @param bool $value
     * @return static
     */
    public function joinInvoiceFilterExcludeInThreshold(bool $value): static
    {
        $this->joinInvoice();
        $this->filterArray('i.exclude_in_threshold', $value);
        return $this;
    }
}
