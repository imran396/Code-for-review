<?php
/**
 * SAM-5454: Extract data loading from form classes
 * SAM-4697: Feed entity editor
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Feed\Edit\Internal\Validate;

/**
 * Trait ValidationHelperCreateTrait
 * @package Sam\Feed\Edit
 */
trait ValidationHelperCreateTrait
{
    /**
     * @var ValidationHelper|null
     */
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
