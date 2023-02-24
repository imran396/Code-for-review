<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\RtbSession;

use RtbSession;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractRtbSessionReadRepository
 * @method RtbSession[] loadEntities()
 * @method RtbSession|null loadEntity()
 */
abstract class AbstractRtbSessionReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_RTB_SESSION;
    protected string $alias = Db::A_RTB_SESSION;

    /**
     * Filter by rtb_session.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_session.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_session.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by rtb_session.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_session.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_session.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_session.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_session.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_session.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_session.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_session.auction_id
     * @return static
     */
    public function groupByAuctionId(): static
    {
        $this->group($this->alias . '.auction_id');
        return $this;
    }

    /**
     * Order by rtb_session.auction_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_session.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_session.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_session.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_session.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_session.user_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUserId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_session.user_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUserId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_session.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by rtb_session.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_session.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_session.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_session.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_session.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_session.user_type
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUserType(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.user_type', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_session.user_type from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUserType(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.user_type', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_session.user_type
     * @return static
     */
    public function groupByUserType(): static
    {
        $this->group($this->alias . '.user_type');
        return $this;
    }

    /**
     * Order by rtb_session.user_type
     * @param bool $ascending
     * @return static
     */
    public function orderByUserType(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_session.user_type
     * @param int $filterValue
     * @return static
     */
    public function filterUserTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_session.user_type
     * @param int $filterValue
     * @return static
     */
    public function filterUserTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_session.user_type
     * @param int $filterValue
     * @return static
     */
    public function filterUserTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_session.user_type
     * @param int $filterValue
     * @return static
     */
    public function filterUserTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_session.sess_id
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSessId(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.sess_id', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_session.sess_id from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSessId(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.sess_id', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_session.sess_id
     * @return static
     */
    public function groupBySessId(): static
    {
        $this->group($this->alias . '.sess_id');
        return $this;
    }

    /**
     * Order by rtb_session.sess_id
     * @param bool $ascending
     * @return static
     */
    public function orderBySessId(bool $ascending = true): static
    {
        $this->order($this->alias . '.sess_id', $ascending);
        return $this;
    }

    /**
     * Filter rtb_session.sess_id by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSessId(string $filterValue): static
    {
        $this->like($this->alias . '.sess_id', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by rtb_session.ip
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterIp(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.ip', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_session.ip from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipIp(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.ip', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_session.ip
     * @return static
     */
    public function groupByIp(): static
    {
        $this->group($this->alias . '.ip');
        return $this;
    }

    /**
     * Order by rtb_session.ip
     * @param bool $ascending
     * @return static
     */
    public function orderByIp(bool $ascending = true): static
    {
        $this->order($this->alias . '.ip', $ascending);
        return $this;
    }

    /**
     * Filter rtb_session.ip by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeIp(string $filterValue): static
    {
        $this->like($this->alias . '.ip', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by rtb_session.port
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterPort(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.port', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_session.port from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipPort(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.port', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_session.port
     * @return static
     */
    public function groupByPort(): static
    {
        $this->group($this->alias . '.port');
        return $this;
    }

    /**
     * Order by rtb_session.port
     * @param bool $ascending
     * @return static
     */
    public function orderByPort(bool $ascending = true): static
    {
        $this->order($this->alias . '.port', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_session.port
     * @param int $filterValue
     * @return static
     */
    public function filterPortGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.port', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_session.port
     * @param int $filterValue
     * @return static
     */
    public function filterPortGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.port', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_session.port
     * @param int $filterValue
     * @return static
     */
    public function filterPortLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.port', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_session.port
     * @param int $filterValue
     * @return static
     */
    public function filterPortLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.port', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_session.participated_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterParticipatedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.participated_on', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_session.participated_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipParticipatedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.participated_on', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_session.participated_on
     * @return static
     */
    public function groupByParticipatedOn(): static
    {
        $this->group($this->alias . '.participated_on');
        return $this;
    }

    /**
     * Order by rtb_session.participated_on
     * @param bool $ascending
     * @return static
     */
    public function orderByParticipatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.participated_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_session.participated_on
     * @param string $filterValue
     * @return static
     */
    public function filterParticipatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.participated_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_session.participated_on
     * @param string $filterValue
     * @return static
     */
    public function filterParticipatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.participated_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_session.participated_on
     * @param string $filterValue
     * @return static
     */
    public function filterParticipatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.participated_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_session.participated_on
     * @param string $filterValue
     * @return static
     */
    public function filterParticipatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.participated_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_session.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_session.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_session.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by rtb_session.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_session.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_session.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_session.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_session.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_session.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_session.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_session.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by rtb_session.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_session.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_session.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_session.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_session.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_session.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_session.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_session.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by rtb_session.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_session.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_session.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_session.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_session.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_session.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_session.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_session.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by rtb_session.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_session.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_session.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_session.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_session.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by rtb_session.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out rtb_session.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by rtb_session.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by rtb_session.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than rtb_session.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than rtb_session.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than rtb_session.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than rtb_session.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
