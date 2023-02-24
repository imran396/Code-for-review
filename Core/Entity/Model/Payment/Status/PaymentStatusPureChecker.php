<?php
/**
 * SAM-11337: Stacked Tax. Admin - Add to Stacked Tax Payment page (Invoice) the functionality from Pay Invoice page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 09, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\Payment\Status;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class PaymentStatusPureChecker
 * @package Sam\Core\Entity\Model\Payment\Status
 */
class PaymentStatusPureChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isCcPaymentMethod(?int $paymentMethodId): bool
    {
        return in_array(
            $paymentMethodId,
            [
                Constants\Payment::PM_CC,
                Constants\Payment::PM_CC_ON_INPUT,
                Constants\Payment::PM_CC_ON_FILE,
                Constants\Payment::PM_CC_EXTERNALLY
            ],
            true
        );
    }

    public function isCcOnFilePaymentMethod(?int $paymentMethodId): bool
    {
        return $paymentMethodId === Constants\Payment::PM_CC_ON_FILE;
    }

    public function isCcOnInputPaymentMethod(?int $paymentMethodId): bool
    {
        return $paymentMethodId === Constants\Payment::PM_CC_ON_INPUT;
    }

    public function isCcExternallyPaymentMethod(?int $paymentMethodId): bool
    {
        return $paymentMethodId === Constants\Payment::PM_CC_EXTERNALLY;
    }
}
