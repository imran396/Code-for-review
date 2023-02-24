<?php
/**
 * SAM-10625: Supply uniqueness for user fields: username
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Save\Internal\Consignor;

/**
 * Trait LotItemConsignorUserProducerCreateTrait
 * @package Sam\EntityMaker\LotItem\Save\Internal\Consignor
 */
trait LotItemConsignorUserProducerCreateTrait
{
    protected ?LotItemConsignorUserProducer $lotItemConsignorUserProducer = null;

    /**
     * @return LotItemConsignorUserProducer
     */
    protected function createLotItemConsignorUserProducer(): LotItemConsignorUserProducer
    {
        return $this->lotItemConsignorUserProducer ?: LotItemConsignorUserProducer::new();
    }

    /**
     * @param LotItemConsignorUserProducer $lotItemConsignorUserProducer
     * @return static
     * @internal
     */
    public function setLotItemConsignorUserProducer(LotItemConsignorUserProducer $lotItemConsignorUserProducer): static
    {
        $this->lotItemConsignorUserProducer = $lotItemConsignorUserProducer;
        return $this;
    }
}
