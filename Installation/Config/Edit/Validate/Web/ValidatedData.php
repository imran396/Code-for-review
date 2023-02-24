<?php
/**
 * Immutable value object with validation results for single config option.
 *
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           03-28, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Validate\Web;

/**
 * Class ValidatedData
 * @package Sam\Installation\Config
 */
final class ValidatedData
{
    /**
     * Validated value.
     * @var mixed
     */
    protected mixed $value;

    /**
     * Array with validation result messages or statuses.
     * @var string[]
     */
    protected array $validationResults = [];

    public function __construct(mixed $value, array $validationResults)
    {
        $this->value = $value;
        $this->validationResults = $validationResults;
    }

    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @return string[]
     */
    public function getValidationResults(): array
    {
        return $this->validationResults;
    }
}
