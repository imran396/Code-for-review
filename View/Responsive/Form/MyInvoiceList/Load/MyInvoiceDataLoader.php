<?php
/**
 * My Invoice Data Loader
 *
 * SAM-6307: Refactor My Invoice List page at client side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 17, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\MyInvoiceList\Load;

use Sam\Account\CrossAccountTransparency\CrossAccountTransparencyCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\ReadRepository\Entity\Invoice\InvoiceReadRepository;
use Sam\Storage\ReadRepository\Entity\Invoice\InvoiceReadRepositoryCreateTrait;

/**
 * Class MyInvoiceDataLoader
 */
class MyInvoiceDataLoader extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use CrossAccountTransparencyCheckerCreateTrait;
    use EditorUserAwareTrait;
    use InvoiceReadRepositoryCreateTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;

    protected string $filterSearchKey = '';
    protected int $filterSelectedStatus = 0;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $searchKey
     * @return static
     */
    public function filterSearchKey(string $searchKey): static
    {
        $this->filterSearchKey = $searchKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilterSearchKey(): string
    {
        return $this->filterSearchKey;
    }

    /**
     * @param int $selectedStatus
     * @return $this
     */
    public function filterSelectedStatus(int $selectedStatus): static
    {
        $this->filterSelectedStatus = $selectedStatus;
        return $this;
    }

    /**
     * @return int
     */
    public function getFilterSelectedStatus(): int
    {
        return $this->filterSelectedStatus;
    }

    /**
     * @return int - return value of Invoices count
     */
    public function count(): int
    {
        return $this->prepareInvoiceRepository()->count();
    }

    /**
     * @return array - return values for Invoices
     */
    public function load(): array
    {
        $repo = $this->prepareInvoiceRepository();

        if ($this->getSortColumn()) {
            switch ($this->getSortColumn()) {
                case 'inv-num':
                    $repo->orderByInvoiceNo($this->isAscendingOrder());
                    break;
                case 'date':
                    $repo->orderByCreatedOn($this->isAscendingOrder());
                    break;
                case 'num-items':    //relegate sorting of num items to QQ::Expand because it is a virtual attribute
                    break;
            }
        } else {
            //order by inv-num by default
            $repo->orderByInvoiceNo(false);
        }

        if ($this->getOffset()) {
            $repo->offset($this->getOffset());
        }

        if ($this->getLimit()) {
            $repo->limit($this->getLimit());
        }

        $dtos = [];
        foreach ($repo->loadRows() as $row) {
            $dtos[] = MyInvoiceDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * @return InvoiceReadRepository
     */
    protected function prepareInvoiceRepository(): InvoiceReadRepository
    {
        $filterAccountId = $this->getSystemAccountId();
        if (
            !$this->cfg()->get('core->portal->enabled')
            || $this->createCrossAccountTransparencyChecker()->isAvailableByAccount($this->getSystemAccount())
        ) {
            $filterAccountId = null;
        }

        $invoiceRepository = $this->createInvoiceReadRepository()
            ->select(
                [
                    'i.id',
                    'i.invoice_no',
                    'i.created_on',
                    'i.invoice_date',
                    'i.invoice_status_id',
                    'i.account_id',
                    'i.tax_designation',
                    '(SELECT IFNULL((SELECT id FROM auction WHERE id = (SELECT auction_id FROM invoice_item WHERE active AND invoice_id = i.id AND auction_id > 0 LIMIT 1)), 0)) AS sale_id',
                    '(SELECT COUNT(1) FROM invoice_item WHERE invoice_id = i.id ) AS num_item',
                ]
            )
            ->enableReadOnlyDb(true)
            ->filterBidderId($this->getEditorUserId())
            ->filterInvoiceStatusId(Constants\Invoice::$publicAvailableInvoiceStatuses)
            ->joinAccountFilterActive(true);

        if ($filterAccountId) {
            $invoiceRepository->filterAccountId($filterAccountId);
        }

        if ($this->getFilterSearchKey() !== '') {
            $key = addslashes('%' . $this->getFilterSearchKey() . '%');
            $invoiceStatusList = implode(',', Constants\Invoice::$publicAvailableInvoiceStatuses);
            $invoiceRepository
                ->likeInvoiceNo($key)
                ->joinUserLikeUsername($key)
                ->likeSubquery(
                    "(SELECT li.name FROM lot_item AS li, invoice_item AS ii, invoice AS inv "
                    . "WHERE li.id = ii.lot_item_id "
                    . "AND ii.invoice_id = i.id "
                    . "AND inv.invoice_status_id IN ({$invoiceStatusList}) LIMIT 1)",
                    $key
                );
        }
        //list action
        if (in_array($this->getFilterSelectedStatus(), Constants\Invoice::$publicAvailableInvoiceStatuses, true)) {
            $invoiceRepository->filterInvoiceStatusId($this->getFilterSelectedStatus());
        }
        $invoiceRepository->skipSubquery(
            "(SELECT IFNULL((SELECT id FROM auction WHERE id = "
            . "(SELECT auction_id FROM invoice_item WHERE active AND invoice_id = i.id AND auction_id > 0 LIMIT 1)), 0))",
            null
        );
        $invoiceRepository->skipSubquery("(SELECT COUNT(1) FROM invoice_item WHERE invoice_id = i.id)", null);

        return $invoiceRepository;
    }
}
