<?php
/**
 * Mobile side component, which implements controls related logic for different phone formats
 * SAM-1526: Structured Phone number editing improvements
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           14 Feb, 2013
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * @property string PhoneNumber         Set it to initialize controls (for view and edit mode)
 * @property string PhoneNumberFormat   Define phone number format
 */

namespace Sam\PhoneNumber\Qform;

/**
 * Class MobileComponent
 * @package Sam\PhoneNumber\Qform
 */
class PublicSidePhoneNumberComponent extends PhoneNumberComponent
{
    protected array $errorMessages = [];

    /**
     * Return html for controls rendering in simple format
     * @return string
     */
    protected function getHtmlForSimpleFormat(): string
    {
        $placeholder = $this->renderLabel(false) . ($this->isRequired ? ' *' : '');
        $this->txtPhoneNumber->SetCustomAttribute('placeholder', $placeholder);
        return parent::getHtmlForSimpleFormat();
    }

    /**
     * Return html for controls rendering in structured format
     * @return string
     */
    protected function getHtmlForStructuredFormat(): string
    {
        $requiredSign = $this->isRequired ? '<span class="req">*</span>' : '';
        $output =
            '<div id="' . $this->getControlId(self::CONTROL_CONTAINER) . '" class="mobui-composite-wrapper">' .
            '<label>' .
            $this->renderLabel(false) .
            $requiredSign .
            '</label>' .
            $this->lstCountryCode->Render(false) .
            $this->txtPhoneNumber->Render(false) .
            ($this->renderError ? $this->lblError->Render(false) : '') .
            '</div>';
        return $output;
    }

    protected function createLstCountryCode(): void
    {
        parent::createLstCountryCode();
        $this->lstCountryCode->CssClass = "combobox";
    }

    protected function createTxtPhoneNumber(): void
    {
        parent::createTxtPhoneNumber();
        $this->txtPhoneNumber->CssClass = 'phone-number';
    }

    /**
     * Validate phone number entered in controls
     * @return bool
     */
    public function validate(): bool
    {
        $this->errorMessages = [];
        return parent::validate();
    }

    /**
     * Validate phone number in simple format
     * @return bool
     */
    protected function validateForSimpleFormat(): bool
    {
        $isValid = true;
        $this->txtPhoneNumber->Warning = null;
        if ($this->isRequired && $this->isEmptyTextBoxPhoneNumber()) {
            $isValid = false;
            $errorMessage = $this->translations['SIGNUP_ERR_REQUIRED'];
            $this->addError($this->getControlId(self::CONTROL_PHONE_NUMBER), $errorMessage);
            $this->txtPhoneNumber->Focus();
            $this->txtPhoneNumber->Blink();
        }
        return $isValid;
    }

    /**
     * Validate phone number in structured format
     * @return bool
     */
    protected function validateForStructuredFormat(): bool
    {
        $isValid = true;
        $this->lblError->Text = null;
        $isEmpty = $this->isEmptyTextBoxPhoneNumber();
        if (
            $this->isRequired
            && $isEmpty
        ) {
            $isValid = false;
            $errorMessage = $this->type === self::TYPE_PHONE
                ? $this->translations['USER_ERR_PHONE_NUMBER_REQUIRED']
                : $this->translations['USER_ERR_FAX_NUMBER_REQUIRED'];
            $this->addError($this->getControlId(self::CONTROL_CONTAINER), $errorMessage);
            $this->txtPhoneNumber->Focus();
            $this->txtPhoneNumber->Blink();
            $this->lblError->Display = false;
        } elseif (!$isEmpty) {
            if (
                !$this->isHideCountryCodeSelection
                && $this->lstCountryCode->SelectedValue === null
            ) {
                $isValid = false;
                $errorMessage = $this->translations['USER_ERR_PHONE_COUNTRY_CODE_REQUIRED'];
                $selector = $this->getControlId(self::CONTROL_COUNTRY_CODE) . '_ctl > .custom-combobox';
                $this->addError($selector, $errorMessage);
                //                 $this->lstCountryCode->Focus();    // we can't blink or focus,
                //                 $this->lstCountryCode->Blink();    // because custom combobox control is used
            } else {
                $structuredPhoneNumber = $this->composeStructuredPhoneNumber();
                $isValid = $this->getPhoneNumberHelper()->isValid($structuredPhoneNumber);
                if (!$isValid) {
                    $errorMessage = $this->type === self::TYPE_PHONE
                        ? $this->translations['USER_ERR_PHONE_NUMBER_INVALID']
                        : $this->translations['USER_ERR_FAX_NUMBER_INVALID'];
                    $this->addError($this->getControlId(self::CONTROL_CONTAINER), $errorMessage);
                    $this->lblError->Text = $errorMessage;
                    $this->lblError->Display = true;
                    $this->txtPhoneNumber->Focus();
                    $this->txtPhoneNumber->Blink();
                }
            }
        }
        return $isValid;
    }

    /**
     * Add error
     * @param string $controlId
     * @param string $message
     */
    public function addError(string $controlId, string $message): void
    {
        $this->errorMessages[] = [$controlId, $message, $this->renderLabel(false)];
    }

    /**
     * Return array of errors
     * @return array (<control id>, <message>, <field name>)
     */
    public function getErrors(): array
    {
        return $this->errorMessages;
    }
}
