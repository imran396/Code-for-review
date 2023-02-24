<?php
/**
 * SAM-4815: Lot Custom Field renderer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-02-07
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Render\Csv;

/**
 * Trait LotCustomFieldCsvRendererAwareTrait
 */
trait LotCustomFieldCsvRendererAwareTrait
{
    /**
     * @var LotCustomFieldCsvRenderer|null
     */
    protected ?LotCustomFieldCsvRenderer $lotCustomFieldCsvRenderer = null;

    /**
     * @param LotCustomFieldCsvRenderer $lotCustomFieldCsvRenderer
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotCustomFieldCsvRenderer(LotCustomFieldCsvRenderer $lotCustomFieldCsvRenderer): static
    {
        $this->lotCustomFieldCsvRenderer = $lotCustomFieldCsvRenderer;
        return $this;
    }

    /**
     * @return LotCustomFieldCsvRenderer
     */
    protected function getLotCustomFieldCsvRenderer(): LotCustomFieldCsvRenderer
    {
        if ($this->lotCustomFieldCsvRenderer === null) {
            $this->lotCustomFieldCsvRenderer = LotCustomFieldCsvRenderer::new();
        }
        return $this->lotCustomFieldCsvRenderer;
    }
}
