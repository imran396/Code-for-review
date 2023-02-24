<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\AdminData;

use Sam\AuctionLot\Sync\Response\Concrete\AdminData\Generated\Message\AdminDataResponse;
use Sam\Core\Service\CustomizableClass;

/**
 * Provides up-to-date data, which is used to synchronize admin lots list page
 *
 * Class AdminDataProducer
 * @package Sam\AuctionLot\Sync\Response\Concrete\AdminData
 */
class AdminDataProducer extends CustomizableClass
{
    use AuctionLotDataCollectionProducerCreateTrait;
    use AuctionTotalDataProducerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Fetch up-to-date sync data for the admin lots list page and put them to the protobuf message object
     *
     * @param array $auctionLotIds
     * @param int $auctionId
     * @param int $editorUserId
     * @param bool $isProfilingEnabled
     * @return AdminDataResponse
     */
    public function produce(
        array $auctionLotIds,
        int $auctionId,
        int $editorUserId,
        bool $isProfilingEnabled = false
    ): AdminDataResponse {
        $auctionTotals = $this->createAuctionTotalDataProducer()
            ->construct()
            ->produce($auctionId, $editorUserId, $isProfilingEnabled);
        $auctionLotDataCollection = $this->createAuctionLotDataCollectionProducer()
            ->produce($auctionLotIds, $auctionId, $isProfilingEnabled);

        $responseMessage = (new AdminDataResponse())
            ->setAuctionTotals($auctionTotals)
            ->setAuctionLotItems($auctionLotDataCollection);
        return $responseMessage;
    }
}
