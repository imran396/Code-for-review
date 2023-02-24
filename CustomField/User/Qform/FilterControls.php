<?php
/**
 * Helper class for QCodo controllers (drafts), which work with filtering controls for user custom field.
 * Back end: user list
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: FilterControls.php 21032 2015-04-25 05:29:02Z SWB\igors $
 * @since           Nov 06, 2012
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Qform;

use QControl;
use QDateTimePicker;
use QForm;
use QLabel;
use QListBox;
use QTextBox;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Css\CssTransformer;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\CustomField\User\Help\UserCustomFieldHelperAwareTrait;
use Sam\CustomField\User\Load\UserCustomFieldLoaderAwareTrait;
use Sam\CustomField\User\Translate\UserCustomFieldTranslationManagerAwareTrait;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;
use UserCustField;

/**
 * Class FilterControls
 * @package Sam\CustomField\User\Qform
 */
class FilterControls extends CustomizableClass
{
    use BaseCustomFieldHelperAwareTrait;
    use FormStateLongevityAwareTrait;
    use UserCustomFieldHelperAwareTrait;
    use UserCustomFieldLoaderAwareTrait;
    use UserCustomFieldTranslationManagerAwareTrait;

    /**
     * Determine some rendering options, e.g. at public we translate labels, at private do not.
     */
    protected bool $isPublic = true;
    /**
     * @var UserCustField[]|null
     */
    protected ?array $userCustomFields = null;
    /**
     * @var QControl[]
     */
    protected array $controls = [];

    /**
     * Class instantiation method
     *
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * For simplifying initialization of class parameters
     *
     * @param bool $enable
     * @return static
     */
    public function enablePublic(bool $enable): static
    {
        $this->isPublic = $enable;
        return $this;
    }

    /**
     * Create and return array of controls for custom field filtering options
     *
     * @param QForm|QControl $parent
     * @param string $dateDisplayFormat
     * @return array
     */
    public function create(QForm|QControl $parent, string $dateDisplayFormat): array
    {
        $this->controls = [];
        foreach ($this->getUserCustomFields() as $userCustomField) {
            $controlId = 'UsrCustFldFlt' . $userCustomField->Id;
            switch ($userCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                case Constants\CustomField::TYPE_DECIMAL:
                    $this->controls[$userCustomField->Id . 'a'] = new QTextBox($parent, $controlId . 'min');
                    $this->controls[$userCustomField->Id . 'b'] = new QTextBox($parent, $controlId . 'max');
                    break;

                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_FULLTEXT:
                case Constants\CustomField::TYPE_PASSWORD:
                case Constants\CustomField::TYPE_FILE:
                    $this->controls[$userCustomField->Id] = new QTextBox($parent, $controlId);
                    break;

                case Constants\CustomField::TYPE_SELECT:
                    $options = $this->getBaseCustomFieldHelper()->extractDropdownOptionsFromString($userCustomField->Parameters);
                    $control = new QListBox($parent, $controlId);
                    $control->AddItem('-Select-');
                    foreach ($options as $option) {
                        $control->AddItem($option, $option);
                    }
                    $this->controls[$userCustomField->Id] = $control;
                    break;

                case Constants\CustomField::TYPE_CHECKBOX:
                    $options = [1 => 'Yes', 0 => 'No'];
                    $control = new QListBox($parent, $controlId);
                    $control->AddItem('-Select-');
                    foreach ($options as $value => $name) {
                        $control->AddItem($name, $value);
                    }
                    $this->controls[$userCustomField->Id] = $control;
                    break;

                case Constants\CustomField::TYPE_DATE:
                    $control = new QDateTimePicker($parent, $controlId . 'min', $dateDisplayFormat);
                    $this->controls[$userCustomField->Id . 'a'] = $control;

                    $control = new QDateTimePicker($parent, $controlId . 'max', $dateDisplayFormat);
                    $this->controls[$userCustomField->Id . 'b'] = $control;
                    break;

                case Constants\CustomField::TYPE_LABEL:
                    $this->controls[$userCustomField->Id] = new QLabel($parent, $controlId);
                    break;
            }
        }
        return $this->controls;
    }

    /**
     * @param array $params
     */
    public function init(array $params = []): void
    {
        foreach ($this->getUserCustomFields() as $userCustomField) {
            $paramKey = 'ucf' . $userCustomField->Id;
            switch ($userCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                case Constants\CustomField::TYPE_DATE:
                case Constants\CustomField::TYPE_DECIMAL:
                    $paramKeyMin = $paramKey . 'min';
                    $paramKeyMax = $paramKey . 'max';
                    if (isset($params[$paramKeyMin])) {
                        /** @var QTextBox $control */
                        $control = $this->controls[$userCustomField->Id . 'a'];
                        $control->Text = $params[$paramKeyMin];
                    }
                    if (isset($params[$paramKeyMax])) {
                        /** @var QTextBox $control */
                        $control = $this->controls[$userCustomField->Id . 'b'];
                        $control->Text = $params[$paramKeyMax];
                    }
                    break;
                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_FULLTEXT:
                case Constants\CustomField::TYPE_PASSWORD:
                case Constants\CustomField::TYPE_FILE:
                    if (isset($params[$paramKey])) {
                        /** @var QTextBox $control */
                        $control = $this->controls[$userCustomField->Id];
                        $control->Text = $params[$paramKey];
                    }
                    break;
                case Constants\CustomField::TYPE_SELECT:
                case Constants\CustomField::TYPE_CHECKBOX:
                    if (isset($params[$paramKey])) {
                        /** @var QListBox $control */
                        $control = $this->controls[$userCustomField->Id];
                        $control->SelectedValue = $params[$paramKey];
                    }
                    break;
            }
        }
    }

    /**
     * Validate controls of custom fields filtering options
     *
     * @param array $controls
     * @param array $userCustomFields
     * @return bool
     * @noinspection PhpUnusedParameterInspection
     */
    public function validate(array $controls, array $userCustomFields): bool
    {
        return true;
    }

    /**
     * Return html for filtering by custom fields
     *
     * @return string
     */
    public function getHtml(): string
    {
        $output = '';
        $cssTransformer = CssTransformer::new();
        foreach ($this->getUserCustomFields() as $userCustomField) {
            if ($this->isPublic) {
                $fieldName = $this->getUserCustomFieldTranslationManager()->translateName($userCustomField);
            } else {
                $fieldName = $userCustomField->Name;
            }
            switch ($userCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                case Constants\CustomField::TYPE_DECIMAL:
                case Constants\CustomField::TYPE_DATE:
                    $controlHtml = $this->controls[$userCustomField->Id . 'a']->RenderWithError(false) . ' - ';
                    $controlHtml .= $this->controls[$userCustomField->Id . 'b']->RenderWithError(false);
                    break;

                default:
                    $this->controls[$userCustomField->Id]->Width = 120;
                    $controlHtml = $this->controls[$userCustomField->Id]->RenderWithError(false);
                    break;
            }
            $typeName = $this->getUserCustomFieldHelper()->makeTypeName($userCustomField->Type);
            $typeName = 'cf-type-' . strtolower($cssTransformer->toClassName(strtolower($typeName)));
            $cssClass = 'cf-name-' . strtolower($cssTransformer->toClassName($fieldName));
            $output .= sprintf('<div class="group solo %s %s">', $typeName, $cssClass) . "\n";
            $output .= '  <div class="group solo">' . "\n";
            $output .= sprintf('    <div class="label-input %s">', $typeName) . "\n";
            $output .= sprintf('      <span class="label">%s:</span>', ee($fieldName)) . "\n";
            $output .= sprintf('      <span class="input">%s</span>', $controlHtml) . "\n";
            $output .= '    </div>' . "\n";
            $output .= '  </div>' . "\n";
            $output .= '</div>' . "\n";
        }
        return $output;
    }

    /**
     * Load array of user custom fields used for filtering search results
     *
     * @return UserCustField[]
     */
    public function getUserCustomFields(): array
    {
        if ($this->userCustomFields === null) {
            $this->userCustomFields = $this->getUserCustomFieldLoader()->loadInAdminSearch();
        }
        return $this->userCustomFields;
    }

    /**
     * @param UserCustField[] $userCustomFields
     * @return static
     */
    public function setUserCustomFields(array $userCustomFields): static
    {
        $this->userCustomFields = $userCustomFields;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    /**
     * @return QControl[]
     */
    public function getControls(): array
    {
        return $this->controls;
    }

    /**
     * @param QControl[] $controls
     * @return static
     * @noinspection PhpUnused
     */
    public function setControls(array $controls): static
    {
        $this->controls = $controls;
        return $this;
    }
}
