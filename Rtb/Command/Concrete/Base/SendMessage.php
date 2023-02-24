<?php

namespace Sam\Rtb\Command\Concrete\Base;

use Sam\Application\Url\Build\Config\Asset\RtbMessageSoundUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Load\RtbMessageLoaderCreateTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\User\Render\UserRendererAwareTrait;

/**
 * Class SendMessage
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class SendMessage extends CommandBase
{
    use RtbGeneralHelperAwareTrait;
    use RtbMessageLoaderCreateTrait;
    use RtbRendererCreateTrait;
    use UrlBuilderAwareTrait;
    use UserRendererAwareTrait;

    protected ?int $receiverUserId = null;
    protected ?int $messageId = null;
    protected string $message = '';
    protected string $bidderNo = '';

    /**
     * @param string $message
     * @return static
     */
    public function setMessage(string $message): static
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param int|null $messageId
     * @return static
     */
    public function setMessageId(?int $messageId): static
    {
        $this->messageId = $messageId;
        return $this;
    }

    /**
     * @param int|null $userId
     * @return static
     */
    public function setReceiverUserId(?int $userId): static
    {
        $this->receiverUserId = $userId;
        return $this;
    }

    /**
     * @param string $bidderNo
     * @return static
     */
    public function setBidderNo(string $bidderNo): static
    {
        $this->bidderNo = $bidderNo;
        return $this;
    }

    public function execute(): void
    {
        if (!$this->checkMessage()) {
            return;
        }

        if ($this->getUserType() === Constants\Rtb::UT_SYSTEM) {
            $this->sendMessageBySystem();
        } elseif ($this->getUserType() === Constants\Rtb::UT_CLERK) {
            $this->sendMessageByAdmin();
        } elseif ($this->getUserType() === Constants\Rtb::UT_BIDDER) {
            $this->sendMessageByBidder();
        } elseif ($this->getUserType() === Constants\Rtb::UT_VIEWER) {
            $this->sendMessageByViewer();
        } elseif ($this->getUserType() === Constants\Rtb::UT_PROJECTOR) {
            $this->sendMessageByProjector();
        } elseif ($this->getUserType() === Constants\Rtb::UT_AUCTIONEER) {
            $this->sendMessageByAuctioneer();
        }
    }

    protected function sendMessageBySystem(): void
    {
        $cleanMessage = $this->cleanMessage();
        $data = [Constants\Rtb::RES_MESSAGE => $cleanMessage];
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_S,
            Constants\Rtb::RES_DATA => $data,
        ];
        $responseJson = json_encode($response);

        /**
         * Send message to individual user
         */
        if ($this->receiverUserId) {
            // Append support log
            $userRoleName = $this->getLogger()->getUserRoleName($this->getUserType());
            $logMessage = "{$userRoleName} sends message to {$this->bidderNo} ({$this->receiverUserId}) '{$this->message}' ";
            $this->getLogger()->log($logMessage);

            // Make responses
            $responses[Constants\Rtb::RT_INDIVIDUAL] = [$this->receiverUserId, $responseJson];
            $this->setResponses($responses);
            return;
        }

        /**
         * Send message to all user consoles
         */
        // Append support log
        $userRoleName = $this->getLogger()->getUserRoleName($this->getUserType());
        $logMessage = "{$userRoleName} sends message to all '{$this->message}' ";
        $this->getLogger()->log($logMessage);

        // Save in static file
        $auctioneerMessage = $this->createRtbRenderer()->renderAuctioneerMessage($cleanMessage, $this->getAuction());
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $auctioneerMessage, true);
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $auctioneerMessage);

        // Make responses
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;
        $responses = $this->getResponseHelper()->addForSimultaneousAuction(
            $responses,
            $this->getSimultaneousAuctionId(),
            $cleanMessage
        );
        $this->setResponses($responses);
    }

    protected function sendMessageByAdmin(): void
    {
        $cleanMessage = $this->cleanMessage();
        $soundEffectUrl = '';

        /**
         * Use prepared message with specific sound
         */
        if ($this->messageId) {
            $rtbMessage = $this->createRtbMessageLoader()->load($this->messageId, true);
            /**
             * Do nothing when message button exists, but the message is not
             * (e.g. was deleted from settings while admin console is open)
             */
            if (!$rtbMessage) {
                return;
            }
            $cleanMessage = $this->getRtbGeneralHelper()->clean($rtbMessage->Message);
            if ($rtbMessage->SoundEffect) {
                $soundEffectUrl = $this->getUrlBuilder()->build(
                    RtbMessageSoundUrlConfig::new()->forWeb($rtbMessage->Id, $this->getAuction()->AccountId)
                );
            }
        }

        $data = [
            Constants\Rtb::RES_MESSAGE => $cleanMessage,
            Constants\Rtb::RES_SOUND_URL => $soundEffectUrl,
        ];
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_S,
            Constants\Rtb::RES_DATA => $data
        ];
        $responseJson = json_encode($response);

        /**
         * Send message to individual user
         */
        if ($this->receiverUserId) {
            // Append support log
            $userRoleName = $this->getLogger()->getUserRoleName($this->getUserType());
            $logMessage = "{$userRoleName} sends message to {$this->bidderNo} ({$this->receiverUserId}) '{$this->message}' ";
            $this->getLogger()->log($logMessage);

            // Make responses
            $responses[Constants\Rtb::RT_INDIVIDUAL] = [$this->receiverUserId, $responseJson];
            $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;
            $this->setResponses($responses);
            return;
        }

        /**
         * Send message to all user consoles
         */
        // Append support log
        $userRoleName = $this->getLogger()->getUserRoleName($this->getUserType());
        $logMessage = "{$userRoleName} sends message to all '{$this->message}' ";
        $this->getLogger()->log($logMessage);

        // Save in static file
        $auctioneerMessage = $this->createRtbRenderer()->renderAuctioneerMessage($cleanMessage, $this->getAuction());
        $this->getMessenger()->createStaticChatMessage($this->getAuctionId(), $auctioneerMessage, true);
        $this->getMessenger()->createStaticChatMessage($this->getAuctionId(), $auctioneerMessage);

        // Make responses
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;
        $responses = $this->getResponseHelper()->addForSimultaneousAuction(
            $responses,
            $this->getSimultaneousAuctionId(),
            $cleanMessage
        );
        $this->setResponses($responses);
    }

    /**
     * @return void
     */
    protected function sendMessageByBidder(): void
    {
        $cleanMessage = $this->cleanMessage();
        $editorUserId = $this->getEditorUserId();
        $editorUser = $this->getUserLoader()->load($editorUserId);

        /**
         * Anonymous user should not send message
         */
        if (!$editorUser) {
            return;
        }

        // Make responses
        $editorAuctionBidder = $this->getAuctionBidderLoader()->load($editorUserId, $this->getAuctionId(), true);
        if (!$editorAuctionBidder) {
            $logData = ['u' => $editorUserId, 'a' => $this->getAuctionId()];
            log_error("Available auction bidder not found, when sending message" . composeSuffix($logData));
            return;
        }
        $cleanBidderNo = $this->getBidderNumberPadding()->clear($editorAuctionBidder->BidderNum);
        $cleanUsernameForAdmin = $this->getRtbGeneralHelper()->clean($editorUser->Username);
        $data = [
            Constants\Rtb::RES_MESSAGE_SENDER_BIDDER_NO => $cleanBidderNo,
            Constants\Rtb::RES_USER_ID => $editorUserId,
            Constants\Rtb::RES_MESSAGE_SENDER_USERNAME => $cleanUsernameForAdmin,
            Constants\Rtb::RES_MESSAGE => $cleanMessage,
        ];
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_S,
            Constants\Rtb::RES_DATA => $data
        ];
        $responseJson = json_encode($response);

        // Append support log
        $userRoleName = $this->getLogger()->getUserRoleName($this->getUserType(), $cleanBidderNo);
        $logMessage = "{$userRoleName} ({$editorUserId}) sends message to clerk '{$this->message}' ";
        $this->getLogger()->log($logMessage);

        // Save in static file
        $cleanBidderNo = $cleanBidderNo ?: '-';
        $messageAdmin = <<<HTML
<span class="auc-lbl umsg"><a uid="{$editorUserId}" href="javascript:void(0)">{$cleanUsernameForAdmin} ({$cleanBidderNo})</a>: </span><span class="umsg">{$cleanMessage}</span><br />
HTML;
        $this->getMessenger()->createStaticChatMessage($this->getAuctionId(), $messageAdmin, true);

        $responses[Constants\Rtb::RT_CLERK] = $responseJson;
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;

        if ($this->isLiveChatViewAll()) {
            $usernameForPublic = $this->getUserRenderer()->maskUsernameIfAlikeEmail($editorUser->Username);
            $cleanUsernameForPublic = $this->getRtbGeneralHelper()->clean($usernameForPublic);
            $messagePublic = <<<HTML
<span class="auc-lbl">{$cleanUsernameForPublic} ({$cleanBidderNo}): </span>{$cleanMessage}<br />
HTML;
            $this->getMessenger()->createStaticChatMessage($this->getAuctionId(), $messagePublic);

            $data = [
                Constants\Rtb::RES_MESSAGE_SENDER_BIDDER_NO => $cleanBidderNo,
                Constants\Rtb::RES_USER_ID => $editorUserId,
                Constants\Rtb::RES_MESSAGE_SENDER_USERNAME => $cleanUsernameForPublic,
                Constants\Rtb::RES_MESSAGE => $cleanMessage,
            ];
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_S,
                Constants\Rtb::RES_DATA => $data
            ];
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
            $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
            $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;
            $responses = $this->getResponseHelper()->addForSimultaneousAuction(
                $responses,
                $this->getSimultaneousAuctionId(),
                $cleanMessage
            );
        } else {
            $responses[Constants\Rtb::RT_INDIVIDUAL] = [$editorUserId, $responseJson];
        }

        $this->setResponses($responses);
    }

    protected function sendMessageByViewer(): void
    {
        $cleanMessage = $this->cleanMessage();
        $editorUserId = $this->getEditorUserId();
        $editorUser = $this->getUserLoader()->load($editorUserId);

        /**
         * Anonymous user should not send message
         */
        if (!$editorUser) {
            return;
        }

        // Make responses
        $editorAuctionBidder = $this->getAuctionBidderLoader()->load($editorUserId, $this->getAuctionId(), true);
        $cleanBidderNo = $editorAuctionBidder ? $this->getBidderNumberPadding()->clear($editorAuctionBidder->BidderNum) : '';
        $cleanUsernameForAdmin = $this->getRtbGeneralHelper()->clean($editorUser->Username);
        $data = [
            Constants\Rtb::RES_MESSAGE_SENDER_BIDDER_NO => $cleanBidderNo,
            Constants\Rtb::RES_USER_ID => $editorUserId,
            Constants\Rtb::RES_MESSAGE_SENDER_USERNAME => $cleanUsernameForAdmin,
            Constants\Rtb::RES_MESSAGE => $cleanMessage,
        ];
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_S,
            Constants\Rtb::RES_DATA => $data
        ];
        $responseJson = json_encode($response);

        // Append support log
        $userRoleName = $this->getLogger()->getUserRoleName($this->getUserType(), $cleanBidderNo);
        $logMessage = "{$userRoleName} ({$editorUserId}) sends message to clerk '{$this->message}' ";
        $this->getLogger()->log($logMessage);

        // Save in static file
        $cleanBidderNo = $cleanBidderNo ?: '-';
        $messageAdmin = <<<HTML
<span class="auc-lbl umsg">{$cleanUsernameForAdmin} ({$cleanBidderNo}): </span><span class="umsg">{$cleanMessage}</span><br />
HTML;
        $this->getMessenger()->createStaticChatMessage($this->getAuctionId(), $messageAdmin, true);

        $responses[Constants\Rtb::RT_CLERK] = $responseJson;
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;

        if ($this->isLiveChatViewAll()) {
            $usernameForPublic = $this->getUserRenderer()->maskUsernameIfAlikeEmail($editorUser->Username);
            $cleanUsernameForPublic = $this->getRtbGeneralHelper()->clean($usernameForPublic);
            $messagePublic = <<<HTML
<span class="auc-lbl">{$cleanUsernameForPublic} ({$cleanBidderNo}): </span>{$cleanMessage}<br />
HTML;
            $this->getMessenger()->createStaticChatMessage($this->getAuctionId(), $messagePublic);

            $data = [
                Constants\Rtb::RES_MESSAGE_SENDER_BIDDER_NO => $cleanBidderNo,
                Constants\Rtb::RES_USER_ID => $editorUserId,
                Constants\Rtb::RES_MESSAGE_SENDER_USERNAME => $cleanUsernameForPublic,
                Constants\Rtb::RES_MESSAGE => $cleanMessage,
            ];
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_S,
                Constants\Rtb::RES_DATA => $data
            ];
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
            $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
            $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;
        } else {
            $responses[Constants\Rtb::RT_INDIVIDUAL] = [$editorUserId, $responseJson];
        }

        $this->setResponses($responses);
    }

    protected function sendMessageByProjector(): void
    {
        $cleanMessage = $this->cleanMessage();
        $editorUserId = $this->getEditorUserId();
        $editorUser = $this->getUserLoader()->load($editorUserId);

        /**
         * Anonymous user should not send message
         */
        if (!$editorUser) {
            return;
        }

        // Make responses
        $cleanUsernameForAdmin = $this->getRtbGeneralHelper()->clean($editorUser->Username);
        $editorAuctionBidder = $this->getAuctionBidderLoader()->load($editorUserId, $this->getAuctionId(), true);
        $cleanBidderNo = $editorAuctionBidder ? $this->getBidderNumberPadding()->clear($editorAuctionBidder->BidderNum) : '';
        $data = [
            Constants\Rtb::RES_MESSAGE_SENDER_BIDDER_NO => $cleanBidderNo,
            Constants\Rtb::RES_USER_ID => $editorUserId,
            Constants\Rtb::RES_MESSAGE_SENDER_USERNAME => $cleanUsernameForAdmin,
            Constants\Rtb::RES_MESSAGE => $cleanMessage,
        ];
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_S,
            Constants\Rtb::RES_DATA => $data
        ];
        $responseJson = json_encode($response);

        // Append support log
        $userRoleName = $this->getLogger()->getUserRoleName($this->getUserType(), $cleanBidderNo);
        $logMessage = "{$userRoleName} sends message to clerk '{$this->message}' ";
        $this->getLogger()->log($logMessage);

        // Save in static file
        $cleanBidderNo = $cleanBidderNo ?: '-';
        $messageAdmin = <<<HTML
<span class="auc-lbl umsg">{$cleanUsernameForAdmin} ({$cleanBidderNo}): </span><span class="umsg">{$cleanMessage}</span><br />
HTML;
        $this->getMessenger()->createStaticChatMessage($this->getAuctionId(), $messageAdmin, true);

        $responses[Constants\Rtb::RT_CLERK] = $responseJson;
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;

        if ($this->isLiveChatViewAll()) {
            $auctioneerMessage = $this->createRtbRenderer()->renderAuctioneerMessage($cleanMessage, $this->getAuction());
            $this->getMessenger()->createStaticChatMessage($this->getAuctionId(), $auctioneerMessage);
            $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
            $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
            $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;
        } else {
            $responses[Constants\Rtb::RT_INDIVIDUAL] = [$editorUserId, $responseJson];
        }

        $this->setResponses($responses);
    }

    protected function sendMessageByAuctioneer(): void
    {
        $cleanMessage = $this->cleanMessage();
        $data = [Constants\Rtb::RES_MESSAGE => $cleanMessage];
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_S,
            Constants\Rtb::RES_DATA => $data,
        ];
        $responseJson = json_encode($response);

        /**
         * Send message to individual user
         */
        if ($this->receiverUserId) {
            // Append support log
            $userRoleName = $this->getLogger()->getUserRoleName($this->getUserType());
            $logMessage = "{$userRoleName} sends message to {$this->bidderNo} ({$this->receiverUserId}) '{$this->message}' ";
            $this->getLogger()->log($logMessage);

            // Make responses
            $responses[Constants\Rtb::RT_INDIVIDUAL] = [$this->receiverUserId, $responseJson];
            $responses[Constants\Rtb::RT_CLERK] = $responseJson;
            $this->setResponses($responses);
            return;
        }

        /**
         * Send message to all user consoles
         */
        // Append support log
        $userRoleName = $this->getLogger()->getUserRoleName($this->getUserType());
        $logMessage = "{$userRoleName} sends message to all '{$this->message}' ";
        $this->getLogger()->log($logMessage);

        // Save in static file
        $auctioneerMessage = $this->createRtbRenderer()->renderAuctioneerMessage($cleanMessage, $this->getAuction());
        $this->getMessenger()->createStaticChatMessage($this->getAuctionId(), $auctioneerMessage, true);
        $this->getMessenger()->createStaticChatMessage($this->getAuctionId(), $auctioneerMessage);

        // Make responses
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;
        $responses = $this->getResponseHelper()->addForSimultaneousAuction(
            $responses,
            $this->getSimultaneousAuctionId(),
            $cleanMessage
        );
        $this->setResponses($responses);
    }

    /**
     * @return bool
     */
    protected function isLiveChatViewAll(): bool
    {
        return (bool)$this->getSettingsManager()
            ->get(Constants\Setting::LIVE_CHAT_VIEW_ALL, $this->getAuction()->AccountId);
    }

    /**
     * Clear html tags and adjust html entities in message text (SAM-6824)
     * @return string
     */
    protected function cleanMessage(): string
    {
        $message = $this->stripTags($this->message);
        $message = $this->getRtbGeneralHelper()->clean($message);
        $message = trim($message);
        return $message;
    }

    /**
     * Remove denied tags
     * @param string $input
     * @return string
     */
    protected function stripTags(string $input): string
    {
        return strip_tags($input, implode('', $this->cfg()->get('core->rtb->messageCenter->htmlTagWhitelist')->toArray()));
    }

    /**
     * @return bool
     */
    protected function checkMessage(): bool
    {
        return $this->messageId
            || $this->cleanMessage() !== '';
    }
}
