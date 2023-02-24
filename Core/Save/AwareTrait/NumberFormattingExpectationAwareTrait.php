<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           02.02.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Core\Save\AwareTrait;

/**
 * Trait NumberFormattingExpectationAwareTrait
 * @package Sam\Core\Save\AwareTrait
 */
trait NumberFormattingExpectationAwareTrait
{
    private bool $numberFormattingExpected = false;

    /**
     * @return bool
     */
    public function isNumberFormattingExpected(): bool
    {
        return $this->numberFormattingExpected;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableNumberFormattingExpectation(bool $enable): static
    {
        $this->numberFormattingExpected = $enable;
        return $this;
    }
}
