<?php
/**
 * Calculate and update bidder outstanding limit values (spent, collected)
 *
 * SAM-2710: Bidonfusion - Bidder spending reports and thresholds
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Mar 27, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\Outstanding;

use AuctionBidder;
use Sam\Bidder\Outstanding\Storage\DataManager;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\OutputBuffer\OutputBufferCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionBidder\AuctionBidderWriteRepositoryAwareTrait;

/**
 * Class Calculator
 * @package Sam\Bidder\Outstanding
 */
class Calculator extends CustomizableClass
{
    use AuctionBidderReadRepositoryCreateTrait;
    use AuctionBidderWriteRepositoryAwareTrait;
    use OutputBufferCreateTrait;

    /**
     * @var DataManager|null
     */
    protected ?DataManager $dataManager = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Init instance with defaults, inject dependencies
     * @return static
     */
    public function initInstance(): static
    {
        $this->setDataManager(DataManager::new());
        return $this;
    }

    /**
     * Setter for main data manager specific for this module
     * @param DataManager $dataManager
     * @return static
     */
    public function setDataManager(DataManager $dataManager): static
    {
        $this->dataManager = $dataManager;
        return $this;
    }

    /**
     * Calculate and update "Spent" value for bidder
     * @param AuctionBidder $auctionBidder
     * @param int $editorUserId
     */
    public function refreshSpent(AuctionBidder $auctionBidder, int $editorUserId): void
    {
        $auctionBidder->Spent = $this->dataManager
            ->calcSpent($auctionBidder->AuctionId, $auctionBidder->UserId);
        $this->getAuctionBidderWriteRepository()->saveWithModifier($auctionBidder, $editorUserId);
    }

    /**
     * Calculate and update "Collected" value for bidder
     * @param AuctionBidder $auctionBidder
     * @param int $editorUserId
     */
    public function refreshCollected(AuctionBidder $auctionBidder, int $editorUserId): void
    {
        $auctionBidder->Collected = $this->dataManager
            ->calcCollected($auctionBidder->AuctionId, $auctionBidder->UserId);
        $this->getAuctionBidderWriteRepository()->saveWithModifier($auctionBidder, $editorUserId);
    }

    /**
     * Update "Spent" and "Collected" values for bidders in auction
     * @param int $auctionId
     * @param int $editorUserId
     * @param bool $isEcho
     */
    public function refreshForAuction(int $auctionId, int $editorUserId, bool $isEcho = false): void
    {
        if ($isEcho) {
            $this->createOutputBuffer()->endFlush();
        }
        if ($isEcho) {
            echo 'Refreshing bidders of auction id: ' . $auctionId . "\n";
        }
        $auctionBidders = $this->createAuctionBidderReadRepository()
            ->filterAuctionId($auctionId)
            ->loadEntities();
        foreach ($auctionBidders as $auctionBidder) {
            $this->refreshSpent($auctionBidder, $editorUserId);
            $this->refreshCollected($auctionBidder, $editorUserId);
        }
    }

    /**
     * Update "Spent" and "Collected" values for auction bidders of definite account
     * @param int $accountId
     * @param int $editorUserId
     * @param bool $isEcho
     */
    public function refreshForAccount(int $accountId, int $editorUserId, bool $isEcho = false): void
    {
        if ($isEcho) {
            $this->createOutputBuffer()->endFlush();
        }
        $auctionBidderRepository = $this->createAuctionBidderReadRepository()
            ->joinAuctionFilterAccountId($accountId)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses);
        $total = $auctionBidderRepository->count();
        if ($total) {
            if ($isEcho) {
                echo 'Refreshing auction bidders (' . $total . ') for account' . composeSuffix(['id' => $accountId]);
            }
            $offset = 0;
            $limit = 100;
            while ($offset < $total) {
                $auctionBidders = $auctionBidderRepository
                    ->offset($offset)
                    ->limit($limit)
                    ->loadEntities();
                foreach ($auctionBidders as $auctionBidder) {
                    $this->refreshSpent($auctionBidder, $editorUserId);
                    $this->refreshCollected($auctionBidder, $editorUserId);
                }
                $count = $offset + count($auctionBidders);
                if ($isEcho) {
                    echo $count . ($count < $total ? ", " : ".\n");
                }
                $offset += $limit;
            }
        }
    }
}
