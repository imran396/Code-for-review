<?php
/**
 * SAM-3865 : Sale group manager class https://bidpath.atlassian.net/browse/SAM-3865
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 17, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\SaleGroup;

/**
 * Trait SaleGroupManagerAwareTrait
 * @package Sam\Auction\SaleGroup
 */
trait SaleGroupManagerAwareTrait
{
    /**
     * @var SaleGroupManager|null
     */
    protected ?SaleGroupManager $saleGroupManager = null;

    /**
     * @return SaleGroupManager
     */
    protected function getSaleGroupManager(): SaleGroupManager
    {
        if ($this->saleGroupManager === null) {
            $this->saleGroupManager = SaleGroupManager::new();
        }
        return $this->saleGroupManager;
    }

    /**
     * @param SaleGroupManager $saleGroupManager
     * @return static
     * @internal
     */
    public function setSaleGroupManager(SaleGroupManager $saleGroupManager): static
    {
        $this->saleGroupManager = $saleGroupManager;
        return $this;
    }
}
