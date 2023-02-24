<?php
/**
 * SAM-8007: Invoice and settlement layout adjustments for custom fields
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           June 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\CustomField\Lot;

/**
 * Trait SettlementLotCustomFieldRendererCreateTrait
 * @package Sam\Settlement\CustomField\Lot
 */
trait SettlementLotCustomFieldRendererCreateTrait
{
    protected ?SettlementLotCustomFieldRenderer $settlementLotCustomFieldRenderer = null;

    /**
     * @return SettlementLotCustomFieldRenderer
     */
    protected function createSettlementLotCustomFieldRenderer(): SettlementLotCustomFieldRenderer
    {
        return $this->settlementLotCustomFieldRenderer ?: SettlementLotCustomFieldRenderer::new();
    }

    /**
     * @param SettlementLotCustomFieldRenderer $settlementLotCustomFieldRenderer
     * @return $this
     * @internal
     */
    public function setSettlementLotCustomFieldRenderer(SettlementLotCustomFieldRenderer $settlementLotCustomFieldRenderer): static
    {
        $this->settlementLotCustomFieldRenderer = $settlementLotCustomFieldRenderer;
        return $this;
    }
}
