<?php
/**
 * SAM-10837: Race condition issue of rtb commands "Change Increment", "Place Floor Bid" sequence - Add console state synchronization check by the current bid value
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\Base\Validate;

/**
 * Trait RtbCommandStateCheckerCreateTrait
 * @package Sam\Rtb\Command\Concrete\Base\Validate
 */
trait RtbCommandStateCheckerCreateTrait
{
    protected ?RtbCommandStateChecker $rtbCommandStateChecker = null;

    /**
     * @return RtbCommandStateChecker
     */
    protected function createRtbCommandStateChecker(): RtbCommandStateChecker
    {
        return $this->rtbCommandStateChecker ?: RtbCommandStateChecker::new();
    }

    /**
     * @param RtbCommandStateChecker $rtbCommandStateChecker
     * @return $this
     * @internal
     */
    public function setRtbCommandStateChecker(RtbCommandStateChecker $rtbCommandStateChecker): static
    {
        $this->rtbCommandStateChecker = $rtbCommandStateChecker;
        return $this;
    }
}
