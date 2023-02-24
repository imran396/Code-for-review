<?php
/**
 * SAM-1700: Walmart - Bulk image upload enhancements
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\LotImage\Validate;

/**
 * Trait LotImageRemoteUploadValidatorCreateTrait
 * @package Sam\Application\Controller\Admin\LotImage\Validate
 */
trait LotImageRemoteUploadValidatorCreateTrait
{
    /**
     * @var LotImageRemoteUploadValidator|null
     */
    protected ?LotImageRemoteUploadValidator $lotImageRemoteUploadValidator = null;

    /**
     * @return LotImageRemoteUploadValidator
     */
    protected function createLotImageRemoteUploadValidator(): LotImageRemoteUploadValidator
    {
        return $this->lotImageRemoteUploadValidator ?: LotImageRemoteUploadValidator::new();
    }

    /**
     * @param LotImageRemoteUploadValidator $lotImageRemoteUploadValidator
     * @return static
     * @internal
     */
    public function setLotImageRemoteUploadValidator(LotImageRemoteUploadValidator $lotImageRemoteUploadValidator): static
    {
        $this->lotImageRemoteUploadValidator = $lotImageRemoteUploadValidator;
        return $this;
    }
}
