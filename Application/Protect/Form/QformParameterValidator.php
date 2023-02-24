<?php
/**
 * SAM-5548 : Qform mandatory parameter validation
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-11-01
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Protect\Form;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\RequestParam\ParamFetcherForPostAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;

/**
 * Class FormParameterValidator
 * @package Sam\Application\Protect\Form
 */
class QformParameterValidator extends CustomizableClass
{
    use ParamFetcherForPostAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use ServerRequestReaderAwareTrait;

    public const ERR_QFORM_FORM_CONTROL = 1;
    public const ERR_QFORM_FORM_CALL_TYPE = 2;
    public const ERR_QFORM_FORM_ID = 3;
    public const ERR_QFORM_FORM_STATE = 4;
    public const ERR_QFORM_FORM_EVENT = 5;
    public const ERR_QFORM_FORM_CHECKABLE_CONTROLS = 6;
    public const WORD_CHARS_REGEX = '/[^\w\-]/';
    public const WORD_SPACE_CHARS_REGEX = '/[^\w\- ]/';

    /**
     * @var string
     */
    protected string $composeMessage = '';
    /**
     * @var array
     */
    protected array $QformErrorCodes = [
        self::ERR_QFORM_FORM_CONTROL,
        self::ERR_QFORM_FORM_CALL_TYPE,
        self::ERR_QFORM_FORM_ID,
        self::ERR_QFORM_FORM_STATE,
        self::ERR_QFORM_FORM_EVENT,
        self::ERR_QFORM_FORM_CHECKABLE_CONTROLS,
    ];

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_QFORM_FORM_CONTROL => 'Invalid form control.',
        self::ERR_QFORM_FORM_CALL_TYPE => 'Invalid form call type.',
        self::ERR_QFORM_FORM_ID => 'Invalid form id.',
        self::ERR_QFORM_FORM_STATE => 'Invalid form state.',
        self::ERR_QFORM_FORM_EVENT => 'Invalid form event.',
        self::ERR_QFORM_FORM_CHECKABLE_CONTROLS => 'Invalid form checkable controls.',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array
     */
    public function getParamLists(): array
    {
        return [
            // next are exposed at error page
            Constants\Qform::FORM_CONTROL => ['regex' => self::WORD_CHARS_REGEX, 'statusCode' => self::ERR_QFORM_FORM_CONTROL],
            Constants\Qform::FORM_CALL_TYPE => ['regex' => self::WORD_CHARS_REGEX, 'statusCode' => self::ERR_QFORM_FORM_CALL_TYPE],
            // JIC next:
            Constants\Qform::FORM_ID => ['regex' => self::WORD_CHARS_REGEX, 'statusCode' => self::ERR_QFORM_FORM_ID],
            Constants\Qform::FORM_STATE_ID => ['regex' => self::WORD_CHARS_REGEX, 'statusCode' => self::ERR_QFORM_FORM_STATE],
            Constants\Qform::FORM_EVENT => ['regex' => self::WORD_CHARS_REGEX, 'statusCode' => self::ERR_QFORM_FORM_EVENT],
            Constants\Qform::FORM_CHECKABLE_CONTROLS => ['regex' => self::WORD_SPACE_CHARS_REGEX, 'statusCode' => self::ERR_QFORM_FORM_CHECKABLE_CONTROLS],
            // Not sure regarding format of value
            // 'Qform__FormParameter',
            // 'Qform__FormUpdates' => '/[^\w-\s]/',
        ];
    }

    /**
     * Check post parameter value is valid or not
     * @return bool
     */
    public function validate(): bool
    {
        $collector = $this->getResultStatusCollector()->construct(static::ERROR_MESSAGES);

        foreach ($this->getParamLists() as $paramName => $values) {
            $paramValue = $this->getParamFetcherForPost()->getString($paramName);
            if (!empty($paramValue)) {
                $filteredParamValue = preg_replace($values['regex'], '', $paramValue);
                if ($filteredParamValue !== $paramValue) {
                    $collector->addError($values['statusCode']);
                    $this->composeErrorMessage($paramName, $paramValue, $values['regex']);
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * compose error message
     * @param string $param
     * @param string $paramValue
     * @param string $filterRegExp
     * @return void
     */
    public function composeErrorMessage(string $param, string $paramValue, string $filterRegExp): void
    {
        $this->composeMessage = 'Please attention on filtering of qform parameter'
            . composeSuffix(
                [
                    'param' => $param,
                    'value' => $paramValue,
                    'reg-exp' => $filterRegExp,
                    'remote addr' => $this->getServerRequestReader()->remoteAddr(),
                    'request uri' => $this->getServerRequestReader()->currentUrl(),
                ]
            );
    }

    /**
     * find first error message
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($this->QformErrorCodes)
            . ' ' . $this->composeMessage;
    }
}
