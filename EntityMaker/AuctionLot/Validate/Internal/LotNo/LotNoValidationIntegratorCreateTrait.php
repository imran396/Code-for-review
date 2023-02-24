<?php
/**
 * SAM-8892: Auction Lot entity maker - extract lot# validation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Validate\Internal\LotNo;

/**
 * Trait LotNoValidationIntegratorCreateTrait
 * @package Sam\EntityMaker\AuctionLot\Validate\Internal\LotNo
 */
trait LotNoValidationIntegratorCreateTrait
{
    /**
     * @var LotNoValidationIntegrator|null
     */
    protected ?LotNoValidationIntegrator $lotNoValidationIntegrator = null;

    /**
     * @return LotNoValidationIntegrator
     */
    protected function createLotNoValidationIntegrator(): LotNoValidationIntegrator
    {
        return $this->lotNoValidationIntegrator ?: LotNoValidationIntegrator::new();
    }

    /**
     * @param LotNoValidationIntegrator $lotNoValidationIntegrator
     * @return $this
     * @internal
     */
    public function setLotNoValidationIntegrator(LotNoValidationIntegrator $lotNoValidationIntegrator): static
    {
        $this->lotNoValidationIntegrator = $lotNoValidationIntegrator;
        return $this;
    }
}
