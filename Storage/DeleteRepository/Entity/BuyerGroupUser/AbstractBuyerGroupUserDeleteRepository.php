<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\BuyerGroupUser;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractBuyerGroupUserDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_BUYER_GROUP_USER;
    protected string $alias = Db::A_BUYER_GROUP_USER;

    /**
     * Filter by buyer_group_user.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by buyer_group_user.buyer_group_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterBuyerGroupId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.buyer_group_id', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.buyer_group_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipBuyerGroupId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.buyer_group_id', $skipValue);
        return $this;
    }

    /**
     * Filter by buyer_group_user.user_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterUserId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.user_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipUserId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Filter by buyer_group_user.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Filter by buyer_group_user.added_by
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAddedBy(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.added_by', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.added_by from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAddedBy(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.added_by', $skipValue);
        return $this;
    }

    /**
     * Filter by buyer_group_user.added_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterAddedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.added_on', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.added_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipAddedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.added_on', $skipValue);
        return $this;
    }

    /**
     * Filter by buyer_group_user.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by buyer_group_user.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by buyer_group_user.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by buyer_group_user.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by buyer_group_user.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out buyer_group_user.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
