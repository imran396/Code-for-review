<?php
/**
 * Helper class for sending "Drop Interest" request to admin clerk console, when client disconnects
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

use Sam\Core\Service\CustomizableClass;
use ReflectionClass;
use ReflectionException;
use Sam\Rtb\Command\Controller\BidderHybridController;
use Sam\Rtb\Command\Controller\BidderLiveController;
use Sam\Core\Constants;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\Server\SocketBase\Event\EventClient;
use Sam\Rtb\Server\SocketBase\Legacy\LegacyClient;

/**
 * Class BidderInterestConsoleDisconnecter
 * @package
 */
class BidderInterestConsoleDisconnecter extends CustomizableClass
{
    use RtbGeneralHelperAwareTrait;

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
        $this->classes['bidderLive'] = BidderLiveController::class;
        $this->classes['bidderHybrid'] = BidderHybridController::class;
        return $this;
    }

    /**
     * @param EventClient|LegacyClient $client
     */
    public function sendDropInterest(EventClient|LegacyClient $client): void
    {
        if (!$this->getRtbGeneralHelper()->checkSocketClient($client)) {
            log_error("SocketClient has unexpected type, when dropping bidder interest");
            return;
        }

        $rtbCommandController = $client->getRtbCommandController();
        if (!$rtbCommandController) {
            log_error("RtbCommandController is not set, when dropping bidder interest");
            return;
        }

        $rtbDaemon = $client->getRtbDaemon();
        $auctionId = $rtbCommandController->getAuctionId();
        $editorUserId = (int)$rtbCommandController->getEditorUserId();
        // Send Drop Interest command, when bidder console is disconnected
        if (
            $rtbDaemon->getBidderInterestManager()->hasInterested($auctionId, $editorUserId)
            && $this->isBidderConnection($rtbCommandController)
        ) {
            $requestJson = json_encode(
                [
                    Constants\Rtb::REQ_COMMAND => Constants\Rtb::CMD_DROP_INTEREST_Q
                ]
            );
            $client->handleRequest($requestJson);
        }
    }

    /**
     * Check if connected user console is from Bidder side
     * @param $userCommand
     * @return bool
     */
    protected function isBidderConnection($userCommand): bool
    {
        $className = get_class($userCommand);
        try {
            $reflection = new ReflectionClass($className);
            $isAllowed = in_array($className, [$this->classes['bidderLive'], $this->classes['bidderHybrid']], true)
                || $reflection->isSubclassOf($this->classes['bidderLive'])
                || $reflection->isSubclassOf($this->classes['bidderHybrid']);
        } catch (ReflectionException $e) {
            $isAllowed = false;
            log_error($e->getMessage());
        }
        return $isAllowed;
    }
}
