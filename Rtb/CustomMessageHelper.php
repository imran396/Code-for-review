<?php
/**
 * Logic for Rtb Messages from Message Center in the admin panel
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 22, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb;

use QUndefinedPrimaryKeyException;
use RtbMessage;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Rtb\Load\RtbMessageLoaderCreateTrait;
use Sam\Storage\ReadRepository\Entity\RtbMessage\RtbMessageReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\RtbMessage\RtbMessageWriteRepositoryAwareTrait;

/**
 * Class CustomMessageHelper
 */
class CustomMessageHelper extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use RtbMessageLoaderCreateTrait;
    use RtbMessageReadRepositoryCreateTrait;
    use RtbMessageWriteRepositoryAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $accountId - null means messages without account
     * @return RtbMessage[]
     */
    public function getMessages(?int $accountId): array
    {
        $repo = $this->createRtbMessageReadRepository()
            ->enableReadOnlyDb(true)
            ->filterAccountId($accountId)
            ->orderByOrder();
        $rtbMessages = $repo->loadEntities();
        return $rtbMessages;
    }

    /**
     * @param string $title
     * @param string $message
     * @param int|null $auctionId
     * @param int $editorUserId
     * @return int|null - means we can't successfully add a rtb message
     */
    public function add(string $title, string $message, ?int $auctionId, int $editorUserId): ?int
    {
        $message = trim($message);
        $title = trim($title);
        if ($message === '' && $title === '') {
            return null;
        }

        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            log_error(
                "Available auction not found for adding rtb message"
                . composeSuffix(['a' => $auctionId, 'u' => $editorUserId])
            );
            return null;
        }
        $rtbMessage = $this->createEntityFactory()->rtbMessage();
        $rtbMessage->AccountId = $auction->AccountId;
        $rtbMessage->Active = true;
        $rtbMessage->Message = $message;
        $rtbMessage->Title = $title;
        $this->getRtbMessageWriteRepository()->saveWithModifier($rtbMessage, $editorUserId);
        return $rtbMessage->Id;
    }

    /**
     * @param int|null $rtbMessageId
     * @param int $editorUserId
     * @return bool
     */
    public function delete(?int $rtbMessageId, int $editorUserId): bool
    {
        if (!$rtbMessageId) {
            return false;
        }

        $rtbMessage = $this->createRtbMessageLoader()->load($rtbMessageId, true);
        if (!$rtbMessage) {
            return false;
        }

        try {
            $this->getRtbMessageWriteRepository()->deleteWithModifier($rtbMessage, $editorUserId);
            return true;
        } catch (QUndefinedPrimaryKeyException $e) {
            log_error($e->getMessage());
        }
        return false;
    }

    /**
     * @param int $id
     * @return string
     */
    public function renderMessageButton(int $id): string
    {
        $rtbMessage = $this->createRtbMessageLoader()->load($id);
        if (!$rtbMessage) {
            log_error("Available RtbMessage not found, when rendering message button" . composeSuffix(['id' => $id]));
            return '';
        }

        $domId = 'rtb-msg' . $rtbMessage->Id;
        $message = htmlentities(addslashes($rtbMessage->Message), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $title = htmlentities(addslashes($rtbMessage->Title), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $output = <<<HTML
<div id="{$domId}" class="button-wrap">
    <button type="button" value="{$title}" class="msg-button" data-msg="{$message}" data-id="{$rtbMessage->Id}">
        {$title}
    </button>
    <a href="#" data-id="{$rtbMessage->Id}" class="msg-close-button">[x]&nbsp;</a>
</div>
HTML;
        return $output;
    }
}
