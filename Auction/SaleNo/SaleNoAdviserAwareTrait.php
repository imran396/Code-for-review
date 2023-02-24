<?php
/**
 * Trait for SaleNo Adviser
 *
 * SAM-4241 Auction Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 3, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\SaleNo;

/**
 * Trait AdviserAwareTrait
 * @package Sam\Auction\SaleNo
 */
trait SaleNoAdviserAwareTrait
{
    /**
     * @var Adviser|null
     */
    protected ?Adviser $saleNoAdviser = null;

    /**
     * @return Adviser
     */
    protected function getSaleNoAdviser(): Adviser
    {
        if ($this->saleNoAdviser === null) {
            $this->saleNoAdviser = Adviser::new();
        }
        return $this->saleNoAdviser;
    }

    /**
     * @param Adviser $adviser
     * @return static
     * @internal
     */
    public function setSaleNoAdviser(Adviser $adviser): static
    {
        $this->saleNoAdviser = $adviser;
        return $this;
    }
}
