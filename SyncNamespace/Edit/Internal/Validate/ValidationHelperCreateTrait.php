<?php
/**
 * SAM-5826: Decouple SyncNamespace Editor to classes and add unit tests
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 16, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\SyncNamespace\Edit\Internal\Validate;

/**
 * Trait ValidationHelperCreateTrait
 * @package
 */
trait ValidationHelperCreateTrait
{
    protected ?ValidationHelper $validationHelper = null;

    /**
     * @return ValidationHelper
     */
    protected function createValidationHelper(): ValidationHelper
    {
        return $this->validationHelper ?: ValidationHelper::new();
    }

    /**
     * @param ValidationHelper $validationHelper
     * @return $this
     * @internal
     */
    public function setValidationHelper(ValidationHelper $validationHelper): static
    {
        $this->validationHelper = $validationHelper;
        return $this;
    }
}
