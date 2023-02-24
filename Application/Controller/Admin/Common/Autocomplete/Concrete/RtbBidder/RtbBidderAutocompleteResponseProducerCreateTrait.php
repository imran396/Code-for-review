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

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\RtbBidder;

/**
 * Trait RtbBidderAutocompleteResponseProducerCreateTrait
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\RtbBidder
 */
trait RtbBidderAutocompleteResponseProducerCreateTrait
{
    /**
     * @var RtbBidderAutocompleteResponseProducer|null
     */
    protected ?RtbBidderAutocompleteResponseProducer $rtbBidderAutocompleteResponseProducer = null;

    /**
     * @return RtbBidderAutocompleteResponseProducer
     */
    protected function createRtbBidderAutocompleteResponseProducer(): RtbBidderAutocompleteResponseProducer
    {
        return $this->rtbBidderAutocompleteResponseProducer ?: RtbBidderAutocompleteResponseProducer::new();
    }

    /**
     * @param RtbBidderAutocompleteResponseProducer $rtbBidderAutocompleteResponseProducer
     * @return static
     * @internal
     */
    public function setRtbBidderAutocompleteResponseProducer(RtbBidderAutocompleteResponseProducer $rtbBidderAutocompleteResponseProducer): static
    {
        $this->rtbBidderAutocompleteResponseProducer = $rtbBidderAutocompleteResponseProducer;
        return $this;
    }
}
