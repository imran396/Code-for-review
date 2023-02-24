<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Consignor;

use Consignor;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractConsignorReadRepository
 * @method Consignor[] loadEntities()
 * @method Consignor|null loadEntity()
 */
abstract class AbstractConsignorReadRepository extends ReadRepositoryBase
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
     * Group by consignor.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by consignor.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by consignor.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by consignor.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
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
     * Group by consignor.sales_tax
     * @return static
     */
    public function groupBySalesTax(): static
    {
        $this->group($this->alias . '.sales_tax');
        return $this;
    }

    /**
     * Order by consignor.sales_tax
     * @param bool $ascending
     * @return static
     */
    public function orderBySalesTax(bool $ascending = true): static
    {
        $this->order($this->alias . '.sales_tax', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '<=');
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
     * Group by consignor.buyer_tax_hp
     * @return static
     */
    public function groupByBuyerTaxHp(): static
    {
        $this->group($this->alias . '.buyer_tax_hp');
        return $this;
    }

    /**
     * Order by consignor.buyer_tax_hp
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyerTaxHp(bool $ascending = true): static
    {
        $this->order($this->alias . '.buyer_tax_hp', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor.buyer_tax_hp
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyerTaxHpGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_tax_hp', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor.buyer_tax_hp
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyerTaxHpGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_tax_hp', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor.buyer_tax_hp
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyerTaxHpLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_tax_hp', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor.buyer_tax_hp
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyerTaxHpLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_tax_hp', $filterValue, '<=');
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
     * Group by consignor.buyer_tax_bp
     * @return static
     */
    public function groupByBuyerTaxBp(): static
    {
        $this->group($this->alias . '.buyer_tax_bp');
        return $this;
    }

    /**
     * Order by consignor.buyer_tax_bp
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyerTaxBp(bool $ascending = true): static
    {
        $this->order($this->alias . '.buyer_tax_bp', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor.buyer_tax_bp
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyerTaxBpGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_tax_bp', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor.buyer_tax_bp
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyerTaxBpGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_tax_bp', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor.buyer_tax_bp
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyerTaxBpLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_tax_bp', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor.buyer_tax_bp
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyerTaxBpLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_tax_bp', $filterValue, '<=');
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
     * Group by consignor.buyer_tax_services
     * @return static
     */
    public function groupByBuyerTaxServices(): static
    {
        $this->group($this->alias . '.buyer_tax_services');
        return $this;
    }

    /**
     * Order by consignor.buyer_tax_services
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyerTaxServices(bool $ascending = true): static
    {
        $this->order($this->alias . '.buyer_tax_services', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor.buyer_tax_services
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyerTaxServicesGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_tax_services', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor.buyer_tax_services
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyerTaxServicesGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_tax_services', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor.buyer_tax_services
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyerTaxServicesLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_tax_services', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor.buyer_tax_services
     * @param bool $filterValue
     * @return static
     */
    public function filterBuyerTaxServicesLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyer_tax_services', $filterValue, '<=');
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
     * Group by consignor.consignor_tax
     * @return static
     */
    public function groupByConsignorTax(): static
    {
        $this->group($this->alias . '.consignor_tax');
        return $this;
    }

    /**
     * Order by consignor.consignor_tax
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorTax(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_tax', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor.consignor_tax
     * @param float $filterValue
     * @return static
     */
    public function filterConsignorTaxGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor.consignor_tax
     * @param float $filterValue
     * @return static
     */
    public function filterConsignorTaxGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor.consignor_tax
     * @param float $filterValue
     * @return static
     */
    public function filterConsignorTaxLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor.consignor_tax
     * @param float $filterValue
     * @return static
     */
    public function filterConsignorTaxLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax', $filterValue, '<=');
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
     * Group by consignor.consignor_tax_hp
     * @return static
     */
    public function groupByConsignorTaxHp(): static
    {
        $this->group($this->alias . '.consignor_tax_hp');
        return $this;
    }

    /**
     * Order by consignor.consignor_tax_hp
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorTaxHp(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_tax_hp', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor.consignor_tax_hp
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxHpGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_hp', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor.consignor_tax_hp
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxHpGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_hp', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor.consignor_tax_hp
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxHpLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_hp', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor.consignor_tax_hp
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxHpLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_hp', $filterValue, '<=');
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
     * Group by consignor.consignor_tax_hp_type
     * @return static
     */
    public function groupByConsignorTaxHpType(): static
    {
        $this->group($this->alias . '.consignor_tax_hp_type');
        return $this;
    }

    /**
     * Order by consignor.consignor_tax_hp_type
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorTaxHpType(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_tax_hp_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor.consignor_tax_hp_type
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorTaxHpTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_hp_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor.consignor_tax_hp_type
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorTaxHpTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_hp_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor.consignor_tax_hp_type
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorTaxHpTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_hp_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor.consignor_tax_hp_type
     * @param int $filterValue
     * @return static
     */
    public function filterConsignorTaxHpTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_hp_type', $filterValue, '<=');
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
     * Group by consignor.consignor_tax_comm
     * @return static
     */
    public function groupByConsignorTaxComm(): static
    {
        $this->group($this->alias . '.consignor_tax_comm');
        return $this;
    }

    /**
     * Order by consignor.consignor_tax_comm
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorTaxComm(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_tax_comm', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor.consignor_tax_comm
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxCommGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_comm', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor.consignor_tax_comm
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxCommGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_comm', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor.consignor_tax_comm
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxCommLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_comm', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor.consignor_tax_comm
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxCommLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_comm', $filterValue, '<=');
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
     * Group by consignor.consignor_tax_services
     * @return static
     */
    public function groupByConsignorTaxServices(): static
    {
        $this->group($this->alias . '.consignor_tax_services');
        return $this;
    }

    /**
     * Order by consignor.consignor_tax_services
     * @param bool $ascending
     * @return static
     */
    public function orderByConsignorTaxServices(bool $ascending = true): static
    {
        $this->order($this->alias . '.consignor_tax_services', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor.consignor_tax_services
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxServicesGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_services', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor.consignor_tax_services
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxServicesGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_services', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor.consignor_tax_services
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxServicesLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_services', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor.consignor_tax_services
     * @param bool $filterValue
     * @return static
     */
    public function filterConsignorTaxServicesLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.consignor_tax_services', $filterValue, '<=');
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
     * Group by consignor.payment_info
     * @return static
     */
    public function groupByPaymentInfo(): static
    {
        $this->group($this->alias . '.payment_info');
        return $this;
    }

    /**
     * Order by consignor.payment_info
     * @param bool $ascending
     * @return static
     */
    public function orderByPaymentInfo(bool $ascending = true): static
    {
        $this->order($this->alias . '.payment_info', $ascending);
        return $this;
    }

    /**
     * Filter consignor.payment_info by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePaymentInfo(string $filterValue): static
    {
        $this->like($this->alias . '.payment_info', "%{$filterValue}%");
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
     * Group by consignor.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by consignor.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
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
     * Group by consignor.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by consignor.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by consignor.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by consignor.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by consignor.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by consignor.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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

    /**
     * Group by consignor.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by consignor.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than consignor.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than consignor.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than consignor.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than consignor.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
