<?php
/**
 * Feed List Data Loader
 *
 * SAM-5885: Refactor feed list management at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 6, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\FeedListForm\Load;

use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\Feed\FeedReadRepository;
use Sam\Storage\ReadRepository\Entity\Feed\FeedReadRepositoryCreateTrait;
use Sam\View\Admin\Form\FeedListForm\FeedListConstants;

/**
 * Class FeedListDataLoader
 */
class FeedListDataLoader extends CustomizableClass
{
    use FeedReadRepositoryCreateTrait;
    use FilterAccountAwareTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * Return count of feeds
     * @return int
     */
    public function count(): int
    {
        return $this->prepareFeedRepository()->count();
    }

    /**
     * Return array of feeds
     * @return array
     */
    public function load(): array
    {
        $feedRepository = $this->prepareFeedRepository();

        $isAscending = $this->isAscendingOrder();
        $sortColumn = $this->getSortColumn();
        if ($sortColumn === FeedListConstants::ORD_BY_DEFAULT) {
            $feedRepository->orderById($isAscending);
        } elseif ($sortColumn === FeedListConstants::ORD_BY_NAME) {
            $feedRepository->orderByName($isAscending);
        } elseif ($sortColumn === FeedListConstants::ORD_BY_SLUG) {
            $feedRepository->orderBySlug($isAscending);
        } elseif ($sortColumn === FeedListConstants::ORD_BY_FEED_TYPE) {
            $feedRepository->orderByFeedType($isAscending);
        } elseif ($sortColumn === FeedListConstants::ORD_BY_ESCAPING) {
            $feedRepository->orderByEscaping($isAscending);
        } elseif ($sortColumn === FeedListConstants::ORD_BY_ACCOUNT) {
            $feedRepository->joinAccountOrderByName($isAscending);
        }

        $offset = $this->getOffset();
        $limit = $this->getLimit();
        if ($limit) {
            $feedRepository->limit($limit);
        }
        if ($offset) {
            $feedRepository->offset($offset);
        }

        $dtos = [];
        foreach ($feedRepository->loadRows() as $row) {
            $dtos[] = FeedListDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * @return FeedReadRepository
     */
    private function prepareFeedRepository(): FeedReadRepository
    {
        $feedRepository = $this->createFeedReadRepository()
            ->joinAccount()
            ->filterActive(true)
            ->select(
                [
                    'f.`escaping` as escaping',
                    'f.`feed_type` as feed_type',
                    'f.`id` as id',
                    'f.`name` as name',
                    'f.`slug` as slug',
                    'acc.`name` as account_name'
                ]
            );

        if ($this->getFilterAccountId()) {
            $feedRepository->filterAccountId($this->getFilterAccountId());
        }

        return $feedRepository;
    }
}
