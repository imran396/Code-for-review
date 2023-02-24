<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\LotItemGeolocation;

use LotItemGeolocation;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractLotItemGeolocationReadRepository
 * @method LotItemGeolocation[] loadEntities()
 * @method LotItemGeolocation|null loadEntity()
 */
abstract class AbstractLotItemGeolocationReadRepository extends ReadRepositoryBase
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
     * Group by lot_item_geolocation.lot_item_id
     * @return static
     */
    public function groupByLotItemId(): static
    {
        $this->group($this->alias . '.lot_item_id');
        return $this;
    }

    /**
     * Order by lot_item_geolocation.lot_item_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotItemId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_item_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_geolocation.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_geolocation.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_geolocation.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_geolocation.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '<=');
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
     * Group by lot_item_geolocation.lot_item_cust_data_id
     * @return static
     */
    public function groupByLotItemCustDataId(): static
    {
        $this->group($this->alias . '.lot_item_cust_data_id');
        return $this;
    }

    /**
     * Order by lot_item_geolocation.lot_item_cust_data_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotItemCustDataId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_item_cust_data_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_geolocation.lot_item_cust_data_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemCustDataIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_cust_data_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_geolocation.lot_item_cust_data_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemCustDataIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_cust_data_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_geolocation.lot_item_cust_data_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemCustDataIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_cust_data_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_geolocation.lot_item_cust_data_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemCustDataIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_cust_data_id', $filterValue, '<=');
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
     * Group by lot_item_geolocation.latitude
     * @return static
     */
    public function groupByLatitude(): static
    {
        $this->group($this->alias . '.latitude');
        return $this;
    }

    /**
     * Order by lot_item_geolocation.latitude
     * @param bool $ascending
     * @return static
     */
    public function orderByLatitude(bool $ascending = true): static
    {
        $this->order($this->alias . '.latitude', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_geolocation.latitude
     * @param float $filterValue
     * @return static
     */
    public function filterLatitudeGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.latitude', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_geolocation.latitude
     * @param float $filterValue
     * @return static
     */
    public function filterLatitudeGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.latitude', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_geolocation.latitude
     * @param float $filterValue
     * @return static
     */
    public function filterLatitudeLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.latitude', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_geolocation.latitude
     * @param float $filterValue
     * @return static
     */
    public function filterLatitudeLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.latitude', $filterValue, '<=');
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
     * Group by lot_item_geolocation.longitude
     * @return static
     */
    public function groupByLongitude(): static
    {
        $this->group($this->alias . '.longitude');
        return $this;
    }

    /**
     * Order by lot_item_geolocation.longitude
     * @param bool $ascending
     * @return static
     */
    public function orderByLongitude(bool $ascending = true): static
    {
        $this->order($this->alias . '.longitude', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_geolocation.longitude
     * @param float $filterValue
     * @return static
     */
    public function filterLongitudeGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.longitude', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_geolocation.longitude
     * @param float $filterValue
     * @return static
     */
    public function filterLongitudeGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.longitude', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_geolocation.longitude
     * @param float $filterValue
     * @return static
     */
    public function filterLongitudeLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.longitude', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_geolocation.longitude
     * @param float $filterValue
     * @return static
     */
    public function filterLongitudeLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.longitude', $filterValue, '<=');
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
     * Group by lot_item_geolocation.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by lot_item_geolocation.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_geolocation.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_geolocation.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_geolocation.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_geolocation.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
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
     * Group by lot_item_geolocation.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by lot_item_geolocation.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_geolocation.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_geolocation.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_geolocation.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_geolocation.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by lot_item_geolocation.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by lot_item_geolocation.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_geolocation.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_geolocation.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_geolocation.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_geolocation.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by lot_item_geolocation.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by lot_item_geolocation.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_geolocation.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_geolocation.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_geolocation.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_geolocation.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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

    /**
     * Group by lot_item_geolocation.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by lot_item_geolocation.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than lot_item_geolocation.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than lot_item_geolocation.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than lot_item_geolocation.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than lot_item_geolocation.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
