<?php
/**
 * Tracks time and sends "Sync Interest" request to clerk console for refreshing interested bidder list
 *
 * SAM-1023: Live Clerking Improvements & Bidder Interest
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           11/6/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\BidderInterest;

use ReflectionException;
use Sam\Core\Service\CustomizableClass;
use ReflectionClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Rtb\Command\Controller\AdminHybridController;
use Sam\Rtb\Command\Controller\AdminLiveController;
use Sam\Rtb\Command\Controller\AuctioneerController;
use Sam\Core\Constants;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\Server\Daemon\RtbDaemonEvent;
use Sam\Rtb\Server\Daemon\RtbDaemonLegacy;

/**
 * Class BidderInterestConsoleUpdater
 * @package
 */
class BidderInterestConsoleUpdater extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use RtbGeneralHelperAwareTrait;

    private ?int $biddingInterestTime = null;
    /** @var string[] */
    private array $classes = [];

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
    public function initInstance(): static
    {
        $this->classes['adminLive'] = AdminLiveController::class;
        $this->classes['adminHybrid'] = AdminHybridController::class;
        $this->classes['auctioneer'] = AuctioneerController::class;
        return $this;
    }

    /**
     * @return static
     */
    public function initTime(): static
    {
        $this->biddingInterestTime = time();
        return $this;
    }

    /**
     * @param RtbDaemonLegacy|RtbDaemonEvent $rtbDaemon
     */
    public function refreshInterest(RtbDaemonLegacy|RtbDaemonEvent $rtbDaemon): void
    {
        // We want to refresh and sync interest state by some interval
        if (
            $this->cfg()->get('core->rtb->biddingInterest->enabled')
            && time() - $this->biddingInterestTime > $this->cfg()->get('core->rtb->biddingInterest->gcTimeout')
        ) {
            $this->biddingInterestTime = time();
            $expiredUserIdsPerAuction = $rtbDaemon->getBidderInterestManager()->dropExpired();
            if (!empty($expiredUserIdsPerAuction)) {
                $expiredAuctionIds = array_keys($expiredUserIdsPerAuction);
                foreach ($expiredAuctionIds as $auctionId) {
                    foreach ($rtbDaemon->clientSockets as $client) {
                        if (!$this->getRtbGeneralHelper()->checkSocketClient($client)) {
                            log_info("SocketClient has unexpected type, when dropping bidder interest" . composeSuffix(['a' => $auctionId]));
                            continue;
                        }

                        $controllerBase = $client->getRtbCommandController();
                        if (!$controllerBase) {
                            log_info("RtbCommandController is not set" . composeSuffix(['a' => $auctionId]));
                            continue;
                        }

                        $className = get_class($controllerBase);
                        try {
                            $reflection = new ReflectionClass($className);
                            $classes = [
                                $this->classes['adminLive'],
                                $this->classes['adminHybrid'],
                                $this->classes['auctioneer']
                            ];
                            if (
                                in_array($className, $classes, true)
                                || $reflection->isSubclassOf($this->classes['adminLive'])
                                || $reflection->isSubclassOf($this->classes['adminHybrid'])
                                || $reflection->isSubclassOf($this->classes['auctioneer'])
                            ) {
                                if ($controllerBase->getAuctionId() === $auctionId) {
                                    $requestJson = json_encode(
                                        [
                                            Constants\Rtb::REQ_COMMAND => Constants\Rtb::CMD_SYNC_INTEREST_Q
                                        ]
                                    );
                                    $client->handleRequest($requestJson);
                                }
                            }
                        } catch (ReflectionException) {
                            log_error("Reflection exception thrown" . composeSuffix(['class' => $className]));
                        }
                    }
                }
            }
        }
    }
}
