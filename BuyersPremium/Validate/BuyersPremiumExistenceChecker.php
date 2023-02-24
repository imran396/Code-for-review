<?php
/**
 * SAM-10464: Separate BP manager to services
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Validate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\BuyersPremium\BuyersPremiumReadRepositoryCreateTrait;

/**
 * Class BuyersPremiumExistenceChecker
 * @package Sam\BuyersPremium\Validate
 */
class BuyersPremiumExistenceChecker extends CustomizableClass
{
    use BuyersPremiumReadRepositoryCreateTrait;

    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    /**
     * Load by short name, where it's not in [Default, H, L, T]
     * @param string $shortName
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existNotDefault(string $shortName, int $accountId, bool $isReadOnlyDb = false): bool
    {
        if (
            $shortName === ''
            || !$accountId
        ) {
            return false;
        }

        return in_array($shortName, array_merge(['Default'], Constants\Auction::AUCTION_TYPES), true)
            || $this->createBuyersPremiumReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->filterShortName($shortName)
                ->filterAccountId($accountId)
                ->filterActive(true)
                ->exist();
    }

    /**
     * @param string $name buyers_premium.name
     * @param int $accountId buyers_premium.account_id
     * @param int[] $skipBuyersPremiumIds
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existCustomBp(string $name, int $accountId, array $skipBuyersPremiumIds, bool $isReadOnlyDb = false): bool
    {
        if (
            $name === ''
            || !$accountId
        ) {
            return false;
        }

        return $this->createBuyersPremiumReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->filterShortName($name)
            ->skipId($skipBuyersPremiumIds)
            ->exist();
    }
}
