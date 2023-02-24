<?php
/**
 * General repository for User entity
 *
 * SAM-3624: User general repository class https://bidpath.atlassian.net/browse/SAM-3624
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           14 Feb, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of users filtered by criteria
 * $userRepository = \Sam\Storage\ReadRepository\Entity\BuyersPremiumRange\BuyersPremiumRangeReadRepository::new()
 *     ->filterAccountId($mainAccountId)          // single value passed as argument
 *     ->filterUsername(['admin', 'bidder'])      // array passed as argument
 *     ->filterUserStatusId(null)                 // filter by user_status_id IS NULL
 *     ->skipId([$myId]);                         // search avoiding these user ids
 * $isFound = $userRepository->exist();
 * $count = $userRepository->count();
 * $users = $userRepository->loadEntities();
 *
 * // Sample2. Load single user
 * $userRepository = \Sam\Storage\ReadRepository\Entity\BuyersPremiumRange\BuyersPremiumRangeReadRepository::new()
 *     ->filterId(1);
 * $user = $userRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\BuyersPremiumRange;

/**
 * Class BuyersPremiumRangeReadRepository
 * @package Sam\Storage\Repository
 */
class BuyersPremiumRangeReadRepository extends AbstractBuyersPremiumRangeReadRepository
{
    protected array $joins = [
        'account' => 'JOIN account acc ON acc.id = bpr.account_id',
        'buyers_premium' => 'JOIN buyers_premium bp ON bp.id = bpr.buyers_premium_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Define filtering by bpr.account_id or bp.account_id
     * Search among users of defined accounts.
     * @param int|int[] $accountIds
     * @return static
     */
    public function filterAccountIdOrBuyersPremiumAccountId(int|array|null $accountIds): static
    {
        $this->inlineCondition(
            sprintf(
                '(bpr.account_id IN (%1$s) OR bp.account_id IN (%1$s))',
                implode(',', (array)$accountIds)
            )
        );
        return $this;
    }

    /**
     * Join 'account' table
     * @return static
     */
    public function joinAccount(): static
    {
        $this->join('account');
        return $this;
    }

    /**
     * Join 'buyers_premium' table
     * @return static
     */
    public function joinBuyersPremium(): static
    {
        $this->join('buyers_premium');
        return $this;
    }
}

