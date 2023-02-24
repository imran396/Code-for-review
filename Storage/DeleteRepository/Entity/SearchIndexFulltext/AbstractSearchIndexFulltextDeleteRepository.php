<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SearchIndexFulltext;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSearchIndexFulltextDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_SEARCH_INDEX_FULLTEXT;
    protected string $alias = Db::A_SEARCH_INDEX_FULLTEXT;

    /**
     * Filter by search_index_fulltext.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out search_index_fulltext.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by search_index_fulltext.entity_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterEntityType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.entity_type', $filterValue);
        return $this;
    }

    /**
     * Filter out search_index_fulltext.entity_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipEntityType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.entity_type', $skipValue);
        return $this;
    }

    /**
     * Filter by search_index_fulltext.entity_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterEntityId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.entity_id', $filterValue);
        return $this;
    }

    /**
     * Filter out search_index_fulltext.entity_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipEntityId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.entity_id', $skipValue);
        return $this;
    }

    /**
     * Filter by search_index_fulltext.public_content
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPublicContent(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.public_content', $filterValue);
        return $this;
    }

    /**
     * Filter out search_index_fulltext.public_content from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPublicContent(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.public_content', $skipValue);
        return $this;
    }

    /**
     * Filter by search_index_fulltext.full_content
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFullContent(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.full_content', $filterValue);
        return $this;
    }

    /**
     * Filter out search_index_fulltext.full_content from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFullContent(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.full_content', $skipValue);
        return $this;
    }

    /**
     * Filter by search_index_fulltext.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out search_index_fulltext.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by search_index_fulltext.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out search_index_fulltext.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by search_index_fulltext.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out search_index_fulltext.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by search_index_fulltext.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out search_index_fulltext.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by search_index_fulltext.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out search_index_fulltext.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
