<?php
/**
 * SAM-9134: Refactor \User_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\User\Internal\Validate;

/**
 * Trait HeaderValidationHelperCreateTrait
 * @package Sam\Import\Csv\User\Internal\Validate
 */
trait HeaderValidationHelperCreateTrait
{
    /**
     * @var HeaderValidationHelper|null
     */
    protected ?HeaderValidationHelper $headerValidationHelper = null;

    /**
     * @return HeaderValidationHelper
     */
    protected function createHeaderValidationHelper(): HeaderValidationHelper
    {
        return $this->headerValidationHelper ?: HeaderValidationHelper::new();
    }

    /**
     * @param HeaderValidationHelper $headerValidationHelper
     * @return static
     * @internal
     */
    public function setHeaderValidationHelper(HeaderValidationHelper $headerValidationHelper): static
    {
        $this->headerValidationHelper = $headerValidationHelper;
        return $this;
    }
}
