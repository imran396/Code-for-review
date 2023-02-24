<?php
/**
 * SAM-5012: Live/Hybrid auction state reset in rtbd process
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

namespace Sam\Rtb\Command\Concrete\Hybrid;

use Sam\Rtb\Hybrid\HelpersAwareTrait;

/**
 * Class ResetAuction
 * @package Sam\Rtb\Command\Concrete\Hybrid
 */
class Resync extends \Sam\Rtb\Command\Concrete\Base\Resync
{
    use HelpersAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function execute(): void
    {
        parent::execute();
        $this->getResponseHelper()->applyAdditionalInfo($this);
    }
}
