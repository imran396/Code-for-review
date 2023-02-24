<?php
/**
 * SAM
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           март 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\LotImage\Validate;

/**
 * Trait LotImageUploadValidatorCreateTrait
 * @package Sam\Application\Controller\Admin\LotImage\Validate
 */
trait LotImageUploadValidatorCreateTrait
{
    /**
     * @var LotImageUploadValidator|null
     */
    protected ?LotImageUploadValidator $lotImageUploadValidator = null;

    /**
     * @return LotImageUploadValidator
     */
    protected function createLotImageUploadValidator(): LotImageUploadValidator
    {
        return $this->lotImageUploadValidator ?: LotImageUploadValidator::new();
    }

    /**
     * @param LotImageUploadValidator|null $lotImageUploadValidator
     * @return static
     * @internal
     */
    public function setLotImageUploadValidator(?LotImageUploadValidator $lotImageUploadValidator): static
    {
        $this->lotImageUploadValidator = $lotImageUploadValidator;
        return $this;
    }
}
