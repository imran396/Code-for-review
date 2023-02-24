<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\Internal\Produce\Internal\Load;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceLoader;
use Sam\Security\Crypt\BlockCipherProvider;
use Sam\User\Load\UserLoader;
use UserBilling;


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

    public function loadInvoice(int $invoiceId, bool $isReadOnlyDb = false): ?Invoice
    {
        return InvoiceLoader::new()->load($invoiceId, $isReadOnlyDb);
    }

    public function loadUserBilling(int $userId, bool $isReadOnlyDb = false): UserBilling
    {
        return UserLoader::new()->loadUserBillingOrCreate($userId, $isReadOnlyDb);
    }

    public function decrypt(string $value): string
    {
        return BlockCipherProvider::new()->construct()->decrypt($value);
    }
}
