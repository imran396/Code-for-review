<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May. 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Convert;

/**
 * Trait ConsignorCommissionFeeRangeDtoConverterCreateTrait
 * @package Sam\Consignor\Commission\Convert
 */
trait ConsignorCommissionFeeRangeDtoConverterCreateTrait
{
    /**
     * @var ConsignorCommissionFeeRangeDtoConverter|null
     */
    protected ?ConsignorCommissionFeeRangeDtoConverter $consignorCommissionFeeRangeDtoConverter = null;

    /**
     * @return ConsignorCommissionFeeRangeDtoConverter
     */
    protected function createConsignorCommissionFeeRangeDtoConverter(): ConsignorCommissionFeeRangeDtoConverter
    {
        return $this->consignorCommissionFeeRangeDtoConverter ?: ConsignorCommissionFeeRangeDtoConverter::new();
    }

    /**
     * @param ConsignorCommissionFeeRangeDtoConverter $consignorCommissionFeeRangeDtoConverter
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeRangeDtoConverter(ConsignorCommissionFeeRangeDtoConverter $consignorCommissionFeeRangeDtoConverter): static
    {
        $this->consignorCommissionFeeRangeDtoConverter = $consignorCommissionFeeRangeDtoConverter;
        return $this;
    }
}
