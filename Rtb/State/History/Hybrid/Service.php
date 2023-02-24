<?php
/**
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           13 Okt, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\State\History\Hybrid;

use Sam\Core\Constants;
use RtbCurrent;

/**
 * Class Service
 * @package Sam\Rtb\State\History\Hybrid
 */
class Service extends \Sam\Rtb\State\History\Base\Service
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * In hybrid auction we unite Place Bid and Accept Bid commands, so they are reverted simultaneously
     * @param RtbCurrent $rtbCurrent
     * @param int $editorUserId
     * @return RtbCurrent|null
     */
    public function restore(RtbCurrent $rtbCurrent, int $editorUserId): ?RtbCurrent
    {
        $rtbCurrent = parent::restore($rtbCurrent, $editorUserId);

        $snapshot = $this->getLastRestoredSnapshot();
        if (
            $snapshot
            && $snapshot->Command === Constants\Rtb::CMD_ACCEPT_Q
        ) {
            $rtbCurrent = parent::restore($rtbCurrent, $editorUserId);
        }

        return $rtbCurrent;
    }
}
