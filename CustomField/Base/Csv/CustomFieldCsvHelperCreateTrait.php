<?php
/**
 * SAM-9134: Refactor \User_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Base\Csv;

/**
 * Trait CustomFieldCsvHelperCreateTrait
 * @package Sam\CustomField\Base\Csv
 */
trait CustomFieldCsvHelperCreateTrait
{
    /**
     * @var CustomFieldCsvHelper|null
     */
    protected ?CustomFieldCsvHelper $customFieldCsvHelper = null;

    /**
     * @return CustomFieldCsvHelper
     */
    protected function createCustomFieldCsvHelper(): CustomFieldCsvHelper
    {
        return $this->customFieldCsvHelper ?: CustomFieldCsvHelper::new();
    }

    /**
     * @param CustomFieldCsvHelper $customFieldCsvHelper
     * @return static
     * @internal
     */
    public function setCustomFieldCsvHelper(CustomFieldCsvHelper $customFieldCsvHelper): static
    {
        $this->customFieldCsvHelper = $customFieldCsvHelper;
        return $this;
    }
}
