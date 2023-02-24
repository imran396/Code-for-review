<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotCategory\Save;

/**
 * Trait LotCategoryMakerProducerCreateTrait
 * @package Sam\EntityMaker\LotCategory\Save
 */
trait LotCategoryMakerProducerCreateTrait
{
    /**
     * @var LotCategoryMakerProducer|null
     */
    protected ?LotCategoryMakerProducer $lotCategoryMakerProducer = null;

    /**
     * @return LotCategoryMakerProducer
     */
    protected function createLotCategoryMakerProducer(): LotCategoryMakerProducer
    {
        return $this->lotCategoryMakerProducer ?: LotCategoryMakerProducer::new();
    }

    /**
     * @param LotCategoryMakerProducer $lotCategoryMakerProducer
     * @return $this
     * @internal
     */
    public function setLotCategoryMakerProducer(LotCategoryMakerProducer $lotCategoryMakerProducer): static
    {
        $this->lotCategoryMakerProducer = $lotCategoryMakerProducer;
        return $this;
    }
}
