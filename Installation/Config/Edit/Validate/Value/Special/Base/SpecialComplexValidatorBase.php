<?php
/**
 * Validator base class for special-complex constraint check
 *
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           3/30/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Validate\Value\Special\Base;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Installation\Config\Edit\Meta\Descriptor\Descriptor;

abstract class SpecialComplexValidatorBase extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    /**
     * Validate actual value loaded from config
     * @param Descriptor[] $descriptors
     * @return bool
     * @noinspection PhpUnusedParameterInspection
     */
    public function validateForLoad(array $descriptors): bool
    {
        return true;
    }

    /**
     * Validate before save
     * @param Descriptor[] $descriptors
     * @param array $data input data, DELIMITER_GENERAL_OPTION_KEY in key, string in value
     * @return bool
     * @noinspection PhpUnusedParameterInspection
     */
    public function validateForSave(array $descriptors, array $data): bool
    {
        return true;
    }

    /**
     * Validate before delete
     * @param Descriptor[] $descriptors
     * @return bool
     * @noinspection PhpUnusedParameterInspection
     */
    public function validateForDelete(array $descriptors): bool
    {
        return true;
    }

    /**
     * @return string[]
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * @return ResultStatus[]
     */
    public function errorStatuses(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }
}
