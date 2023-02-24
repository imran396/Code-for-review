<?php
/**
 * SAM-10477: Reject assigning both BP rules on the same level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyersPremiumPanel;

/**
 * Trait BuyersPremiumRangeNormalizerCreateTrait
 * @package Sam\BuyersPremium\Edit\Normalize
 */
trait BuyersPremiumRangeNormalizerCreateTrait
{
    protected ?BuyersPremiumRangeNormalizer $buyersPremiumRangeNormalizer = null;

    /**
     * @return BuyersPremiumRangeNormalizer
     */
    protected function createBuyersPremiumRangeNormalizer(): BuyersPremiumRangeNormalizer
    {
        return $this->buyersPremiumRangeNormalizer ?: BuyersPremiumRangeNormalizer::new();
    }

    /**
     * @param BuyersPremiumRangeNormalizer $buyersPremiumRangeNormalizer
     * @return static
     * @internal
     */
    public function setBuyersPremiumRangeNormalizer(BuyersPremiumRangeNormalizer $buyersPremiumRangeNormalizer): static
    {
        $this->buyersPremiumRangeNormalizer = $buyersPremiumRangeNormalizer;
        return $this;
    }
}
