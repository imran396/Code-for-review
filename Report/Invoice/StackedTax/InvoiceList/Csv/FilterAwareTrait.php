<?php
/**
 * SAM-4623 : Refactor invoice list report
 * https://bidpath.atlassian.net/browse/SAM-4623
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/17/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Invoice\StackedTax\InvoiceList\Csv;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Settings\SettingsManager;

/**
 * Trait FilterAwareTrait
 * @package Sam\Report\Invoice\StackedTax\InvoiceList\Csv
 */
trait FilterAwareTrait
{
    protected ?string $primarySort = null;
    protected ?string $secondarySort = null;
    protected ?string $searchKey = null;
    protected ?string $winningUserSearchKey = null;
    protected ?int $invoiceNo = null;
    protected ?int $invoiceStatus = null;
    protected ?int $winningUserId = null;
    protected ?bool $isMultipleSaleInvoice = null;
    protected bool $isCustomFieldRender = false;
    protected bool $isAccountFiltering = false;

    /**
     * @return bool
     */
    public function isAccountFiltering(): bool
    {
        return $this->isAccountFiltering;
    }

    public function enableAccountFiltering(bool $enable): static
    {
        $this->isAccountFiltering = $enable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCustomFieldRender(): bool
    {
        return $this->isCustomFieldRender;
    }

    /**
     * @param bool $isIncludeCustomFields
     * @return static
     */
    public function enableCustomFieldRender(bool $isIncludeCustomFields): static
    {
        $this->isCustomFieldRender = $isIncludeCustomFields;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrimarySort(): ?string
    {
        return $this->primarySort;
    }

    /**
     * @param string $primarySort
     * @return static
     */
    public function setPrimarySort(string $primarySort): static
    {
        $this->primarySort = trim($primarySort);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSecondarySort(): ?string
    {
        return $this->secondarySort;
    }

    /**
     * @param string $secondarySort
     * @return static
     */
    public function setSecondarySort(string $secondarySort): static
    {
        $this->secondarySort = trim($secondarySort);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getInvoiceStatus(): ?int
    {
        return $this->invoiceStatus;
    }

    /**
     * @param int|null $status
     * @return static
     */
    public function filterInvoiceStatus(?int $status): static
    {
        $this->invoiceStatus = Cast::toInt($status, Constants\Invoice::$availableInvoiceStatuses);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSearchKey(): ?string
    {
        return $this->searchKey;
    }

    /**
     * @param string $searchKey
     * @return static
     */
    public function filterSearchKey(string $searchKey): static
    {
        $this->searchKey = trim($searchKey);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getInvoiceNo(): ?int
    {
        return $this->invoiceNo;
    }

    /**
     * @param int|null $invoiceNo
     * @return static
     */
    public function filterInvoiceNo(?int $invoiceNo): static
    {
        $this->invoiceNo = Cast::toInt($invoiceNo);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getWinningUserId(): ?int
    {
        return $this->winningUserId;
    }

    /**
     * @param int|null $userId
     * @return static
     */
    public function filterWinningUserId(?int $userId): static
    {
        $this->winningUserId = Cast::toInt($userId);
        return $this;
    }

    /**
     * @param string|null $bidderKey
     * @return static
     */
    public function filterWinningUserSearchKey(?string $bidderKey): static
    {
        $this->winningUserSearchKey = trim((string)$bidderKey);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getWinningUserSearchKey(): ?string
    {
        return $this->winningUserSearchKey;
    }

    /**
     * @param bool $isMultipleSale
     * @return static
     */
    public function enableMultipleSaleInvoice(bool $isMultipleSale): static
    {
        $this->isMultipleSaleInvoice = $isMultipleSale;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMultipleSaleInvoice(): bool
    {
        if ($this->isMultipleSaleInvoice === null) {
            $this->isMultipleSaleInvoice = (bool)SettingsManager::new()
                ->get(Constants\Setting::MULTIPLE_SALE_INVOICE, $this->getSystemAccountId());
        }
        return $this->isMultipleSaleInvoice;
    }
}
