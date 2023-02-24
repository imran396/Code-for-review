<?php
/**
 * SAM-9365: Refactor BidIncrementCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\BidIncrement\Internal\Validate\Internal;

/**
 * Trait RangesValidatorCreateTrait
 * @package Sam\Import\Csv\BidIncrement\Internal\Validate\Internal
 */
trait RangesValidatorCreateTrait
{
    /**
     * @var RangesValidator|null
     */
    protected ?RangesValidator $rangesValidator = null;

    /**
     * @return RangesValidator
     */
    protected function createRangesValidator(): RangesValidator
    {
        return $this->rangesValidator ?: RangesValidator::new();
    }

    /**
     * @param RangesValidator $rangesValidator
     * @return static
     * @internal
     */
    public function setRangesValidator(RangesValidator $rangesValidator): static
    {
        $this->rangesValidator = $rangesValidator;
        return $this;
    }
}
