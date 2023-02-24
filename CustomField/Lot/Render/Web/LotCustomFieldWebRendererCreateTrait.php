<?php
/**
 * SAM-6592: Move lot item custom field logic to \Sam\CustomField\Lot namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 12, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Render\Web;


/**
 * Trait LotCustomFieldWebRendererCreateTrait
 * @package Sam\CustomField\Lot\Render\Web
 */
trait LotCustomFieldWebRendererCreateTrait
{
    /**
     * @var LotCustomFieldWebRenderer|null
     */
    protected ?LotCustomFieldWebRenderer $lotCustomFieldWebRenderer = null;

    /**
     * @return LotCustomFieldWebRenderer
     */
    protected function createLotCustomFieldWebRenderer(): LotCustomFieldWebRenderer
    {
        return $this->lotCustomFieldWebRenderer ?: LotCustomFieldWebRenderer::new();
    }

    /**
     * @param LotCustomFieldWebRenderer $lotCustomFieldWebRenderer
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotCustomFieldWebRenderer(LotCustomFieldWebRenderer $lotCustomFieldWebRenderer): static
    {
        $this->lotCustomFieldWebRenderer = $lotCustomFieldWebRenderer;
        return $this;
    }
}
