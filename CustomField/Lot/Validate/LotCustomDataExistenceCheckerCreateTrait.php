<?php
/**
 * SAM-6592: Move lot item custom field logic to \Sam\CustomField\Lot namespace
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Validate;

/**
 * Trait LotCustomDataExistenceCheckerCreateTrait
 * @package Sam\CustomField\Lot\Validate
 */
trait LotCustomDataExistenceCheckerCreateTrait
{
    protected ?LotCustomDataExistenceChecker $lotCustomDataExistenceChecker = null;

    /**
     * @return LotCustomDataExistenceChecker
     */
    protected function createLotCustomDataExistenceChecker(): LotCustomDataExistenceChecker
    {
        return $this->lotCustomDataExistenceChecker ?: LotCustomDataExistenceChecker::new();
    }

    /**
     * @param LotCustomDataExistenceChecker $lotCustomDataExistenceChecker
     * @return static
     * @internal
     */
    public function setLotCustomDataExistenceChecker(LotCustomDataExistenceChecker $lotCustomDataExistenceChecker): static
    {
        $this->lotCustomDataExistenceChecker = $lotCustomDataExistenceChecker;
        return $this;
    }
}
