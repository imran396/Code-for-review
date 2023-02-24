<?php
/**
 * SAM-7846: Refactor \Lot_Upload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Base\FileType\Validate;

/**
 * Trait FileValidatorCreateTrait
 * @package Sam\CustomField\Base\FileType\Validate
 */
trait CustomFieldFileValidatorCreateTrait
{
    /**
     * @var CustomFieldFileValidator|null
     */
    protected ?CustomFieldFileValidator $customFieldFileValidator = null;

    /**
     * @return CustomFieldFileValidator
     */
    protected function createCustomFieldFileValidator(): CustomFieldFileValidator
    {
        return $this->customFieldFileValidator ?: CustomFieldFileValidator::new();
    }

    /**
     * @param CustomFieldFileValidator|null $customFieldFileValidator
     * @return static
     * @internal
     */
    public function setCustomFieldFileValidator(?CustomFieldFileValidator $customFieldFileValidator): static
    {
        $this->customFieldFileValidator = $customFieldFileValidator;
        return $this;
    }
}
