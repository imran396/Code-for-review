<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Consignor;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractConsignorDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_CONSIGNOR;
    protected string $alias = Db::A_CONSIGNOR;

    /**
     * Filter by consignor.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by consignor.user_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUserId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor.user_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUserId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Filter by consignor.sales_tax
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterSalesTax(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.sales_tax', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor.sales_tax from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipSalesTax(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.sales_tax', $skipValue);
        return $this;
    }

    /**
     * Filter by consignor.buyer_tax_hp
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBuyerTaxHp(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.buyer_tax_hp', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor.buyer_tax_hp from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBuyerTaxHp(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.buyer_tax_hp', $skipValue);
        return $this;
    }

    /**
     * Filter by consignor.buyer_tax_bp
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBuyerTaxBp(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.buyer_tax_bp', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor.buyer_tax_bp from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBuyerTaxBp(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.buyer_tax_bp', $skipValue);
        return $this;
    }

    /**
     * Filter by consignor.buyer_tax_services
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBuyerTaxServices(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.buyer_tax_services', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor.buyer_tax_services from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBuyerTaxServices(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.buyer_tax_services', $skipValue);
        return $this;
    }

    /**
     * Filter by consignor.consignor_tax
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterConsignorTax(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_tax', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor.consignor_tax from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipConsignorTax(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_tax', $skipValue);
        return $this;
    }

    /**
     * Filter by consignor.consignor_tax_hp
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterConsignorTaxHp(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_tax_hp', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor.consignor_tax_hp from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipConsignorTaxHp(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_tax_hp', $skipValue);
        return $this;
    }

    /**
     * Filter by consignor.consignor_tax_hp_type
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterConsignorTaxHpType(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_tax_hp_type', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor.consignor_tax_hp_type from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipConsignorTaxHpType(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_tax_hp_type', $skipValue);
        return $this;
    }

    /**
     * Filter by consignor.consignor_tax_comm
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterConsignorTaxComm(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_tax_comm', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor.consignor_tax_comm from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipConsignorTaxComm(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_tax_comm', $skipValue);
        return $this;
    }

    /**
     * Filter by consignor.consignor_tax_services
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterConsignorTaxServices(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.consignor_tax_services', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor.consignor_tax_services from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipConsignorTaxServices(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.consignor_tax_services', $skipValue);
        return $this;
    }

    /**
     * Filter by consignor.payment_info
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPaymentInfo(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.payment_info', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor.payment_info from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPaymentInfo(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.payment_info', $skipValue);
        return $this;
    }

    /**
     * Filter by consignor.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by consignor.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by consignor.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by consignor.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by consignor.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out consignor.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
