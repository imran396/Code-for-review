<?php
/**
 * SAM-5020: RtbCurrent record change outside of rtb daemon process
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/25/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */


namespace Sam\Rtb\WebClient;


/**
 * Trait BuyNowInformatorAwareTrait
 * @package
 */
trait BuyNowInformatorAwareTrait
{
    /**
     * @var BuyNowInformator|null
     */
    protected ?BuyNowInformator $buyNowInformator = null;

    /**
     * @return BuyNowInformator
     */
    protected function getBuyNowInformator(): BuyNowInformator
    {
        if ($this->buyNowInformator === null) {
            $this->buyNowInformator = BuyNowInformator::new();
        }
        return $this->buyNowInformator;
    }

    /**
     * @param BuyNowInformator $buyNowInformator
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setBuyNowInformator(BuyNowInformator $buyNowInformator): static
    {
        $this->buyNowInformator = $buyNowInformator;
        return $this;
    }
}
