<?php
/**
 * SAM-5306: Local installation correctness check
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 27, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Validate\Exceptions;

use Exception;

/**
 * Exception OptionValueCheckerExistenceException
 * @package Sam\Installation\Config
 */
class OptionValueCheckerExistenceException extends Exception
{
    /**
     * @param string $validationType
     * @return self
     */
    public static function create(string $validationType): self
    {
        return new self(sprintf('Checker for "%s" validation type doesn`t exist', $validationType));
    }
}
