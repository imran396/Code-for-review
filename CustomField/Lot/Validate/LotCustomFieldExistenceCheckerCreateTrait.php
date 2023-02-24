<?php
/**
 * SAM-6592: Move lot item custom field logic to \Sam\CustomField\Lot namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 13, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Validate;


/**
 * Trait LotCustomFieldExistenceCheckerCreateTrait
 * @package Sam\CustomField\Lot\Validate
 */
trait LotCustomFieldExistenceCheckerCreateTrait
{
    /**
     * @var LotCustomFieldExistenceChecker|null
     */
    protected ?LotCustomFieldExistenceChecker $lotCustomFieldExistenceChecker = null;

    /**
     * @return LotCustomFieldExistenceChecker
     */
    protected function createLotCustomFieldExistenceChecker(): LotCustomFieldExistenceChecker
    {
        return $this->lotCustomFieldExistenceChecker ?: LotCustomFieldExistenceChecker::new();
    }

    /**
     * @param LotCustomFieldExistenceChecker $lotCustomFieldExistenceChecker
     * @return static
     * @internal
     */
    public function setLotCustomFieldExistenceChecker(LotCustomFieldExistenceChecker $lotCustomFieldExistenceChecker): static
    {
        $this->lotCustomFieldExistenceChecker = $lotCustomFieldExistenceChecker;
        return $this;
    }
}
