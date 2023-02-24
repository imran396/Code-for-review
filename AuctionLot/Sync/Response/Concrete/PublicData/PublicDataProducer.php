<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\PublicData;

use Sam\AuctionLot\Sync\Response\Concrete\PublicData\Generated\Message\PublicDataResponse;
use Sam\AuctionLot\Sync\Response\Concrete\PublicData\Internal\AuctionLotDataMessageFactoryCreateTrait;
use Sam\AuctionLot\Sync\Response\Concrete\PublicData\Dto\SyncAuctionLotDto;
use Sam\AuctionLot\Sync\Response\Concrete\PublicData\Internal\Load\PublicDataLoaderAwareTrait;
use Sam\AuctionLot\Sync\Response\Concrete\PublicData\Internal\Load\SystemParametersLoaderCreateTrait;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Log\Support\SupportLoggerAwareTrait;

/**
 * Provides up-to-date data, which is used to synchronize frontend catalog page
 *
 * Class PublicDataProducer
 * @package Sam\AuctionLot\Sync\Response\Concrete\PublicData
 */
class PublicDataProducer extends CustomizableClass
{
    use AuctionLotDataMessageFactoryCreateTrait;
    use PublicDataLoaderAwareTrait;
    use SupportLoggerAwareTrait;
    use SystemParametersLoaderCreateTrait;

    public const SETTINGS_DISPLAY_BIDDING_INFO = 'display_bidder_info';
    public const SETTINGS_SHOW_WINNER_IN_CATALOG = 'show_winner_in_catalog';

    protected bool $isProfilingEnabled;
    protected array $auctionAdditionalCurrenciesCache = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $isProfilingEnabled
     * @return static
     */
    public function construct(bool $isProfilingEnabled = false): static
    {
        $this->isProfilingEnabled = $isProfilingEnabled;
        $this->getPublicDataLoader()->construct($isProfilingEnabled);
        return $this;
    }

    /**
     * Fetch up-to-date sync data and put them to the protobuf message object
     *
     * @param int $systemAccountId
     * @param int|null $editorUserId
     * @param array $auctionLotIds
     * @return PublicDataResponse
     */
    public function produce(int $systemAccountId, ?int $editorUserId, array $auctionLotIds): PublicDataResponse
    {
        $absoluteTimeStart = microtime(true) * 1000;
        $absoluteTimestamp = ceil($absoluteTimeStart + (round(microtime(true) * 1000) - $absoluteTimeStart) / 2);
        $tmpTs = microtime(true);
        $auctionLotItemDataMessages = $this->produceAuctionLotDataMessages($systemAccountId, $editorUserId, $auctionLotIds);
        $response = (new PublicDataResponse())
            ->setAbsoluteTimestamp((int)$absoluteTimestamp)
            ->setAuctionLotItems($auctionLotItemDataMessages);
        if ($this->isProfilingEnabled) {
            $this->getSupportLogger()->debug('create array: ' . ((microtime(true) - $tmpTs) * 1000) . 'ms');
        }
        return $response;
    }

    protected function produceAuctionLotDataMessages(int $systemAccountId, ?int $editorUserId, array $auctionLotIds): array
    {
        if (empty($auctionLotIds)) {
            return [];
        }

        $systemParameters = $this->fetchSystemParameters($systemAccountId, $this->isProfilingEnabled);
        $displayBidderInfo = $systemParameters[self::SETTINGS_DISPLAY_BIDDING_INFO];
        $auctionLotDtos = $this->getPublicDataLoader()->loadAuctionLotDtos($editorUserId, $auctionLotIds, $displayBidderInfo);
        $auctionLotChanges = $this->getPublicDataLoader()->loadAuctionLotChanges($auctionLotIds);
        $auctionLotItemOrderNumList = $this->makeAuctionLotItemOrderNumList($auctionLotDtos);

        $isShowWinnerInCatalog = (bool)$systemParameters[self::SETTINGS_SHOW_WINNER_IN_CATALOG];
        $auctionLotDataMessageFactory = $this->createAuctionLotDataMessageFactory()
            ->construct(
                $editorUserId,
                $this->extractAuctionIds($auctionLotDtos),
                $isShowWinnerInCatalog,
                $this->isProfilingEnabled
            );

        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $auctionLotItemDataMessages = [];
        foreach ($auctionLotDtos as $auctionLotDto) {
            $auctionLotId = $auctionLotDto->auctionLotId;
            $auctionAdditionalCurrencies = $this->fetchAuctionAdditionalCurrencies($auctionLotDto->auctionId);
            if ($auctionStatusPureChecker->isTimed($auctionLotDto->auctionType)) {
                $auctionLotItemDataMessages[$auctionLotId] = $auctionLotDataMessageFactory->createForTimedAuction(
                    $auctionLotDto,
                    $auctionAdditionalCurrencies,
                    $auctionLotChanges[$auctionLotDto->auctionLotId] ?? []
                );
            } elseif ($auctionStatusPureChecker->isLive($auctionLotDto->auctionType)) {
                $auctionLotItemDataMessages[$auctionLotId] = $auctionLotDataMessageFactory->createForLiveAuction(
                    $auctionLotDto,
                    $auctionAdditionalCurrencies
                );
            } else {
                $auctionLotItemDataMessages[$auctionLotId] = $auctionLotDataMessageFactory->createForHybridAuction(
                    $auctionLotDto,
                    $auctionAdditionalCurrencies,
                    $auctionLotItemOrderNumList
                );
            }
        }
        return $auctionLotItemDataMessages;
    }

    /**
     * @param SyncAuctionLotDto[] $auctionLotDtos
     * @return array
     */
    protected function extractAuctionIds(array $auctionLotDtos): array
    {
        $auctionIds = array_map(
            static function (SyncAuctionLotDto $auctionLotDto) {
                return $auctionLotDto->auctionId;
            },
            $auctionLotDtos
        );
        $auctionIds = array_unique($auctionIds);
        return $auctionIds;
    }

    /**
     * @param SyncAuctionLotDto[] $auctionLotDtos
     * @return array
     */
    protected function makeAuctionLotItemOrderNumList(array $auctionLotDtos): array
    {
        $list = [];
        foreach ($auctionLotDtos as $auctionLotDto) {
            $list[$auctionLotDto->lotItemId] = ['order_num' => $auctionLotDto->auctionLotItemOrderNum];
        }
        return $list;
    }

    /**
     * @param int $accountId
     * @param bool $isProfilingEnabled
     * @return array
     */
    protected function fetchSystemParameters(int $accountId, bool $isProfilingEnabled): array
    {
        return $this->createSystemParametersLoader()->load($accountId, $isProfilingEnabled);
    }

    /**
     * @param int $auctionId
     * @return array
     */
    protected function fetchAuctionAdditionalCurrencies(int $auctionId): array
    {
        if (!array_key_exists($auctionId, $this->auctionAdditionalCurrenciesCache)) {
            $currencies = $this->getPublicDataLoader()->loadAuctionAdditionalCurrencies($auctionId);
            $this->auctionAdditionalCurrenciesCache[$auctionId] = $currencies;
        }
        return $this->auctionAdditionalCurrenciesCache[$auctionId];
    }
}
