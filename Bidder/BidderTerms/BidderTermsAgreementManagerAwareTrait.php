<?php
/**
 * SAM-4450: Apply Bidder Terms Agreement Manager
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/17/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\BidderTerms;

/**
 * Trait BidderTermsAgreementManagerAwareTrait
 * @package Sam\Bidder\BidderTerms
 */
trait BidderTermsAgreementManagerAwareTrait
{
    /**
     * @var BidderTermsAgreementManager|null
     */
    protected ?BidderTermsAgreementManager $bidderTermsAgreementManager = null;

    /**
     * @return BidderTermsAgreementManager
     */
    protected function getBidderTermsAgreementManager(): BidderTermsAgreementManager
    {
        if ($this->bidderTermsAgreementManager === null) {
            $this->bidderTermsAgreementManager = BidderTermsAgreementManager::new();
        }
        return $this->bidderTermsAgreementManager;
    }

    /**
     * @param BidderTermsAgreementManager $bidderTermsAgreementManager
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setBidderTermsAgreementManager(BidderTermsAgreementManager $bidderTermsAgreementManager): static
    {
        $this->bidderTermsAgreementManager = $bidderTermsAgreementManager;
        return $this;
    }
}
