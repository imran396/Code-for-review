<?php
/**
 * SAM-6799: Refactor consignor pdf report
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Consignor\Pdf\Internal\Content;

/**
 * Trait ConsignorPdfBuilderCreateTrait
 * @package Sam\Report\Consignor\Pdf\Internal\Content
 */
trait ConsignorPdfBuilderCreateTrait
{
    protected ?ConsignorPdfBuilder $consignorPdfBuilder = null;

    /**
     * @return ConsignorPdfBuilder
     */
    protected function createConsignorPdfBuilder(): ConsignorPdfBuilder
    {
        return $this->consignorPdfBuilder ?: new ConsignorPdfBuilder('');
    }

    /**
     * @param ConsignorPdfBuilder $consignorPdfBuilder
     * @return static
     * @internal
     */
    public function setConsignorPdfBuilder(ConsignorPdfBuilder $consignorPdfBuilder): static
    {
        $this->consignorPdfBuilder = $consignorPdfBuilder;
        return $this;
    }
}
