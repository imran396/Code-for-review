<?php
/**
 * SAM-8891: Auction entity-maker - Extract sale# validation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\SaleNo\Internal\Validate;


trait SaleNoValidatorCreateTrait
{
    /**
     * @var SaleNoValidator|null
     */
    protected ?SaleNoValidator $saleNoValidator = null;

    /**
     * @return SaleNoValidator
     */
    protected function createSaleNoValidator(): SaleNoValidator
    {
        return $this->saleNoValidator ?: SaleNoValidator::new();
    }

    /**
     * @param SaleNoValidator $saleNoValidator
     * @return $this
     * @internal
     */
    public function setItemNoValidator(SaleNoValidator $saleNoValidator): static
    {
        $this->saleNoValidator = $saleNoValidator;
        return $this;
    }
}
