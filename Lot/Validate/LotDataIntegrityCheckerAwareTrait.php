<?php
/**
 * Trait for LotDataIntegrityChecker
 *
 * SAM-5069: Data integrity checker - there shall only be one active lot_item_cust_data record for one lot_item
 * and one lot_item_cust_field
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           7/30/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Validate;

/**
 * Trait LotDataIntegrityCheckerAwareTrait
 * @package Sam\Lot\Validate
 */
trait LotDataIntegrityCheckerAwareTrait
{
    /**
     * @var LotDataIntegrityChecker|null
     */
    protected ?LotDataIntegrityChecker $lotDataIntegrityChecker = null;

    /**
     * @return LotDataIntegrityChecker
     */
    protected function getLotDataIntegrityChecker(): LotDataIntegrityChecker
    {
        if ($this->lotDataIntegrityChecker === null) {
            $this->lotDataIntegrityChecker = LotDataIntegrityChecker::new();
        }
        return $this->lotDataIntegrityChecker;
    }

    /**
     * @param LotDataIntegrityChecker $lotDataIntegrityChecker
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotDataIntegrityChecker(LotDataIntegrityChecker $lotDataIntegrityChecker): static
    {
        $this->lotDataIntegrityChecker = $lotDataIntegrityChecker;
        return $this;
    }
}
