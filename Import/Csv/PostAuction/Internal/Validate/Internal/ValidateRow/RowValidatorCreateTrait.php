<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Internal\Validate\Internal\ValidateRow;

/**
 * Trait RowValidatorCreateTrait
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
