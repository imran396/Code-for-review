<?php
/**
 * SAM-10948: Stacked Tax. Invoice Management pages. Prepare Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Chunk\Internal\Limit;

/**
 * Trait LimitCheckerAwareTrait
 * @package Sam\Invoice\StackedTax\Generate\Chunk\Internal\Limit
 */
trait LimitCheckerAwareTrait
{
    protected ?LimitChecker $limitChecker = null;

    /**
     * @return LimitChecker
     */
    protected function getLimitChecker(): LimitChecker
    {
        if ($this->limitChecker === null) {
            $this->limitChecker = LimitChecker::new();
        }
        return $this->limitChecker;
    }

    /**
     * @param LimitChecker $limitChecker
     * @return $this
     * @internal
     */
    public function setLimitChecker(LimitChecker $limitChecker): static
    {
        $this->limitChecker = $limitChecker;
        return $this;
    }
}
