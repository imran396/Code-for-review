<?php
/**
 * SAM-8107: Issues related to Validation and Values of Buyer's Premium
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 21, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Save\Internal\BuyersPremium;

/**
 * Trait BuyersPremiumSaverCreateTrait
 * @package Sam\EntityMaker\LotItem
 */
trait BuyersPremiumSaverCreateTrait
{
    /**
     * @var BuyersPremiumSaver|null
     */
    protected ?BuyersPremiumSaver $buyersPremiumSaver = null;

    /**
     * @return BuyersPremiumSaver
     */
    protected function createBuyersPremiumSaver(): BuyersPremiumSaver
    {
        return $this->buyersPremiumSaver ?: BuyersPremiumSaver::new();
    }

    /**
     * @param BuyersPremiumSaver $buyersPremiumSaver
     * @return $this
     * @internal
     */
    public function setBuyersPremiumSaver(BuyersPremiumSaver $buyersPremiumSaver): static
    {
        $this->buyersPremiumSaver = $buyersPremiumSaver;
        return $this;
    }
}
