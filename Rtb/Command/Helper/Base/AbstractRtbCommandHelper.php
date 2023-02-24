<?php
/**
 * SAM-10452: Decouple HelpersAwareTrait to rtb modules for v3-7
 */

namespace Sam\Rtb\Command\Helper\Base;

use Auction;
use AuctionLotItem;
use LotItem;
use RtbCurrent;
use Sam\AuctionLot\Date\AuctionLotDateAssignorCreateTrait;
use Sam\AuctionLot\Date\Dto\LiveHybridAuctionLotDates;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Load\PositionalAuctionLotLoaderAwareTrait;
use Sam\Core\Auction\Render\AuctionPureRenderer;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Date\CurrentDateTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Rtb\Base\IHelpersAware;
use Sam\Rtb\Command\Concrete\Base\CommandBase;
use Sam\Rtb\Command\Concrete\Ping\PingHandler;
use Sam\Rtb\Command\Concrete\ReversePing\ReversePingHandler;
use Sam\Rtb\Command\Concrete\SellLots\Hybrid\SellLotsHybridBidderHandler;
use Sam\Rtb\Command\Concrete\SellLots\Hybrid\SellLotsHybridClerkHandler;
use Sam\Rtb\Command\Concrete\SellLots\Live\SellLotsLiveBidderHandler;
use Sam\Rtb\Command\Concrete\SellLots\Live\SellLotsLiveClerkHandler;
use Sam\Rtb\Load\RtbLoaderAwareTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\Server\SocketBase\Event\EventClient;
use Sam\Rtb\Server\SocketBase\Legacy\LegacyClient;
use Sam\Rtb\State\Reset\RtbStateResetterAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use User;

/**
 * Class CommandHelper
 * @package Sam\Rtb\Base
 */
abstract class AbstractRtbCommandHelper extends CustomizableClass implements IHelpersAware
{
    use AuctionLotDateAssignorCreateTrait;
    use AuctionLotLoaderAwareTrait;
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use LotItemLoaderAwareTrait;
    use PositionalAuctionLotLoaderAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use RtbLoaderAwareTrait;
    use RtbStateResetterAwareTrait;
    use TranslatorAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Activate lot
     * @param RtbCurrent $rtbCurrent
     * @param int $lotActivity
     * @param int $editorUserId
     * @return RtbCurrent
     */
    abstract public function activateLot(RtbCurrent $rtbCurrent, int $lotActivity, int $editorUserId): RtbCurrent;

    /**
     * Return command instance by name
     * @param string $name
     * @return CommandBase
     */
    abstract public function createCommand(string $name): CommandBase;

    /**
     * Implement behavior, when we want to find the next lot.
     * If lot cannot be found, it may return null (hybrid) or currently running lot (live)
     * @param RtbCurrent $rtbCurrent
     * @param bool $onlyActive
     * @return AuctionLotItem|null
     */
    abstract public function findNextAuctionLot(RtbCurrent $rtbCurrent, bool $onlyActive = false): ?AuctionLotItem;

    /**
     * Implement behavior, when we want to find the next lot.
     * It searches for the next open lot, then it searches from auction beginning.
     * It returns null, if lot for bidding not found.
     * @param RtbCurrent $rtbCurrent
     * @param bool $onlyActive
     * @return LotItem|null
     */
    public function findNextLotItem(
        RtbCurrent $rtbCurrent,
        bool $onlyActive = false
    ): ?LotItem {
        $lotItem = null;
        $auctionLot = $this->findNextAuctionLot($rtbCurrent, $onlyActive);
        if ($auctionLot) {
            $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId, true);
        }
        return $lotItem;
    }

    /**
     * Change running lot
     * @param RtbCurrent $rtbCurrent
     * @param LotItem|null $lotItem
     * @return RtbCurrent
     */
    public function switchRunningLot(RtbCurrent $rtbCurrent, LotItem $lotItem = null): RtbCurrent
    {
        $rtbCurrent->LotItemId = $lotItem->Id ?? null;
        return $rtbCurrent;
    }

    /**
     * Initialize start date of auction lot by current date, if it is empty yet.
     * We need this start date for statistic purpose.
     * @param int $lotItemId
     * @param int $auctionId
     * @param int $editorUserId
     */
    public function initAuctionLotStartDate(int $lotItemId, int $auctionId, int $editorUserId): void
    {
        $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $auctionId);
        if (
            $auctionLot
            && $auctionLot->StartDate === null
        ) {
            $lotDates = LiveHybridAuctionLotDates::new()->setStartDate($this->getCurrentDateUtc());
            $this->createAuctionLotDateAssignor()->assignForLiveOrHybrid($auctionLot, $lotDates, $editorUserId);
        }
    }

    /**
     * @param int $auctionId
     * @return RtbCurrent
     */
    public function createRtbCurrent(int $auctionId): RtbCurrent
    {
        $rtbCurrent = $this->createEntityFactory()->rtbCurrent();
        $rtbCurrent->AuctionId = $auctionId;
        $rtbCurrent = $this->getRtbStateResetter()->restartState($rtbCurrent);
        return $rtbCurrent;
    }

    /**
     * Load RtbCurrent for auction. Create and init, if it doesn't exist
     * @param Auction $auction
     * @return RtbCurrent
     */
    public function loadRtbCurrentOrCreate(Auction $auction): RtbCurrent
    {
        $rtbCurrent = $this->getRtbLoader()->loadByAuctionId($auction->Id);
        if (!$rtbCurrent) {
            $rtbCurrent = $this->createRtbCurrent($auction->Id);
        }
        $lotItem = $this->getLotItemLoader()->load($rtbCurrent->LotItemId);
        if (
            !$lotItem
            && $auction->isStartedOrPaused()
        ) {
            $lotItem = $this->findNextLotItem($rtbCurrent);
            $rtbCurrent = $this->switchRunningLot($rtbCurrent, $lotItem);
        }
        return $rtbCurrent;
    }

    /**
     * @param array $data
     * @return array [?int, bool]
     */
    public function extractQuantityValuesFromLotData(array $data): array
    {
        $quantity = isset($data[Constants\Rtb::RES_QUANTITY])
            ? (float)$data[Constants\Rtb::RES_QUANTITY]
            : null;
        $isQuantityXMoney = $data[Constants\Rtb::RES_IS_QUANTITY_X_MONEY] ?? false;
        return [$quantity, $isQuantityXMoney];
    }

    /**
     * Get button label for online/absentee bid
     *
     * @param User $user
     * @param int $onlinebidButtonInfo
     * @return string
     */
    public function getButtonInfo(User $user, int $onlinebidButtonInfo): string
    {
        switch ($onlinebidButtonInfo) {
            case Constants\UserButtonInfo::CUSTOMER_NO:
                $output = $user->CustomerNo;
                break;
            case Constants\UserButtonInfo::NAME:
                $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($user->Id, true);
                $output = UserPureRenderer::new()->renderFullName($userInfo);
                break;
            case Constants\UserButtonInfo::USERNAME:
            default:
                $output = $user->Username;
                break;
        }
        return (string)$output;
    }

    /**
     * Return connected sockets filtered by passed arguments
     * @param LegacyClient[]|EventClient[] $clients
     * @param int|null $userId null - for all users
     * @param int|null $auctionId null - for all auctions
     * @param int[] $consoleTypes null - for any console type
     * @return LegacyClient[]|EventClient[]
     */
    public function getConnectedUserSockets(
        array $clients,
        ?int $userId = null,
        ?int $auctionId = null,
        array $consoleTypes = []
    ): array {
        $foundSockets = [];
        foreach ($clients ?: [] as $client) {
            // ignore Stats_SocketClient etc
            if (!$this->getRtbGeneralHelper()->checkSocketClient($client)) {
                continue;
            }

            $rtbCommandController = $client->getRtbCommandController();
            if (!$rtbCommandController) {
                log_error("RtbCommandController is not set, when searching for connected user sockets");
                continue;
            }

            $hasMetConsole =
                (
                    $userId === null
                    || $rtbCommandController->getEditorUserId() === $userId
                ) && (
                    $auctionId === null
                    || $rtbCommandController->getAuctionId() === $auctionId
                ) && (
                    empty($consoleTypes)
                    || in_array(
                        $rtbCommandController->userType,
                        $consoleTypes,
                        true
                    )
                );

            if ($hasMetConsole) {
                $foundSockets[] = $client;
            }
        }
        return $foundSockets;
    }

    /**
     * These class names are mapped directly to class
     */
    protected const COMMAND_HANDLER_MAP = [
        'PingHandler' => PingHandler::class,
        'ReversePingHandler' => ReversePingHandler::class,
        'SellLotsHybridBidderHandler' => SellLotsHybridBidderHandler::class,
        'SellLotsHybridClerkHandler' => SellLotsHybridClerkHandler::class,
        'SellLotsLiveBidderHandler' => SellLotsLiveBidderHandler::class,
        'SellLotsLiveClerkHandler' => SellLotsLiveClerkHandler::class,
    ];

    /**
     * Return command instance by auction type and command name
     * @param string $auctionType auction.auction_type
     * @param string $name e.g. 'Sync'
     * @return CommandBase
     */
    protected function createCommandByTypeAndName(string $auctionType, string $name): CommandBase
    {
        if (isset(self::COMMAND_HANDLER_MAP[$name])) {
            $class = self::COMMAND_HANDLER_MAP[$name];
        } else {
            $type = AuctionPureRenderer::new()->makeAuctionType($auctionType);
            $classTemplate = '\Sam\Rtb\Command\Concrete\%s\%s';
            $class = sprintf($classTemplate, $type, $name);
        }
        $instance = call_user_func([$class, 'new']); // $class::new();
        return $instance;
    }
}
