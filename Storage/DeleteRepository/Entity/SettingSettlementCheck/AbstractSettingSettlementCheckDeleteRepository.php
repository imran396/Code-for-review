<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingSettlementCheck;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingSettlementCheckDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_SETTING_SETTLEMENT_CHECK;
    protected string $alias = Db::A_SETTING_SETTLEMENT_CHECK;

    /**
     * Filter by setting_settlement_check.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_file
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterStlmCheckFile(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_file', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_file from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipStlmCheckFile(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_file', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_name_coord_x
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckNameCoordX(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_name_coord_x', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_name_coord_x from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckNameCoordX(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_name_coord_x', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_name_coord_y
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckNameCoordY(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_name_coord_y', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_name_coord_y from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckNameCoordY(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_name_coord_y', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_amount_coord_x
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckAmountCoordX(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_amount_coord_x', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_amount_coord_x from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckAmountCoordX(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_amount_coord_x', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_amount_coord_y
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckAmountCoordY(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_amount_coord_y', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_amount_coord_y from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckAmountCoordY(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_amount_coord_y', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_date_coord_x
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckDateCoordX(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_date_coord_x', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_date_coord_x from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckDateCoordX(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_date_coord_x', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_date_coord_y
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckDateCoordY(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_date_coord_y', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_date_coord_y from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckDateCoordY(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_date_coord_y', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_memo_coord_x
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckMemoCoordX(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_memo_coord_x', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_memo_coord_x from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckMemoCoordX(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_memo_coord_x', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_memo_coord_y
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckMemoCoordY(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_memo_coord_y', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_memo_coord_y from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckMemoCoordY(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_memo_coord_y', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_address_coord_x
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckAddressCoordX(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_address_coord_x', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_address_coord_x from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckAddressCoordX(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_address_coord_x', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_address_coord_y
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckAddressCoordY(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_address_coord_y', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_address_coord_y from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckAddressCoordY(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_address_coord_y', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_amount_spelling_coord_x
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckAmountSpellingCoordX(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_amount_spelling_coord_x', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_amount_spelling_coord_x from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckAmountSpellingCoordX(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_amount_spelling_coord_x', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_amount_spelling_coord_y
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckAmountSpellingCoordY(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_amount_spelling_coord_y', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_amount_spelling_coord_y from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckAmountSpellingCoordY(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_amount_spelling_coord_y', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_height
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckHeight(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_height', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_height from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckHeight(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_height', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_per_page
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterStlmCheckPerPage(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_per_page', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_per_page from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipStlmCheckPerPage(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_per_page', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_repeat_count
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterStlmCheckRepeatCount(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_repeat_count', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_repeat_count from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipStlmCheckRepeatCount(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_repeat_count', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_address_template
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckAddressTemplate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_address_template', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_address_template from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckAddressTemplate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_address_template', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_payee_template
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckPayeeTemplate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_payee_template', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_payee_template from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckPayeeTemplate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_payee_template', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_memo_template
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterStlmCheckMemoTemplate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_memo_template', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_memo_template from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipStlmCheckMemoTemplate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_memo_template', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.stlm_check_enabled
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterStlmCheckEnabled(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.stlm_check_enabled', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.stlm_check_enabled from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipStlmCheckEnabled(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.stlm_check_enabled', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_settlement_check.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_settlement_check.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
