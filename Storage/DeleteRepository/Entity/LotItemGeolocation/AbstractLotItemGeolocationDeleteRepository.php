<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LotItemGeolocation;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractLotItemGeolocationDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_LOT_ITEM_GEOLOCATION;
    protected string $alias = Db::A_LOT_ITEM_GEOLOCATION;

    /**
     * Filter by lot_item_geolocation.lot_item_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterLotItemId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_geolocation.lot_item_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipLotItemId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_geolocation.lot_item_cust_data_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterLotItemCustDataId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_cust_data_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_geolocation.lot_item_cust_data_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipLotItemCustDataId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_cust_data_id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_geolocation.latitude
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterLatitude(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.latitude', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_geolocation.latitude from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipLatitude(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.latitude', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_geolocation.longitude
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterLongitude(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.longitude', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_geolocation.longitude from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipLongitude(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.longitude', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_geolocation.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_geolocation.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_geolocation.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_geolocation.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_geolocation.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_geolocation.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_geolocation.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_geolocation.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_geolocation.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_geolocation.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
