<?php
/**
 * Auction Custom Field List Data Loader
 *
 * SAM-6440: Refactor auction custom field list page at admin side
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

namespace Sam\View\Admin\Form\AuctionCustomFieldListForm\Load;

use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionCustField\AuctionCustFieldReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionCustField\AuctionCustFieldReadRepositoryCreateTrait;
use Sam\View\Admin\Form\AuctionCustomFieldListForm\AuctionCustomFieldListConstants;

/**
 * Class AuctionCustomFieldListDataLoader
 */
class AuctionCustomFieldListDataLoader extends CustomizableClass
{
    use AuctionCustFieldReadRepositoryCreateTrait;
    use SortInfoAwareTrait;
    use LimitInfoAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int - return value of Auction Custom Fields Count
     */
    public function count(): int
    {
        return $this->prepareAuctionCustFieldRepository()->count();
    }

    /**
     * @return array - return values for Auction Custom Fields
     */
    public function load(): array
    {
        $repo = $this->prepareAuctionCustFieldRepository();

        switch ($this->getSortColumn()) {
            case AuctionCustomFieldListConstants::ORD_NAME:
                $repo->orderByName($this->isAscendingOrder());
                break;
            case AuctionCustomFieldListConstants::ORD_TYPE:
                $repo->orderByType($this->isAscendingOrder());
                break;
            case AuctionCustomFieldListConstants::ORD_REQUIRED:
                $repo->orderByRequired($this->isAscendingOrder());
                break;
            case AuctionCustomFieldListConstants::ORD_CLONE:
                $repo->orderByClone($this->isAscendingOrder());
                break;
            case AuctionCustomFieldListConstants::ORD_PUBLIC_LIST:
                $repo->orderByPublicList($this->isAscendingOrder());
                break;
            case AuctionCustomFieldListConstants::ORD_ADMIN_LIST:
                $repo->orderByAdminList($this->isAscendingOrder());
                break;
            case AuctionCustomFieldListConstants::ORD_PARAMETERS:
                $repo->orderByParameters($this->isAscendingOrder());
                break;
            case AuctionCustomFieldListConstants::ORD_DEFAULT:
                $repo->orderByOrder($this->isAscendingOrder());
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
     * @return AuctionCustFieldReadRepository
     */
    protected function prepareAuctionCustFieldRepository(): AuctionCustFieldReadRepository
    {
        return $this->createAuctionCustFieldReadRepository()->filterActive(true);
    }
}
