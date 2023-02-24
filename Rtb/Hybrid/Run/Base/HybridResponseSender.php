<?php
/**
 * Response sending logic for hybrid auction rtbd processing
 * SAM-3775: Rtbd improvements
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/24/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Hybrid\Run\Base;

use Sam\Core\Service\CustomizableClass;
use Sam\Rtb\Command\Controller\AdminHybridController;
use Sam\Rtb\Command\Controller\BidderHybridController;
use Sam\Rtb\Command\Controller\BidderLiveController;
use Sam\Rtb\Command\Controller\ProjectorController;
use Sam\Rtb\Command\Controller\ViewerHybridController;
use Sam\Rtb\Command\Controller\ViewerLiveController;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\Server\ServerResponseSenderCreateTrait;
use Sam\Rtb\Server\SocketBase\Event\EventClient;
use Sam\Rtb\Server\SocketBase\Legacy\LegacyClient;

/**
 * Class ResponseSender
 * @package Sam\Rtb\Hybrid\Run\Base
 */
class HybridResponseSender extends CustomizableClass
{
    use RtbGeneralHelperAwareTrait;
    use ServerResponseSenderCreateTrait;

    /**
     * @var LegacyClient[]|EventClient[]
     */
    protected array $clients = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LegacyClient[]|EventClient[] $clients set sockets currently are connected
     * @return static
     */
    public function construct(array $clients): static
    {
        $this->clients = $clients;
        return $this;
    }

    /**
     * Send responses to connected console sockets
     * @param array $responses
     * @param int $auctionId
     */
    public function send(array $responses, int $auctionId): void
    {
        if (!$this->clients) {
            return;
        }

        $serverResponseSender = $this->createServerResponseSender();
        foreach ($this->clients as $client) {
            // ignore Stats_SocketClient etc
            if (!$this->getRtbGeneralHelper()->checkSocketClient($client)) {
                continue;
            }

            $rtbCommandController = $client->getRtbCommandController();
            if (!$rtbCommandController) {
                log_error("RtbCommandController is not set, when sending hybrid response");
                continue;
            }

            $allClients = $client->getRtbDaemon()->clientSockets;
            if ($rtbCommandController->getAuctionId() === $auctionId) {
                if ($rtbCommandController instanceof AdminHybridController) {
                    if (!empty($responses[Constants\Rtb::RT_CLERK])) {
                        $serverResponseSender->handleResponse($client, (string)$responses[Constants\Rtb::RT_CLERK]);
                    }
                } elseif ($rtbCommandController instanceof BidderHybridController) {
                    if (!empty($responses[Constants\Rtb::RT_BIDDER])) {
                        $serverResponseSender->handleResponse($client, (string)$responses[Constants\Rtb::RT_BIDDER]);
                    }
                } elseif ($rtbCommandController instanceof ViewerHybridController) {
                    if (!empty($responses[Constants\Rtb::RT_VIEWER])) {
                        $serverResponseSender->handleResponse($client, (string)$responses[Constants\Rtb::RT_VIEWER]);
                    }
                } elseif ($rtbCommandController instanceof ProjectorController) {
                    if (!empty($responses[Constants\Rtb::RT_PROJECTOR])) {
                        $serverResponseSender->handleResponse($client, (string)$responses[Constants\Rtb::RT_PROJECTOR]);
                    }
                }
                if (
                    !empty($responses[Constants\Rtb::RT_INDIVIDUAL])
                    && count($responses[Constants\Rtb::RT_INDIVIDUAL]) > 1
                ) {
                    $userId = (int)$responses[Constants\Rtb::RT_INDIVIDUAL][0];
                    if ($rtbCommandController->getEditorUserId() === $userId) {
                        $response = (string)$responses[Constants\Rtb::RT_INDIVIDUAL][1];
                        $serverResponseSender->broadcast($allClients, $response, null, $auctionId, $userId);
                    }
                }
            }
            if (
                !empty($responses[Constants\Rtb::RT_SIMULT_BIDDER])
                && (
                    $rtbCommandController instanceof BidderHybridController
                    || $rtbCommandController instanceof BidderLiveController
                )
            )    // we support simultaneous live/hybrid auctions
            {
                $simultaneousAuctionId = Cast::toInt($responses[Constants\Rtb::RT_SIMULT_BIDDER][Constants\Rtb::RES_SA_AUCTION_ID], Constants\Type::F_INT_POSITIVE);
                if ($rtbCommandController->getAuctionId() === $simultaneousAuctionId) {
                    $message = (string)$responses[Constants\Rtb::RT_SIMULT_BIDDER][Constants\Rtb::RES_SA_MESSAGE];
                    $serverResponseSender->broadcast($allClients, $message, Constants\Rtb::UT_BIDDER, $simultaneousAuctionId);
                }
            }
            if (
                !empty($responses[Constants\Rtb::RT_SIMULT_VIEWER])
                && (
                    $rtbCommandController instanceof ViewerHybridController
                    || $rtbCommandController instanceof ViewerLiveController
                )
            )    // we support simultaneous live/hybrid auctions
            {
                $simultaneousAuctionId = Cast::toInt($responses[Constants\Rtb::RT_SIMULT_VIEWER][Constants\Rtb::RES_SA_AUCTION_ID], Constants\Type::F_INT_POSITIVE);
                if ($rtbCommandController->getAuctionId() === $simultaneousAuctionId) {
                    $message = (string)$responses[Constants\Rtb::RT_SIMULT_VIEWER][Constants\Rtb::RES_SA_MESSAGE];
                    $serverResponseSender->broadcast($allClients, $message, Constants\Rtb::UT_VIEWER, $simultaneousAuctionId);
                }
            }
            if (!empty($responses[Constants\Rtb::RT_SIMULT_INDIVIDUAL])) {
                $simultaneousAuctionId = Cast::toInt($responses[Constants\Rtb::RT_SIMULT_INDIVIDUAL][Constants\Rtb::RES_SA_AUCTION_ID], Constants\Type::F_INT_POSITIVE);
                $userId = Cast::toInt($responses[Constants\Rtb::RT_SIMULT_INDIVIDUAL][Constants\Rtb::RES_SA_USER_ID], Constants\Type::F_INT_POSITIVE);
                if (
                    $rtbCommandController->getAuctionId() === $simultaneousAuctionId
                    && $rtbCommandController->getEditorUserId() === $userId
                ) {
                    $message = (string)$responses[Constants\Rtb::RT_SIMULT_INDIVIDUAL][Constants\Rtb::RES_SA_MESSAGE];
                    $serverResponseSender->broadcast($allClients, $message, null, $simultaneousAuctionId, $userId);
                }
            }
        }
    }

    /**
     * @return LegacyClient[]|EventClient[]
     */
    public function getClients(): array
    {
        return $this->clients;
    }
}
