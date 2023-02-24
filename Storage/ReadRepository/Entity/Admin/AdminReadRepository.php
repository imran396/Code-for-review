<?php

namespace Sam\Storage\ReadRepository\Entity\Admin;

/**
 * Class AdminReadRepository
 * @package Sam\Storage\ReadRepository\Entity\Admin
 */
class AdminReadRepository extends AbstractAdminReadRepository
{
    protected array $joins = [
        'account' => 'JOIN `account` acc ON acc.id = u.account_id',
        'user' => 'JOIN `user` u ON u.id = ad.user_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join 'account' table
     * @return static
     */
    public function joinAccount(): static
    {
        $this->joinUser();
        $this->join('account');
        return $this;
    }

    /**
     * Define filtering by account.id
     * @param int|int[] $accountId
     * @return static
     */
    public function joinAccountFilterId(int|array|null $accountId): static
    {
        $this->joinAccount();
        $this->filterArray('acc.id', $accountId);
        return $this;
    }

    /**
     * Join 'user' table
     * @return static
     */
    public function joinUser(): static
    {
        $this->join('user');
        return $this;
    }

    /**
     * Join `user` table and define filtering by u.user_status_id
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
