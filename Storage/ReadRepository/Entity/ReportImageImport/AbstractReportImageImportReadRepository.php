<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\ReportImageImport;

use ReportImageImport;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractReportImageImportReadRepository
 * @method ReportImageImport[] loadEntities()
 * @method ReportImageImport|null loadEntity()
 */
abstract class AbstractReportImageImportReadRepository extends ReadRepositoryBase
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
     * Group by report_image_import.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by report_image_import.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than report_image_import.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than report_image_import.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than report_image_import.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than report_image_import.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by report_image_import.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by report_image_import.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than report_image_import.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than report_image_import.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than report_image_import.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than report_image_import.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
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
     * Group by report_image_import.profile_name
     * @return static
     */
    public function groupByProfileName(): static
    {
        $this->group($this->alias . '.profile_name');
        return $this;
    }

    /**
     * Order by report_image_import.profile_name
     * @param bool $ascending
     * @return static
     */
    public function orderByProfileName(bool $ascending = true): static
    {
        $this->order($this->alias . '.profile_name', $ascending);
        return $this;
    }

    /**
     * Filter report_image_import.profile_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeProfileName(string $filterValue): static
    {
        $this->like($this->alias . '.profile_name', "%{$filterValue}%");
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
     * Group by report_image_import.replace_existing_image
     * @return static
     */
    public function groupByReplaceExistingImage(): static
    {
        $this->group($this->alias . '.replace_existing_image');
        return $this;
    }

    /**
     * Order by report_image_import.replace_existing_image
     * @param bool $ascending
     * @return static
     */
    public function orderByReplaceExistingImage(bool $ascending = true): static
    {
        $this->order($this->alias . '.replace_existing_image', $ascending);
        return $this;
    }

    /**
     * Filter by greater than report_image_import.replace_existing_image
     * @param bool $filterValue
     * @return static
     */
    public function filterReplaceExistingImageGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.replace_existing_image', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than report_image_import.replace_existing_image
     * @param bool $filterValue
     * @return static
     */
    public function filterReplaceExistingImageGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.replace_existing_image', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than report_image_import.replace_existing_image
     * @param bool $filterValue
     * @return static
     */
    public function filterReplaceExistingImageLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.replace_existing_image', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than report_image_import.replace_existing_image
     * @param bool $filterValue
     * @return static
     */
    public function filterReplaceExistingImageLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.replace_existing_image', $filterValue, '<=');
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
     * Group by report_image_import.location_type
     * @return static
     */
    public function groupByLocationType(): static
    {
        $this->group($this->alias . '.location_type');
        return $this;
    }

    /**
     * Order by report_image_import.location_type
     * @param bool $ascending
     * @return static
     */
    public function orderByLocationType(bool $ascending = true): static
    {
        $this->order($this->alias . '.location_type', $ascending);
        return $this;
    }

    /**
     * Filter report_image_import.location_type by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLocationType(string $filterValue): static
    {
        $this->like($this->alias . '.location_type', "%{$filterValue}%");
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
     * Group by report_image_import.username
     * @return static
     */
    public function groupByUsername(): static
    {
        $this->group($this->alias . '.username');
        return $this;
    }

    /**
     * Order by report_image_import.username
     * @param bool $ascending
     * @return static
     */
    public function orderByUsername(bool $ascending = true): static
    {
        $this->order($this->alias . '.username', $ascending);
        return $this;
    }

    /**
     * Filter report_image_import.username by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeUsername(string $filterValue): static
    {
        $this->like($this->alias . '.username', "%{$filterValue}%");
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
     * Group by report_image_import.pword
     * @return static
     */
    public function groupByPword(): static
    {
        $this->group($this->alias . '.pword');
        return $this;
    }

    /**
     * Order by report_image_import.pword
     * @param bool $ascending
     * @return static
     */
    public function orderByPword(bool $ascending = true): static
    {
        $this->order($this->alias . '.pword', $ascending);
        return $this;
    }

    /**
     * Filter report_image_import.pword by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePword(string $filterValue): static
    {
        $this->like($this->alias . '.pword', "%{$filterValue}%");
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
     * Group by report_image_import.host
     * @return static
     */
    public function groupByHost(): static
    {
        $this->group($this->alias . '.host');
        return $this;
    }

    /**
     * Order by report_image_import.host
     * @param bool $ascending
     * @return static
     */
    public function orderByHost(bool $ascending = true): static
    {
        $this->order($this->alias . '.host', $ascending);
        return $this;
    }

    /**
     * Filter report_image_import.host by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeHost(string $filterValue): static
    {
        $this->like($this->alias . '.host', "%{$filterValue}%");
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
     * Group by report_image_import.directory
     * @return static
     */
    public function groupByDirectory(): static
    {
        $this->group($this->alias . '.directory');
        return $this;
    }

    /**
     * Order by report_image_import.directory
     * @param bool $ascending
     * @return static
     */
    public function orderByDirectory(bool $ascending = true): static
    {
        $this->order($this->alias . '.directory', $ascending);
        return $this;
    }

    /**
     * Filter report_image_import.directory by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeDirectory(string $filterValue): static
    {
        $this->like($this->alias . '.directory', "%{$filterValue}%");
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
     * Group by report_image_import.image_identifier
     * @return static
     */
    public function groupByImageIdentifier(): static
    {
        $this->group($this->alias . '.image_identifier');
        return $this;
    }

    /**
     * Order by report_image_import.image_identifier
     * @param bool $ascending
     * @return static
     */
    public function orderByImageIdentifier(bool $ascending = true): static
    {
        $this->order($this->alias . '.image_identifier', $ascending);
        return $this;
    }

    /**
     * Filter report_image_import.image_identifier by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeImageIdentifier(string $filterValue): static
    {
        $this->like($this->alias . '.image_identifier', "%{$filterValue}%");
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
     * Group by report_image_import.image_number_seperator
     * @return static
     */
    public function groupByImageNumberSeperator(): static
    {
        $this->group($this->alias . '.image_number_seperator');
        return $this;
    }

    /**
     * Order by report_image_import.image_number_seperator
     * @param bool $ascending
     * @return static
     */
    public function orderByImageNumberSeperator(bool $ascending = true): static
    {
        $this->order($this->alias . '.image_number_seperator', $ascending);
        return $this;
    }

    /**
     * Filter by greater than report_image_import.image_number_seperator
     * @param int $filterValue
     * @return static
     */
    public function filterImageNumberSeperatorGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_number_seperator', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than report_image_import.image_number_seperator
     * @param int $filterValue
     * @return static
     */
    public function filterImageNumberSeperatorGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_number_seperator', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than report_image_import.image_number_seperator
     * @param int $filterValue
     * @return static
     */
    public function filterImageNumberSeperatorLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_number_seperator', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than report_image_import.image_number_seperator
     * @param int $filterValue
     * @return static
     */
    public function filterImageNumberSeperatorLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.image_number_seperator', $filterValue, '<=');
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
     * Group by report_image_import.status
     * @return static
     */
    public function groupByStatus(): static
    {
        $this->group($this->alias . '.status');
        return $this;
    }

    /**
     * Order by report_image_import.status
     * @param bool $ascending
     * @return static
     */
    public function orderByStatus(bool $ascending = true): static
    {
        $this->order($this->alias . '.status', $ascending);
        return $this;
    }

    /**
     * Filter by greater than report_image_import.status
     * @param int $filterValue
     * @return static
     */
    public function filterStatusGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.status', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than report_image_import.status
     * @param int $filterValue
     * @return static
     */
    public function filterStatusGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.status', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than report_image_import.status
     * @param int $filterValue
     * @return static
     */
    public function filterStatusLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.status', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than report_image_import.status
     * @param int $filterValue
     * @return static
     */
    public function filterStatusLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.status', $filterValue, '<=');
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
     * Group by report_image_import.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by report_image_import.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than report_image_import.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than report_image_import.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than report_image_import.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than report_image_import.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
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
     * Group by report_image_import.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by report_image_import.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than report_image_import.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than report_image_import.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than report_image_import.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than report_image_import.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by report_image_import.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by report_image_import.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than report_image_import.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than report_image_import.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than report_image_import.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than report_image_import.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by report_image_import.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by report_image_import.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than report_image_import.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than report_image_import.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than report_image_import.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than report_image_import.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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

    /**
     * Group by report_image_import.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by report_image_import.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than report_image_import.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than report_image_import.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than report_image_import.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than report_image_import.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
