<?php
/**
 * SAM-5400: Rtb state update refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/19/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\State;

/**
 * Trait RtbStateUpdaterCreateTrait
 * @package Sam\Rtb\State
 */
trait RtbStateUpdaterCreateTrait
{
    /**
     * @var RtbStateUpdater|null
     */
    protected ?RtbStateUpdater $rtbStateUpdater = null;

    /**
     * @return RtbStateUpdater
     */
    protected function createRtbStateUpdater(): RtbStateUpdater
    {
        $rtbStateUpdater = $this->rtbStateUpdater ?: RtbStateUpdater::new();
        return $rtbStateUpdater;
    }

    /**
     * @param RtbStateUpdater $rtbStateUpdater
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setRtbStateUpdater(RtbStateUpdater $rtbStateUpdater): static
    {
        $this->rtbStateUpdater = $rtbStateUpdater;
        return $this;
    }
}
