<?php
/**
 * SAM-6018: Implement auction start closing date
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 06, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\StaggerClosing;

/**
 * Trait StaggerClosingHelperCreateTrait
 * @package Sam\AuctionLot\StaggerClosing
 */
trait StaggerClosingHelperCreateTrait
{
    /**
     * @var Helper|null
     */
    protected ?Helper $staggerClosingHelper = null;

    /**
     * @return Helper
     */
    protected function createStaggerClosingHelper(): Helper
    {
        return $this->staggerClosingHelper ?: Helper::new();
    }

    /**
     * @param Helper $staggerClosingHelper
     * @return static
     * @internal
     */
    public function setStaggerClosingHelper(Helper $staggerClosingHelper): static
    {
        $this->staggerClosingHelper = $staggerClosingHelper;
        return $this;
    }
}
