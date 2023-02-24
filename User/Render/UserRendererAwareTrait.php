<?php
/**
 * Trait for User Renderer
 *
 * SAM-4140: User Renderer class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 9, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Render;

/**
 * Trait UserRendererAwareTrait
 * @package Sam\User\Render
 */
trait UserRendererAwareTrait
{
    protected ?UserRenderer $userRenderer = null;

    /**
     * @return UserRenderer
     */
    protected function getUserRenderer(): UserRenderer
    {
        if ($this->userRenderer === null) {
            $this->userRenderer = UserRenderer::new();
        }
        return $this->userRenderer;
    }

    /**
     * @param UserRenderer $userRenderer
     * @return static
     * @internal
     */
    public function setUserRenderer(UserRenderer $userRenderer): static
    {
        $this->userRenderer = $userRenderer;
        return $this;
    }
}
