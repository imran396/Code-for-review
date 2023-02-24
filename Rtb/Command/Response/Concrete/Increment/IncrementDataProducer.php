<?php
/**
 * Produce rtb response data
 *
 * SAM-5201: Apply constants for response data and keys
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           6/23/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Response\Concrete\Increment;

use RtbCurrent;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Bidding\CurrentBid\HighBidDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ResponseDataProducer
 */
class IncrementDataProducer extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use HighBidDetectorCreateTrait;

    protected ?DataLoader $dataProvider = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Generate increment response data
     * @param RtbCurrent $rtbCurrent
     * @param array $optionals = [
     *  'currentBid' => float,
     * ]
     * @return array = [
     *  Constants\Rtb::RES_INCREMENT_CURRENT => float,
     *  Constants\Rtb::RES_INCREMENT_DEFAULT => float,
     *  Constants\Rtb::RES_INCREMENT_NEXT_1 => float, // only for simple console
     *  Constants\Rtb::RES_INCREMENT_NEXT_2 => float, // only for simple console
     *  Constants\Rtb::RES_INCREMENT_NEXT_3 => float, // only for simple console
     *  Constants\Rtb::RES_INCREMENT_NEXT_4 => float, // only for simple console
     *  Constants\Rtb::RES_INCREMENT_RESTORE => float, // only for simple console
     * ]
     */
    public function produceData(RtbCurrent $rtbCurrent, array $optionals = []): array
    {
        $currentBid = (float)($optionals['currentBid']
            ?? $this->createHighBidDetector()->detectAmount($rtbCurrent->LotItemId, $rtbCurrent->AuctionId));

        $auction = $this->getAuctionLoader()->load($rtbCurrent->AuctionId);
        if (!$auction) {
            log_error(
                "Available auction not found, when building increment response data"
                . composeSuffix(['a' => $rtbCurrent->AuctionId])
            );
            return [];
        }

        if ($auction->isSimpleClerking()) {
            [$currentIncrement, $restoreIncrement, $nextIncrements] = $this->getDataLoader()
                ->loadIncrementsForSimpleClerking(
                    $rtbCurrent->AuctionId,
                    $currentBid,
                    $rtbCurrent->Increment,
                    $rtbCurrent->LotItemId
                );
            $data = [
                Constants\Rtb::RES_INCREMENT_CURRENT => $currentIncrement,
                Constants\Rtb::RES_INCREMENT_NEXT_1 => $nextIncrements[1] ?? 0.,
                Constants\Rtb::RES_INCREMENT_NEXT_2 => $nextIncrements[2] ?? 0.,
                Constants\Rtb::RES_INCREMENT_NEXT_3 => $nextIncrements[3] ?? 0.,
                Constants\Rtb::RES_INCREMENT_NEXT_4 => $nextIncrements[4] ?? 0.,
                Constants\Rtb::RES_INCREMENT_RESTORE => $restoreIncrement,
            ];
        } else {
            $data = [
                Constants\Rtb::RES_INCREMENT_CURRENT => $rtbCurrent->Increment,
            ];
        }
        $data[Constants\Rtb::RES_INCREMENT_DEFAULT] = $rtbCurrent->DefaultIncrement;
        return $data;
    }

    /**
     * @return DataLoader
     */
    public function getDataLoader(): DataLoader
    {
        if ($this->dataProvider === null) {
            $this->dataProvider = DataLoader::new();
        }
        return $this->dataProvider;
    }

    /**
     * @param DataLoader $dataProvider
     * @return static
     */
    public function setDataLoader(DataLoader $dataProvider): IncrementDataProducer
    {
        $this->dataProvider = $dataProvider;
        return $this;
    }
}
