<?php
/**
 * Web component, which implements controls related logic for different phone formats
 * SAM-1474: Phone number editing and validation improvements
 * SAM-1526: Structured Phone number editing improvements
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           2013
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Notes: we validate component before rendering, so we could show problem before form submitting.
 */

namespace Sam\PhoneNumber\Qform;

use QControl;
use QForm;
use QLabel;
use QListBox;
use QPanel;
use QTextBox;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Location\PhoneCountry\PhoneCountryHelperAwareTrait;
use Sam\PhoneNumber\PhoneNumberHelperAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Core\Constants;

/**
 * Class PhoneNumberComponent
 * @package Sam\PhoneNumber\Qform
 */
class PhoneNumberComponent extends QPanel
{
    use ConfigRepositoryAwareTrait;
    use PhoneCountryHelperAwareTrait;
    use PhoneNumberHelperAwareTrait;
    use SettingsManagerAwareTrait;
    use TranslatorAwareTrait;

    public const PHONE_NUMBER_FORMAT_SIMPLE = 'simple';
    public const PHONE_NUMBER_FORMAT_STRUCTURED = 'structured';

    public const TYPE_PHONE = 'phone';
    public const TYPE_FAX = 'fax';

    public const PANEL_PERSONAL = 'personal';
    public const PANEL_BILLING = 'billing';
    public const PANEL_SHIPPING = 'shipping';

    public const CONTROL_COUNTRY_CODE = 1;
    public const CONTROL_PHONE_NUMBER = 2;
    public const CONTROL_CONTAINER = 3;

    /**
     * Set it to initialize controls (for view and edit mode)
     */
    protected string $phoneNumber = '';
    /**
     * Define phone number format
     * @var string
     */
    protected string $phoneNumberFormat = self::PHONE_NUMBER_FORMAT_STRUCTURED;
    /**
     * Check phone is entered
     * @var bool
     */
    protected bool $isRequired = false;
    /**
     * Type of the component - phone (def) or fax (for validation messages)
     * @var string
     */
    protected string $type = self::TYPE_PHONE;
    /**
     * Define panel type where controls are related
     * @var string
     */
    protected string $panel = self::PANEL_PERSONAL;
    /**
     * True (def) for rendering at public side, false - admin side
     * @var bool
     */
    protected bool $isPublic = true;
    /**
     * False - for view mode of component
     * @var bool
     */
    protected bool $isAllowEdit = true;
    /**
     * @var int
     */
    protected int $defaultCountryCode;
    /**
     * @var bool
     */
    protected bool $isHideCountryCodeSelection = false;

    /**
     * @var QListBox|null
     */
    protected ?QListBox $lstCountryCode = null;
    /**
     * @var QTextBox|null
     */
    protected ?QTextBox $txtPhoneNumber = null;
    /**
     * @var QLabel|null
     */
    protected ?QLabel $lblError = null;
    /**
     * @var bool
     */
    protected bool $renderError = false;

    /** @var string[] */
    protected array $controlIdPrefixes = [
        self::CONTROL_COUNTRY_CODE => 'cc',
        self::CONTROL_PHONE_NUMBER => 'pn',
        self::CONTROL_CONTAINER => 'co',
    ];
    /** @var string[] */
    protected array $translations = [
        'USER_PHONE' => 'Phone',
        'USER_BILL_PHONE' => 'Phone',
        'USER_BILL_FAX' => 'Fax',
        'USER_SHIP_PHONE' => 'Phone',
        'USER_SHIP_FAX' => 'Fax',
        'SIGNUP_ERR_REQUIRED' => 'Required',
        'USER_PHONE_NUMBER' => 'phone number',
        'USER_ERR_PHONE_NUMBER_REQUIRED' => 'Phone number required',
        'USER_ERR_FAX_NUMBER_REQUIRED' => 'Fax number required',
        'USER_ERR_PHONE_COUNTRY_CODE_REQUIRED' => 'Country code required',
        'USER_ERR_PHONE_NUMBER_INVALID' => 'Invalid phone number',
        'USER_ERR_FAX_NUMBER_INVALID' => 'Invalid fax number',
    ];
    /**
     * @var string
     */
    private string $outputCache = '';

    /**
     * WebComponent constructor.
     * @param QForm|QControl $parentObject
     * @param string $controlId
     */
    public function __construct($parentObject, string $controlId)
    {
        $sm = $this->getSettingsManager();
        $this->defaultCountryCode = (int)$sm->getForMain(Constants\Setting::DEFAULT_COUNTRY_CODE);
        $this->isHideCountryCodeSelection = (bool)$sm->getForMain(Constants\Setting::HIDE_COUNTRY_CODE_SELECTION);
        $this->phoneNumberFormat = $this->cfg()->get('core->user->phoneNumberFormat');
        parent::__construct($parentObject, $controlId);
        $this->createControls();
        $this->initControls();
    }

    /**
     * Create controls, if they still has not been created
     */
    public function createControls(): void
    {
        if ($this->phoneNumberFormat === self::PHONE_NUMBER_FORMAT_SIMPLE) {
            $this->createTxtPhoneNumber();
        } elseif ($this->phoneNumberFormat === self::PHONE_NUMBER_FORMAT_STRUCTURED) {
            $this->createStructuredControls();
        }
    }

    /**
     * Initialize controls with values
     */
    public function initControls(): void
    {
        if ($this->phoneNumberFormat === self::PHONE_NUMBER_FORMAT_SIMPLE) {
            $this->txtPhoneNumber->Text = $this->phoneNumber;
        } elseif ($this->phoneNumberFormat === self::PHONE_NUMBER_FORMAT_STRUCTURED) {
            if (empty($this->phoneNumber)) {
                $this->lstCountryCode->SelectedValue = $this->defaultCountryCode;
                $this->txtPhoneNumber->Text = '';
            } else {
                $phoneNumbers = $this->getPhoneNumberHelper()->getParts($this->phoneNumber);
                $this->lstCountryCode->SelectedValue = $phoneNumbers[0];
                $this->txtPhoneNumber->Text = $phoneNumbers[1];
            }
        }
        $this->initTranslation();
    }

    /**
     * Render controls
     * @param bool $isDisplayOutput
     * @return string
     */
    public function render($isDisplayOutput = true): string
    {
        if ($this->phoneNumber !== '') {
            $this->validate();
        }
        $output = $this->phoneNumberFormat === self::PHONE_NUMBER_FORMAT_SIMPLE
            ? $this->getHtmlForSimpleFormat()
            : $this->getHtmlForStructuredFormat();
        if ($isDisplayOutput) {
            print($output);
            return '';
        }

        return $output;
    }

    /**
     * Call and cache produced rendering output, so we could render controls only once.
     * @return string
     */
    public function renderCached(): string
    {
        if ($this->outputCache === '') {
            $this->outputCache = $this->render(false);
        }
        return $this->outputCache;
    }

    /**
     * Drop cached value and call rendering with caching again.
     * @return string
     */
    public function renderFresh(): string
    {
        return $this->dropCache()->renderCached();
    }

    /**
     * Empty cache.
     * @return $this
     */
    protected function dropCache(): static
    {
        $this->outputCache = '';
        return $this;
    }

    /**
     * Render label for phone controls
     * @param bool $isDisplayOutput
     * @return string
     */
    public function renderLabel(bool $isDisplayOutput = true): string
    {
        $output = '';
        if ($this->panel === self::PANEL_PERSONAL) {
            $output = $this->translations['USER_PHONE'];
        } elseif ($this->panel === self::PANEL_BILLING) {
            $output = $this->type === self::TYPE_PHONE
                ? $this->translations['USER_BILL_PHONE']
                : $this->translations['USER_BILL_FAX'];
        } elseif ($this->panel === self::PANEL_SHIPPING) {
            $output = $this->type === self::TYPE_PHONE
                ? $this->translations['USER_SHIP_PHONE']
                : $this->translations['USER_SHIP_FAX'];
        }
        if ($isDisplayOutput) {
            print($output);
            return '';
        }

        return $output;
    }

    /**
     * YV, 2020-09, SAM-6461: We should create PhoneNumberComponentValidator and put all related with validation logic to it.
     * Validate phone number entered in controls
     * Note: if number is not required, we will check if it is valid, only if it is entered (actual for fax numbers)
     * @return bool
     */
    public function validate(): bool
    {
        $isValid = false;
        if ($this->phoneNumberFormat === self::PHONE_NUMBER_FORMAT_SIMPLE) {
            $isValid = $this->validateForSimpleFormat();
        } elseif ($this->phoneNumberFormat === self::PHONE_NUMBER_FORMAT_STRUCTURED) {
            $isValid = $this->validateForStructuredFormat();
        }
        return $isValid;
    }

    public function getWarning(): ?string
    {
        return $this->lblError->Text;
    }

    /**
     * Initialize translations
     */
    protected function initTranslation(): void
    {
        if ($this->isPublic) {
            $tr = $this->getTranslator();
            $this->translations = [
                'USER_PHONE' => $tr->translate('USER_PHONE', 'user'),
                'USER_BILL_PHONE' => $tr->translate('USER_BILL_PHONE', 'user'),
                'USER_BILL_FAX' => $tr->translate('USER_BILL_FAX', 'user'),
                'USER_SHIP_PHONE' => $tr->translate('USER_SHIP_PHONE', 'user'),
                'USER_SHIP_FAX' => $tr->translate('USER_SHIP_FAX', 'user'),
                'SIGNUP_ERR_REQUIRED' => $tr->translate('SIGNUP_ERR_REQUIRED', 'user'),
                'USER_PHONE_NUMBER' => $tr->translate('USER_PHONE_NUMBER', 'user'),
                'USER_ERR_PHONE_NUMBER_REQUIRED' => $tr->translate('USER_ERR_PHONE_NUMBER_REQUIRED', 'user'),
                'USER_ERR_FAX_NUMBER_REQUIRED' => $tr->translate('USER_ERR_FAX_NUMBER_REQUIRED', 'user'),
                'USER_ERR_PHONE_COUNTRY_CODE_REQUIRED' => $tr->translate('USER_ERR_PHONE_COUNTRY_CODE_REQUIRED', 'user'),
                'USER_ERR_PHONE_NUMBER_INVALID' => $tr->translate('USER_ERR_PHONE_NUMBER_INVALID', 'user'),
                'USER_ERR_FAX_NUMBER_INVALID' => $tr->translate('USER_ERR_FAX_NUMBER_INVALID', 'user'),
            ];
        }
    }

    /**
     * Return html for controls rendering in simple format
     * @return string
     */
    protected function getHtmlForSimpleFormat(): string
    {
        if ($this->isAllowEdit) {
            $controlId = $this->getControlId(self::CONTROL_CONTAINER);
            $txtPhoneNumberHtml = $this->txtPhoneNumber->RenderWithError(false);
            $output = <<<HTML
<div id="{$controlId}">{$txtPhoneNumberHtml}</div>
HTML;
        } else {
            $output = $this->getHtmlForViewMode();
        }
        return $output;
    }

    /**
     * Return html for controls rendering in structured format
     * @return string
     */
    protected function getHtmlForStructuredFormat(): string
    {
        if ($this->isAllowEdit) {
            $controlId = $this->getControlId(self::CONTROL_CONTAINER);
            $lstCountryCodeHtml = $this->lstCountryCode->Render(false);
            $txtPhoneNumberHtml = $this->txtPhoneNumber->Render(false);
            $lblErrorHtml = $this->lblError->RenderWithError(false);
            $output = <<<HTML
<div id="{$controlId}" class="input-phone-number">
    <div class="country-box">{$lstCountryCodeHtml}</div>
    <div class="phone-edit">{$txtPhoneNumberHtml}</div>
    <div class="clear"></div>
    {$lblErrorHtml}
</div>
HTML;
        } else {
            $output = $this->getHtmlForViewMode();
        }
        return $output;
    }

    /**
     * Return html for phone number rendering in view mode
     * @return string
     */
    protected function getHtmlForViewMode(): string
    {
        $controlId = $this->getControlId(self::CONTROL_CONTAINER);
        $phoneNumber = $this->getPhoneNumber();
        $output = <<<HTML
<div id="{$controlId}"  class="input-phone-number">
    <div class="phone-view">{$phoneNumber}</div>
</div>
HTML;
        return $output;
    }

    /**
     * Create controls for structured phone format
     */
    protected function createStructuredControls(): void
    {
        $this->createLstCountryCode();
        $this->createTxtPhoneNumber();
        $this->createLblError();
    }

    /**
     * Create country code dropdown for structured format
     */
    protected function createLstCountryCode(): void
    {
        if ($this->lstCountryCode) {
            return;
        }
        $controlId = $this->getControlId(self::CONTROL_COUNTRY_CODE);
        $this->lstCountryCode = new QListBox($this, $controlId);
        $this->fillCountryCode();
        $this->lstCountryCode->Display = !$this->isHideCountryCodeSelection;
    }

    /**
     * Fill country code dropdown with data
     */
    protected function fillCountryCode(): void
    {
        $this->lstCountryCode->AddItem("- Country -");
        $countryCodes = $this->getPhoneCountryHelper()->getCountryNamesToCodes();
        foreach ($countryCodes as $country => $code) {
            $isSelected = (int)$code === $this->defaultCountryCode;
            $line = '+' . $code . ' (' . $country . ')';
            $this->lstCountryCode->AddItem($line, $code, $isSelected);
        }
    }

    /**
     * Create phone number edit control for simple format
     */
    protected function createTxtPhoneNumber(): void
    {
        if ($this->txtPhoneNumber) {
            return;
        }
        $controlId = $this->getControlId(self::CONTROL_PHONE_NUMBER);
        $this->txtPhoneNumber = new QTextBox($this, $controlId);
        if ($this->phoneNumberFormat === self::PHONE_NUMBER_FORMAT_STRUCTURED) {
            $this->txtPhoneNumber->CssClass = "phone-number-structured";
        }
    }

    /**
     * Create label for error message for structured format
     */
    protected function createLblError(): void
    {
        if ($this->lblError) {
            return;
        }
        $this->lblError = new QLabel($this, 'lpe' . $this->ControlId);
        $this->lblError->CssClass = 'warning';
    }

    /**
     * Return control id for control
     * @param int $controlType
     * @return string
     */
    protected function getControlId(int $controlType): string
    {
        return $this->controlIdPrefixes[$controlType] . $this->ControlId;
    }

    /**
     * Assemble structured phone number using values entered in controls
     * @return string
     */
    protected function composeStructuredPhoneNumber(): string
    {
        $structuredPhoneNumber = '';
        if (trim($this->txtPhoneNumber->Text) !== '') {
            $phoneParts[0] = $this->isHideCountryCodeSelection
                ? $this->defaultCountryCode
                : preg_replace('/\D/', '', (string)$this->lstCountryCode->SelectedValue);
            $phoneParts[1] = $this->txtPhoneNumber->Text;
            $structuredPhoneNumber = $this->getPhoneNumberHelper()->composeFromParts($phoneParts);
        }
        return $structuredPhoneNumber;
    }

    /**
     * YV, 2020-09, SAM-6461: we should not init controls in validation methods. It should return (bool) result only and maybe init error code.
     * Validate phone number in simple format
     * @return bool
     */
    protected function validateForSimpleFormat(): bool
    {
        $isValid = true;
        if ($this->isAllowEdit) {
            $this->txtPhoneNumber->Warning = null;
            if (
                $this->isRequired
                && $this->isEmptyTextBoxPhoneNumber()
            ) {
                $isValid = false;
                $this->txtPhoneNumber->Warning = $this->translations['SIGNUP_ERR_REQUIRED'];
                $this->txtPhoneNumber->Focus();
                $this->txtPhoneNumber->Blink();
            }
        }
        return $isValid;
    }

    /**
     * YV, 2020-09, SAM-6461: we should not init controls in validation methods. It should return (bool) result only and maybe init error code.
     * Validate phone number in structured format
     * @return bool
     */
    protected function validateForStructuredFormat(): bool
    {
        $isValid = true;
        if ($this->isAllowEdit) {
            $this->lblError->Text = null;
            $isEmpty = $this->isEmptyTextBoxPhoneNumber();
            if (
                $this->isRequired
                && $isEmpty
            ) {
                $isValid = false;
                $this->lblError->Text = $this->type === self::TYPE_PHONE
                    ? $this->translations['USER_ERR_PHONE_NUMBER_REQUIRED']
                    : $this->translations['USER_ERR_FAX_NUMBER_REQUIRED'];
                $this->txtPhoneNumber->Blink();
                $this->txtPhoneNumber->Focus();
            } elseif (!$isEmpty) {
                if (
                    !$this->isHideCountryCodeSelection
                    && $this->lstCountryCode->SelectedValue === null
                ) {
                    $isValid = false;
                    $this->lblError->Text = $this->translations['USER_ERR_PHONE_COUNTRY_CODE_REQUIRED'];
                    $this->lstCountryCode->Focus();
                    $this->lstCountryCode->Blink();
                } else {
                    $structuredPhoneNumber = $this->composeStructuredPhoneNumber();
                    $isValid = $this->getPhoneNumberHelper()->isValid($structuredPhoneNumber);
                    if (!$isValid) {
                        $this->lblError->Text = $this->type === self::TYPE_PHONE
                            ? $this->translations['USER_ERR_PHONE_NUMBER_INVALID']
                            : $this->translations['USER_ERR_FAX_NUMBER_INVALID'];
                        $this->txtPhoneNumber->Blink();
                        $this->txtPhoneNumber->Focus();
                    }
                }
            }
        }
        return $isValid;
    }

    /**
     * Check if phone number considered to be not entered
     * @return bool
     */
    protected function isEmptyTextBoxPhoneNumber(): bool
    {
        return trim($this->txtPhoneNumber->Text) === '';
    }

    /**
     * Return value of isAllowEdit property
     * @return bool
     */
    public function isAllowEdit(): bool
    {
        return $this->isAllowEdit;
    }

    /**
     * Set isAllowEdit property value and normalize boolean value
     * @param bool $isAllowEdit
     * @return static
     */
    public function enableAllowEdit(bool $isAllowEdit): static
    {
        $this->isAllowEdit = $isAllowEdit;
        return $this;
    }

    /**
     * @return string
     */
    public function getContainerControlId(): string
    {
        return $this->getControlId(self::CONTROL_CONTAINER);
    }

    /**
     * Return value of panel property
     * @return string
     */
    public function getPanel(): string
    {
        return $this->panel;
    }

    /**
     * Set panel property value and normalize to string value
     * @param string $panel
     * @return static
     */
    public function setPanel(string $panel): static
    {
        $this->panel = trim($panel);
        return $this;
    }

    /**
     * Return value of phoneNumber property
     * @return string
     */
    public function getPhoneNumber(): string
    {
        if ($this->getPhoneNumberFormat() === self::PHONE_NUMBER_FORMAT_SIMPLE) {
            $this->phoneNumber = $this->txtPhoneNumber->Text;
        } elseif ($this->getPhoneNumberFormat() === self::PHONE_NUMBER_FORMAT_STRUCTURED) {
            $this->phoneNumber = $this->composeStructuredPhoneNumber();
            $this->phoneNumber = $this->getPhoneNumberHelper()->formatToInternational($this->phoneNumber);
        }
        return $this->phoneNumber;
    }

    /**
     * Set phoneNumber property value and normalize to string value
     * @param string $phoneNumber
     * @return static
     */
    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = trim($phoneNumber);
        $this->initControls();
        return $this;
    }

    /**
     * Return value of phoneNumberFormat property
     * @return string
     */
    public function getPhoneNumberFormat(): string
    {
        return $this->phoneNumberFormat;
    }

    /**
     * Set phoneNumberFormat property value and normalize to string value
     * @param string $phoneNumberFormat
     * @return static
     */
    public function setPhoneNumberFormat(string $phoneNumberFormat): static
    {
        $this->phoneNumberFormat = trim($phoneNumberFormat);
        $this->createControls();
        return $this;
    }

    /**
     * Return value of type property
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set type property value and normalize to string value
     * @param string $type
     * @return static
     */
    public function setType(string $type): static
    {
        $this->type = trim($type);
        return $this;
    }

    /**
     * Return value of isPublic property
     * @return bool
     */
    public function isPublic(): ?bool
    {
        return $this->isPublic;
    }

    /**
     * Set isPublic property value and normalize boolean value
     * @param bool $isPublic
     * @return static
     */
    public function enablePublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;
        $this->initTranslation();
        return $this;
    }

    /**
     * Return value of isRequired property
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    /**
     * Set isRequired property value and normalize boolean value
     * @param bool $isRequired
     * @return static
     */
    public function enableRequired(bool $isRequired): static
    {
        $this->isRequired = $isRequired;
        return $this;
    }
}
