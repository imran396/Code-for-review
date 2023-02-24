<?php
/**
 * SAM-10335: Allow to adjust CC surcharge per account: Implementation (Dev)
 * https://bidpath.atlassian.net/browse/SAM-10335
 *
 * @author        Oleh Kovalov
 * @since         Apr 24, 2022
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>*
 */

namespace Sam\Billing\CreditCard\Build;

use Sam\Billing\CreditCard\Load\CreditCardLoaderAwareTrait;
use Sam\Billing\CreditCard\Load\CreditCardSurchargeLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;


class CcSurchargeDetector extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use CreditCardLoaderAwareTrait;
    use CreditCardSurchargeLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $ccId
     * @param int $accountId
     * @return float|null
     */
    public function detect(?int $ccId, int $accountId): ?float
    {
        $isMainAccount = $accountId === $this->cfg()->get('core->portal->mainAccountId');
        if (!$isMainAccount) {
            $ccSurcharge = $this->getCreditCardSurchargeLoader()->loadSurchargeForAccount($ccId, $accountId, true);
            if (
                $ccSurcharge
                && $ccSurcharge->Percent !== null
            ) {
                return $ccSurcharge->Percent;
            }
        }

        $creditCard = $this->getCreditCardLoader()->load($ccId, true);
        return $creditCard?->Surcharge;
    }
}
