<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingInvoice;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingInvoice;

/**
 * Abstract class AbstractSettingInvoiceReadRepository
 * @method SettingInvoice[] loadEntities()
 * @method SettingInvoice|null loadEntity()
 */
abstract class AbstractSettingInvoiceReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_SETTING_INVOICE;
    protected string $alias = Db::A_SETTING_INVOICE;

    /**
     * Filter by setting_invoice.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_invoice.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_invoice.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.auto_invoice
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAutoInvoice(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auto_invoice', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.auto_invoice from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAutoInvoice(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auto_invoice', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.auto_invoice
     * @return static
     */
    public function groupByAutoInvoice(): static
    {
        $this->group($this->alias . '.auto_invoice');
        return $this;
    }

    /**
     * Order by setting_invoice.auto_invoice
     * @param bool $ascending
     * @return static
     */
    public function orderByAutoInvoice(bool $ascending = true): static
    {
        $this->order($this->alias . '.auto_invoice', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.auto_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoInvoiceGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_invoice', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.auto_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoInvoiceGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_invoice', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.auto_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoInvoiceLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_invoice', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.auto_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoInvoiceLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_invoice', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.cash_discount
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterCashDiscount(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.cash_discount', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.cash_discount from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipCashDiscount(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.cash_discount', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.cash_discount
     * @return static
     */
    public function groupByCashDiscount(): static
    {
        $this->group($this->alias . '.cash_discount');
        return $this;
    }

    /**
     * Order by setting_invoice.cash_discount
     * @param bool $ascending
     * @return static
     */
    public function orderByCashDiscount(bool $ascending = true): static
    {
        $this->order($this->alias . '.cash_discount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.cash_discount
     * @param float $filterValue
     * @return static
     */
    public function filterCashDiscountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.cash_discount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.cash_discount
     * @param float $filterValue
     * @return static
     */
    public function filterCashDiscountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.cash_discount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.cash_discount
     * @param float $filterValue
     * @return static
     */
    public function filterCashDiscountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.cash_discount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.cash_discount
     * @param float $filterValue
     * @return static
     */
    public function filterCashDiscountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.cash_discount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.category_in_invoice
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterCategoryInInvoice(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.category_in_invoice', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.category_in_invoice from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipCategoryInInvoice(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.category_in_invoice', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.category_in_invoice
     * @return static
     */
    public function groupByCategoryInInvoice(): static
    {
        $this->group($this->alias . '.category_in_invoice');
        return $this;
    }

    /**
     * Order by setting_invoice.category_in_invoice
     * @param bool $ascending
     * @return static
     */
    public function orderByCategoryInInvoice(bool $ascending = true): static
    {
        $this->order($this->alias . '.category_in_invoice', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.category_in_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterCategoryInInvoiceGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.category_in_invoice', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.category_in_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterCategoryInInvoiceGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.category_in_invoice', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.category_in_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterCategoryInInvoiceLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.category_in_invoice', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.category_in_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterCategoryInInvoiceLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.category_in_invoice', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.default_invoice_notes
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterDefaultInvoiceNotes(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.default_invoice_notes', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.default_invoice_notes from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipDefaultInvoiceNotes(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.default_invoice_notes', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.default_invoice_notes
     * @return static
     */
    public function groupByDefaultInvoiceNotes(): static
    {
        $this->group($this->alias . '.default_invoice_notes');
        return $this;
    }

    /**
     * Order by setting_invoice.default_invoice_notes
     * @param bool $ascending
     * @return static
     */
    public function orderByDefaultInvoiceNotes(bool $ascending = true): static
    {
        $this->order($this->alias . '.default_invoice_notes', $ascending);
        return $this;
    }

    /**
     * Filter setting_invoice.default_invoice_notes by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeDefaultInvoiceNotes(string $filterValue): static
    {
        $this->like($this->alias . '.default_invoice_notes', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_invoice.default_lot_item_no_tax_oos
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterDefaultLotItemNoTaxOos(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.default_lot_item_no_tax_oos', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.default_lot_item_no_tax_oos from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipDefaultLotItemNoTaxOos(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.default_lot_item_no_tax_oos', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.default_lot_item_no_tax_oos
     * @return static
     */
    public function groupByDefaultLotItemNoTaxOos(): static
    {
        $this->group($this->alias . '.default_lot_item_no_tax_oos');
        return $this;
    }

    /**
     * Order by setting_invoice.default_lot_item_no_tax_oos
     * @param bool $ascending
     * @return static
     */
    public function orderByDefaultLotItemNoTaxOos(bool $ascending = true): static
    {
        $this->order($this->alias . '.default_lot_item_no_tax_oos', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.default_lot_item_no_tax_oos
     * @param bool $filterValue
     * @return static
     */
    public function filterDefaultLotItemNoTaxOosGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_lot_item_no_tax_oos', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.default_lot_item_no_tax_oos
     * @param bool $filterValue
     * @return static
     */
    public function filterDefaultLotItemNoTaxOosGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_lot_item_no_tax_oos', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.default_lot_item_no_tax_oos
     * @param bool $filterValue
     * @return static
     */
    public function filterDefaultLotItemNoTaxOosLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_lot_item_no_tax_oos', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.default_lot_item_no_tax_oos
     * @param bool $filterValue
     * @return static
     */
    public function filterDefaultLotItemNoTaxOosLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_lot_item_no_tax_oos', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.default_post_auc_import_premium
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterDefaultPostAucImportPremium(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.default_post_auc_import_premium', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.default_post_auc_import_premium from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipDefaultPostAucImportPremium(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.default_post_auc_import_premium', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.default_post_auc_import_premium
     * @return static
     */
    public function groupByDefaultPostAucImportPremium(): static
    {
        $this->group($this->alias . '.default_post_auc_import_premium');
        return $this;
    }

    /**
     * Order by setting_invoice.default_post_auc_import_premium
     * @param bool $ascending
     * @return static
     */
    public function orderByDefaultPostAucImportPremium(bool $ascending = true): static
    {
        $this->order($this->alias . '.default_post_auc_import_premium', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.default_post_auc_import_premium
     * @param float $filterValue
     * @return static
     */
    public function filterDefaultPostAucImportPremiumGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_post_auc_import_premium', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.default_post_auc_import_premium
     * @param float $filterValue
     * @return static
     */
    public function filterDefaultPostAucImportPremiumGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_post_auc_import_premium', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.default_post_auc_import_premium
     * @param float $filterValue
     * @return static
     */
    public function filterDefaultPostAucImportPremiumLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_post_auc_import_premium', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.default_post_auc_import_premium
     * @param float $filterValue
     * @return static
     */
    public function filterDefaultPostAucImportPremiumLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_post_auc_import_premium', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.invoice_identification
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInvoiceIdentification(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_identification', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.invoice_identification from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInvoiceIdentification(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_identification', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.invoice_identification
     * @return static
     */
    public function groupByInvoiceIdentification(): static
    {
        $this->group($this->alias . '.invoice_identification');
        return $this;
    }

    /**
     * Order by setting_invoice.invoice_identification
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceIdentification(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_identification', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.invoice_identification
     * @param bool $filterValue
     * @return static
     */
    public function filterInvoiceIdentificationGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_identification', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.invoice_identification
     * @param bool $filterValue
     * @return static
     */
    public function filterInvoiceIdentificationGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_identification', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.invoice_identification
     * @param bool $filterValue
     * @return static
     */
    public function filterInvoiceIdentificationLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_identification', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.invoice_identification
     * @param bool $filterValue
     * @return static
     */
    public function filterInvoiceIdentificationLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_identification', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.invoice_item_description
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInvoiceItemDescription(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_item_description', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.invoice_item_description from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInvoiceItemDescription(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_item_description', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.invoice_item_description
     * @return static
     */
    public function groupByInvoiceItemDescription(): static
    {
        $this->group($this->alias . '.invoice_item_description');
        return $this;
    }

    /**
     * Order by setting_invoice.invoice_item_description
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceItemDescription(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_item_description', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.invoice_item_description
     * @param bool $filterValue
     * @return static
     */
    public function filterInvoiceItemDescriptionGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_item_description', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.invoice_item_description
     * @param bool $filterValue
     * @return static
     */
    public function filterInvoiceItemDescriptionGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_item_description', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.invoice_item_description
     * @param bool $filterValue
     * @return static
     */
    public function filterInvoiceItemDescriptionLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_item_description', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.invoice_item_description
     * @param bool $filterValue
     * @return static
     */
    public function filterInvoiceItemDescriptionLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_item_description', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.invoice_item_sales_tax_application
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterInvoiceItemSalesTaxApplication(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_item_sales_tax_application', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.invoice_item_sales_tax_application from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipInvoiceItemSalesTaxApplication(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_item_sales_tax_application', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.invoice_item_sales_tax_application
     * @return static
     */
    public function groupByInvoiceItemSalesTaxApplication(): static
    {
        $this->group($this->alias . '.invoice_item_sales_tax_application');
        return $this;
    }

    /**
     * Order by setting_invoice.invoice_item_sales_tax_application
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceItemSalesTaxApplication(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_item_sales_tax_application', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.invoice_item_sales_tax_application
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceItemSalesTaxApplicationGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_item_sales_tax_application', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.invoice_item_sales_tax_application
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceItemSalesTaxApplicationGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_item_sales_tax_application', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.invoice_item_sales_tax_application
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceItemSalesTaxApplicationLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_item_sales_tax_application', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.invoice_item_sales_tax_application
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceItemSalesTaxApplicationLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_item_sales_tax_application', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.invoice_item_sales_tax
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInvoiceItemSalesTax(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_item_sales_tax', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.invoice_item_sales_tax from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInvoiceItemSalesTax(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_item_sales_tax', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.invoice_item_sales_tax
     * @return static
     */
    public function groupByInvoiceItemSalesTax(): static
    {
        $this->group($this->alias . '.invoice_item_sales_tax');
        return $this;
    }

    /**
     * Order by setting_invoice.invoice_item_sales_tax
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceItemSalesTax(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_item_sales_tax', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.invoice_item_sales_tax
     * @param bool $filterValue
     * @return static
     */
    public function filterInvoiceItemSalesTaxGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_item_sales_tax', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.invoice_item_sales_tax
     * @param bool $filterValue
     * @return static
     */
    public function filterInvoiceItemSalesTaxGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_item_sales_tax', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.invoice_item_sales_tax
     * @param bool $filterValue
     * @return static
     */
    public function filterInvoiceItemSalesTaxLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_item_sales_tax', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.invoice_item_sales_tax
     * @param bool $filterValue
     * @return static
     */
    public function filterInvoiceItemSalesTaxLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_item_sales_tax', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.invoice_item_separate_tax
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInvoiceItemSeparateTax(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_item_separate_tax', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.invoice_item_separate_tax from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInvoiceItemSeparateTax(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_item_separate_tax', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.invoice_item_separate_tax
     * @return static
     */
    public function groupByInvoiceItemSeparateTax(): static
    {
        $this->group($this->alias . '.invoice_item_separate_tax');
        return $this;
    }

    /**
     * Order by setting_invoice.invoice_item_separate_tax
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceItemSeparateTax(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_item_separate_tax', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.invoice_item_separate_tax
     * @param bool $filterValue
     * @return static
     */
    public function filterInvoiceItemSeparateTaxGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_item_separate_tax', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.invoice_item_separate_tax
     * @param bool $filterValue
     * @return static
     */
    public function filterInvoiceItemSeparateTaxGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_item_separate_tax', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.invoice_item_separate_tax
     * @param bool $filterValue
     * @return static
     */
    public function filterInvoiceItemSeparateTaxLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_item_separate_tax', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.invoice_item_separate_tax
     * @param bool $filterValue
     * @return static
     */
    public function filterInvoiceItemSeparateTaxLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_item_separate_tax', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.invoice_logo
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterInvoiceLogo(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_logo', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.invoice_logo from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipInvoiceLogo(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_logo', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.invoice_logo
     * @return static
     */
    public function groupByInvoiceLogo(): static
    {
        $this->group($this->alias . '.invoice_logo');
        return $this;
    }

    /**
     * Order by setting_invoice.invoice_logo
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceLogo(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_logo', $ascending);
        return $this;
    }

    /**
     * Filter setting_invoice.invoice_logo by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeInvoiceLogo(string $filterValue): static
    {
        $this->like($this->alias . '.invoice_logo', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_invoice.keep_decimal_invoice
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterKeepDecimalInvoice(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.keep_decimal_invoice', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.keep_decimal_invoice from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipKeepDecimalInvoice(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.keep_decimal_invoice', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.keep_decimal_invoice
     * @return static
     */
    public function groupByKeepDecimalInvoice(): static
    {
        $this->group($this->alias . '.keep_decimal_invoice');
        return $this;
    }

    /**
     * Order by setting_invoice.keep_decimal_invoice
     * @param bool $ascending
     * @return static
     */
    public function orderByKeepDecimalInvoice(bool $ascending = true): static
    {
        $this->order($this->alias . '.keep_decimal_invoice', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.keep_decimal_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterKeepDecimalInvoiceGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.keep_decimal_invoice', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.keep_decimal_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterKeepDecimalInvoiceGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.keep_decimal_invoice', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.keep_decimal_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterKeepDecimalInvoiceLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.keep_decimal_invoice', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.keep_decimal_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterKeepDecimalInvoiceLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.keep_decimal_invoice', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.multiple_sale_invoice
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterMultipleSaleInvoice(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.multiple_sale_invoice', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.multiple_sale_invoice from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipMultipleSaleInvoice(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.multiple_sale_invoice', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.multiple_sale_invoice
     * @return static
     */
    public function groupByMultipleSaleInvoice(): static
    {
        $this->group($this->alias . '.multiple_sale_invoice');
        return $this;
    }

    /**
     * Order by setting_invoice.multiple_sale_invoice
     * @param bool $ascending
     * @return static
     */
    public function orderByMultipleSaleInvoice(bool $ascending = true): static
    {
        $this->order($this->alias . '.multiple_sale_invoice', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.multiple_sale_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterMultipleSaleInvoiceGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.multiple_sale_invoice', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.multiple_sale_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterMultipleSaleInvoiceGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.multiple_sale_invoice', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.multiple_sale_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterMultipleSaleInvoiceLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.multiple_sale_invoice', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.multiple_sale_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterMultipleSaleInvoiceLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.multiple_sale_invoice', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.one_sale_grouped_invoice
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterOneSaleGroupedInvoice(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.one_sale_grouped_invoice', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.one_sale_grouped_invoice from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipOneSaleGroupedInvoice(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.one_sale_grouped_invoice', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.one_sale_grouped_invoice
     * @return static
     */
    public function groupByOneSaleGroupedInvoice(): static
    {
        $this->group($this->alias . '.one_sale_grouped_invoice');
        return $this;
    }

    /**
     * Order by setting_invoice.one_sale_grouped_invoice
     * @param bool $ascending
     * @return static
     */
    public function orderByOneSaleGroupedInvoice(bool $ascending = true): static
    {
        $this->order($this->alias . '.one_sale_grouped_invoice', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.one_sale_grouped_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterOneSaleGroupedInvoiceGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.one_sale_grouped_invoice', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.one_sale_grouped_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterOneSaleGroupedInvoiceGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.one_sale_grouped_invoice', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.one_sale_grouped_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterOneSaleGroupedInvoiceLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.one_sale_grouped_invoice', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.one_sale_grouped_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterOneSaleGroupedInvoiceLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.one_sale_grouped_invoice', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.processing_charge
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterProcessingCharge(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.processing_charge', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.processing_charge from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipProcessingCharge(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.processing_charge', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.processing_charge
     * @return static
     */
    public function groupByProcessingCharge(): static
    {
        $this->group($this->alias . '.processing_charge');
        return $this;
    }

    /**
     * Order by setting_invoice.processing_charge
     * @param bool $ascending
     * @return static
     */
    public function orderByProcessingCharge(bool $ascending = true): static
    {
        $this->order($this->alias . '.processing_charge', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.processing_charge
     * @param float $filterValue
     * @return static
     */
    public function filterProcessingChargeGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.processing_charge', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.processing_charge
     * @param float $filterValue
     * @return static
     */
    public function filterProcessingChargeGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.processing_charge', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.processing_charge
     * @param float $filterValue
     * @return static
     */
    public function filterProcessingChargeLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.processing_charge', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.processing_charge
     * @param float $filterValue
     * @return static
     */
    public function filterProcessingChargeLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.processing_charge', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.quantity_in_invoice
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterQuantityInInvoice(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.quantity_in_invoice', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.quantity_in_invoice from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipQuantityInInvoice(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.quantity_in_invoice', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.quantity_in_invoice
     * @return static
     */
    public function groupByQuantityInInvoice(): static
    {
        $this->group($this->alias . '.quantity_in_invoice');
        return $this;
    }

    /**
     * Order by setting_invoice.quantity_in_invoice
     * @param bool $ascending
     * @return static
     */
    public function orderByQuantityInInvoice(bool $ascending = true): static
    {
        $this->order($this->alias . '.quantity_in_invoice', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.quantity_in_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterQuantityInInvoiceGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_in_invoice', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.quantity_in_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterQuantityInInvoiceGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_in_invoice', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.quantity_in_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterQuantityInInvoiceLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_in_invoice', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.quantity_in_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterQuantityInInvoiceLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_in_invoice', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.render_lot_custom_fields_in_separate_row_for_invoice
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRenderLotCustomFieldsInSeparateRowForInvoice(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.render_lot_custom_fields_in_separate_row_for_invoice', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.render_lot_custom_fields_in_separate_row_for_invoice from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRenderLotCustomFieldsInSeparateRowForInvoice(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.render_lot_custom_fields_in_separate_row_for_invoice', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.render_lot_custom_fields_in_separate_row_for_invoice
     * @return static
     */
    public function groupByRenderLotCustomFieldsInSeparateRowForInvoice(): static
    {
        $this->group($this->alias . '.render_lot_custom_fields_in_separate_row_for_invoice');
        return $this;
    }

    /**
     * Order by setting_invoice.render_lot_custom_fields_in_separate_row_for_invoice
     * @param bool $ascending
     * @return static
     */
    public function orderByRenderLotCustomFieldsInSeparateRowForInvoice(bool $ascending = true): static
    {
        $this->order($this->alias . '.render_lot_custom_fields_in_separate_row_for_invoice', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.render_lot_custom_fields_in_separate_row_for_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterRenderLotCustomFieldsInSeparateRowForInvoiceGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.render_lot_custom_fields_in_separate_row_for_invoice', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.render_lot_custom_fields_in_separate_row_for_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterRenderLotCustomFieldsInSeparateRowForInvoiceGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.render_lot_custom_fields_in_separate_row_for_invoice', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.render_lot_custom_fields_in_separate_row_for_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterRenderLotCustomFieldsInSeparateRowForInvoiceLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.render_lot_custom_fields_in_separate_row_for_invoice', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.render_lot_custom_fields_in_separate_row_for_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterRenderLotCustomFieldsInSeparateRowForInvoiceLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.render_lot_custom_fields_in_separate_row_for_invoice', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.sales_tax_services
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSalesTaxServices(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.sales_tax_services', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.sales_tax_services from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSalesTaxServices(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.sales_tax_services', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.sales_tax_services
     * @return static
     */
    public function groupBySalesTaxServices(): static
    {
        $this->group($this->alias . '.sales_tax_services');
        return $this;
    }

    /**
     * Order by setting_invoice.sales_tax_services
     * @param bool $ascending
     * @return static
     */
    public function orderBySalesTaxServices(bool $ascending = true): static
    {
        $this->order($this->alias . '.sales_tax_services', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.sales_tax_services
     * @param bool $filterValue
     * @return static
     */
    public function filterSalesTaxServicesGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax_services', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.sales_tax_services
     * @param bool $filterValue
     * @return static
     */
    public function filterSalesTaxServicesGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax_services', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.sales_tax_services
     * @param bool $filterValue
     * @return static
     */
    public function filterSalesTaxServicesLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax_services', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.sales_tax_services
     * @param bool $filterValue
     * @return static
     */
    public function filterSalesTaxServicesLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax_services', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.sales_tax
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterSalesTax(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.sales_tax', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.sales_tax from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipSalesTax(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.sales_tax', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.sales_tax
     * @return static
     */
    public function groupBySalesTax(): static
    {
        $this->group($this->alias . '.sales_tax');
        return $this;
    }

    /**
     * Order by setting_invoice.sales_tax
     * @param bool $ascending
     * @return static
     */
    public function orderBySalesTax(bool $ascending = true): static
    {
        $this->order($this->alias . '.sales_tax', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.sam_tax_default_country
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSamTaxDefaultCountry(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.sam_tax_default_country', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.sam_tax_default_country from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSamTaxDefaultCountry(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.sam_tax_default_country', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.sam_tax_default_country
     * @return static
     */
    public function groupBySamTaxDefaultCountry(): static
    {
        $this->group($this->alias . '.sam_tax_default_country');
        return $this;
    }

    /**
     * Order by setting_invoice.sam_tax_default_country
     * @param bool $ascending
     * @return static
     */
    public function orderBySamTaxDefaultCountry(bool $ascending = true): static
    {
        $this->order($this->alias . '.sam_tax_default_country', $ascending);
        return $this;
    }

    /**
     * Filter setting_invoice.sam_tax_default_country by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSamTaxDefaultCountry(string $filterValue): static
    {
        $this->like($this->alias . '.sam_tax_default_country', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_invoice.sam_tax
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSamTax(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.sam_tax', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.sam_tax from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSamTax(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.sam_tax', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.sam_tax
     * @return static
     */
    public function groupBySamTax(): static
    {
        $this->group($this->alias . '.sam_tax');
        return $this;
    }

    /**
     * Order by setting_invoice.sam_tax
     * @param bool $ascending
     * @return static
     */
    public function orderBySamTax(bool $ascending = true): static
    {
        $this->order($this->alias . '.sam_tax', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.sam_tax
     * @param bool $filterValue
     * @return static
     */
    public function filterSamTaxGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.sam_tax', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.sam_tax
     * @param bool $filterValue
     * @return static
     */
    public function filterSamTaxGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.sam_tax', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.sam_tax
     * @param bool $filterValue
     * @return static
     */
    public function filterSamTaxLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.sam_tax', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.sam_tax
     * @param bool $filterValue
     * @return static
     */
    public function filterSamTaxLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.sam_tax', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.shipping_charge
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterShippingCharge(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.shipping_charge', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.shipping_charge from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipShippingCharge(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.shipping_charge', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.shipping_charge
     * @return static
     */
    public function groupByShippingCharge(): static
    {
        $this->group($this->alias . '.shipping_charge');
        return $this;
    }

    /**
     * Order by setting_invoice.shipping_charge
     * @param bool $ascending
     * @return static
     */
    public function orderByShippingCharge(bool $ascending = true): static
    {
        $this->order($this->alias . '.shipping_charge', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.shipping_charge
     * @param float $filterValue
     * @return static
     */
    public function filterShippingChargeGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.shipping_charge', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.shipping_charge
     * @param float $filterValue
     * @return static
     */
    public function filterShippingChargeGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.shipping_charge', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.shipping_charge
     * @param float $filterValue
     * @return static
     */
    public function filterShippingChargeLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.shipping_charge', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.shipping_charge
     * @param float $filterValue
     * @return static
     */
    public function filterShippingChargeLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.shipping_charge', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.invoice_hp_tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterInvoiceHpTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_hp_tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.invoice_hp_tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipInvoiceHpTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_hp_tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.invoice_hp_tax_schema_id
     * @return static
     */
    public function groupByInvoiceHpTaxSchemaId(): static
    {
        $this->group($this->alias . '.invoice_hp_tax_schema_id');
        return $this;
    }

    /**
     * Order by setting_invoice.invoice_hp_tax_schema_id
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceHpTaxSchemaId(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_hp_tax_schema_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.invoice_hp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceHpTaxSchemaIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_hp_tax_schema_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.invoice_hp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceHpTaxSchemaIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_hp_tax_schema_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.invoice_hp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceHpTaxSchemaIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_hp_tax_schema_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.invoice_hp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceHpTaxSchemaIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_hp_tax_schema_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.invoice_bp_tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterInvoiceBpTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_bp_tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.invoice_bp_tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipInvoiceBpTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_bp_tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.invoice_bp_tax_schema_id
     * @return static
     */
    public function groupByInvoiceBpTaxSchemaId(): static
    {
        $this->group($this->alias . '.invoice_bp_tax_schema_id');
        return $this;
    }

    /**
     * Order by setting_invoice.invoice_bp_tax_schema_id
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceBpTaxSchemaId(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_bp_tax_schema_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.invoice_bp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceBpTaxSchemaIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_bp_tax_schema_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.invoice_bp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceBpTaxSchemaIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_bp_tax_schema_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.invoice_bp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceBpTaxSchemaIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_bp_tax_schema_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.invoice_bp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceBpTaxSchemaIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_bp_tax_schema_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.invoice_services_tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterInvoiceServicesTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_services_tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.invoice_services_tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipInvoiceServicesTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_services_tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.invoice_services_tax_schema_id
     * @return static
     */
    public function groupByInvoiceServicesTaxSchemaId(): static
    {
        $this->group($this->alias . '.invoice_services_tax_schema_id');
        return $this;
    }

    /**
     * Order by setting_invoice.invoice_services_tax_schema_id
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceServicesTaxSchemaId(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_services_tax_schema_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.invoice_services_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceServicesTaxSchemaIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_services_tax_schema_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.invoice_services_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceServicesTaxSchemaIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_services_tax_schema_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.invoice_services_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceServicesTaxSchemaIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_services_tax_schema_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.invoice_services_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceServicesTaxSchemaIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_services_tax_schema_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.invoice_tax_designation_strategy
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterInvoiceTaxDesignationStrategy(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_tax_designation_strategy', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.invoice_tax_designation_strategy from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipInvoiceTaxDesignationStrategy(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_tax_designation_strategy', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.invoice_tax_designation_strategy
     * @return static
     */
    public function groupByInvoiceTaxDesignationStrategy(): static
    {
        $this->group($this->alias . '.invoice_tax_designation_strategy');
        return $this;
    }

    /**
     * Order by setting_invoice.invoice_tax_designation_strategy
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceTaxDesignationStrategy(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_tax_designation_strategy', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.invoice_tax_designation_strategy
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceTaxDesignationStrategyGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_tax_designation_strategy', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.invoice_tax_designation_strategy
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceTaxDesignationStrategyGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_tax_designation_strategy', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.invoice_tax_designation_strategy
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceTaxDesignationStrategyLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_tax_designation_strategy', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.invoice_tax_designation_strategy
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceTaxDesignationStrategyLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_tax_designation_strategy', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_invoice.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_invoice.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_invoice.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_invoice.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_invoice.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_invoice.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by setting_invoice.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_invoice.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_invoice.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_invoice.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_invoice.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_invoice.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
