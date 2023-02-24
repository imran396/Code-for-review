<?php
/**
 * Invoice Line Items Data Loader
 *
 * SAM-6442: Refactor system parameters invoicing and payment page at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SystemParameterInvoicingForm\Load;


use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceLineItem\InvoiceLineItemReadRepository;
use Sam\Storage\ReadRepository\Entity\InvoiceLineItem\InvoiceLineItemReadRepositoryCreateTrait;
use Sam\View\Admin\Form\SystemParameterInvoicingForm\SystemParameterInvoicingConstants;

/**
 * Class InvoiceLineItemsDataLoader
 */
class InvoiceLineItemsDataLoader extends CustomizableClass
{
    use InvoiceLineItemReadRepositoryCreateTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * @return int - return value of Invoice Line Items count
     */
    public function count(): int
    {
        return $this->prepareInvoiceLineItemRepository()->count();
    }

    /**
     * @return array - return values for Invoice Line Items
     */
    public function load(): array
    {
        $repo = $this->prepareInvoiceLineItemRepository();

        switch ($this->getSortColumn()) {
            case SystemParameterInvoicingConstants::ORD_INVOICE_LINE_ITEMS_LABEL:
                $repo->orderByLabel($this->isAscendingOrder());
                break;
            case SystemParameterInvoicingConstants::ORD_INVOICE_LINE_ITEMS_AMOUNT:
                $repo->orderByAmount($this->isAscendingOrder());
                break;
            case SystemParameterInvoicingConstants::ORD_INVOICE_LINE_ITEMS_AUCTION_TYPE:
                $repo->orderByAuctionType($this->isAscendingOrder());
                break;
        }

        if ($this->getOffset()) {
            $repo->offset($this->getOffset());
        }

        if ($this->getLimit()) {
            $repo->limit($this->getLimit());
        }

        return $repo->loadEntities();
    }

    /**
     * @return InvoiceLineItemReadRepository
     */
    protected function prepareInvoiceLineItemRepository(): InvoiceLineItemReadRepository
    {
        return $this->createInvoiceLineItemReadRepository()
            ->filterAccountId($this->getSystemAccountId())
            ->filterActive(true);
    }
}
