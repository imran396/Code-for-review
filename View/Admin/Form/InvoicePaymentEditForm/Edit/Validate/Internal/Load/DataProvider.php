<?php
/**
 * SAM-11127: Stacked Tax. New Invoice Edit page: Payment Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Validate\Internal\Load;

use Sam\Billing\Payment\Load\PaymentLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataProvider
 * @package Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Validate\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use PaymentLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadPaymentCreditCardId(?int $paymentId, bool $isReadOnlyDb = false): ?int
    {
        return $this->getPaymentLoader()->load($paymentId, $isReadOnlyDb)?->CreditCardId;
    }
}
