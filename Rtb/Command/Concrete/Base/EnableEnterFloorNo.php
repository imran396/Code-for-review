<?php
/**
 * Handle click on "Enter Floor#", that enables/disables popup of "Enter Floor Bidder#" dialog
 *
 * SAM-3091: Hybrid/Virtual-Live Auctions Feature
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           14 Nov, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\Base;

use Sam\Core\Constants;
use Sam\Core\Data\JsonArray;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class EnableEnterFloorNo
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class EnableEnterFloorNo extends CommandBase implements RtbCommandHelperAwareInterface
{
    use RtbCurrentWriteRepositoryAwareTrait;

    protected bool $isEnterFloorNo = false;

    /**
     * @param bool $enterFloorNo
     * @return static
     */
    public function enableEnterFloorNo(bool $enterFloorNo): static
    {
        $this->isEnterFloorNo = $enterFloorNo;
        return $this;
    }

    public function execute(): void
    {
        if (!$this->checkRunningLot()) {
            $this->getRtbCommandHelper()->createCommand('Sync')->runInContext($this);
            return;
        }

        $rtbCurrent = $this->getRtbCurrent();
        $enterFloorNoOptions = new JsonArray($rtbCurrent->EnterFloorNo);
        $enterFloorNoOptions->set($this->getEditorUserId(), $this->isEnterFloorNo);
        $rtbCurrent->EnterFloorNo = $enterFloorNoOptions->getJson();
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
        log_info(
            'Enter Floor No by Clerk is ' . ($this->isEnterFloorNo ? 'Enabled' : 'Disabled')
            . composeSuffix(['a' => $rtbCurrent->AuctionId, 'u' => $this->getEditorUserId()])
        );
    }

    protected function createResponses(): void
    {
        $rtbCurrent = $this->getRtbCurrent();
        $data = [
            Constants\Rtb::RES_ENTER_FLOOR_NO => [
                $this->getEditorUserId() => $this->isEnterFloorNo,
            ],
        ];
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_ENABLE_ENTER_FLOOR_NO_S,
            Constants\Rtb::RES_DATA => $data,
        ];

        $responseJson = json_encode($response);
        $individualResponse = [
            Constants\Rtb::RES_SA_USER_ID => $this->getEditorUserId(),
            Constants\Rtb::RES_SA_AUCTION_ID => $rtbCurrent->AuctionId,
            Constants\Rtb::RES_SA_MESSAGE => $responseJson,
        ];
        $responses[Constants\Rtb::RT_SIMULT_INDIVIDUAL] = $individualResponse;

        $this->setResponses($responses);
    }
}
