<?php
/**
 * Trait for Lot Buyer Group Loader
 *
 * SAM-4439 : Move lot's buyer group logic to Sam\Lot\BuyerGroup namespace
 * https://bidpath.atlassian.net/browse/SAM-4439
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/6/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\BuyerGroup\Load;

/**
 * Trait LotBuyerGroupLoaderAwareTrait
 * @package Sam\Lot\BuyerGroup\Load
 */
trait BuyerGroupUserLoaderCreateTrait
{
    /**
     * @var BuyerGroupUserLoader|null
     */
    protected ?BuyerGroupUserLoader $buyerGroupUserLoader = null;

    /**
     * @return BuyerGroupUserLoader
     */
    protected function createBuyerGroupUserLoader(): BuyerGroupUserLoader
    {
        return $this->buyerGroupUserLoader ?: BuyerGroupUserLoader::new();
    }

    /**
     * @param BuyerGroupUserLoader $buyerGroupUserLoader
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setBuyerGroupUserLoader(BuyerGroupUserLoader $buyerGroupUserLoader): static
    {
        $this->buyerGroupUserLoader = $buyerGroupUserLoader;
        return $this;
    }
}
