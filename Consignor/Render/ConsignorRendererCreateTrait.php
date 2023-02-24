<?php
/**
 * SAM-9669: Refactor \Qform_LotEditHelper
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Render;

/**
 * Trait ConsignorRendererCreateTrait
 * @package Sam\Consignor\Render
 */
trait ConsignorRendererCreateTrait
{
    protected ?ConsignorRenderer $consignorRenderer = null;

    /**
     * @return ConsignorRenderer
     */
    protected function createConsignorRenderer(): ConsignorRenderer
    {
        return $this->consignorRenderer ?: ConsignorRenderer::new();
    }

    /**
     * @param ConsignorRenderer $consignorRenderer
     * @return static
     * @internal
     */
    public function setConsignorRenderer(ConsignorRenderer $consignorRenderer): static
    {
        $this->consignorRenderer = $consignorRenderer;
        return $this;
    }
}
