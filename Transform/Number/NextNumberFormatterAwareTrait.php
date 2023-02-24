<?php
/**
 * SAM-4173: Consider number formatting at public side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Transform\Number;


/**
 * Trait NextNumberFormatterAwareTrait
 * @package Sam\Transform\Number
 */
trait NextNumberFormatterAwareTrait
{
    protected ?NextNumberFormatter $nextNumberFormatter = null;

    /**
     * @return NextNumberFormatter
     */
    protected function getNextNumberFormatter(): NextNumberFormatter
    {
        if ($this->nextNumberFormatter === null) {
            $this->nextNumberFormatter = NextNumberFormatter::new();
        }
        return $this->nextNumberFormatter;
    }

    /**
     * @param NextNumberFormatter $nextNumberFormatter
     * @return static
     * @internal
     */
    public function setNextNumberFormatter(NextNumberFormatter $nextNumberFormatter): static
    {
        $this->nextNumberFormatter = $nextNumberFormatter;
        return $this;
    }
}
