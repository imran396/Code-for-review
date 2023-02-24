<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\ImageImportQueue;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractImageImportQueueDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_IMAGE_IMPORT_QUEUE;
    protected string $alias = Db::A_IMAGE_IMPORT_QUEUE;

    /**
     * Filter by image_import_queue.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out image_import_queue.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by image_import_queue.lot_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_id', $filterValue);
        return $this;
    }

    /**
     * Filter out image_import_queue.lot_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_id', $skipValue);
        return $this;
    }

    /**
     * Filter by image_import_queue.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out image_import_queue.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Filter by image_import_queue.image_name_base
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterImageNameBase(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.image_name_base', $filterValue);
        return $this;
    }

    /**
     * Filter out image_import_queue.image_name_base from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipImageNameBase(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.image_name_base', $skipValue);
        return $this;
    }

    /**
     * Filter by image_import_queue.location_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterLocationType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.location_type', $filterValue);
        return $this;
    }

    /**
     * Filter out image_import_queue.location_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipLocationType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.location_type', $skipValue);
        return $this;
    }

    /**
     * Filter by image_import_queue.host
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterHost(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.host', $filterValue);
        return $this;
    }

    /**
     * Filter out image_import_queue.host from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipHost(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.host', $skipValue);
        return $this;
    }

    /**
     * Filter by image_import_queue.directory
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterDirectory(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.directory', $filterValue);
        return $this;
    }

    /**
     * Filter out image_import_queue.directory from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipDirectory(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.directory', $skipValue);
        return $this;
    }

    /**
     * Filter by image_import_queue.passive
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterPassive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.passive', $filterValue);
        return $this;
    }

    /**
     * Filter out image_import_queue.passive from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipPassive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.passive', $skipValue);
        return $this;
    }

    /**
     * Filter by image_import_queue.username
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterUsername(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.username', $filterValue);
        return $this;
    }

    /**
     * Filter out image_import_queue.username from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipUsername(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.username', $skipValue);
        return $this;
    }

    /**
     * Filter by image_import_queue.password
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPassword(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.password', $filterValue);
        return $this;
    }

    /**
     * Filter out image_import_queue.password from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPassword(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.password', $skipValue);
        return $this;
    }

    /**
     * Filter by image_import_queue.processed
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterProcessed(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.processed', $filterValue);
        return $this;
    }

    /**
     * Filter out image_import_queue.processed from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipProcessed(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.processed', $skipValue);
        return $this;
    }

    /**
     * Filter by image_import_queue.replace_existing
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterReplaceExisting(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.replace_existing', $filterValue);
        return $this;
    }

    /**
     * Filter out image_import_queue.replace_existing from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipReplaceExisting(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.replace_existing', $skipValue);
        return $this;
    }

    /**
     * Filter by image_import_queue.lot_num_separator
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterLotNumSeparator(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_num_separator', $filterValue);
        return $this;
    }

    /**
     * Filter out image_import_queue.lot_num_separator from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipLotNumSeparator(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_num_separator', $skipValue);
        return $this;
    }

    /**
     * Filter by image_import_queue.image_auto_orient
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterImageAutoOrient(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.image_auto_orient', $filterValue);
        return $this;
    }

    /**
     * Filter out image_import_queue.image_auto_orient from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipImageAutoOrient(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.image_auto_orient', $skipValue);
        return $this;
    }

    /**
     * Filter by image_import_queue.image_optimize
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterImageOptimize(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.image_optimize', $filterValue);
        return $this;
    }

    /**
     * Filter out image_import_queue.image_optimize from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipImageOptimize(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.image_optimize', $skipValue);
        return $this;
    }

    /**
     * Filter by image_import_queue.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out image_import_queue.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by image_import_queue.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out image_import_queue.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by image_import_queue.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out image_import_queue.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by image_import_queue.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out image_import_queue.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by image_import_queue.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out image_import_queue.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
