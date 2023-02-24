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

namespace Sam\Billing\CreditCard\Load;

use CreditCardSurcharge;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\CreditCardSurcharge\CreditCardSurchargeReadRepository;
use Sam\Storage\ReadRepository\Entity\CreditCardSurcharge\CreditCardSurchargeReadRepositoryCreateTrait;

/**
 * Class CreditCardSurchargeLoader
 */
class CreditCardSurchargeLoader extends EntityLoaderBase
{
    use CreditCardSurchargeReadRepositoryCreateTrait;
    use MemoryCacheManagerAwareTrait;

    protected ?bool $filterActive = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->filterActive(true);
        return $this;
    }

    /**
     * @return static
     */
    public function clear(): static
    {
        $this->dropFilterActive();
        return $this;
    }

    /**
     * @param bool $active
     * @return static
     */
    public function filterActive(bool $active): static
    {
        $this->filterActive = $active;
        return $this;
    }

    /**
     * Drop filtering by cc.active
     * @return static
     */
    public function dropFilterActive(): static
    {
        $this->filterActive = null;
        return $this;
    }

    /**
     * @param int|null $ccId
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return CreditCardSurcharge|null
     */
    public function loadSurchargeForAccount(?int $ccId, int $accountId, bool $isReadOnlyDb = false): ?CreditCardSurcharge
    {
        $ccId = Cast::toInt($ccId, Constants\Type::F_INT_POSITIVE);
        if (!$ccId) {
            return null;
        }

        $creditCardSurcharge = $this->prepareCreditCardSurchargeRepository($isReadOnlyDb)
            ->joinCreditCardFilterActive(true)
            ->filterCreditCardId($ccId)
            ->filterAccountId($accountId)
            ->loadEntity();

        return $creditCardSurcharge;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return CreditCardSurchargeReadRepository
     */
    protected function prepareCreditCardSurchargeRepository(bool $isReadOnlyDb = false): CreditCardSurchargeReadRepository
    {
        $repo = $this->createCreditCardSurchargeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        return $repo;
    }
}
