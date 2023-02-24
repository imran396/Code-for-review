<?php
/**
 * SAM-6606: Refactoring classes in the \MySearch namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 06, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Search\Query\Build\Helper;

/**
 * Trait LotSearchQueryBuilderHelperCreateTrait
 * @package Sam\Lot\Search\Query
 */
trait LotSearchQueryBuilderHelperCreateTrait
{
    protected ?LotSearchQueryBuilderHelper $lotSearchQueryBuilderHelper = null;

    /**
     * @return LotSearchQueryBuilderHelper
     */
    protected function createLotSearchQueryBuilderHelper(): LotSearchQueryBuilderHelper
    {
        return $this->lotSearchQueryBuilderHelper ?: LotSearchQueryBuilderHelper::new();
    }

    /**
     * @param LotSearchQueryBuilderHelper $lotSearchQueryBuilderHelper
     * @return static
     * @internal
     */
    public function setLotSearchQueryBuilderHelper(LotSearchQueryBuilderHelper $lotSearchQueryBuilderHelper): static
    {
        $this->lotSearchQueryBuilderHelper = $lotSearchQueryBuilderHelper;
        return $this;
    }
}
