<?php
/**
 * LotItem existence checker aware trait
 *
 * SAM-4348: Lot validators
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 17, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Validate;

/**
 * Trait LotItemExistenceCheckerAwareTrait
 * @package Sam\Lot\Validate
 */
trait LotItemExistenceCheckerAwareTrait
{
    /**
     * @var LotItemExistenceChecker|null $lotItemExistenceChecker
     */
    protected ?LotItemExistenceChecker $lotItemExistenceChecker = null;

    /**
     * @param LotItemExistenceChecker $lotItemExistenceChecker
     * @return static
     * @internal
     */
    public function setLotItemExistenceChecker(LotItemExistenceChecker $lotItemExistenceChecker): static
    {
        $this->lotItemExistenceChecker = $lotItemExistenceChecker;
        return $this;
    }

    /**
     * @return LotItemExistenceChecker
     */
    protected function getLotItemExistenceChecker(): LotItemExistenceChecker
    {
        if ($this->lotItemExistenceChecker === null) {
            $this->lotItemExistenceChecker = LotItemExistenceChecker::new();
        }
        return $this->lotItemExistenceChecker;
    }
}
