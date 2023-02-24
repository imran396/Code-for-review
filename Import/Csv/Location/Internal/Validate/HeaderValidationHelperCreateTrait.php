<?php
/**
 * SAM-10435: Add csv quick upload form to locations page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Location\Internal\Validate;

/**
 * Trait HeaderValidationHelperCreateTrait
 * @package Sam\Import\Csv\Location\Internal\Validate
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
