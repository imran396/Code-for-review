<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\ReportImageImport;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractReportImageImportDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_REPORT_IMAGE_IMPORT;
    protected string $alias = Db::A_REPORT_IMAGE_IMPORT;

    /**
     * Filter by report_image_import.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out report_image_import.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by report_image_import.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out report_image_import.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by report_image_import.profile_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterProfileName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.profile_name', $filterValue);
        return $this;
    }

    /**
     * Filter out report_image_import.profile_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipProfileName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.profile_name', $skipValue);
        return $this;
    }

    /**
     * Filter by report_image_import.replace_existing_image
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterReplaceExistingImage(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.replace_existing_image', $filterValue);
        return $this;
    }

    /**
     * Filter out report_image_import.replace_existing_image from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipReplaceExistingImage(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.replace_existing_image', $skipValue);
        return $this;
    }

    /**
     * Filter by report_image_import.location_type
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLocationType(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.location_type', $filterValue);
        return $this;
    }

    /**
     * Filter out report_image_import.location_type from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLocationType(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.location_type', $skipValue);
        return $this;
    }

    /**
     * Filter by report_image_import.username
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterUsername(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.username', $filterValue);
        return $this;
    }

    /**
     * Filter out report_image_import.username from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipUsername(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.username', $skipValue);
        return $this;
    }

    /**
     * Filter by report_image_import.pword
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPword(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pword', $filterValue);
        return $this;
    }

    /**
     * Filter out report_image_import.pword from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPword(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pword', $skipValue);
        return $this;
    }

    /**
     * Filter by report_image_import.host
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterHost(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.host', $filterValue);
        return $this;
    }

    /**
     * Filter out report_image_import.host from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipHost(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.host', $skipValue);
        return $this;
    }

    /**
     * Filter by report_image_import.directory
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterDirectory(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.directory', $filterValue);
        return $this;
    }

    /**
     * Filter out report_image_import.directory from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipDirectory(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.directory', $skipValue);
        return $this;
    }

    /**
     * Filter by report_image_import.image_identifier
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterImageIdentifier(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.image_identifier', $filterValue);
        return $this;
    }

    /**
     * Filter out report_image_import.image_identifier from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipImageIdentifier(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.image_identifier', $skipValue);
        return $this;
    }

    /**
     * Filter by report_image_import.image_number_seperator
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterImageNumberSeperator(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.image_number_seperator', $filterValue);
        return $this;
    }

    /**
     * Filter out report_image_import.image_number_seperator from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipImageNumberSeperator(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.image_number_seperator', $skipValue);
        return $this;
    }

    /**
     * Filter by report_image_import.status
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterStatus(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.status', $filterValue);
        return $this;
    }

    /**
     * Filter out report_image_import.status from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipStatus(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.status', $skipValue);
        return $this;
    }

    /**
     * Filter by report_image_import.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out report_image_import.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by report_image_import.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out report_image_import.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by report_image_import.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out report_image_import.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by report_image_import.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out report_image_import.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by report_image_import.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out report_image_import.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
