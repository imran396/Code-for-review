<?php
/**
 * Saver methods for AuctionBidder manipulations
 *
 * SAM-6719: Apply WriteRepository and unit tests to auction bidder command services
 * SAM-3893: Refactor auction bidder related functionality
 * SAM-3904: Auction bidder registration class
 *
 * @author        Igors Kotlevskis
 * @since         Nov 30, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidder\AuctionBidder\Save;

use AuctionBidder;
use QMySqliDatabaseException;
use Sam\Bidder\BidderNum\Advise\BidderNumberAdviserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionBidder\AuctionBidderWriteRepositoryAwareTrait;

/**
 * Class Saver
 * @package Sam\Bidder\AuctionBidder
 */
class AuctionBidderSaver extends CustomizableClass
{
    use AuctionBidderWriteRepositoryAwareTrait;
    use BidderNumberAdviserAwareTrait;
    use ConfigRepositoryAwareTrait;

    public const OP_RETRY_COUNT = 'retryCount';

    protected int $retryCount = 0;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->retryCount = (int)($optionals[self::OP_RETRY_COUNT]
            ?? $this->cfg()->get('core->auction->bidderNumAssignmentRetries'));
        return $this;
    }

    /**
     * Save AuctionBidder record.
     * If BidderNum changed and record cannot be saved, because of BidderNum unique constraint, then assign another no.
     * @param AuctionBidder $auctionBidder
     * @param int $editorUserId
     * @return AuctionBidder $auctionBidder
     */
    public function save(AuctionBidder $auctionBidder, int $editorUserId): AuctionBidder
    {
        $auctionBidderWriteRepository = $this->getAuctionBidderWriteRepository();
        if (
            isset($auctionBidder->__Modified['BidderNum'])
            && $auctionBidder->BidderNum
            && $auctionBidder->__Modified['BidderNum'] !== $auctionBidder->BidderNum
        ) {
            $isSaved = false;
            $retryCount = $this->retryCount;
            $retry = 0;
            do {
                try {
                    $auctionBidderWriteRepository->saveWithModifier($auctionBidder, $editorUserId);
                    $isSaved = true;
                } catch (QMySqliDatabaseException $exception) {
                    if ($exception->ErrorNumber === 1062) { // Duplicate entry in mysql
                        $logData = [
                            'bidder#' => $auctionBidder->BidderNum,
                            'u' => $auctionBidder->UserId,
                            'a' => $auctionBidder->AuctionId
                        ];
                        log_debug('Duplicate bidder number for entry' . composeSuffix($logData));
                        $bidderNumber = $this->getBidderNumberAdviser()
                            ->construct()
                            ->suggest($auctionBidder->AuctionId);
                        $auctionBidder->BidderNum = $bidderNumber;
                    }
                    $retry++;
                }
            } while (!$isSaved && $retry < $retryCount);
        } else {
            $auctionBidderWriteRepository->saveWithModifier($auctionBidder, $editorUserId);
        }
        return $auctionBidder;
    }
}
