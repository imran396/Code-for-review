<?php
/**
 * SAM-6308: Refactor custom field management to separate modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Dto;

/**
 * Trait FormDataReaderCreateTrait
 * @package Sam\Core\Dto
 */
trait FormDataReaderCreateTrait
{
    /**
     * @var FormDataReader|null
     */
    protected ?FormDataReader $formDataReader = null;

    /**
     * @return FormDataReader
     */
    protected function createFormDataReader(): FormDataReader
    {
        return $this->formDataReader ?: FormDataReader::new();
    }

    /**
     * @param FormDataReader $formDataReader
     * @return static
     * @internal
     */
    public function setFormDataReader(FormDataReader $formDataReader): static
    {
        $this->formDataReader = $formDataReader;
        return $this;
    }
}
