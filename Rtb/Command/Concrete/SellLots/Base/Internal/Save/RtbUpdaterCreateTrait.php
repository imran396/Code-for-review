<?php
/**
 * SAM-6527: Rtb refactor SellLots command
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 16, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\SellLots\Base\Internal\Save;

/**
 * Trait RtbStateUpdaterCreateTrait
 * @package Sam\Rtb
 */
trait RtbUpdaterCreateTrait
{
    protected ?RtbUpdater $rtbUpdater = null;

    /**
     * @return RtbUpdater
     */
    protected function createRtbUpdater(): RtbUpdater
    {
        return $this->rtbUpdater ?: RtbUpdater::new();
    }

    /**
     * @param RtbUpdater $rtbUpdater
     * @return $this
     * @internal
     */
    public function setRtbUpdater(RtbUpdater $rtbUpdater): static
    {
        $this->rtbUpdater = $rtbUpdater;
        return $this;
    }
}
