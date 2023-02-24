<?php
/**
 * Trait for Lot Buyer Group AccessHelper
 *
 * SAM-4449 : Language label translation modules
 * https://bidpath.atlassian.net/browse/SAM-4449
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/2/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\BuyerGroup\Access;

/**
 * Trait LotBuyerGroupAccessHelperAwareTrait
 * @package Sam\Lot\BuyerGroup\Access
 */
trait LotBuyerGroupAccessHelperAwareTrait
{
    /**
     * @var LotBuyerGroupAccessHelper|null $lotBuyerGroupAccessHelper
     */
    protected ?LotBuyerGroupAccessHelper $lotBuyerGroupAccessHelper = null;

    /**
     * @param LotBuyerGroupAccessHelper $lotBuyerGroupAccessHelper
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotBuyerGroupAccessHelper(LotBuyerGroupAccessHelper $lotBuyerGroupAccessHelper): static
    {
        $this->lotBuyerGroupAccessHelper = $lotBuyerGroupAccessHelper;
        return $this;
    }

    /**
     * @return LotBuyerGroupAccessHelper
     */
    protected function getLotBuyerGroupAccessHelper(): LotBuyerGroupAccessHelper
    {
        if ($this->lotBuyerGroupAccessHelper === null) {
            $this->lotBuyerGroupAccessHelper = LotBuyerGroupAccessHelper::new();
        }
        return $this->lotBuyerGroupAccessHelper;
    }
}
