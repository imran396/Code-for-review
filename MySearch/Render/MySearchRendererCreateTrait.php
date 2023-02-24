<?php
/**
 * SAM-6473: Move "my_search" table related logic to separate module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\MySearch\Render;


/**
 * Trait MySearchRendererCreateTrait
 * @package Sam\MySearch\Render
 */
trait MySearchRendererCreateTrait
{
    /**
     * @var MySearchRenderer|null
     */
    protected ?MySearchRenderer $mySearchRenderer = null;

    /**
     * @return MySearchRenderer
     */
    protected function createMySearchRenderer(): MySearchRenderer
    {
        return $this->mySearchRenderer ?: MySearchRenderer::new();
    }

    /**
     * @param MySearchRenderer $mySearchRenderer
     * @return static
     * @internal
     */
    public function setMySearchRenderer(MySearchRenderer $mySearchRenderer): static
    {
        $this->mySearchRenderer = $mySearchRenderer;
        return $this;
    }
}
