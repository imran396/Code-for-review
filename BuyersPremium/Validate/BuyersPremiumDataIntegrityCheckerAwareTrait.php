<?php
/**
 * Trait for BuyersPremiumDataIntegrityChecker
 *
 * SAM-5076: Data integrity checker - one buyer's premium table (eg live, or per auction) must have exactly one range
 * starting at zero
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/19/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Validate;

/**
 * Trait BuyersPremiumDataIntegrityCheckerAwareTrait
 * @package Sam\BuyersPremium\Validate
 */
trait BuyersPremiumDataIntegrityCheckerAwareTrait
{
    /**
     * @var BuyersPremiumDataIntegrityChecker|null
     */
    protected ?BuyersPremiumDataIntegrityChecker $buyersPremiumDataIntegrityChecker = null;

    /**
     * @return BuyersPremiumDataIntegrityChecker
     */
    protected function getBuyersPremiumDataIntegrityChecker(): BuyersPremiumDataIntegrityChecker
    {
        if ($this->buyersPremiumDataIntegrityChecker === null) {
            $this->buyersPremiumDataIntegrityChecker = BuyersPremiumDataIntegrityChecker::new();
        }
        return $this->buyersPremiumDataIntegrityChecker;
    }

    /**
     * @param BuyersPremiumDataIntegrityChecker $buyersPremiumDataIntegrityChecker
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setBuyersPremiumDataIntegrityChecker(BuyersPremiumDataIntegrityChecker $buyersPremiumDataIntegrityChecker): static
    {
        $this->buyersPremiumDataIntegrityChecker = $buyersPremiumDataIntegrityChecker;
        return $this;
    }
}
