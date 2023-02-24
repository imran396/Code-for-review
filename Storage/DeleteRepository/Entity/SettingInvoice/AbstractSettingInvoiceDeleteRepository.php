<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingInvoice;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingInvoiceDeleteRepository extends DeleteRepositoryBase
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
}
