<?php
/**
 * SAM-4377: Invoice producer
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01.08.2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\StackedTax\Generate\Note\General;

use Invoice;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\Config\Invoice\ResponsiveInvoiceViewUrlConfig;
use Sam\Core\Service\CustomizableClass;
use InvalidArgumentException;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class InvoiceNoteGeneralInfoBuilder
 * @package Sam\Invoice\StackedTax\Generate\Note\General
 */
class InvoiceNoteGeneralInfoBuilder extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use NumberFormatterAwareTrait;
    use SettingsManagerAwareTrait;
    use UrlBuilderAwareTrait;
    use UrlParserAwareTrait;
    use UserLoaderAwareTrait;

    /** @var string[] */
    protected array $defaultInvoiceNotes = [];

    /** @var bool[] */
    protected array $multipleSaleInvoice = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Parse Default Invoice Note variables with invoice values and build invoice notes
     * @param Invoice $invoice
     * @return string
     */
    public function build(Invoice $invoice): string
    {
        $placeholderValues = [];
        $note = '';
        if (!$this->isMultipleSaleInvoice($invoice->AccountId)) {
            // We take auction specific note of the first invoice item (SAM-1323)
            $invoiceItemRows = $this->getInvoiceItemLoader()->loadSelectedByInvoiceId(['ii.auction_id'], $invoice->Id);
            if ($invoiceItemRows) {
                $auction = $this->getAuctionLoader()->load((int)$invoiceItemRows[0]['auction_id'], true);
                $note = $auction ? trim($auction->InvoiceNotes) : '';
            }
        }
        if (empty($note)) {
            $note = $this->getDefaultInvoiceNotes($invoice->AccountId);
        }
        if (!empty($note)) {
            $winningUserId = $invoice->BidderId;
            $winningUser = $this->getUserLoader()->load($winningUserId);
            if (!$winningUser) {
                $this->handleError(
                    "Available winning bidder user not found for invoice note general info building "
                    . "(u: {$winningUserId}, i: {$invoice->Id})"
                );
            }
            $placeholderValues['username'] = $winningUser->Username;
            $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($winningUser->Id);
            $placeholderValues['first_name'] = $userInfo->FirstName;
            $placeholderValues['last_name'] = $userInfo->LastName;
            $invoiceTotal = $invoice->calcInvoiceTotal(); // TODO: Should we consider cash discount there?
            $placeholderValues['invoice_total'] = $this->getNumberFormatter()->formatMoney($invoiceTotal);
            $placeholderValues['invoice_number'] = $invoice->InvoiceNo;
            $invoiceViewUrl = $this->getUrlBuilder()->build(
                ResponsiveInvoiceViewUrlConfig::new()->forDomainRule(
                    $invoice->Id,
                    [UrlConfigConstants::OP_ACCOUNT_ID => $invoice->AccountId]
                )
            );
            $placeholderValues['invoice_url'] = $invoiceViewUrl;
            $placeholderValues['currency'] = $invoice->CurrencySign;
            foreach ($placeholderValues as $key => $value) {
                $note = str_replace('{' . $key . '}', $value, $note);
            }
        }
        return trim($note);
    }

    /**
     * @param int $accountId
     * @return string
     */
    public function getDefaultInvoiceNotes(int $accountId): string
    {
        if (!isset($this->defaultInvoiceNotes[$accountId])) {
            $this->defaultInvoiceNotes[$accountId] =
                $this->getSettingsManager()->get(Constants\Setting::DEFAULT_INVOICE_NOTES, $accountId);
        }
        return $this->defaultInvoiceNotes[$accountId];
    }

    /**
     * @param int $accountId
     * @return bool
     */
    public function isMultipleSaleInvoice(int $accountId): bool
    {
        if (!isset($this->multipleSaleInvoice[$accountId])) {
            $this->multipleSaleInvoice[$accountId] =
                (bool)$this->getSettingsManager()->get(Constants\Setting::MULTIPLE_SALE_INVOICE, $accountId);
        }
        return $this->multipleSaleInvoice[$accountId];
    }

    /**
     * @param string $message
     */
    protected function handleError(string $message): void
    {
        log_error($message);
        throw new InvalidArgumentException($message);
    }
}
