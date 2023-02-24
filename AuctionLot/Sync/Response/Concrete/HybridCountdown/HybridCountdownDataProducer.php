<?php
/**
 * Hybrid countdown producer
 *
 * SAM-6388: Active countdown on admin - auction - lots
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/21/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\HybridCountdown;

use DateTime;
use Sam\Auction\Hybrid\Countdown\HybridCountdownCalculatorCreateTrait;
use Sam\AuctionLot\Sync\Response\Concrete\HybridCountdown\Dto\HybridCountdownInputDto;
use Sam\AuctionLot\Sync\Response\Concrete\HybridCountdown\Internal\Load\AdminDataLoader;
use Sam\AuctionLot\Sync\Response\Concrete\HybridCountdown\Internal\Load\DataLoaderInterface;
use Sam\AuctionLot\Sync\Response\Concrete\HybridCountdown\Internal\Load\PublicDataLoader;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;

/**
 * Class HybridCountdownDataProducer
 * @package Sam\AuctionLot\Sync\Response\Concrete\HybridCountdown
 */
class HybridCountdownDataProducer extends CustomizableClass
{
    use DateHelperAwareTrait;
    use HybridCountdownCalculatorCreateTrait;

    /**
     * @var DataLoaderInterface|null
     */
    protected ?DataLoaderInterface $hybridCountdownDataLoader = null;

    /**
     * @var int[]
     */
    protected array $runningLotOrderNums = [];  // cache found order numbers

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function constructForAdmin(): static
    {
        $this->hybridCountdownDataLoader = AdminDataLoader::new();
        return $this;
    }

    /**
     * @return static
     */
    public function constructForPublic(): static
    {
        $this->hybridCountdownDataLoader = PublicDataLoader::new();
        return $this;
    }

    /**
     * Calculate values used for countdown rendering
     *
     * @param HybridCountdownInputDto $dto
     * @param array $orderNums
     * @return array
     */
    public function produce(HybridCountdownInputDto $dto, array $orderNums): array
    {
        $data = [];

        $auctionStartClosingDate = new DateTime($dto->auctionStartClosingDate);
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isStartedOrPaused($dto->auctionStatusId)) {
            $runningLotOrderNum = $this->detectOrderNumOfRunningLotInHybridAuction(
                $dto->auctionId,
                $dto->rtbCurrentLotId,
                $orderNums
            );
            $runningLotEndDate = $dto->rtbLotEndDate ? new DateTime($dto->rtbLotEndDate) : null;
            $pauseDate = $dto->rtbPauseDate ? new DateTime($dto->rtbPauseDate) : null;
        } else {
            $runningLotOrderNum = 1;
            $runningLotEndDate = $pauseDate = null;
        }
        $countdownCalculator = $this->createHybridCountdownCalculator();
        [$secondsBefore, $secondsLeft] = $countdownCalculator->calcTimeLeft(
            $dto->orderNum,
            $runningLotOrderNum,
            $runningLotEndDate,
            $pauseDate,
            $dto->extendTime,
            $dto->lotStartGapTime,
            $auctionStartClosingDate
        );

        if ($secondsBefore > 0) {
            $data['ts'] = $countdownCalculator->calcDateTs($secondsBefore, $dto->auctionTzLocation);   // timestamp of current date in time zone of auction
        } elseif ($secondsLeft > 0) {
            $data['ts'] = $countdownCalculator->calcDateTs($secondsLeft, $dto->auctionTzLocation);
        } else {
            $data['ts'] = null;
        }
        $auctionStartClosingDate = $dto->auctionStartClosingDate ? new DateTime($dto->auctionStartClosingDate) : null;
        $data['astzc'] = $this->getDateHelper()->getTimezoneCodeByLocation($dto->auctionTzLocation, $auctionStartClosingDate);
        $data['astzn'] = $dto->auctionTzLocation;
        $data[Constants\LotDisplay::RES_SECONDS_BEFORE] = $secondsBefore;
        $data[Constants\LotDisplay::RES_SECONDS_LEFT] = $secondsLeft;
        return $data;
    }

    /**
     * @param int $auctionId
     * @param int|null $runningLotItemId - null/0 when running lot is not defined in hybrid auction yet
     * @param array $orderNums
     * @return int|null
     */
    protected function detectOrderNumOfRunningLotInHybridAuction(int $auctionId, ?int $runningLotItemId, array $orderNums): ?int
    {
        if (!isset($this->runningLotOrderNums[$auctionId])) {
            if ($runningLotItemId) {
                $orderNum = null;
                foreach ($orderNums as $id => $value) {
                    if ($id === $runningLotItemId) {
                        $orderNum = (int)$value['order_num'];
                        break;
                    }
                }

                // we may don't have record for rtb_current_lot_id among loaded rows
                if ($orderNum === null) {
                    $orderNum = $this->hybridCountdownDataLoader->load($runningLotItemId, $auctionId);
                }
            } else {
                // if current lot isn't defined consider it is the first
                $orderNum = 1;
            }
            $this->runningLotOrderNums[$auctionId] = $orderNum;
        }
        return $this->runningLotOrderNums[$auctionId];
    }

}
