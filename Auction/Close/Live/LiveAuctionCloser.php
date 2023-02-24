<?php
/**
 * It is live auction closer class. It closes active/started/paused auctions which end date is less than current date.
 *
 * SAM-7685 : Implement house bidder feature execution on scheduled sale closing
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Close\Live;

use Auction;
use Exception;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;

/**
 * Class LiveAuctionCloser
 * @package Sam\Auction\Close\Live
 */
class LiveAuctionCloser extends CustomizableClass
{
    use AuctionReadRepositoryCreateTrait;
    use AuctionWriteRepositoryAwareTrait;
    use CurrentDateTrait;
    use DbConnectionTrait;
    use LiveLotCloserCreateTrait;

    /**
     * @var array
     */
    protected array $closedAuctions = [];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Main Method
     * @param int $editorUserId
     * @return void
     */
    public function close(int $editorUserId): void
    {
        try {
            // Fetch ready for closing live auctions
            $auctions = $this->createAuctionReadRepository()
                ->filterAuctionType(Constants\Auction::LIVE)
                ->filterAuctionStatusId(Constants\Auction::$openAuctionStatuses)
                ->filterEndDateLess($this->getCurrentDateUtcIso())
                ->loadEntities();

            $this->logReadyForClosingAuctionsInfo($auctions);
            $this->getDb()->transactionBegin();
            $this->closeAuctions($auctions, $editorUserId);
            $this->getDb()->transactionCommit();
            $this->logClosedAuctionsInfo();
        } catch (Exception $e) {
            log_error('Failed closing live auctions: ' . $e->getMessage());
        }
    }

    /**
     * Close expired auctions
     * @param array $auctions
     * @param int $editorUserId
     * @return void
     */
    protected function closeAuctions(array $auctions, int $editorUserId): void
    {
        // Close live auctions
        foreach ($auctions as $auction) {
            try {
                $auction->toClosed();
                $this->getAuctionWriteRepository()->saveWithModifier($auction, $editorUserId);
                $this->closedAuctions[] = $auction;
                $this->createLiveLotCloser()->close($auction->Id, $editorUserId);
            } catch (Exception $e) {
                log_warning(
                    'Failed closing auction'
                    . composeSuffix(['a' => $auction->Id, 'error' => $e->getMessage()])
                );
            }
        }
    }

    /**
     * @return array
     * @internal for unit testing
     */
    public function getClosedAuctions(): array
    {
        return $this->closedAuctions;
    }

    /**
     * @param array $auctions
     * @return void
     */
    protected function logReadyForClosingAuctionsInfo(array $auctions): void
    {
        // Log prepared for closing live auctions
        $auctionIds = $this->getAuctionIds($auctions);
        log_info(
            'Live auctions ready for closing'
            . composeSuffix(['count' => count($auctionIds), 'a' => $auctionIds,])
        );
    }

    /**
     * Log closed auctions Id
     * @return void
     */
    protected function logClosedAuctionsInfo(): void
    {
        $auctionIds = $this->getAuctionIds($this->closedAuctions);
        log_info(
            'Live auctions successfully closed'
            . composeSuffix(['count' => count($this->closedAuctions), 'a' => $auctionIds])
        );
    }

    /**
     * @param array $auctions
     * @return array
     */
    protected function getAuctionIds(array $auctions): array
    {
        return array_map(
            static function (Auction $auction) {
                return $auction->Id;
            },
            $auctions
        );
    }
}
