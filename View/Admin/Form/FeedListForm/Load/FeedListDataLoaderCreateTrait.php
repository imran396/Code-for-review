<?php
/**
 * Feed List Data Loader Create Trait
 *
 * SAM-5885: Refactor feed list management at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 6, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\FeedListForm\Load;

/**
 * Trait FeedListDataLoaderCreateTrait
 */
trait FeedListDataLoaderCreateTrait
{
    protected ?FeedListDataLoader $feedListDataLoader = null;

    /**
     * @return FeedListDataLoader
     */
    protected function createFeedListDataLoader(): FeedListDataLoader
    {
        $feedListDataLoader = $this->feedListDataLoader ?: FeedListDataLoader::new();
        return $feedListDataLoader;
    }

    /**
     * @param FeedListDataLoader $feedListDataLoader
     * @return $this
     * @noinspection PhpUnused
     * @internal
     */
    public function setFeedListDataLoader(FeedListDataLoader $feedListDataLoader): static
    {
        $this->feedListDataLoader = $feedListDataLoader;
        return $this;
    }
}
