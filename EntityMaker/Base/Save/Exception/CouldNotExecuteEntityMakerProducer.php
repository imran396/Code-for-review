<?php
/**
 * SAM-10310: Add flag to the entity maker config-dto that indicates successfully completed validation
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Save\Exception;

use Exception;

/**
 * Class CouldNotExecuteEntityMakerProducer
 * @package Sam\EntityMaker\Base\Save\Exception
 */
class CouldNotExecuteEntityMakerProducer extends Exception
{

    public static function becauseInputDtoNotValidated(): self
    {
        return new self('Input DTO should be validated before producing entity');
    }

    public static function becauseInputDtoValidationFailed(): self
    {
        return new self('Can\'t produce entity with invalid input DTO');
    }
}
