<?php
/**
 * SAM-11127: Stacked Tax. New Invoice Edit page: Payment Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoicePaymentEditFormInput
 * @package Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Dto
 */
class InvoicePaymentEditFormInput extends CustomizableClass
{
    public readonly ?int $creditCardId;
    public readonly ?int $paymentMethod;
    public readonly string $paymentGateway;
    public readonly ?int $paymentId;
    public readonly ?int $taxSchemaId;
    public readonly bool $applyCashDiscount;
    public readonly int $invoiceAccountId;
    public readonly int $invoiceId;
    public readonly int $systemAccountId;
    public readonly string $dateSysIso;
    public readonly string $language;
    public readonly string $netAmount;
    public readonly string $note;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        ?int $creditCardId,
        ?int $paymentMethod,
        string $paymentGateway,
        ?int $paymentId,
        ?int $taxSchemaId,
        bool $applyCashDiscount,
        int $invoiceAccountId,
        int $invoiceId,
        int $systemAccountId,
        string $amount,
        string $dateSysIso,
        string $note,
        string $language
    ): static {
        $this->creditCardId = $creditCardId;
        $this->paymentMethod = $paymentMethod;
        $this->paymentGateway = $paymentGateway;
        $this->paymentId = $paymentId;
        $this->taxSchemaId = $taxSchemaId;
        $this->applyCashDiscount = $applyCashDiscount;
        $this->invoiceAccountId = $invoiceAccountId;
        $this->invoiceId = $invoiceId;
        $this->systemAccountId = $systemAccountId;
        $this->netAmount = trim($amount);
        $this->dateSysIso = trim($dateSysIso);
        $this->note = trim($note);
        $this->language = $language;
        return $this;
    }
}
