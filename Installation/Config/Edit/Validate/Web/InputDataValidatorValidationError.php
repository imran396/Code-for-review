<?php
/**
 * Immutable value object to store validation error information
 * (returned by \Sam\Installation\Config\Edit\Validate\Web\InputDataValidator after validation of input data)
 * for certain option key.
 *
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           03-15, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Installation\Config\Edit\Validate\Web;

/**
 * Class InputDataValidatorValidationError
 * @package Sam\Installation\Config
 */
final class InputDataValidatorValidationError
{
    /**
     * array of strings with error messages,
     * provided by \Sam\Installation\Config\Edit\Validate\Web\InputDataValidator
     * @var string[]
     */
    private array $messages;

    /**
     * option key value, provided by user via web interface form.
     * @var mixed
     */
    private mixed $value;

    /**
     * InputDataValidatorValidationError constructor.
     * @param mixed $value
     * @param array $messages
     */
    public function __construct(mixed $value, array $messages)
    {
        $this->value = $value;
        $this->messages = $messages;
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
    public function getMessages(): array
    {
        return $this->messages;
    }
}
