<?php
/**
 * Consignor table repository
 *
 * SAM-3680: Bidder and consignor related repositories https://bidpath.atlassian.net/browse/SAM-3680
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           05 Apr, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * Usage example:
 * $consignorRepository = \Sam\Storage\ReadRepository\Entity\Consignor\ConsignorReadRepository::new()
 *      ->enableReadOnlyDb(true)
 *      ->filterUserId($userIds);
 * $isFound = $consignorRepository->exist();
 * $count = $consignorRepository->count();
 * $item = $consignorRepository->loadEntity();
 * $items = $consignorRepository->loadEntities();
 */

namespace Sam\Storage\ReadRepository\Entity\Consignor;

/**
 * Class ConsignorReadRepository
 */
class ConsignorReadRepository extends AbstractConsignorReadRepository
{
    protected array $joins = [
        'user' => 'JOIN user u ON cons.user_id = u.id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * join `user` table
     * @return static
     */
    public function joinUser(): static
    {
        $this->join('user');
        return $this;
    }

    /**
     * join `user` table and define filtering by u.user_status_id
     * @param int|int[] $userStatusId
     * @return static
     */
    public function joinUserFilterUserStatusId(int|array|null $userStatusId): static
    {
        $this->joinUser();
        $this->filterArray('u.user_status_id', $userStatusId);
        return $this;
    }
}
