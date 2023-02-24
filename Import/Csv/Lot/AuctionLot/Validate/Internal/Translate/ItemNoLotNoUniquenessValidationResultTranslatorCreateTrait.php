<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\AuctionLot\Validate\Internal\Translate;

/**
 * Trait ItemNoLotNoUniquenessValidationResultTranslatorCreateTrait
 * @package Sam\Import\Csv\Lot\AuctionLot\Validate\Internal\Translate
 */
trait ItemNoLotNoUniquenessValidationResultTranslatorCreateTrait
{
    /**
     * @var ItemNoLotNoUniquenessValidationResultTranslator|null
     */
    protected ?ItemNoLotNoUniquenessValidationResultTranslator $itemNoLotNoUniquenessValidationResultTranslator = null;

    /**
     * @return ItemNoLotNoUniquenessValidationResultTranslator
     */
    protected function createItemNoLotNoUniquenessValidationResultTranslator(): ItemNoLotNoUniquenessValidationResultTranslator
    {
        return $this->itemNoLotNoUniquenessValidationResultTranslator ?: ItemNoLotNoUniquenessValidationResultTranslator::new();
    }

    /**
     * @param ItemNoLotNoUniquenessValidationResultTranslator $itemNoLotNoUniquenessValidationResultTranslator
     * @return static
     * @internal
     */
    public function setItemNoLotNoUniquenessValidationResultTranslator(ItemNoLotNoUniquenessValidationResultTranslator $itemNoLotNoUniquenessValidationResultTranslator): static
    {
        $this->itemNoLotNoUniquenessValidationResultTranslator = $itemNoLotNoUniquenessValidationResultTranslator;
        return $this;
    }
}
