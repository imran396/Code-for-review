<?php
/**
 * SAM-10967: Stacked Tax. Public My Invoice pages. Extract Opayo invoice charging.
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 20, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Responsive\Gate\Common;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoaderAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Render\UserRendererAwareTrait;


class ResponsiveInvoiceViewChargingHelper extends CustomizableClass
{
    use InvoiceUserLoaderAwareTrait;
    use UserLoaderAwareTrait;
    use UserRendererAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function getAccountingEmail(int $userId, string $email): string
    {
        $user = $this->getUserLoader()->load($userId);
        $email = trim($email) ?: $user->Email;
        return $email;
    }
}
