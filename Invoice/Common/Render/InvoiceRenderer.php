<?php
/**
 * Helping methods for rendering invoice fields
 *
 * SAM-4111: Invoice and settlement fields renderers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 21, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Render;

use Invoice;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Html\HtmlRenderer;
use Sam\Invoice\Common\Render\Logo\InvoiceLogoPathResolverCreateTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class InvoiceRenderer
 * @package Sam\Invoice\Common\Render
 */
class InvoiceRenderer extends CustomizableClass
{
    use AccountLoaderAwareTrait;
    use AdminTranslatorAwareTrait;
    use InvoiceLogoPathResolverCreateTrait;
    use TranslatorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $invoiceStatus
     * @param int|null $accountId
     * @param int|null $languageId
     * @return string
     */
    public function makeInvoiceStatusTranslated(
        int $invoiceStatus,
        ?int $accountId = null,
        ?int $languageId = null
    ): string {
        $langStatuses = [
            Constants\Invoice::IS_OPEN => 'MYINVOICES_STATUS_OPEN',
            Constants\Invoice::IS_PENDING => 'MYINVOICES_STATUS_PENDING',
            Constants\Invoice::IS_PAID => 'MYINVOICES_STATUS_PAID',
            Constants\Invoice::IS_SHIPPED => 'MYINVOICES_STATUS_SHIPPED',
            Constants\Invoice::IS_CANCELED => 'MYINVOICES_STATUS_CANCELED',
            Constants\Invoice::IS_DELETED => 'MYINVOICES_STATUS_DELETED',
        ];
        $output = $this->getTranslator()->translate(
            $langStatuses[$invoiceStatus],
            'myinvoices',
            $accountId,
            $languageId
        );
        return $output;
    }

    /**
     * Render payment status based on invoice status
     * @param int $invoiceStatus
     * @param int|null $accountId
     * @param int|null $languageId
     * @return string
     */
    public function makePaymentStatusTranslated(
        int $invoiceStatus,
        ?int $accountId = null,
        ?int $languageId = null
    ): string {
        $langPaymentStatuses = [
            Constants\Invoice::IS_OPEN => 'PAYMENT_OPEN',
            Constants\Invoice::IS_PENDING => 'PAYMENT_PENDING',
            Constants\Invoice::IS_PAID => 'PAYMENT_PAID',
            Constants\Invoice::IS_SHIPPED => 'PAYMENT_SHIPPED',
            Constants\Invoice::IS_CANCELED => 'PAYMENT_CANCELED',
            Constants\Invoice::IS_DELETED => 'PAYMENT_DELETED',
        ];
        $output = '';
        if (isset($langPaymentStatuses[$invoiceStatus])) {
            $output = $this->getTranslator()->translate(
                $langPaymentStatuses[$invoiceStatus],
                'auctions',
                $accountId,
                $languageId
            );
        }
        return $output;
    }

    /**
     * Return invoice logo image tag
     * @param Invoice $invoice
     * @param array $attributes
     * @return string
     */
    public function renderLogoTag(Invoice $invoice, array $attributes = []): string
    {
        $attributes['src'] = $this->createInvoiceLogoPathResolver()->buildUrlByInvoice($invoice);
        $attributes['title'] = $attributes['title'] ?? '';
        if (!isset($attributes['alt'])) {
            $account = $this->getAccountLoader()->load($invoice->AccountId);
            $attributes['alt'] = $account->Name ?? '';
        }
        $output = HtmlRenderer::new()->makeImgHtmlTag('img', $attributes);
        return $output;
    }

    /**
     * Render service type translated
     * @param int $type
     * @param int|null $accountId
     * @param int|null $languageId
     * @return string
     */
    public function makeServiceTypeTranslated(int $type, ?int $accountId = null, ?int $languageId = null): string
    {
        $langKeys = [
            Constants\Invoice::IA_EXTRA_CHARGE => 'MYINVOICES_SERVICETYPE_EXTRACHARGE',
            Constants\Invoice::IA_EXTRA_FEE => 'MYINVOICES_SERVICETYPE_EXTRAFEE',
            Constants\Invoice::IA_SHIPPING => 'MYINVOICES_SERVICETYPE_SHIPPING',
            Constants\Invoice::IA_ARTIST_RESALE_RIGHTS => 'MYINVOICES_SERVICETYPE_ARTISTRESALERIGHTS',
            Constants\Invoice::IA_PROCESSING_FEE => 'MYINVOICES_SERVICETYPE_PROCESSINGFEE',
            Constants\Invoice::IA_CC_SURCHARGE => 'MYINVOICES_SERVICETYPE_SURCHARGE',
            Constants\Invoice::IA_CASH_DISCOUNT => 'MYINVOICES_SERVICETYPE_CASHDISCOUNT',
        ];
        if (!isset($langKeys[$type])) {
            return '';
        }
        return $this->getTranslator()->translate($langKeys[$type], 'myinvoices', $accountId, $languageId);
    }

    /**
     * Render service type translated
     * @param int $type
     * @param string $language
     * @param string $domain
     * @return string
     */
    public function makeServiceTypeTranslatedForAdmin(int $type, string $language, string $domain = 'admin_stacked_tax'): string
    {
        $langKeys = [
            Constants\Invoice::IA_EXTRA_CHARGE => 'invoice_additional.type.extra_charge',
            Constants\Invoice::IA_EXTRA_FEE => 'invoice_additional.type.extra_fee',
            Constants\Invoice::IA_SHIPPING => 'invoice_additional.type.shipping',
            Constants\Invoice::IA_ARTIST_RESALE_RIGHTS => 'invoice_additional.type.artist_resale_rights',
            Constants\Invoice::IA_PROCESSING_FEE => 'invoice_additional.type.processing_fee',
            Constants\Invoice::IA_CC_SURCHARGE => 'invoice_additional.type.cc_surcharge',
            Constants\Invoice::IA_CASH_DISCOUNT => 'invoice_additional.type.cash_discount',
        ];
        if (!isset($langKeys[$type])) {
            return '';
        }

        return $this->getAdminTranslator()->trans($langKeys[$type], [], $domain, $language);
    }

    /**
     * Render service type translated
     */
    public function makeServiceTypeTranslatedForResponsive(int $type, int $languageId, int $accountId, string $section = 'stacked_tax_invoices'): string
    {
        $langKeys = [
            Constants\Invoice::IA_EXTRA_CHARGE => 'INVOICE_ADDITIONAL_TYPE_EXTRA_CHARGE',
            Constants\Invoice::IA_EXTRA_FEE => 'INVOICE_ADDITIONAL_TYPE_EXTRA_FEE',
            Constants\Invoice::IA_SHIPPING => 'INVOICE_ADDITIONAL_TYPE_SHIPPING',
            Constants\Invoice::IA_ARTIST_RESALE_RIGHTS => 'INVOICE_ADDITIONAL_TYPE_ARTIST_RESALE_RIGHTS',
            Constants\Invoice::IA_PROCESSING_FEE => 'INVOICE_ADDITIONAL_TYPE_PROCESSING_FEE',
            Constants\Invoice::IA_CC_SURCHARGE => 'INVOICE_ADDITIONAL_TYPE_CC_SURCHARGE',
            Constants\Invoice::IA_CASH_DISCOUNT => 'INVOICE_ADDITIONAL_TYPE_CASHDISCOUNT',
        ];
        if (!isset($langKeys[$type])) {
            return '';
        }

        return $this->getTranslator()->translate($langKeys[$type], $section, $accountId, $languageId);
    }


}
