<?php
/**
 * Home Dashboard Renderer Create Trait
 *
 * SAM-5891: Admin - Dashboard - lazy loading
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 19, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\HomeDashboardForm\Render;


/**
 * Trait HomeDashboardRendererCreateTrait
 * @package Sam\View\Admin\Form\HomeDashboardForm\Render
 */
trait HomeDashboardRendererCreateTrait
{
    protected ?HomeDashboardRenderer $homeDashboardRenderer = null;

    /**
     * @return HomeDashboardRenderer
     */
    protected function createHomeDashboardRenderer(): HomeDashboardRenderer
    {
        return $this->homeDashboardRenderer ?: HomeDashboardRenderer::new();
    }

    /**
     * @param HomeDashboardRenderer $homeDashboardRenderer
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setHomeDashboardRenderer(HomeDashboardRenderer $homeDashboardRenderer): static
    {
        $this->homeDashboardRenderer = $homeDashboardRenderer;
        return $this;
    }
}
