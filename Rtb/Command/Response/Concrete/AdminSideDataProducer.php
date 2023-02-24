<?php
/**
 * SAM-6459: Rtbd response - lot data producers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 02, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Response\Concrete;

use RtbCurrent;
use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\PositionalAuctionLotLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Bidding\AbsenteeBid\Detect\HighAbsenteeBidDetectorCreateTrait;
use Sam\Bidding\StartingBid\FlexibleStartingBidCalculatorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\JsonArray;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Image\ImageHelper;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class ClerkConsoleDataProducer
 * @package Sam\Rtb\Command\Response\Concrete
 */
class AdminSideDataProducer extends CustomizableClass
{
    use AuctionBidderLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use BidderNumPaddingAwareTrait;
    use ConfigRepositoryAwareTrait;
    use FlexibleStartingBidCalculatorCreateTrait;
    use HighAbsenteeBidDetectorCreateTrait;
    use LotImageLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use PositionalAuctionLotLoaderAwareTrait;
    use ResponseDataProducerAwareTrait;
    use UrlBuilderAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * SAM-1456: Clerking and Assistant console lot-item/info
     * Return additional lot item info needed for clerking
     *
     * @param RtbCurrent $rtbCurrent
     * @return array [
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
    public function produceData(RtbCurrent $rtbCurrent): array
    {
        $auction = $this->getAuctionLoader()->load($rtbCurrent->AuctionId, true);
        if (!$auction) {
            log_error(
                "Available auction not found, when building rtb data for clerking console"
                . composeSuffix(['a' => $rtbCurrent->AuctionId])
            );
            return [];
        }

        if (!$rtbCurrent->LotItemId) {
            return [
                Constants\Rtb::RES_ABSENTEE_BID_INFO => [],
                Constants\Rtb::RES_CLERK_NOTE => '',
                Constants\Rtb::RES_ENTER_FLOOR_NO => '',
                Constants\Rtb::RES_GENERAL_NOTE => '',
                Constants\Rtb::RES_IMAGE_BIG_PATHS => '',
                Constants\Rtb::RES_IMAGE_PATHS => '',
                Constants\Rtb::RES_IMAGE_PRELOAD_PATHS => '',
                Constants\Rtb::RES_NEXT_LOT_ITEM_ID => null,
                Constants\Rtb::RES_STARTING_BID => null,
            ];
        }

        $imagePaths = [];
        $imageBigPaths = [];

        $lotImage = $this->getLotImageLoader()->loadDefaultForLot($rtbCurrent->LotItemId, true);
        if ($lotImage) {
            $lotItem = $this->getLotItemLoader()->load($rtbCurrent->LotItemId, true);
            if ($lotItem) {
                $accountId = $lotItem->AccountId;
                $imagePaths[] = $this->getUrlBuilder()->build(
                    LotImageUrlConfig::new()->construct($lotImage->Id, Constants\Image::MEDIUM, $accountId)
                );
                $imageBigPaths[] = $this->getUrlBuilder()->build(
                    LotImageUrlConfig::new()->construct($lotImage->Id, Constants\Image::LARGE, $accountId)
                );
            }
        }

        $preloadImagePaths = [];

        $nextLotItem = $this->getPositionalAuctionLotLoader()
            ->loadNextLot($rtbCurrent->AuctionId, $rtbCurrent->LotItemId);
        $nextLotItemId = null;
        if ($nextLotItem) {
            $nextLotItemId = $nextLotItem->LotItemId;
            $lotImage = $this->getLotImageLoader()->loadDefaultForLot($nextLotItem->LotItemId, true);
            $clerkingClientSize = ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->clerkingClient'));
            if ($lotImage) {
                $preloadImagePaths[] = $this->getUrlBuilder()->build(
                    LotImageUrlConfig::new()->construct($lotImage->Id, $clerkingClientSize, $nextLotItem->AccountId)
                );
            }
        }

        $absenteeInfo = [];
        $highAbsenteeBidDetector = $this->createHighAbsenteeBidDetector();
        $absenteeBids = $highAbsenteeBidDetector
            ->detectTwoHighestByCurrentBid($rtbCurrent->LotItemId, $rtbCurrent->AuctionId, null, true);

        foreach ($absenteeBids as $absenteeBid) {
            if ($absenteeBid) {
                $user = $this->getUserLoader()->load($absenteeBid->UserId);
                if (!$user) {
                    log_error(
                        "Available user not found, when building rtb data for clerking console"
                        . composeSuffix(['u' => $absenteeBid->UserId, 'a' => $rtbCurrent->AuctionId])
                    );
                    continue;
                }
                $auctionBidder = $this->getAuctionBidderLoader()->load($absenteeBid->UserId, $rtbCurrent->AuctionId, true);
                if (!$auctionBidder) {
                    log_error(
                        "Available auction bidder not found, when building rtb data for clerking console"
                        . composeSuffix(['u' => $absenteeBid->UserId, 'a' => $rtbCurrent->AuctionId])
                    );
                    continue;
                }

                $absenteeMaxBid = $absenteeBid->MaxBid;

                $bidderNumPad = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
                if ($auctionBidder->BidBudget > 0) {
                    $absenteeMaxBid = $absenteeBid->GetVirtualAttribute('rem_max_bid');
                }

                $absenteeBidInteger = (int)$absenteeMaxBid;
                $decimal = $absenteeMaxBid - $absenteeBidInteger;
                if (Floating::gt($decimal, 0)) {
                    $absenteeBidFormatted = number_format($absenteeMaxBid, 2, '.', '');
                } // w/ decimals
                else {
                    $absenteeBidFormatted = number_format($absenteeMaxBid, 0, '', '');
                } // w/o decimals

                $outstandingLeft = $highAbsenteeBidDetector->calcOutstandingLeft($absenteeBid);
                $outstandingLeftFormatted = null;
                if (
                    $outstandingLeft !== null
                    && $outstandingLeft < $absenteeMaxBid
                ) {
                    $outstandingLeftFormatted = number_format($outstandingLeft, 0, '', '');
                }

                $absenteeInfo[] = [
                    $absenteeBidFormatted,
                    $absenteeBid->UserId,
                    $user->Username . ' ' . $bidderNumPad,
                    $outstandingLeftFormatted
                ];
            }
        }

        $suggestedBid = null;
        if (
            $absenteeInfo
            && $auction->SuggestedStartingBid
        ) {
            $flexibleStartingBid = $this->createFlexibleStartingBidCalculator()
                ->setAuction($auction)
                ->setLotItemId($rtbCurrent->LotItemId)
                ->calculate();
            $suggestedBid = $flexibleStartingBid;
            $lotItem = $this->getLotItemLoader()->load($rtbCurrent->LotItemId);
            if (!$lotItem) {
                log_error(
                    "Available lot item not found, when building rtb data for clerking console"
                    . composeSuffix(['li' => $rtbCurrent->LotItemId, 'a' => $rtbCurrent->AuctionId])
                );
                return [];
            }
            if (Floating::gt($lotItem->StartingBid, $flexibleStartingBid)) {
                $suggestedBid = $lotItem->StartingBid;
            }
        }

        $enterFloorNoOptions = new JsonArray($rtbCurrent->EnterFloorNo);

        $data = [
            Constants\Rtb::RES_ABSENTEE_BID_INFO => $absenteeInfo,
            Constants\Rtb::RES_ENTER_FLOOR_NO => $enterFloorNoOptions->getArray(),
            Constants\Rtb::RES_IMAGE_BIG_PATHS => $imageBigPaths,
            Constants\Rtb::RES_IMAGE_PATHS => $imagePaths,
            Constants\Rtb::RES_IMAGE_PRELOAD_PATHS => $preloadImagePaths,
            Constants\Rtb::RES_NEXT_LOT_ITEM_ID => $nextLotItemId,
            Constants\Rtb::RES_STARTING_BID => $suggestedBid,
        ];

        $responseDataProducer = $this->getResponseDataProducer();
        $data = array_merge(
            $data,
            $responseDataProducer->produceNoteData($rtbCurrent)
        );
        return $data;
    }
}
