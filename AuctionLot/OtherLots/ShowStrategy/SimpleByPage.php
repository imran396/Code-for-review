<?php

/**
 * A strategy how we will select "Other lots".
 * Here we just select all lots from the beginning by page.
 *
 * @see https://bidpath.atlassian.net/browse/SAM-3241 Refactor Live and Timed Lot Details pages of responsive side
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 31, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\OtherLots\ShowStrategy;

use AuctionLotItem;
use Sam\AuctionLot\OtherLots\Storage\DataManagerInterface;
use Sam\Core\Constants\Responsive\OtherLots\ActionConstants;

/**
 * Class SimpleByPage
 */
class SimpleByPage implements ShowStrategyInterface
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
     * SimpleByPage constructor.
     * @param DataManagerInterface $dataManager
     * @param int $auctionId
     * @param int $amountOfLotToShow
     */
    public function __construct(DataManagerInterface $dataManager, int $auctionId, int $amountOfLotToShow)
    {
        $this->dataManager = $dataManager;
        $this->auctionId = $auctionId;
        $this->amount = $amountOfLotToShow > 0 ? $amountOfLotToShow : 1;
    }

    /**
     * @param int $action
     * @param int $currentPage
     * @return AuctionLotItem[]
     */
    public function getAuctionLots(int $action, int $currentPage): iterable
    {
        $offset = $this->getOffset($action, $this->amount, $currentPage);
        $auctionLots = $this->dataManager->getAuctionLots($this->auctionId, $this->amount, $offset);
        return $auctionLots;
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
            $this->countAllLots = $this->dataManager->countAllAuctionLots($this->auctionId);
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

        switch ($action) {
            case ActionConstants::PREV:
                $nextPage = $currentPage - 1;
                break;
            case ActionConstants::NEXT:
                $nextPage = $currentPage + 1;
                $nextPage = $nextPage > $lastPage ? $lastPage : $nextPage;
                break;
            case ActionConstants::LAST:
                $nextPage = $lastPage;
                break;
            case ActionConstants::CURRENT:
                $nextPage = $currentPage;
                break;
            case ActionConstants::FIRST:
            default:
                $nextPage = 0;
                break;
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
            $nextPage = $this->getNextPageFromAction($action, $currentPage);
            $offset = $nextPage * $amount;
            if ($nextPage === $this->getLastPage()) {
                $offset = $countAllLots - $amount;
            }
        }
        return $offset;
    }
}
