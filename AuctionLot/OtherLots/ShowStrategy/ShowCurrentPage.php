<?php

/**
 * A strategy how we will select "Other lots".
 * It select x lot before and y lot after the current lot
 *
 * SAM-3506: Other lots on responsive lot detail page needs to be refactored
 * SAM-3241: Refactor Live and Timed Lot Details pages of responsive side
 * @see https://bidpath.atlassian.net/browse/SAM-3241 Refactor Live and Timed Lot Details pages of responsive side
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 23, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\OtherLots\ShowStrategy;

use AuctionLotItem;
use Sam\Core\Constants\Responsive\OtherLots\ActionConstants;
use Sam\AuctionLot\OtherLots\Storage\DataManagerInterface;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ShowCurrentPage
 */
class ShowCurrentPage extends CustomizableClass implements ShowStrategyInterface
{
    /**
     * @var int
     */
    private int $auctionId;
    /**
     * @var int
     */
    private int $amount;
    /**
     * @var DataManagerInterface
     */
    private DataManagerInterface $dataManager;
    /**
     * Amount of all lots
     * @var int|null
     */
    private ?int $countAllLots = null;
    /**
     * Id of the current lot on the detailed page
     * @var int
     */
    private int $currentLotId;
    /**
     * Get array of the auction lots by order
     * @var array|null
     */
    private ?array $auctionLotsOrdered = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * ShowLotsBeforeAndAfterCurrentLot constructor.
     * @param DataManagerInterface $dataManager
     * @param int $auctionId
     * @param int $amountOfLotToShow
     * @param int|null $currentLotId
     * @return static
     */
    public function construct(
        DataManagerInterface $dataManager,
        int $auctionId,
        int $amountOfLotToShow,
        ?int $currentLotId
    ): static {
        $this->dataManager = $dataManager;
        $this->auctionId = $auctionId;
        $this->amount = $amountOfLotToShow > 0 ? $amountOfLotToShow : 1;
        $this->currentLotId = (int)$currentLotId;
        return $this;
    }

    /**
     * @param int $action
     * @param int $currentPage
     * @return AuctionLotItem[]
     */
    public function getAuctionLots(int $action, int $currentPage): iterable
    {
        $offset = $this->getOffset($action, $this->amount, $currentPage);
        if ($offset > 0) {
            $offset--;
        }
        $auctionLotsIdsToShow = [];
        $isCurrentLotExcluded = false;
        for ($i = $offset; $i < $this->countAllAuctionLots(); $i++) {
            if (count($auctionLotsIdsToShow) === $this->amount + 1) {
                break;
            }
            if ((int)$this->auctionLotsOrdered[$i]['lot_item_id'] !== $this->currentLotId) {
                $auctionLotsIdsToShow[] = Cast::toInt($this->auctionLotsOrdered[$i]['id'], Constants\Type::F_INT_POSITIVE);
            } else {
                $isCurrentLotExcluded = true;
            }
        }
        if (count($auctionLotsIdsToShow) > $this->amount) {
            if (
                !$isCurrentLotExcluded
                && $offset > 0
            ) {
                array_shift($auctionLotsIdsToShow);
            } else {
                array_pop($auctionLotsIdsToShow);
            }
        }
        $auctionLots = $this->dataManager->getAuctionLotsByIds($auctionLotsIdsToShow);
        $auctionLotsOrdered = ArrayHelper::orderEntities($auctionLots, 'Id', $auctionLotsIdsToShow);
        return $auctionLotsOrdered;
    }

    /**
     * @param bool $refresh
     * @return int
     */
    public function countAllAuctionLots(bool $refresh = false): int
    {
        if (
            $this->countAllLots === null
            || $refresh
        ) {
            $this->countAllLots = count($this->getAuctionLotsOrdered($refresh));
        }
        return $this->countAllLots;
    }

    /**
     * @return int
     */
    public function getLastPage(): int
    {
        $lastPage = floor($this->countAllAuctionLots() / $this->amount);
        if ($this->countAllAuctionLots() % $this->amount === 0) {
            $lastPage--;
        }
        return (int)$lastPage;
    }

    /**
     * @param int $action
     * @param int $currentPage
     * @return int
     */
    public function getNextPageFromAction(int $action, int $currentPage): int
    {
        if (!in_array($action, ActionConstants::$all, true)) {
            return 0;
        }
        $lastPage = $this->getLastPage();
        $currentPage = (int)(string)$currentPage;
        if ($currentPage < 0) {
            return $action === ActionConstants::LAST ? $lastPage : 0;
        }
        $nextPage = 0;
        if ($action === ActionConstants::FIRST) {
            $nextPage = 0;
        } elseif ($action === ActionConstants::PREV) {
            $nextPage = $currentPage - 1;
        } elseif ($action === ActionConstants::NEXT) {
            $nextPage = $currentPage + 1;
            $nextPage = $nextPage > $lastPage ? $lastPage : $nextPage;
        } elseif ($action === ActionConstants::LAST) {
            $nextPage = $lastPage;
        } elseif ($action === ActionConstants::CURRENT) {
            $nextPage = $currentPage <= 0 ? $this->calculateCurrentPageForCurrentLot() : $currentPage;
        }
        $nextPage = $nextPage < 0 ? 0 : $nextPage;
        return $nextPage;
    }

    /**
     * @param int $action
     * @param int $amount
     * @param int $currentPage
     * @return int
     */
    public function getOffset(int $action, int $amount, int $currentPage): int
    {
        $countAllLots = $this->countAllAuctionLots();
        $offset = 0;
        if ($countAllLots > $amount) {
            if ($currentPage === 0 && $action === ActionConstants::CURRENT) {
                $offset = $this->calculateOffsetForCurrentLot($amount, $countAllLots);
            } else {
                $nextPage = $this->getNextPageFromAction($action, $currentPage);
                $offset = $nextPage * $amount;
                if ($nextPage === $this->getLastPage()) {
                    $offset = $countAllLots - $amount;
                }
            }
        }
        return $offset < 0 ? 0 : $offset;
    }

    /**
     * @param int $amount
     * @param int $countAllLots
     * @return int
     */
    protected function calculateOffsetForCurrentLot(int $amount, int $countAllLots): int
    {
        $auctionLotsOrdered = $this->getAuctionLotsOrdered();
        $before = (int)round($amount / 2);
        $currentLotItemOrder = 0;
        foreach ($auctionLotsOrdered as $item) {
            if ((int)$item['lot_item_id'] === $this->currentLotId) {
                $currentLotItemOrder = $item['order'];
            }
        }
        if ($currentLotItemOrder > ($countAllLots - $before)) {
            $offset = $countAllLots - $amount;
        } else {
            $offset = $currentLotItemOrder - $before;
        }
        return $offset < 0 ? 0 : $offset;
    }

    /**
     * @return int
     */
    protected function calculateCurrentPageForCurrentLot(): int
    {
        $auctionLotsOrdered = $this->getAuctionLotsOrdered();
        $currentLotItemOrder = 0;
        foreach ($auctionLotsOrdered as $item) {
            if ((int)$item['lot_item_id'] === $this->currentLotId) {
                $currentLotItemOrder = $item['order'];
            }
        }
        $currentPage = (int)ceil($currentLotItemOrder / $this->amount) - 1;
        return $currentPage;
    }

    /**
     * @param bool $refresh
     * @return AuctionLotItem[]
     */
    protected function getAuctionLotsOrdered(bool $refresh = false): iterable
    {
        if (
            $this->auctionLotsOrdered === null
            || $refresh
        ) {
            $this->auctionLotsOrdered = $this->dataManager->getAllOrderedAuctionLotIds($this->auctionId);
        }
        return $this->auctionLotsOrdered;
    }
}
