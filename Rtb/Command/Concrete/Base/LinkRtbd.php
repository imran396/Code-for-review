<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/6/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\Base;

use Sam\Core\Constants;
use Sam\Rtb\Pool\Auction\Load\AuctionRtbdLoaderAwareTrait;
use Sam\Rtb\Pool\Auction\Save\AuctionRtbdProducerAwareTrait;
use Sam\Rtb\Pool\Instance\RtbdNameAwareTrait;

/**
 * Class LinkRtbd
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class LinkRtbd extends CommandBase
{
    use AuctionRtbdLoaderAwareTrait;
    use AuctionRtbdProducerAwareTrait;
    use RtbdNameAwareTrait;

    public function execute(): void
    {
        $auctionRtbd = $this->getAuctionRtbdLoader()
            ->loadOrCreate($this->getAuctionId());
        $this->getAuctionRtbdProducer()
            ->setEditorUserId($this->getEditorUserId())
            ->setRtbdName($this->getRtbdName())
            ->update($auctionRtbd);
    }

    protected function createResponses(): void
    {
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_RELOAD_S
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;

        $responseForCaller = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_LINK_RTBD_S
        ];
        $responseForCallerJson = json_encode($responseForCaller);
        $responses[Constants\Rtb::RT_SINGLE] = $responseForCallerJson;
        $this->setResponses($responses);
    }
}
