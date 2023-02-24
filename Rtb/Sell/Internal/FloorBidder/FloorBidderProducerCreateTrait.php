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

namespace Sam\Rtb\Sell\Internal\FloorBidder;

/**
 * Trait FloorBidderProducerCreateTrait
 * @package Sam\Rtb\Sell
 */
trait FloorBidderProducerCreateTrait
{
    /**
     * @var FloorBidderProducer|null
     */
    protected ?FloorBidderProducer $floorBidderProducer = null;

    /**
     * @return FloorBidderProducer
     */
    protected function createFloorBidderProducer(): FloorBidderProducer
    {
        return $this->floorBidderProducer ?: FloorBidderProducer::new();
    }

    /**
     * @param FloorBidderProducer $floorBidderProducer
     * @return static
     * @internal
     */
    public function setFloorBidderProducer(FloorBidderProducer $floorBidderProducer): static
    {
        $this->floorBidderProducer = $floorBidderProducer;
        return $this;
    }
}
