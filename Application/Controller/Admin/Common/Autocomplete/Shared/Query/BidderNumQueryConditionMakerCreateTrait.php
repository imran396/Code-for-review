<?php
/**
 * SAM-10096: Refactor auto-completer data loading end-points for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Query;

/**
 * Trait BidderNumQueryConditionMakerCreateTrait
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Query
 */
trait BidderNumQueryConditionMakerCreateTrait
{
    /**
     * @var BidderNumQueryConditionMaker|null
     */
    protected ?BidderNumQueryConditionMaker $bidderNumQueryConditionMaker = null;

    /**
     * @return BidderNumQueryConditionMaker
     */
    protected function createBidderNumQueryConditionMaker(): BidderNumQueryConditionMaker
    {
        return $this->bidderNumQueryConditionMaker ?: BidderNumQueryConditionMaker::new();
    }

    /**
     * @param BidderNumQueryConditionMaker $bidderNumQueryConditionMaker
     * @return static
     * @internal
     */
    public function setBidderNumQueryConditionMaker(BidderNumQueryConditionMaker $bidderNumQueryConditionMaker): static
    {
        $this->bidderNumQueryConditionMaker = $bidderNumQueryConditionMaker;
        return $this;
    }
}
