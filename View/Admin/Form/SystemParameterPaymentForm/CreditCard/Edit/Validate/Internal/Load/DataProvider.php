<?php
/**
 * SAM-10335: Allow to adjust CC surcharge per account: Implementation (Dev)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SystemParameterPaymentForm\CreditCard\Edit\Validate\Internal\Load;

use CreditCard;
use Sam\Billing\CreditCard\Load\CreditCardLoader;
use Sam\Billing\CreditCard\Validate\CreditCardExistenceChecker;
use Sam\Core\Service\CustomizableClass;


class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadCreditCard(?int $creditCardId, bool $isReadOnlyDb = false): ?CreditCard
    {
        return CreditCardLoader::new()->load($creditCardId, $isReadOnlyDb);
    }

    public function existCreditCardByName(string $name): bool
    {
        return CreditCardExistenceChecker::new()->existByName($name);
    }
}
