<?php
/**
 * Trait for Account Renderer
 *
 * SAM-4121: Account fields renderer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 28, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Render;

/**
 * Trait AccountRendererAwareTrait
 * @package Sam\Account\Render
 */
trait AccountRendererAwareTrait
{
    protected ?AccountRenderer $accountRenderer = null;

    /**
     * @return AccountRenderer
     */
    protected function getAccountRenderer(): AccountRenderer
    {
        if ($this->accountRenderer === null) {
            $this->accountRenderer = AccountRenderer::new();
        }
        return $this->accountRenderer;
    }

    /**
     * @param AccountRenderer $accountRenderer
     * @return static
     * @internal
     */
    public function setAccountRenderer(AccountRenderer $accountRenderer): static
    {
        $this->accountRenderer = $accountRenderer;
        return $this;
    }
}
