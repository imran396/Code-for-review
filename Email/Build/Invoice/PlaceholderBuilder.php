<?php
/**
 * SAM-5018 : Refactor Email_Template to sub classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Apr 1, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Build\Invoice;

use Invoice;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Register\AuctionRegistrationManagerAwareTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Email\Build\PlaceholdersAbstractBuilder;
use Sam\Invoice\Common\Calculate\Basic\AnyInvoiceCalculatorCreateTrait;
use Sam\Invoice\Legacy\View\Print\LegacyInvoiceViewPrintRenderer;
use Sam\Invoice\StackedTax\View\InvoiceEmail\StackedTaxInvoiceEmailRenderer;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class PlaceholdersBuilder
 * @package Sam\Email\Build\Test
 */
class PlaceholderBuilder extends PlaceholdersAbstractBuilder
{
    use AnyInvoiceCalculatorCreateTrait;
    use AuctionLoaderAwareTrait;
    use AuctionRegistrationManagerAwareTrait;
    use DateHelperAwareTrait;
    use NumberFormatterAwareTrait;
    use TimezoneLoaderAwareTrait;

    public const AVAILABLE_PLACEHOLDERS = [
        'first_name',
        'last_name',
        'username',
        'reset_password_url',
        'invoice_total',
        'invoice_balance',
        'invoice_id',
        'invoice_number',
        'invoice_html',
        'invoice_url',
        'currency',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array
     */
    public function build(): array
    {
        $invoice = $this->getDataConverter()->getInvoice();
        $editorUserId = $this->getDataConverter()->getEditorUserId();
        $viewLanguageId = $this->getDataConverter()->getViewLanguageId();
        $invoiceId = $invoice->Id;
        $user = $this->getUserLoader()->load($invoice->BidderId, true);
        if (!$user) {
            log_error(
                "Available user not found for invoice bidder user"
                . composeSuffix(['u' => $invoice->BidderId])
            );
            return [];
        }

        $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($user->Id, true);

        $grandTotal = $this->createAnyInvoiceCalculator()->calcGrandTotal($invoice);
        $balanceDue = $this->createAnyInvoiceCalculator()->calcRoundedBalanceDue($invoice);

        $placeholders = [
            'first_name' => $userInfo->FirstName,
            'last_name' => $userInfo->LastName,
            'username' => $user->Username,
            'invoice_total' => $this->getNumberFormatter()->formatMoney($grandTotal),
            'invoice_balance' => $this->getNumberFormatter()->formatMoney($balanceDue),
            'invoice_id' => $invoiceId,
            'invoice_number' => $invoice->InvoiceNo,
            'invoice_html' => $this->renderInvoiceHtml($invoice, $viewLanguageId),
            'currency' => $invoice->CurrencySign,
            'reset_password_url' => $this->buildResetPasswordUrl($user->Id, $invoice->AccountId, $editorUserId),
            'invoice_url' => $this->buildResponsiveInvoiceUrl($invoice),
        ];
        $placeholders = array_merge($placeholders, $this->getDefaultPlaceholders());
        return $placeholders;
    }

    protected function renderInvoiceHtml(Invoice $invoice, int $viewLanguageId): string
    {
        if ($invoice->isStackedTaxDesignation()) {
            return StackedTaxInvoiceEmailRenderer::new()->render($invoice->Id, $viewLanguageId);
        }

        return LegacyInvoiceViewPrintRenderer::new()->render($invoice->Id, $viewLanguageId);
    }

}
