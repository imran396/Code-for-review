<?php
/**
 * General repository for BuyerGroupUser entity
 *
 * SAM-3691: Buyer Group related repositories https://bidpath.atlassian.net/projects/SAM/issues/SAM-3691
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           29 Mar, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of buyer group user filtered by criteria
 * $buyerGroupUserRepository = \Sam\Storage\ReadRepository\Entity\BuyerGroupUser\BuyerGroupUserReadRepository::new()
 *     ->filterUserId($ids)      // array passed as argument
 *     ->filterActive(true)
 * $isFound = $buyerGroupUserRepository->exist();
 * $count = $buyerGroupUserRepository->count();
 * $users = $buyerGroupUserRepository->loadEntities();
 *
 */

namespace Sam\Storage\ReadRepository\Entity\BuyerGroupUser;

/**
 * Class BuyerGroupUserReadRepository
 */
class BuyerGroupUserReadRepository extends AbstractBuyerGroupUserReadRepository
{
    protected array $joins = [
        'user' => 'JOIN user u ON u.id = bgu.user_id ',
        'user_info' => 'JOIN user_info ui ON ui.user_id = bgu.user_id ',
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
     * join `user_info` table
     * @return static
     */
    public function joinUserInfo(): static
    {
        $this->join('user_info');
        return $this;
    }

    /**
     * Define ORDER BY u.username
     * @param bool $ascending
     * @return static
     */
    public function innerJoinUserOrderByUsername(bool $ascending = true): static
    {
        $this->innerJoin('user');
        $this->order('u.username', $ascending);
        return $this;
    }

    /**
     * Define ORDER BY u.email
     * @param bool $ascending
     * @return static
     */
    public function innerJoinUserOrderByEmail(bool $ascending = true): static
    {
        $this->innerJoin('user');
        $this->order('u.email', $ascending);
        return $this;
    }

    /**
     * Define ORDER BY ui.first_name
     * @param bool $ascending
     * @return static
     */
    public function joinUserInfoOrderByFirstName(bool $ascending = true): static
    {
        $this->joinUserInfo();
        $this->order('ui.first_name', $ascending);
        return $this;
    }

    /**
     * Define ORDER BY ui.last_name
     * @param bool $ascending
     * @return static
     */
    public function joinUserInfoOrderByLastName(bool $ascending = true): static
    {
        $this->joinUserInfo();
        $this->order('ui.last_name', $ascending);
        return $this;
    }

    /**
     * Define LIKE filter condition u.username
     * @param string $username
     * @return static
     */
    public function joinUserLikeUsername(string $username): static
    {
        $this->joinUser();
        $this->like('u.username', $username);
        return $this;
    }

    /**
     * Define LIKE filter condition u.email
     * @param string $email
     * @return static
     */
    public function joinUserLikeEmail(string $email): static
    {
        $this->joinUser();
        $this->like('u.email', $email);
        return $this;
    }

    /**
     * Define LIKE filter condition ui.first_name
     * @param string $firstName
     * @return static
     */
    public function joinUserInfoLikeFirstName(string $firstName): static
    {
        $this->joinUserInfo();
        $this->like('ui.first_name', $firstName);
        return $this;
    }

    /**
     * Define LIKE filter condition ui.last_name
     * @param string $lastName
     * @return static
     */
    public function joinUserInfoLikeLastName(string $lastName): static
    {
        $this->joinUserInfo();
        $this->like('ui.last_name', $lastName);
        return $this;
    }

    /**
     * Define LIKE filter condition CONCAT(IFNULL(ui.first_name,''),' ',IFNULL(ui.last_name,'')
     * @param string $firstLastName
     * @return static
     */
    public function joinUserInfoLikeFirstLastName(string $firstLastName): static
    {
        $this->joinUserInfo();
        $this->like("CONCAT(IFNULL(ui.first_name,''),' ',IFNULL(ui.last_name,''))", $firstLastName);
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

