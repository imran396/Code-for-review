<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/18/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Increment\Save;

/**
 * Trait RtbIncrementUpdaterCreateTrait
 * @package Sam\Rtb\Increment\Save
 */
trait RtbIncrementUpdaterCreateTrait
{
    /**
     * @var RtbIncrementUpdater|null
     */
    protected ?RtbIncrementUpdater $rtbIncrementUpdater = null;

    /**
     * @return RtbIncrementUpdater
     */
    protected function createRtbIncrementUpdater(): RtbIncrementUpdater
    {
        $rtbIncrementUpdater = $this->rtbIncrementUpdater ?: RtbIncrementUpdater::new();
        return $rtbIncrementUpdater;
    }

    /**
     * @param RtbIncrementUpdater $rtbIncrementUpdater
     * @return static
     * @internal
     */
    public function setRtbIncrementUpdater(RtbIncrementUpdater $rtbIncrementUpdater): static
    {
        $this->rtbIncrementUpdater = $rtbIncrementUpdater;
        return $this;
    }
}
