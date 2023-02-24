<?php
/**
 * Trait for MultipleLotCloner
 *
 * SAM-5668: Extract multiple lot cloning logic from controller action
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Save;

/**
 * Trait MultipleLotClonerCreateTrait
 * @package Sam\Lot\Save
 */
trait MultipleLotClonerCreateTrait
{
    /**
     * @var MultipleLotCloner|null
     */
    protected ?MultipleLotCloner $multipleLotCloner = null;

    /**
     * @return MultipleLotCloner
     */
    protected function createMultipleLotCloner(): MultipleLotCloner
    {
        return $this->multipleLotCloner ?: MultipleLotCloner::new();
    }

    /**
     * @param MultipleLotCloner $multipleLotCloner
     * @return $this
     * @internal
     */
    public function setMultipleLotCloner(MultipleLotCloner $multipleLotCloner): static
    {
        $this->multipleLotCloner = $multipleLotCloner;
        return $this;
    }
}
