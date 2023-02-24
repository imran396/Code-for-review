<?php
/**
 * Home Dashboard Data Loader Create Trait
 *
 * SAM-5599: Refactor data loader for Home Dashboard at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 20, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\HomeDashboardForm\Load;

/**
 * Trait HomeDashboardDataLoaderCreateTrait
 */
trait HomeDashboardDataLoaderCreateTrait
{
    protected ?HomeDashboardDataLoader $homeDashboardDataLoader = null;

    /**
     * @return HomeDashboardDataLoader
     */
    protected function createHomeDashboardDataLoader(): HomeDashboardDataLoader
    {
        $homeDashboardDataLoader = $this->homeDashboardDataLoader ?: HomeDashboardDataLoader::new();
        return $homeDashboardDataLoader;
    }

    /**
     * @param HomeDashboardDataLoader $homeDashboardDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setHomeDashboardDataLoader(HomeDashboardDataLoader $homeDashboardDataLoader): static
    {
        $this->homeDashboardDataLoader = $homeDashboardDataLoader;
        return $this;
    }
}
