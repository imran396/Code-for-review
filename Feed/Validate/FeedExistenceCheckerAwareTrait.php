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

namespace Sam\Feed\Validate;

/**
 * Trait FeedExistenceCheckerAwareTrait
 * @package Sam\Feed\Validate
 */
trait FeedExistenceCheckerAwareTrait
{
    /**
     * @var FeedExistenceChecker|null
     */
    protected ?FeedExistenceChecker $feedExistenceChecker = null;

    /**
     * @return FeedExistenceChecker
     */
    protected function getFeedExistenceChecker(): FeedExistenceChecker
    {
        if ($this->feedExistenceChecker === null) {
            $this->feedExistenceChecker = FeedExistenceChecker::new();
        }
        return $this->feedExistenceChecker;
    }

    /**
     * @param FeedExistenceChecker $feedExistenceChecker
     * @return static
     * @internal
     */
    public function setFeedExistenceChecker(FeedExistenceChecker $feedExistenceChecker): static
    {
        $this->feedExistenceChecker = $feedExistenceChecker;
        return $this;
    }
}
