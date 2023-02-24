<?php
/**
 * Produce rtb response data. Facade class for concrete data producers
 *
 * TODO: move data producing logic in this class, it should be independent of response structure object (array of json-encoded arrays), that we process in ResponseHelper
 * SAM-4620: Bid request not showing on clerk when clerk action collides with incoming bid request
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

namespace Sam\Rtb\Command\Response;

use Sam\Core\Service\CustomizableClass;
use RtbCurrent;
use Sam\Core\Constants;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Rtb\Command\Response\Concrete\BidDataProducer;
use Sam\Rtb\Command\Response\Concrete\BidderAddressDataProducer;
use Sam\Rtb\Command\Response\Concrete\BuyerGroupDataProducer;
use Sam\Rtb\Command\Response\Concrete\AdminSideDataProducer;
use Sam\Rtb\Command\Response\Concrete\GameStatusDataProducer;
use Sam\Rtb\Command\Response\Concrete\GroupDataProducer;
use Sam\Rtb\Command\Response\Concrete\Increment\IncrementDataProducer;
use Sam\Rtb\Command\Response\Concrete\LotChangesDataProducer;
use Sam\Rtb\Command\Response\Concrete\LotGeneralDataProducer;
use Sam\Rtb\Command\Response\Concrete\LotPositionDataProducer;
use Sam\Rtb\Command\Response\Concrete\NoteDataProducer;
use Sam\Rtb\Command\Response\Concrete\ParcelDataProducer;
use Sam\Rtb\Command\Response\Concrete\UndoButtonDataProducer;

/**
 * Class ResponseDataProducer
 * @package Sam\Rtb\Command\Response
 */
class ResponseDataProducer extends CustomizableClass
{
    use TranslatorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Produce meta data array, that describes response describing properties
     * @return array = [
     *  Constants\Rtb::RES_RESPONSE_MICRO_TS => float
     * ]
     */
    public function produceMetaData(): array
    {
        $metaData = [
            Constants\Rtb::RES_RESPONSE_MICRO_TS => $this->produceResponseMicroTs(),
        ];
        return $metaData;
    }

    /**
     * Return response finalization timestamp with microseconds
     * @return float
     */
    public function produceResponseMicroTs(): float
    {
        return microtime(true);
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @return array = [
     *  Constants\Rtb::RES_BUYER_GROUP_IDS => string, // TODO: int[]
     * ]
     */
    public function produceBuyerGroupData(RtbCurrent $rtbCurrent): array
    {
        return BuyerGroupDataProducer::new()->produceData($rtbCurrent);
    }

    /**
     * Return lot grouping response data with considering parcel id feature
     * @param RtbCurrent $rtbCurrent
     * @return array
     */
    public function produceGroupingData(RtbCurrent $rtbCurrent): array
    {
        $data = [];
        if ($rtbCurrent->LotGroup) {
            $data = GroupDataProducer::new()->produceData($rtbCurrent);
        } else {
            $parcelDataProducer = ParcelDataProducer::new()
                ->setRtbCurrent($rtbCurrent);
            if ($parcelDataProducer->isParcelAvailable()) {
                $data = $parcelDataProducer->produceData();
            }
        }
        return $data;
    }

    /**
     * Return increment response data
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
    public function produceIncrementData(RtbCurrent $rtbCurrent, array $optionals = []): array
    {
        return IncrementDataProducer::new()->produceData($rtbCurrent, $optionals);
    }

    /**
     * Return the position of an lot in an auction
     * @param RtbCurrent $rtbCurrent
     * @return array = [
     *  Constants\Rtb::RES_LOT_POSITION => int,
     * ]
     */
    public function produceLotPositionData(RtbCurrent $rtbCurrent): array
    {
        return LotPositionDataProducer::new()->produceData($rtbCurrent);
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @return array = [
     *  Constants\Rtb::RES_CLERK_NOTE => string,
     *  Constants\Rtb::RES_GENERAL_NOTE => string,
     * ]
     */
    public function produceNoteData(RtbCurrent $rtbCurrent): array
    {
        return NoteDataProducer::new()->produceData($rtbCurrent);
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @return array = [
     *  Constants\Rtb::RES_STATUS => string
     * ]
     */
    public function produceGameStatusData(RtbCurrent $rtbCurrent): array
    {
        return GameStatusDataProducer::new()->produceData($rtbCurrent);
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @return array = [
     *  Constants\Rtb::RES_UNDO_BUTTON_DATA => [
     *   'Cnt' => int,
     *   'Cmd' => string
     *  ]
     * ]
     */
    public function produceUndoButtonData(RtbCurrent $rtbCurrent): array
    {
        return UndoButtonDataProducer::new()->produceData($rtbCurrent);
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @return array = [
     *  Constants\Rtb::RES_GROUP_ID => ?int,
     *  Constants\Rtb::RES_HIGH_ESTIMATE => ?float,
     *  Constants\Rtb::RES_IS_INTERNET_BID => bool,
     *  Constants\Rtb::RES_IS_QUANTITY_X_MONEY => bool,
     *  Constants\Rtb::RES_LISTING_ONLY => bool,
     *  Constants\Rtb::RES_LOT_ITEM_ID => int,
     *  Constants\Rtb::RES_LOT_NAME => string,
     *  Constants\Rtb::RES_LOT_NO => string,
     *  Constants\Rtb::RES_LOW_ESTIMATE => ?float,
     *  Constants\Rtb::RES_QUANTITY => ?int,
     *  Constants\Rtb::RES_RESERVE_PRICE => ?float,
     *  Constants\Rtb::RES_SEO_URL => string,
     * ]
     */
    public function produceLotGeneralData(RtbCurrent $rtbCurrent): array
    {
        return LotGeneralDataProducer::new()->produceData($rtbCurrent);
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @return array = [
     *  Constants\Rtb::RES_LOT_CHANGES => string,
     * ]
     */
    public function produceLotChangesData(RtbCurrent $rtbCurrent): array
    {
        return LotChangesDataProducer::new()->produceData($rtbCurrent);
    }

    /**
     * Produce bid amount values
     * @param RtbCurrent $rtbCurrent
     * @param array $optionals = [
     *  'askingBid' => float,
     *  'currentBid' => float,
     *  'startingBid' => float,
     * ]
     * @return array = [
     *  Constants\Rtb::RES_ASKING_BID => float,
     *  Constants\Rtb::RES_CURRENT_BID => float,
     *  Constants\Rtb::RES_STARTING_BID => float
     * ]
     */
    public function produceBidData(RtbCurrent $rtbCurrent, array $optionals = []): array
    {
        return BidDataProducer::new()->produceData($rtbCurrent, $optionals);
    }

    /**
     * Produce values specialized for clerk and auctioneer consoles, i.e. admin side consoles
     * @param RtbCurrent $rtbCurrent
     * @return array = [
     *  Constants\Rtb::RES_ABSENTEE_BID_INFO => array,
     *  Constants\Rtb::RES_CLERK_NOTE => string,
     *  Constants\Rtb::RES_ENTER_FLOOR_NO => string,
     *  Constants\Rtb::RES_GENERAL_NOTE => string,
     *  Constants\Rtb::RES_IMAGE_BIG_PATHS => array,
     *  Constants\Rtb::RES_IMAGE_PATHS => array,
     *  Constants\Rtb::RES_IMAGE_PRELOAD_PATHS => array,
     *  Constants\Rtb::RES_NEXT_LOT_ITEM_ID => integer
     *  Constants\Rtb::RES_STARTING_BID => float,
     * ]
     */
    public function produceAdminSideData(RtbCurrent $rtbCurrent): array
    {
        return AdminSideDataProducer::new()->produceData($rtbCurrent);
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @param int $userType
     * @param array $optionals = [
     *  'highBidUserId' => int,
     * ]
     * @return array = [
     *  Constants\Rtb::RES_CURRENT_BIDDER_ADDRESS => string,
     * ]
     */
    public function produceBidderAddressData(RtbCurrent $rtbCurrent, int $userType, array $optionals = []): array
    {
        return BidderAddressDataProducer::new()->produceData($rtbCurrent, $userType, $optionals);
    }
}
