<?php
/**
 * SAM-3224:Refactoring of auction_closer.php
 * https://bidpath.atlassian.net/browse/SAM-3224
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Closer;

use Sam\Core\Service\CustomizableClass;
use Email_Template;
use Exception;
use Sam\Storage\Entity\AwareTrait\AuctionLotAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Core\Constants;

/**
 * Class LotCloseNotifier
 * @package Sam\AuctionLot\Closer
 */
class LotCloseNotifier extends CustomizableClass
{
    use AuctionLotAwareTrait;
    use LotItemAwareTrait;
    use UserAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Notify to item winner and consignor.
     * @param int $editorUserId
     * @return void
     */
    public function notifyLotWon(int $editorUserId): void
    {
        log_debug('Sending item won notification: ');
        try {
            $emailManager = Email_Template::new()->construct(
                $this->getAuctionLot()->AccountId,
                Constants\EmailKey::ITEM_WON_TIMED,
                $editorUserId,
                [$this->getUser(), $this->getLotItem(), $this->getAuctionLot()],
                $this->getAuctionLot()->AuctionId
            );
            $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);
            $emailManager = Email_Template::new()->construct(
                $this->getAuctionLot()->AccountId,
                Constants\EmailKey::WINNING_BID_NOTIFICATION_SENT_CONSIGNOR,
                $editorUserId,
                [$this->getUser(), $this->getLotItem(), $this->getAuctionLot()],
                $this->getAuctionLot()->AuctionId
            );
            $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);
        } catch (Exception $e) {
            log_warning(composeLogData(['Caught exception' => $e->getMessage()]));
        }
    }
}
