<?php
/**
 * SAM-6780: Move sections' logic to separate Panel classes at Manage auction lots page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\Filtering\LotCategory;

/**
 * Trait LotCategoryFilteringOptionProviderCreateTrait
 */
trait LotCategoryFilteringOptionProviderCreateTrait
{
    protected ?LotCategoryFilteringOptionProvider $lotCategoryFilteringContentProvider = null;

    /**
     * @return LotCategoryFilteringOptionProvider
     */
    protected function createLotCategoryFilteringOptionProvider(): LotCategoryFilteringOptionProvider
    {
        return $this->lotCategoryFilteringContentProvider ?: LotCategoryFilteringOptionProvider::new();
    }

    /**
     * @param LotCategoryFilteringOptionProvider $lotCategoryFilteringContentProvider
     * @return $this
     * @internal
     */
    public function setLotCategoryFilteringOptionProvider(LotCategoryFilteringOptionProvider $lotCategoryFilteringContentProvider): static
    {
        $this->lotCategoryFilteringContentProvider = $lotCategoryFilteringContentProvider;
        return $this;
    }
}
