<?php
/**
 * SAM-9416: Decouple logic of AdvancedSearch class for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\ViewMode\Url;

/**
 * Trait ViewModeUrlBuilderCreateTrait
 * @package
 */
trait ViewModeUrlBuilderCreateTrait
{
    protected ?ViewModeUrlBuilder $viewModeUrlBuilder = null;

    /**
     * @return ViewModeUrlBuilder
     */
    protected function createViewModeUrlBuilder(): ViewModeUrlBuilder
    {
        return $this->viewModeUrlBuilder ?: ViewModeUrlBuilder::new();
    }

    /**
     * @param ViewModeUrlBuilder $viewModeUrlBuilder
     * @return $this
     * @internal
     */
    public function setViewModeUrlBuilder(ViewModeUrlBuilder $viewModeUrlBuilder): static
    {
        $this->viewModeUrlBuilder = $viewModeUrlBuilder;
        return $this;
    }
}
