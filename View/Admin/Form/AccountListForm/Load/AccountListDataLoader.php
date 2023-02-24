<?php
/**
 * SAM-5673: Refactor data loader for Account List datagrid at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 09, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AccountListForm\Load;

use Sam\Core\Constants;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\ReadRepository\Entity\Account\AccountReadRepository;
use Sam\Storage\ReadRepository\Entity\Account\AccountReadRepositoryCreateTrait;
use Sam\View\Admin\Form\AccountListForm\AccountListConstants;

/**
 * Class AccountListDataLoader
 * @package Sam\View\Admin\Form\AccountListForm\Load
 */
class AccountListDataLoader extends CustomizableClass
{
    use AccountReadRepositoryCreateTrait;
    use ConfigRepositoryAwareTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;

    private const DEFAULT_SORT_COLUMN = 'id';
    private const DEFAULT_SORT_ENABLE_ASCENDING = true;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->setSortColumn(self::DEFAULT_SORT_COLUMN);
        $this->enableAscendingOrder(self::DEFAULT_SORT_ENABLE_ASCENDING);

        return $this;
    }

    /**
     * @return array
     */
    public function load(): array
    {
        $dtos = [];
        foreach ($this->prepareRepository()->loadRows() as $row) {
            $dtos[] = AccountListDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->prepareRepository()->count();
    }

    /**
     * @return bool
     */
    private function isPortalUrlHandlingEqMainDomain(): bool
    {
        return $this->cfg()->get('core->portal->urlHandling') === Constants\PortalUrlHandling::MAIN_DOMAIN;
    }

    /**
     * @return AccountReadRepository
     */
    private function prepareRepository(): AccountReadRepository
    {
        $repository = $this->createAccountReadRepository();
        $repository
            ->enableReadOnlyDb(true)
            ->filterActive(true)
            ->select(
                [
                    'acc.`id`',
                    'acc.`name`',
                    'acc.`company_name`',
                    'acc.`url_domain`'
                ]
            );
        $this->prepareRepositoryLimits($repository)
            ->prepareRepositoryOrderBy($repository);
        return $repository;
    }

    /**
     * @param AccountReadRepository $repository
     * @return static
     */
    private function prepareRepositoryLimits(AccountReadRepository $repository): static
    {
        if ($this->getLimit() !== null) {
            $repository->limit($this->getLimit());
        }
        if ($this->getOffset() !== null) {
            $repository->offset($this->getOffset());
        }
        return $this;
    }

    /**
     * @param AccountReadRepository $repository
     * @return static
     */
    private function prepareRepositoryOrderBy(AccountReadRepository $repository): static
    {
        $sortColumn = $this->getSortColumn();
        if ($sortColumn === AccountListConstants::ORD_BY_ACCOUNT_URL) {
            if ($this->isPortalUrlHandlingEqMainDomain()) {
                $repository->orderByUrlDomain($this->isAscendingOrder());
            }
            $repository->orderByName($this->isAscendingOrder());
        } elseif ($sortColumn === AccountListConstants::ORD_BY_NAME) {
            $repository->orderByName($this->isAscendingOrder());
        } elseif ($sortColumn === AccountListConstants::ORD_BY_COMPANY_NAME) {
            $repository->orderByCompanyName($this->isAscendingOrder());
        } elseif ($sortColumn === AccountListConstants::ORD_BY_ID) {
            $repository->orderById($this->isAscendingOrder());
        }
        return $this;
    }
}
