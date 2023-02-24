<?php
/**
 * Helper class for QCodo controllers (drafts), which work with editing controls for custom user fields at mobile side.
 * Mobile Front end: registration and profile pages
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Benjo
 * @package         com.swb.sam2
 * @version         SVN: $Id: EditControls.php 13991 2013-07-31 20:45:43Z SWB\igors $
 * @since           Nov 02, 2012
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Qform\Mobile;

use QControl;
use QForm;
use Sam\Core\Constants;
use Sam\Qform\PublicErrorCollectionAwareTrait;
use Sam\View\Responsive\Panel\ProfileBillingPanel;
use Sam\View\Responsive\Panel\ProfilePersonalPanel;
use Sam\View\Responsive\Panel\ProfileShippingPanel;
use Sam\View\Responsive\Panel\RegisterBillingPanel;
use Sam\View\Responsive\Panel\RegisterPersonalPanel;
use Sam\View\Responsive\Panel\RegisterShippingPanel;

/**
 * Class EditControls
 * @package Sam\CustomField\User\Qform\Mobile
 */
class EditControls extends \Sam\CustomField\User\Qform\EditControls
{
    use PublicErrorCollectionAwareTrait;

    /**
     * Identifiable css class names. See SAM-7976     *
     *
     * 1st %s - panel specific css class,
     * 2nd,3rd,4th %s (free order) - cust.fld. name css class, cust.fld. type css class, cust.fld. id css class.
     */
    public const CSS_CLASS_CUSTOM_FIELD_PANEL_GENERAL_TPL = 'custom-field %s %s %s %s';
    public const CSS_CLASS_PROFILE_BILLING_PANEL = 'profile-billing';
    public const CSS_CLASS_PROFILE_PERSONAL_PANEL = 'profile-info';
    public const CSS_CLASS_PROFILE_SHIPPING_PANEL = 'profile-shipping';
    public const CSS_CLASS_REGISTER_BILLING_PANEL = 'register-billing';
    public const CSS_CLASS_REGISTER_PERSONAL_PANEL = 'register-info';
    public const CSS_CLASS_REGISTER_SHIPPING_PANEL = 'register-shipping';

    /**
     * Class instantiation method
     * @return static or customized class extending EditControls
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return HTML for user custom fields controls
     *
     * @return string
     */
    public function getHtml(): string
    {
        $output = '';
        $wrapperElement = 'li';
        $parentObject = $this->getParentObject();
        $wrapperElementCssClass = sprintf(
            self::CSS_CLASS_CUSTOM_FIELD_PANEL_GENERAL_TPL,
            $this->findCssClassNameByParentObject($parentObject),
            '%s',
            '%s',
            '%s'
        );
        $customFieldCssClassMaker = $this->createCustomFieldCssClassMaker();
        foreach ($this->getUserCustFields() as $userCustomField) {
            $customFieldCssClass = $customFieldCssClassMaker->makeCssClassByUserCustomField($userCustomField, $wrapperElementCssClass);
            $output .= "<{$wrapperElement} class='{$customFieldCssClass}'>";

            if ($this->isTranslating()) {
                if ($userCustomField->Type === Constants\CustomField::TYPE_LABEL) {
                    $name = '';
                } else {
                    $name = $this->getUserCustomFieldTranslationManager()->translateName($userCustomField);
                    $name .= '';
                }
            } else {
                $name = $userCustomField->Name;
            }
            if ($userCustomField->Required) {
                $name .= ' *';
            }

            $component = $this->components[$userCustomField->Id];

            //set the custom attribute if not a checkbox or listbox
            $component->getControl()->SetCustomAttribute('placeholder', $name);
            //if this is a checkbox, set the name
            if ($userCustomField->Type === Constants\CustomField::TYPE_CHECKBOX) {
                $component->getControl()->Name = $name;
            }

            $renderMethod = $this->getUserCustomFieldHelper()->makeCustomMethodName($userCustomField->Name, "Render");    // SAM-1573

            //add the combobox class to list types
            if ($userCustomField->Type === Constants\CustomField::TYPE_SELECT) {
                $component->getControl()->CssClass = "combobox";
            }

            if (method_exists($parentObject, $renderMethod)) {
                $controlHtml = $parentObject->$renderMethod($component);
            } else {
                $controlHtml = $component->render();
            }

            //wrap the checkbox first
            if ($userCustomField->Type === Constants\CustomField::TYPE_CHECKBOX) {
                $controlHtml = <<<HTML
                <div class="selector">{$controlHtml}</div>
                <div class="clear"></div>
HTML;
            }

            //if this is a listbox add the styled listbox script here
            if ($userCustomField->Type === Constants\CustomField::TYPE_SELECT) {
                $controlHtml = <<<HTML
                <div class="ui-widget custom-field-listbox">{$controlHtml}</div>
                <div class="clear"></div>
HTML;
            }

            $output .= $controlHtml;

            $output .= "</{$wrapperElement}>";
        }

        return $output;
    }

    /**
     * Errors (control id, message) should be returned by getErrorInfo()
     *
     * @return bool
     */
    public function validate(): bool
    {
        if (!$this->isEditMode()) {
            return false;
        }
        $this->getPublicErrorCollection()->clearErrors();
        foreach ($this->components as $component) {
            $component->clearWarning();
        }
        $parentObject = $this->getParentObject();
        foreach ($this->getUserCustFields() as $userCustomField) {
            $component = $this->components[$userCustomField->Id];
            $control = $component->getControl();

            $controlSelector = $control->ControlId;
            if (get_class($control) === 'QListBox') {
                $controlSelector .= '_ctl';
            }
            $validateMethod = $this->getUserCustomFieldHelper()->makeCustomMethodName($userCustomField->Name, "Validate");    // SAM-1573
            if (method_exists($parentObject, $validateMethod)) {
                if (!$parentObject->$validateMethod($component)) {
                    $this->getPublicErrorCollection()->addError(
                        $controlSelector,
                        $component->getControl()->Warning,
                        $component->getControl()->GetCustomAttribute('placeholder')
                    );
                }
            } elseif (!$component->validate()) {
                $this->getPublicErrorCollection()->addError(
                    $controlSelector,
                    $component->getControl()->Warning,
                    $component->getControl()->GetCustomAttribute('placeholder')
                );
            }
        }
        $hasError = $this->getPublicErrorCollection()->hasErrors();
        return !$hasError;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->getPublicErrorCollection()->getErrors();
    }

    /**
     * @param QForm|QControl $parentObject
     * @return string
     */
    protected function findCssClassNameByParentObject(QControl|QForm $parentObject): string
    {
        if ($parentObject instanceof ProfileBillingPanel) {
            return self::CSS_CLASS_PROFILE_BILLING_PANEL;
        }
        if ($parentObject instanceof ProfilePersonalPanel) {
            return self::CSS_CLASS_PROFILE_PERSONAL_PANEL;
        }
        if ($parentObject instanceof ProfileShippingPanel) {
            return self::CSS_CLASS_PROFILE_SHIPPING_PANEL;
        }
        if ($parentObject instanceof RegisterBillingPanel) {
            return self::CSS_CLASS_REGISTER_BILLING_PANEL;
        }
        if ($parentObject instanceof RegisterPersonalPanel) {
            return self::CSS_CLASS_REGISTER_PERSONAL_PANEL;
        }
        if ($parentObject instanceof RegisterShippingPanel) {
            return self::CSS_CLASS_REGISTER_SHIPPING_PANEL;
        }

        return '';
    }
}
