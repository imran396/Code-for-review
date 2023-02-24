<?php
/**
 * SAM-5012: Live/Hybrid auction state reset in rtbd process
 * SAM-5020: RtbCurrent record change outside of rtb daemon process
 *
 * When auction rtb state is changed at web side, eg:
 * - when live/hybrid auction reset is called from Auction List page,
 * - when we drop asking bid and increments at Auction Edit page saving \Sam\View\Admin\Form\AuctionEditForm::save()
 * then system sends auction rtb state resync request to rtb daemon, because we need to reload RtbCurrent object in rtbd scope.
 * We use this web side command handler \Sam\Rtb\WebClient\AuctionStateResyncer.
 * It sends rtbd command \Sam\Rtb\Command\Concrete\Base\Resync, that sends re-sync responses to all consoles.
 * When console receives re-sync response it initiates SyncQ for itself.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 27, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\Base;

use Sam\Core\Constants;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class Resync
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class Resync extends CommandBase
{
    use RtbCurrentWriteRepositoryAwareTrait;

    public function execute(): void
    {
        $rtbCurrent = $this->getRtbCurrent();
        $rtbCurrent->Reload();
        $this->getRtbCurrentWriteRepository()->save($rtbCurrent); // IK: it was ->forceSave($auctionLot), IDK why (SAM-5436)
    }

    protected function createResponses(): void
    {
        $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_RESYNC_S];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_SINGLE] = $responseJson;
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;
        $this->setResponses($responses);
    }
}
