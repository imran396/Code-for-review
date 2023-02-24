<?php

namespace Sam\Rtb;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use RuntimeException;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Rtb\Command\Controller\AdminHybridController;
use Sam\Rtb\Command\Controller\AdminLiveController;
use Sam\Rtb\Command\Controller\AuctioneerController;
use Sam\Rtb\Command\Controller\BidderHybridController;
use Sam\Rtb\Command\Controller\BidderLiveController;
use Sam\Rtb\Command\Controller\ClientHybridController;
use Sam\Rtb\Command\Controller\ClientLiveController;
use Sam\Rtb\Command\Controller\ControllerBase;
use Sam\Rtb\Command\Controller\ProjectorController;
use Sam\Rtb\Command\Controller\ViewerHybridController;
use Sam\Rtb\Command\Controller\ViewerLiveController;
use Sam\Rtb\Console\Admin\Auctioneer\AdminAuctioneerConsoleBuilder;
use Sam\Rtb\Console\Admin\Clerk\Hybrid\AdminHybridClerkConsoleBuilder;
use Sam\Rtb\Console\Admin\Clerk\Live\AdminLiveClerkConsoleBuilder;
use Sam\Rtb\Console\Internal\AbstractConsoleBuilder;
use Sam\Rtb\Console\Responsive\Bidder\Hybrid\ResponsiveHybridBidderConsoleBuilder;
use Sam\Rtb\Console\Responsive\Bidder\Live\ResponsiveLiveBidderConsoleBuilder;
use Sam\Rtb\Console\Responsive\Viewer\Hybrid\ResponsiveHybridViewerConsoleBuilder;
use Sam\Rtb\Console\Responsive\Viewer\Live\ResponsiveLiveViewerConsoleBuilder;
use Sam\Rtb\Command\Helper\Hybrid\HybridRtbCommandHelper;
use Sam\Rtb\Command\Helper\Live\LiveRtbCommandHelper;
use Sam\Rtb\Server\Daemon\RtbDaemonEvent;
use Sam\Rtb\Server\Daemon\RtbDaemonLegacy;

/**
 * Class Factory
 * @package Sam\Rtb
 */
class RtbFactory extends CustomizableClass
{
    use AuctionLoaderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return command helper for definite auction type
     * @param string $auctionType
     * @return LiveRtbCommandHelper|HybridRtbCommandHelper
     */
    public function createCommandHelper(string $auctionType): LiveRtbCommandHelper|HybridRtbCommandHelper
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isLive($auctionType)) {
            $commandHelper = LiveRtbCommandHelper::new();
        } elseif ($auctionStatusPureChecker->isHybrid($auctionType)) {
            $commandHelper = HybridRtbCommandHelper::new();
        } else {
            throw new RuntimeException("Rtbd doesn't support auction with type: {$auctionType}");
        }
        return $commandHelper;
    }

    /**
     * @param int $userType
     * @param int $auctionId
     * @return AbstractConsoleBuilder|null
     */
    public function createConsoleRenderer(int $userType, int $auctionId): ?AbstractConsoleBuilder
    {
        $rtb = null;
        $auctionType = null;
        if (in_array($userType, [Constants\Rtb::UT_CLERK, Constants\Rtb::UT_BIDDER, Constants\Rtb::UT_VIEWER], true)) {
            $auction = $this->getAuctionLoader()->load($auctionId, true);
            if (!$auction) {
                log_error(
                    "Available auction not found for rtb console rendering"
                    . composeSuffix(['a' => $auctionId, 'ut' => $userType])
                );
                return null;
            }
            $auctionType = $auction->AuctionType;
        }

        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($userType === Constants\Rtb::UT_CLERK) {
            $rtb = $auctionStatusPureChecker->isHybrid($auctionType)
                ? AdminHybridClerkConsoleBuilder::new()
                : AdminLiveClerkConsoleBuilder::new();
        } elseif ($userType === Constants\Rtb::UT_BIDDER) {
            $rtb = $auctionStatusPureChecker->isHybrid($auctionType)
                ? ResponsiveHybridBidderConsoleBuilder::new()
                : ResponsiveLiveBidderConsoleBuilder::new();
        } elseif ($userType === Constants\Rtb::UT_VIEWER) {
            $rtb = $auctionStatusPureChecker->isHybrid($auctionType)
                ? ResponsiveHybridViewerConsoleBuilder::new()
                : ResponsiveLiveViewerConsoleBuilder::new();
        } elseif ($userType === Constants\Rtb::UT_PROJECTOR) {
            $rtb = ResponsiveLiveViewerConsoleBuilder::new();
            $rtb->setUserType(Constants\Rtb::UT_PROJECTOR);
        } elseif ($userType === Constants\Rtb::UT_AUCTIONEER) {
            $rtb = AdminAuctioneerConsoleBuilder::new();
        }

        $rtb->construct($auctionId);

        return $rtb;
    }

    /**
     * @param int|null $editorUserId
     * @param int $auctionId
     * @param int $userType
     * @param string $sessionId
     * @param string $remoteAddress
     * @param int $remotePort
     * @param RtbDaemonLegacy|RtbDaemonEvent $daemon
     * @return ControllerBase|null
     */
    public function createCommandController(
        ?int $editorUserId,
        int $auctionId,
        int $userType,
        string $sessionId,
        string $remoteAddress,
        int $remotePort,
        RtbDaemonLegacy|RtbDaemonEvent $daemon
    ): ?ControllerBase {
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            $logData = [
                'a' => $auctionId,
                'ut' => $userType,
                'u' => $editorUserId,
                'remote' => $remoteAddress . ':' . $remotePort
            ];
            log_error("Available auction not found for creating command controller" . composeSuffix($logData));
            return null;
        }

        $rtbCommandController = null;
        if ($auction->isLiveOrHybrid()) {
            $isHybrid = $auction->isHybrid();
            if ($userType === Constants\Rtb::UT_CLERK) {
                $rtbCommandController = $isHybrid
                    ? AdminHybridController::new()
                    : AdminLiveController::new();
            } elseif ($userType === Constants\Rtb::UT_BIDDER) {
                $rtbCommandController = $isHybrid
                    ? BidderHybridController::new()
                    : BidderLiveController::new();
            } elseif ($userType === Constants\Rtb::UT_VIEWER) {
                $rtbCommandController = $isHybrid
                    ? ViewerHybridController::new()
                    : ViewerLiveController::new();
            } elseif ($userType === Constants\Rtb::UT_PROJECTOR) {
                $rtbCommandController = ProjectorController::new();
            } elseif ($userType === Constants\Rtb::UT_AUCTIONEER) {
                $rtbCommandController = AuctioneerController::new();
            } elseif ($userType === Constants\Rtb::UT_CLIENT) {
                $rtbCommandController = $isHybrid
                    ? ClientHybridController::new()
                    : ClientLiveController::new();
            }

            $rtbCommandController->init(
                $auctionId,
                $editorUserId,
                $userType,
                $sessionId,
                $remoteAddress,
                $remotePort,
                $daemon
            );
        }
        return $rtbCommandController;
    }
}
