<?php
/**
 * SAM-7984: Adjustments for Settlement printable with responsive layout [dev only]
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           05-01, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Printable\Internal\LotsTable;

/**
 * Trait LotsTableItemRendererCreateTrait
 * @package Sam\Settlement\Printable\Internal\LotsTable
 */
trait LotsTableItemRendererCreateTrait
{
    protected ?LotsTableItemRenderer $lotsTableItemRenderer = null;

    /**
     * @return LotsTableItemRenderer
     */
    protected function createLotsTableItemRenderer(): LotsTableItemRenderer
    {
        return $this->lotsTableItemRenderer ?: LotsTableItemRenderer::new();
    }

    /**
     * @param LotsTableItemRenderer $lotsTableItemRenderer
     * @return $this
     * @internal
     */
    public function setLotsTableItemRenderer(LotsTableItemRenderer $lotsTableItemRenderer): static
    {
        $this->lotsTableItemRenderer = $lotsTableItemRenderer;
        return $this;
    }
}
