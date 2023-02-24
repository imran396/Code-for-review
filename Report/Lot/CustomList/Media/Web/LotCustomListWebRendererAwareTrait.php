<?php
/**
 * SAM-4629: Refactor custom lot report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 11, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\CustomList\Media\Web;

/**
 * Trait LotCustomListWebRendererAwareTrait
 * @package Sam\Report\Lot\CustomList\Media\Web
 */
trait LotCustomListWebRendererAwareTrait
{
    protected ?LotCustomListWebRenderer $lotCustomListWebRenderer = null;

    /**
     * @return LotCustomListWebRenderer
     */
    protected function getLotCustomListWebRenderer(): LotCustomListWebRenderer
    {
        if ($this->lotCustomListWebRenderer === null) {
            $this->lotCustomListWebRenderer = LotCustomListWebRenderer::new();
        }
        return $this->lotCustomListWebRenderer;
    }

    /**
     * @param LotCustomListWebRenderer $lotCustomListWebRenderer
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotCustomListWebRenderer(LotCustomListWebRenderer $lotCustomListWebRenderer): static
    {
        $this->lotCustomListWebRenderer = $lotCustomListWebRenderer;
        return $this;
    }
}
