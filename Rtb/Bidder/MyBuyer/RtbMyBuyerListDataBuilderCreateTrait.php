<?php
/**
 * ${TICKET}
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Янв. 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Bidder\MyBuyer;

/**
 * Trait RtbMyBuyerListDataBuilderCreateTrait
 * @package Sam\Rtb\Bidder\MyBuyer
 */
trait RtbMyBuyerListDataBuilderCreateTrait
{
    /**
     * @var RtbMyBuyerListDataBuilder|null
     */
    protected ?RtbMyBuyerListDataBuilder $rtbMyBuyerListDataBuilder = null;

    /**
     * @return RtbMyBuyerListDataBuilder
     */
    protected function createRtbMyBuyerListDataBuilder(): RtbMyBuyerListDataBuilder
    {
        return $this->rtbMyBuyerListDataBuilder ?: RtbMyBuyerListDataBuilder::new();
    }

    /**
     * @param RtbMyBuyerListDataBuilder $rtbMyBuyerListDataBuilder
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setRtbMyBuyerListDataBuilder(RtbMyBuyerListDataBuilder $rtbMyBuyerListDataBuilder): static
    {
        $this->rtbMyBuyerListDataBuilder = $rtbMyBuyerListDataBuilder;
        return $this;
    }
}
