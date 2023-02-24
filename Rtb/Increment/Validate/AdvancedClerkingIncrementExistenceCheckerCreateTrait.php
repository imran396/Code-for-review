<?php
/**
 * SAM-5346: Rtb asking bid calculator
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/10/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Increment\Validate;

/**
 * Trait AdvancedClerkingIncrementExistenceCheckerCreateTrait
 * @package Sam\Rtb\Increment\Validate
 */
trait AdvancedClerkingIncrementExistenceCheckerCreateTrait
{
    protected ?AdvancedClerkingIncrementExistenceChecker $advancedClerkingIncrementExistenceChecker = null;

    /**
     * @return AdvancedClerkingIncrementExistenceChecker
     */
    protected function createAdvancedClerkingIncrementExistenceChecker(): AdvancedClerkingIncrementExistenceChecker
    {
        $advancedClerkingIncrementExistenceChecker = $this->advancedClerkingIncrementExistenceChecker ?: AdvancedClerkingIncrementExistenceChecker::new();
        return $advancedClerkingIncrementExistenceChecker;
    }

    /**
     * @param AdvancedClerkingIncrementExistenceChecker $advancedClerkingIncrementExistenceChecker
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAdvancedClerkingIncrementExistenceChecker(AdvancedClerkingIncrementExistenceChecker $advancedClerkingIncrementExistenceChecker): static
    {
        $this->advancedClerkingIncrementExistenceChecker = $advancedClerkingIncrementExistenceChecker;
        return $this;
    }
}
