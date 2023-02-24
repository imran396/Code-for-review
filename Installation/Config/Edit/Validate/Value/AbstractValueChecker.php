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

namespace Sam\Installation\Config\Edit\Validate\Value;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Validate\Exceptions\OptionValueCheckerExistenceException;

/**
 * Class AbstractValueChecker
 * @package Sam\Installation\Config
 */
abstract class AbstractValueChecker extends CustomizableClass
{
    /**
     * @param mixed $value
     * @param string $constraintType
     * @param mixed $constraintArguments
     * @return bool
     * @throws OptionValueCheckerExistenceException
     */
    public function isValid(mixed $value, string $constraintType, mixed $constraintArguments = null): bool
    {
        $checker = $this->getChecker($constraintType);
        if ($checker === null) {
            throw OptionValueCheckerExistenceException::create($constraintType);
        }
        return $checker($value, $constraintArguments);
    }

    /**
     * @param string $constraintType
     * @return bool
     */
    public function hasChecker(string $constraintType): bool
    {
        $checker = $this->getChecker($constraintType);
        return !empty($checker);
    }

    /**
     * @param string $constraintType
     * @return array|null
     */
    protected function getChecker(string $constraintType): ?array
    {
        return $this->getCheckerMap()[$constraintType] ?? null;
    }

    /**
     * Get array of callable checker functions
     * @return array
     */
    abstract protected function getCheckerMap(): array;
}
