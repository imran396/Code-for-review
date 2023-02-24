<?php
/**
 * SAM-4389: Problems with role permission check for lot custom field
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           8/23/2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Access;

/**
 * Trait LotAccessCheckerAwareTrait
 * @package Sam\User\Access
 */
trait LotAccessCheckerAwareTrait
{
    protected ?LotAccessChecker $lotAccessChecker = null;

    /**
     * @return LotAccessChecker
     */
    protected function getLotAccessChecker(): LotAccessChecker
    {
        if ($this->lotAccessChecker === null) {
            $this->lotAccessChecker = LotAccessChecker::new();
        }
        return $this->lotAccessChecker;
    }

    /**
     * @param LotAccessChecker $lotAccessChecker
     * @return static
     * @internal
     */
    public function setLotAccessChecker(LotAccessChecker $lotAccessChecker): static
    {
        $this->lotAccessChecker = $lotAccessChecker;
        return $this;
    }
}
