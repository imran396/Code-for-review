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
 * Trait RowValidatorCreateTrait
 * @package Sam\Import\Csv\BidIncrement\Internal\Validate\Internal
 */
trait RowValidatorCreateTrait
{
    /**
     * @var RowValidator|null
     */
    protected ?RowValidator $rowValidator = null;

    /**
     * @return RowValidator
     */
    protected function createRowValidator(): RowValidator
    {
        return $this->rowValidator ?: RowValidator::new();
    }

    /**
     * @param RowValidator $rowValidator
     * @return static
     * @internal
     */
    public function setRowValidator(RowValidator $rowValidator): static
    {
        $this->rowValidator = $rowValidator;
        return $this;
    }
}
