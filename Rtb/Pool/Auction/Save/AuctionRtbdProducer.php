<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/31/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Auction\Save;

use AuctionRtbd;
use InvalidArgumentException;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Rtb\Pool\Instance\RtbdNameAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionRtbd\AuctionRtbdWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class AuctionRtbdBuilder
 * @package
 */
class AuctionRtbdProducer extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionRtbdWriteRepositoryAwareTrait;
    use CurrentDateTrait;
    use EditorUserAwareTrait;
    use EntityFactoryCreateTrait;
    use RtbdNameAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return AuctionRtbd
     */
    public function create(): AuctionRtbd
    {
        // we should check `auction` record existence
        $auction = $this->getAuction();
        if (!$auction) {
            $message = "Available auction not found, when creating auction_rtbd record"
                . composeSuffix(['a' => $this->getAuctionId()]);
            log_error($message);
            throw new InvalidArgumentException($message);
        }

        $auctionRtbd = $this->createEntityFactory()->auctionRtbd();
        $auctionRtbd->AuctionId = $this->getAuctionId();
        $editorUserId = $this->getEditorUserId()
            ?: $this->getUserLoader()->loadSystemUserId();
        $auctionRtbd->RtbdName = $this->getRtbdName();
        $this->getAuctionRtbdWriteRepository()->saveWithModifier($auctionRtbd, $editorUserId);
        return $auctionRtbd;
    }

    /**
     * @param AuctionRtbd $auctionRtbd
     * @return AuctionRtbd
     */
    public function update(AuctionRtbd $auctionRtbd): AuctionRtbd
    {
        $auctionRtbd->RtbdName = $this->getRtbdName();
        $editorUserId = $this->getEditorUserId()
            ?: $this->getUserLoader()->loadSystemUserId();
        $this->getAuctionRtbdWriteRepository()->saveWithModifier($auctionRtbd, $editorUserId);
        return $auctionRtbd;
    }
}
