<?php
/**
 * SAM-9688: Ability to make Auction and Lot/Item fields required to have values: Implementation (Developer)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\LotFieldConfig\Produce;

/**
 * Trait LotFieldConfigProducerCreateTrait
 * @package Sam\Lot\LotFieldConfig\Produce
 */
trait LotFieldConfigProducerCreateTrait
{
    /**
     * @var LotFieldConfigProducer|null
     */
    protected ?LotFieldConfigProducer $lotFieldConfigProducer = null;

    /**
     * @return LotFieldConfigProducer
     */
    protected function createLotFieldConfigProducer(): LotFieldConfigProducer
    {
        return $this->lotFieldConfigProducer ?: LotFieldConfigProducer::new();
    }

    /**
     * @param LotFieldConfigProducer $lotFieldConfigProducer
     * @return static
     * @internal
     */
    public function setLotFieldConfigProducer(LotFieldConfigProducer $lotFieldConfigProducer): static
    {
        $this->lotFieldConfigProducer = $lotFieldConfigProducer;
        return $this;
    }
}
