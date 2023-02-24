<?php
/**
 * Repository for UserAuthentication entity
 *
 * SAM-3654: User related repositories https://bidpath.atlassian.net/browse/SAM-3654
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           27 Apr, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\ReadRepository\Entity\UserAuthentication;

/**
 * Class UserAuthenticationReadRepository
 * @package Sam\Storage\ReadRepository\Entity\UserAuthentication
 */
class UserAuthenticationReadRepository extends AbstractUserAuthenticationReadRepository
{
    protected array $joins = [
        'user' => 'JOIN `user` AS u ON uau.user_id = u.id ',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Left join `user` table
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
