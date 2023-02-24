<?php
/**
 * Account List Data Loader
 *
 * SAM-6289: Refactor Account List page at client side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 14, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AccountListForm\Load;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\ReadRepository\Entity\Account\AccountReadRepository;
use Sam\Storage\ReadRepository\Entity\Account\AccountReadRepositoryCreateTrait;

/**
 * Class AccountListDataLoader
 */
class AccountListDataLoader extends CustomizableClass
{
    use AccountReadRepositoryCreateTrait;
    use ConfigRepositoryAwareTrait;
    use LimitInfoAwareTrait;

    protected ?int $filterSelectedStatus = null;
    protected ?string $filterSearchKey = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $status - null means that there no status selected
     * @return static
     */
    public function filterSelectedStatus(?int $status): static
    {
        $this->filterSelectedStatus = Cast::toInt($status, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFilterSelectedStatus(): ?int
    {
        return $this->filterSelectedStatus;
    }

    /**
     * @param string|null $searchKey - null means that there is no search key passed
     * @return static
     */
    public function filterSearchKey(?string $searchKey): static
    {
        $this->filterSearchKey = Cast::toString($searchKey);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterSearchKey(): ?string
    {
        return $this->filterSearchKey;
    }

    /**
     * @return int - return value of Accounts count
     */
    public function count(): int
    {
        return $this->prepareAccountRepository()->count();
    }

    /**
     * @return array - return values for Accounts
     */
    public function load(): array
    {
        $repo = $this->prepareAccountRepository();

        if ($this->getOffset()) {
            $repo->offset($this->getOffset());
        }

        if ($this->getLimit()) {
            $repo->limit($this->getLimit());
        }

        return $repo->loadRows();
    }

    /**
     * @return AccountReadRepository
     */
    protected function prepareAccountRepository(): AccountReadRepository
    {
        $accountRepository = $this->createAccountReadRepository()
            ->select(
                [
                    'DISTINCT(acc.id) AS id',
                    'acc.name',
                    'acc.company_name',
                    'acc.phone',
                    'acc.email',
                    'acc.site_url',
                    'acc.public_support_contact_name AS contact_name',
                ]
            )
            ->filterActive(true);
        if ($this->cfg()->get('core->account->pageExcludeMainAccount')) {
            $accountRepository->skipId($this->cfg()->get('core->portal->mainAccountId'));
        }
        if ($this->cfg()->get('core->account->pageHideWithoutAuction')) {
            $selectedStatus = $this->getFilterSelectedStatus();
            if ($selectedStatus) {
                $accountRepository->joinAuctionFilterAuctionStatusId($selectedStatus);
            } else {
                $accountRepository->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses);
            }
        }
        $searchKey = $this->getFilterSearchKey();
        if ($searchKey) {
            $accountRepository
                ->likeName($searchKey)
                ->likeCompanyName($searchKey);
        }
        $accountRepository->orderByName();

        return $accountRepository;
    }
}
