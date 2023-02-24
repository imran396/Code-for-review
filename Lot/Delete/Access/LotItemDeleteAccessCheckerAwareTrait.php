<?php
/**
 * SAM-9129: Verify lot item deleting operation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Delete\Access;

/**
 * Trait LotItemDeleteAccessCheckerAwareTrait
 * @package Sam\Lot\Delete\Access
 */
trait LotItemDeleteAccessCheckerAwareTrait
{
    /**
     * @var LotItemDeleteAccessChecker|null
     */
    protected ?LotItemDeleteAccessChecker $lotItemDeleteAccessChecker = null;

    /**
     * @return LotItemDeleteAccessChecker
     */
    protected function getLotItemDeleteAccessChecker(): LotItemDeleteAccessChecker
    {
        if ($this->lotItemDeleteAccessChecker === null) {
            $this->lotItemDeleteAccessChecker = LotItemDeleteAccessChecker::new();
        }
        return $this->lotItemDeleteAccessChecker;
    }

    /**
     * @param LotItemDeleteAccessChecker $lotItemDeleteAccessChecker
     * @return $this
     * @internal
     */
    public function setLotItemDeleteAccessChecker(LotItemDeleteAccessChecker $lotItemDeleteAccessChecker): static
    {
        $this->lotItemDeleteAccessChecker = $lotItemDeleteAccessChecker;
        return $this;
    }
}
