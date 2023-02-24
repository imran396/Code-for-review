<?php
/**
 * SAM-7661: Settlement Printable & viewable version to the new layout Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           03-15, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Printable\Internal\Common;

/**
 * Trait CommonRendererAwareTrait
 * @package Sam\Settlement\Printable
 */
trait CommonRendererAwareTrait
{
    protected ?CommonRenderer $commonRenderer = null;

    /**
     * @return CommonRenderer
     */
    protected function getCommonRenderer(): CommonRenderer
    {
        if ($this->commonRenderer === null) {
            $this->commonRenderer = CommonRenderer::new();
        }
        return $this->commonRenderer;
    }

    /**
     * @param CommonRenderer $commonRenderer
     * @return $this
     * @internal
     */
    public function setCommonRenderer(CommonRenderer $commonRenderer): static
    {
        $this->commonRenderer = $commonRenderer;
        return $this;
    }
}
