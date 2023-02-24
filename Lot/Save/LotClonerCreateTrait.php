<?php
/**
 * SAM-4401: Clone lot manager class
 * https://bidpath.atlassian.net/browse/SAM-4401
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/21/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Save;

/**
 * Trait LotClonerCreateTrait
 * @package Sam\Lot\Save
 */
trait LotClonerCreateTrait
{
    /**
     * @var LotCloner|null;
     */
    protected ?LotCloner $lotCloner = null;

    /**
     * @return LotCloner
     */
    protected function createLotCloner(): LotCloner
    {
        return $this->lotCloner ?: LotCloner::new();
    }

    /**
     * @param LotCloner $lotCloner
     * @return static
     * @internal
     */
    public function setLotCloner(LotCloner $lotCloner): static
    {
        $this->lotCloner = $lotCloner;
        return $this;
    }
}
