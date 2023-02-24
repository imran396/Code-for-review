<?php
/**
 * SAM-4440: Refactor feed management logic to \Sam\Feed namespace
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/15/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Feed\CustomReplacement;

/**
 * Trait FeedCustomReplacementHelperAwareTrait
 * @package Sam\Feed\CustomReplacement
 */
trait FeedCustomReplacementHelperAwareTrait
{
    /**
     * @var FeedCustomReplacementHelper|null
     */
    protected ?FeedCustomReplacementHelper $feedCustomReplacementHelper = null;

    /**
     * @return FeedCustomReplacementHelper
     */
    protected function getFeedCustomReplacementHelper(): FeedCustomReplacementHelper
    {
        if ($this->feedCustomReplacementHelper === null) {
            $this->feedCustomReplacementHelper = FeedCustomReplacementHelper::new();
        }
        return $this->feedCustomReplacementHelper;
    }

    /**
     * @param FeedCustomReplacementHelper $feedCustomReplacementHelper
     * @return static
     * @internal
     */
    public function setFeedCustomReplacementHelper(FeedCustomReplacementHelper $feedCustomReplacementHelper): static
    {
        $this->feedCustomReplacementHelper = $feedCustomReplacementHelper;
        return $this;
    }
}
