<?php
/**
 * Immutable value object to store input web-ready validation information. We use it for rendering
 * input option validation messages, input post values.
 * Used in app/admin/views/scripts/installation-setting/edit.tpl.php
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


namespace Sam\Installation\Config\Edit\Render\WebData\OptionInput\Validation;


/**
 * Class OptionInputValidationWebData
 * @package Sam\Installation\Config
 */
final class OptionInputValidationWebData
{
    /**
     * Validation error status code
     * (0 - not set, 1 - validation error, 2 - valid)
     * @var int
     */
    private int $validationStatus;

    /**
     * Error description text.
     * @var array
     */
    private array $errorText;

    /**
     * Regular (non object, itn|float|string) input value, passed from Post request.
     * @var mixed
     */
    private mixed $postValue;

    /**
     * OptionInputValidationWebData constructor.
     * @param int $validationStatus
     * @param mixed $postValue
     * @param array $errorText
     */
    public function __construct(int $validationStatus, mixed $postValue, array $errorText)
    {
        $this->validationStatus = $validationStatus;
        $this->errorText = $errorText;
        $this->postValue = $postValue;
    }

    /**
     * @return int
     */
    public function getValidationStatus(): int
    {
        return $this->validationStatus;
    }

    /**
     * @return array
     */
    public function getErrorText(): array
    {
        return $this->errorText;
    }

    /**
     * @return mixed
     */
    public function getPostValue(): mixed
    {
        return $this->postValue;
    }
}
