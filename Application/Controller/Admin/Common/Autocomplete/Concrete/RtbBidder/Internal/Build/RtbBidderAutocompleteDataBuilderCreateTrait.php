<?php
/**
 * SAM-10119: Refactor RTB bidder autocomplete
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\RtbBidder\Internal\Build;

/**
 * Trait RtbBidderAutocompleteDataBuilderCreateTrait
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\RtbBidder\Internal\Build
 */
trait RtbBidderAutocompleteDataBuilderCreateTrait
{
    /**
     * @var RtbBidderAutocompleteDataBuilder|null
     */
    protected ?RtbBidderAutocompleteDataBuilder $rtbBidderAutocompleteDataBuilder = null;

    /**
     * @return RtbBidderAutocompleteDataBuilder
     */
    protected function createRtbBidderAutocompleteDataBuilder(): RtbBidderAutocompleteDataBuilder
    {
        return $this->rtbBidderAutocompleteDataBuilder ?: RtbBidderAutocompleteDataBuilder::new();
    }

    /**
     * @param RtbBidderAutocompleteDataBuilder $rtbBidderAutocompleteDataBuilder
     * @return static
     * @internal
     */
    public function setRtbBidderAutocompleteDataBuilder(RtbBidderAutocompleteDataBuilder $rtbBidderAutocompleteDataBuilder): static
    {
        $this->rtbBidderAutocompleteDataBuilder = $rtbBidderAutocompleteDataBuilder;
        return $this;
    }
}
