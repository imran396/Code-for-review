<?php
/**
 * SAM-5750: Rtb lot preview data builder
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Lot\Preview;

/**
 * Trait RtbLotPreviewDataBuilderCreateTrait
 * @package Sam\Rtb\Lot\Preview
 */
trait RtbLotPreviewDataBuilderCreateTrait
{
    /**
     * @var RtbLotPreviewDataBuilder|null
     */
    protected ?RtbLotPreviewDataBuilder $rtbLotPreviewDataBuilder = null;

    /**
     * @return RtbLotPreviewDataBuilder
     */
    protected function createRtbLotPreviewDataBuilder(): RtbLotPreviewDataBuilder
    {
        return $this->rtbLotPreviewDataBuilder ?: RtbLotPreviewDataBuilder::new();
    }

    /**
     * @param RtbLotPreviewDataBuilder $rtbLotPreviewDataBuilder
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setRtbLotPreviewDataBuilder(RtbLotPreviewDataBuilder $rtbLotPreviewDataBuilder): static
    {
        $this->rtbLotPreviewDataBuilder = $rtbLotPreviewDataBuilder;
        return $this;
    }
}
