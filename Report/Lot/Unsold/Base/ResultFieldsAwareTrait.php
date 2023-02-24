<?php
/**
 * SAM-4687: Refactor "Unsold Lots" report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/18/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\Unsold\Base;

use Sam\Core\Data\TypeCast\ArrayCast;

/**
 * Trait ResultFieldsAwareTrait
 * @package Sam\Report\Lot\Unsold\Base
 */
trait ResultFieldsAwareTrait
{
    /**
     * @var string[]
     */
    protected array $resultFields = [];

    /**
     * @return string[]
     */
    public function getResultFields(): array
    {
        return $this->resultFields;
    }

    /**
     * @param string[] $resultFields
     * @return static
     */
    public function setResultFields(array $resultFields): static
    {
        $this->resultFields = ArrayCast::makeStringArray($resultFields);
        return $this;
    }
}
