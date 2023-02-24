<?php
/**
 * SAM-5495: Rtb server and daemon refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           11/18/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Sell;

/**
 * Trait LotSellerAwareTrait
 * @package Sam\Rtb\Sell
 */
trait LotSellerAwareTrait
{
    /**
     * @var LotSeller|null
     */
    protected ?LotSeller $lotSeller = null;

    /**
     * @return LotSeller
     */
    protected function getLotSeller(): LotSeller
    {
        if ($this->lotSeller === null) {
            $this->lotSeller = LotSeller::new();
        }
        return $this->lotSeller;
    }

    /**
     * @param LotSeller $lotSeller
     * @return static
     */
    public function setLotSeller(LotSeller $lotSeller): static
    {
        $this->lotSeller = $lotSeller;
        return $this;
    }
}
