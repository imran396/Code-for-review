<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\RtbSession;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractRtbSessionDeleteRepository extends DeleteRepositoryBase
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
}
