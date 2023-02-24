<?php

/**
 * SAM-3694:Invoice related repositories  https://bidpath.atlassian.net/browse/SAM-3694
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           19 August, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of Invoice filtered by criteria
 * $invoiceRepository = \Sam\Storage\ReadRepository\Entity\Invoice\InvoiceReadRepository::new()
 *     ->filterActive($active)          // single value passed as argument
 *     ->filterId($ids);   // array passed as argument
 *
 * $isFound = $invoiceRepository->exist();
 * $count = $invoiceRepository->count();
 * $invoices = $invoiceRepository->loadEntities();
 *
 * // Sample2. Load single Invoice
 * $invoiceRepository = \Sam\Storage\ReadRepository\Entity\Invoice\InvoiceReadRepository::new()
 *     ->filterId(1);
 * $invoice = $invoiceRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\Invoice;

/**
 * Class InvoiceReadRepository
 * @package Sam\Storage\ReadRepository\Entity\Invoice
 */
class InvoiceReadRepository extends AbstractInvoiceReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'account' => 'JOIN account acc ON acc.id = i.account_id',
        'invoice_additional' => 'JOIN invoice_additional iadd ON iadd.invoice_id = i.id',
        'invoice_item' => 'JOIN invoice_item ii ON ii.invoice_id = i.id',
        'invoice_user_billing' => 'JOIN invoice_user_billing iub ON iub.invoice_id = i.id',
        'invoice_user_shipping' => 'JOIN invoice_user_shipping ius ON ius.invoice_id = i.id',
        'lot_item' => 'JOIN lot_item li ON ii.lot_item_id = li.id',
        'user' => 'JOIN user u ON u.id = i.bidder_id ',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join 'account' table
     * @return static
     */
    public function joinAccount(): static
    {
        $this->join('account');
        return $this;
    }

    /**
     * Define filtering by account.active
     * @param bool|bool[] $active
     * @return static
     */
    public function joinAccountFilterActive(bool|array|null $active): static
    {
        $this->joinAccount();
        $this->filterArray('acc.active', $active);
        return $this;
    }

    public function joinInvoiceAdditional(): static
    {
        $this->join('invoice_additional');
        return $this;
    }

    public function joinInvoiceAdditionalFilterId(?int $invoiceAdditionalId): static
    {
        $this->joinInvoiceAdditional();
        $this->filterArray('iadd.id', $invoiceAdditionalId);
        return $this;
    }

    /**
     * Left join `invoice_item` table
     * @return static
     */
    public function joinInvoiceItem(): static
    {
        $this->join('invoice_item');
        return $this;
    }

    /**
     * Left join invoice_item and lot_item (based on invoice_item.lot_item_id) tables
     * @return static
     */
    public function joinLotItem(): static
    {
        $this->join('invoice_item');
        $this->join('lot_item');
        return $this;
    }

    /**
     * Define filtering by ii.active
     * @param bool $active
     * @return static
     */
    public function joinInvoiceItemFilterActive(bool $active): static
    {
        $this->joinInvoiceItem();
        $this->filterArray('ii.active', $active);
        return $this;
    }

    /**
     * Join invoice_item table
     * Define filtering by ii.lot_item_id
     * @param int|int[] $lotItemId
     * @return static
     */
    public function joinInvoiceItemFilterLotItemId(int|array|null $lotItemId): static
    {
        $this->joinInvoiceItem();
        $this->filterArray('ii.lot_item_id', $lotItemId);
        return $this;
    }

    /**
     * Define filtering by ii.auction_id
     * @param int|int[] $auctionId
     * @return static
     */
    public function joinInvoiceItemFilterAuctionId(int|array|null $auctionId): static
    {
        $this->joinInvoiceItem();
        $this->filterArray('ii.auction_id', $auctionId);
        return $this;
    }

    /**
     * Define filtering by li.auction_id
     * @param int|int[] $auctionId
     * @return static
     */
    public function joinLotItemFilterAuctionId(int|array|null $auctionId): static
    {
        $this->joinLotItem();
        $this->filterArray('li.auction_id', $auctionId);
        return $this;
    }

    /**
     * Left join `invoice_user_billing` table
     * @return static
     */
    public function joinInvoiceUserBilling(): static
    {
        $this->join('invoice_user_billing');
        return $this;
    }

    /**
     * Define filtering by iub.invoice_id
     * @param int|int[] $invoiceId
     * @return static
     */
    public function joinInvoiceUserBillingFilterInvoiceId(int|array|null $invoiceId): static
    {
        $this->joinInvoiceUserBilling();
        $this->filterArray('iub.invoice_id', $invoiceId);
        return $this;
    }

    /**
     * Left join `invoice_user_shipping` table
     * @return static
     */
    public function joinInvoiceUserShipping(): static
    {
        $this->join('invoice_user_shipping');
        return $this;
    }

    /**
     * Define filtering by ius.invoice_id
     * @param int|int[] $invoiceId
     * @return static
     */
    public function joinInvoiceUserShippingFilterInvoiceId(int|array|null $invoiceId): static
    {
        $this->joinInvoiceUserShipping();
        $this->filterArray('ius.invoice_id', $invoiceId);
        return $this;
    }

    /**
     * Left join `user` table
     * @return static
     */
    public function joinUser(): static
    {
        $this->join('user');
        return $this;
    }

    /**
     * Define filtering by u.username
     * @param string $username
     * @return static
     */
    public function joinUserLikeUsername(string $username): static
    {
        $this->joinUser();
        $this->like('u.username', $username);
        return $this;
    }

    /**
     * @param string $invoiceNo
     * @return static
     */
    public function likeInvoiceNo(string $invoiceNo): static
    {
        $this->like('i.invoice_no', $invoiceNo);
        return $this;
    }
}
