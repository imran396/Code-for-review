<?php
/**
 * SAM-9365: Refactor BidIncrementCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\BidIncrement\Internal\Validate\Internal\Translate;

/**
 * Trait RowValidationResultTranslatorCreateTrait
 * @package Sam\Import\Csv\BidIncrement\Internal\Validate\Internal\Translate
 */
trait RowValidationResultTranslatorCreateTrait
{
    /**
     * @var RowValidationResultTranslator|null
     */
    protected ?RowValidationResultTranslator $rowValidationResultTranslator = null;

    /**
     * @return RowValidationResultTranslator
     */
    protected function createRowValidationResultTranslator(): RowValidationResultTranslator
    {
        return $this->rowValidationResultTranslator ?: RowValidationResultTranslator::new();
    }

    /**
     * @param RowValidationResultTranslator $rowValidationResultTranslator
     * @return static
     * @internal
     */
    public function setRowValidationResultTranslator(RowValidationResultTranslator $rowValidationResultTranslator): static
    {
        $this->rowValidationResultTranslator = $rowValidationResultTranslator;
        return $this;
    }
}
