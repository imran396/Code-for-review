<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserCustData;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use UserCustData;

/**
 * Abstract class AbstractUserCustDataReadRepository
 * @method UserCustData[] loadEntities()
 * @method UserCustData|null loadEntity()
 */
abstract class AbstractUserCustDataReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_USER_CUST_DATA;
    protected string $alias = Db::A_USER_CUST_DATA;

    /**
     * Filter by user_cust_data.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_data.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_data.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by user_cust_data.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_data.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_data.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_data.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_data.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_data.user_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUserId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_data.user_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUserId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_data.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by user_cust_data.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_data.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_data.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_data.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_data.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_data.user_cust_field_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUserCustFieldId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.user_cust_field_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_data.user_cust_field_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUserCustFieldId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.user_cust_field_id', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_data.user_cust_field_id
     * @return static
     */
    public function groupByUserCustFieldId(): static
    {
        $this->group($this->alias . '.user_cust_field_id');
        return $this;
    }

    /**
     * Order by user_cust_data.user_cust_field_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserCustFieldId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_cust_field_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_data.user_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserCustFieldIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_cust_field_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_data.user_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserCustFieldIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_cust_field_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_data.user_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserCustFieldIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_cust_field_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_data.user_cust_field_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserCustFieldIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_cust_field_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_data.numeric
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterNumeric(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.numeric', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_data.numeric from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipNumeric(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.numeric', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_data.numeric
     * @return static
     */
    public function groupByNumeric(): static
    {
        $this->group($this->alias . '.numeric');
        return $this;
    }

    /**
     * Order by user_cust_data.numeric
     * @param bool $ascending
     * @return static
     */
    public function orderByNumeric(bool $ascending = true): static
    {
        $this->order($this->alias . '.numeric', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_data.numeric
     * @param int $filterValue
     * @return static
     */
    public function filterNumericGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.numeric', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_data.numeric
     * @param int $filterValue
     * @return static
     */
    public function filterNumericGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.numeric', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_data.numeric
     * @param int $filterValue
     * @return static
     */
    public function filterNumericLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.numeric', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_data.numeric
     * @param int $filterValue
     * @return static
     */
    public function filterNumericLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.numeric', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_data.text
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterText(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.text', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_data.text from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipText(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.text', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_data.text
     * @return static
     */
    public function groupByText(): static
    {
        $this->group($this->alias . '.text');
        return $this;
    }

    /**
     * Order by user_cust_data.text
     * @param bool $ascending
     * @return static
     */
    public function orderByText(bool $ascending = true): static
    {
        $this->order($this->alias . '.text', $ascending);
        return $this;
    }

    /**
     * Filter user_cust_data.text by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeText(string $filterValue): static
    {
        $this->like($this->alias . '.text', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_cust_data.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_data.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_data.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by user_cust_data.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_data.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_data.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_data.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_data.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_data.encrypted
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterEncrypted(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.encrypted', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_data.encrypted from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipEncrypted(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.encrypted', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_data.encrypted
     * @return static
     */
    public function groupByEncrypted(): static
    {
        $this->group($this->alias . '.encrypted');
        return $this;
    }

    /**
     * Order by user_cust_data.encrypted
     * @param bool $ascending
     * @return static
     */
    public function orderByEncrypted(bool $ascending = true): static
    {
        $this->order($this->alias . '.encrypted', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_data.encrypted
     * @param bool $filterValue
     * @return static
     */
    public function filterEncryptedGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.encrypted', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_data.encrypted
     * @param bool $filterValue
     * @return static
     */
    public function filterEncryptedGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.encrypted', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_data.encrypted
     * @param bool $filterValue
     * @return static
     */
    public function filterEncryptedLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.encrypted', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_data.encrypted
     * @param bool $filterValue
     * @return static
     */
    public function filterEncryptedLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.encrypted', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_data.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_data.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_data.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by user_cust_data.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_data.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_data.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_data.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_data.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_data.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_data.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_data.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by user_cust_data.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_data.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_data.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_data.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_data.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_data.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_data.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_data.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by user_cust_data.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_data.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_data.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_data.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_data.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_data.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_data.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_data.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by user_cust_data.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_data.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_data.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_data.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_data.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_data.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_data.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_data.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by user_cust_data.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_data.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_data.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_data.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_data.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
