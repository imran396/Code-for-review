<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\ImageImportQueue;

use ImageImportQueue;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractImageImportQueueReadRepository
 * @method ImageImportQueue[] loadEntities()
 * @method ImageImportQueue|null loadEntity()
 */
abstract class AbstractImageImportQueueReadRepository extends ReadRepositoryBase
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
     * Group by image_import_queue.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by image_import_queue.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than image_import_queue.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than image_import_queue.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than image_import_queue.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than image_import_queue.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by image_import_queue.lot_id
     * @return static
     */
    public function groupByLotId(): static
    {
        $this->group($this->alias . '.lot_id');
        return $this;
    }

    /**
     * Order by image_import_queue.lot_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than image_import_queue.lot_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than image_import_queue.lot_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than image_import_queue.lot_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than image_import_queue.lot_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_id', $filterValue, '<=');
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
     * Group by image_import_queue.auction_id
     * @return static
     */
    public function groupByAuctionId(): static
    {
        $this->group($this->alias . '.auction_id');
        return $this;
    }

    /**
     * Order by image_import_queue.auction_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than image_import_queue.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than image_import_queue.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than image_import_queue.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than image_import_queue.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<=');
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
     * Group by image_import_queue.image_name_base
     * @return static
     */
    public function groupByImageNameBase(): static
    {
        $this->group($this->alias . '.image_name_base');
        return $this;
    }

    /**
     * Order by image_import_queue.image_name_base
     * @param bool $ascending
     * @return static
     */
    public function orderByImageNameBase(bool $ascending = true): static
    {
        $this->order($this->alias . '.image_name_base', $ascending);
        return $this;
    }

    /**
     * Filter image_import_queue.image_name_base by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeImageNameBase(string $filterValue): static
    {
        $this->like($this->alias . '.image_name_base', "%{$filterValue}%");
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
     * Group by image_import_queue.location_type
     * @return static
     */
    public function groupByLocationType(): static
    {
        $this->group($this->alias . '.location_type');
        return $this;
    }

    /**
     * Order by image_import_queue.location_type
     * @param bool $ascending
     * @return static
     */
    public function orderByLocationType(bool $ascending = true): static
    {
        $this->order($this->alias . '.location_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than image_import_queue.location_type
     * @param int $filterValue
     * @return static
     */
    public function filterLocationTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.location_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than image_import_queue.location_type
     * @param int $filterValue
     * @return static
     */
    public function filterLocationTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.location_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than image_import_queue.location_type
     * @param int $filterValue
     * @return static
     */
    public function filterLocationTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.location_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than image_import_queue.location_type
     * @param int $filterValue
     * @return static
     */
    public function filterLocationTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.location_type', $filterValue, '<=');
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
     * Group by image_import_queue.host
     * @return static
     */
    public function groupByHost(): static
    {
        $this->group($this->alias . '.host');
        return $this;
    }

    /**
     * Order by image_import_queue.host
     * @param bool $ascending
     * @return static
     */
    public function orderByHost(bool $ascending = true): static
    {
        $this->order($this->alias . '.host', $ascending);
        return $this;
    }

    /**
     * Filter image_import_queue.host by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeHost(string $filterValue): static
    {
        $this->like($this->alias . '.host', "%{$filterValue}%");
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
     * Group by image_import_queue.directory
     * @return static
     */
    public function groupByDirectory(): static
    {
        $this->group($this->alias . '.directory');
        return $this;
    }

    /**
     * Order by image_import_queue.directory
     * @param bool $ascending
     * @return static
     */
    public function orderByDirectory(bool $ascending = true): static
    {
        $this->order($this->alias . '.directory', $ascending);
        return $this;
    }

    /**
     * Filter image_import_queue.directory by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeDirectory(string $filterValue): static
    {
        $this->like($this->alias . '.directory', "%{$filterValue}%");
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
     * Group by image_import_queue.passive
     * @return static
     */
    public function groupByPassive(): static
    {
        $this->group($this->alias . '.passive');
        return $this;
    }

    /**
     * Order by image_import_queue.passive
     * @param bool $ascending
     * @return static
     */
    public function orderByPassive(bool $ascending = true): static
    {
        $this->order($this->alias . '.passive', $ascending);
        return $this;
    }

    /**
     * Filter by greater than image_import_queue.passive
     * @param bool $filterValue
     * @return static
     */
    public function filterPassiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.passive', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than image_import_queue.passive
     * @param bool $filterValue
     * @return static
     */
    public function filterPassiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.passive', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than image_import_queue.passive
     * @param bool $filterValue
     * @return static
     */
    public function filterPassiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.passive', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than image_import_queue.passive
     * @param bool $filterValue
     * @return static
     */
    public function filterPassiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.passive', $filterValue, '<=');
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
     * Group by image_import_queue.username
     * @return static
     */
    public function groupByUsername(): static
    {
        $this->group($this->alias . '.username');
        return $this;
    }

    /**
     * Order by image_import_queue.username
     * @param bool $ascending
     * @return static
     */
    public function orderByUsername(bool $ascending = true): static
    {
        $this->order($this->alias . '.username', $ascending);
        return $this;
    }

    /**
     * Filter image_import_queue.username by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeUsername(string $filterValue): static
    {
        $this->like($this->alias . '.username', "%{$filterValue}%");
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
     * Group by image_import_queue.password
     * @return static
     */
    public function groupByPassword(): static
    {
        $this->group($this->alias . '.password');
        return $this;
    }

    /**
     * Order by image_import_queue.password
     * @param bool $ascending
     * @return static
     */
    public function orderByPassword(bool $ascending = true): static
    {
        $this->order($this->alias . '.password', $ascending);
        return $this;
    }

    /**
     * Filter image_import_queue.password by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePassword(string $filterValue): static
    {
        $this->like($this->alias . '.password', "%{$filterValue}%");
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
     * Group by image_import_queue.processed
     * @return static
     */
    public function groupByProcessed(): static
    {
        $this->group($this->alias . '.processed');
        return $this;
    }

    /**
     * Order by image_import_queue.processed
     * @param bool $ascending
     * @return static
     */
    public function orderByProcessed(bool $ascending = true): static
    {
        $this->order($this->alias . '.processed', $ascending);
        return $this;
    }

    /**
     * Filter by greater than image_import_queue.processed
     * @param bool $filterValue
     * @return static
     */
    public function filterProcessedGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.processed', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than image_import_queue.processed
     * @param bool $filterValue
     * @return static
     */
    public function filterProcessedGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.processed', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than image_import_queue.processed
     * @param bool $filterValue
     * @return static
     */
    public function filterProcessedLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.processed', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than image_import_queue.processed
     * @param bool $filterValue
     * @return static
     */
    public function filterProcessedLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.processed', $filterValue, '<=');
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
     * Group by image_import_queue.replace_existing
     * @return static
     */
    public function groupByReplaceExisting(): static
    {
        $this->group($this->alias . '.replace_existing');
        return $this;
    }

    /**
     * Order by image_import_queue.replace_existing
     * @param bool $ascending
     * @return static
     */
    public function orderByReplaceExisting(bool $ascending = true): static
    {
        $this->order($this->alias . '.replace_existing', $ascending);
        return $this;
    }

    /**
     * Filter by greater than image_import_queue.replace_existing
     * @param bool $filterValue
     * @return static
     */
    public function filterReplaceExistingGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.replace_existing', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than image_import_queue.replace_existing
     * @param bool $filterValue
     * @return static
     */
    public function filterReplaceExistingGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.replace_existing', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than image_import_queue.replace_existing
     * @param bool $filterValue
     * @return static
     */
    public function filterReplaceExistingLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.replace_existing', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than image_import_queue.replace_existing
     * @param bool $filterValue
     * @return static
     */
    public function filterReplaceExistingLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.replace_existing', $filterValue, '<=');
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
     * Group by image_import_queue.lot_num_separator
     * @return static
     */
    public function groupByLotNumSeparator(): static
    {
        $this->group($this->alias . '.lot_num_separator');
        return $this;
    }

    /**
     * Order by image_import_queue.lot_num_separator
     * @param bool $ascending
     * @return static
     */
    public function orderByLotNumSeparator(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_num_separator', $ascending);
        return $this;
    }

    /**
     * Filter by greater than image_import_queue.lot_num_separator
     * @param int $filterValue
     * @return static
     */
    public function filterLotNumSeparatorGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_num_separator', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than image_import_queue.lot_num_separator
     * @param int $filterValue
     * @return static
     */
    public function filterLotNumSeparatorGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_num_separator', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than image_import_queue.lot_num_separator
     * @param int $filterValue
     * @return static
     */
    public function filterLotNumSeparatorLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_num_separator', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than image_import_queue.lot_num_separator
     * @param int $filterValue
     * @return static
     */
    public function filterLotNumSeparatorLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_num_separator', $filterValue, '<=');
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
     * Group by image_import_queue.image_auto_orient
     * @return static
     */
    public function groupByImageAutoOrient(): static
    {
        $this->group($this->alias . '.image_auto_orient');
        return $this;
    }

    /**
     * Order by image_import_queue.image_auto_orient
     * @param bool $ascending
     * @return static
     */
    public function orderByImageAutoOrient(bool $ascending = true): static
    {
        $this->order($this->alias . '.image_auto_orient', $ascending);
        return $this;
    }

    /**
     * Filter by greater than image_import_queue.image_auto_orient
     * @param bool $filterValue
     * @return static
     */
    public function filterImageAutoOrientGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_auto_orient', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than image_import_queue.image_auto_orient
     * @param bool $filterValue
     * @return static
     */
    public function filterImageAutoOrientGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_auto_orient', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than image_import_queue.image_auto_orient
     * @param bool $filterValue
     * @return static
     */
    public function filterImageAutoOrientLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_auto_orient', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than image_import_queue.image_auto_orient
     * @param bool $filterValue
     * @return static
     */
    public function filterImageAutoOrientLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_auto_orient', $filterValue, '<=');
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
     * Group by image_import_queue.image_optimize
     * @return static
     */
    public function groupByImageOptimize(): static
    {
        $this->group($this->alias . '.image_optimize');
        return $this;
    }

    /**
     * Order by image_import_queue.image_optimize
     * @param bool $ascending
     * @return static
     */
    public function orderByImageOptimize(bool $ascending = true): static
    {
        $this->order($this->alias . '.image_optimize', $ascending);
        return $this;
    }

    /**
     * Filter by greater than image_import_queue.image_optimize
     * @param bool $filterValue
     * @return static
     */
    public function filterImageOptimizeGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_optimize', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than image_import_queue.image_optimize
     * @param bool $filterValue
     * @return static
     */
    public function filterImageOptimizeGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_optimize', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than image_import_queue.image_optimize
     * @param bool $filterValue
     * @return static
     */
    public function filterImageOptimizeLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_optimize', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than image_import_queue.image_optimize
     * @param bool $filterValue
     * @return static
     */
    public function filterImageOptimizeLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_optimize', $filterValue, '<=');
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
     * Group by image_import_queue.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by image_import_queue.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than image_import_queue.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than image_import_queue.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than image_import_queue.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than image_import_queue.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
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
     * Group by image_import_queue.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by image_import_queue.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than image_import_queue.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than image_import_queue.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than image_import_queue.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than image_import_queue.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by image_import_queue.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by image_import_queue.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than image_import_queue.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than image_import_queue.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than image_import_queue.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than image_import_queue.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by image_import_queue.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by image_import_queue.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than image_import_queue.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than image_import_queue.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than image_import_queue.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than image_import_queue.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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

    /**
     * Group by image_import_queue.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by image_import_queue.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than image_import_queue.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than image_import_queue.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than image_import_queue.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than image_import_queue.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
