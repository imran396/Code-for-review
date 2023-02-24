<?php
/**
 * Selection-type custom field editing component
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: EditControls.php 13797 2013-07-09 15:26:18Z SWB\igors $
 * @since           Jul 20, 2013
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * @method QListBox getControl()
 */

namespace Sam\CustomField\Base\Qform\Component;

use QListBox;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;

/**
 * Class SelectionEdit
 * @package Sam\CustomField\Base\Qform\Component
 * @method QListBox getControl()
 */
class SelectionEdit extends BaseEdit
{
    use BaseCustomFieldHelperAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Create controls for custom field editing
     *
     * @param bool $isMobile
     * @return static
     */
    public function create(bool $isMobile = false): static
    {
        $optionNameList = $this->isTranslating()
            ? $this->translator->translateParameters($this->getCustomField())
            : $this->getCustomField()->Parameters;
        $fieldName = $this->isTranslating()
            ? $this->translator->translateName($this->getCustomField())
            : $this->getCustomField()->Name;

        $optionNames = [];
        if ($optionNameList !== '') {
            $optionNames = $this->getBaseCustomFieldHelper()->extractDropdownOptionsFromString($optionNameList);
        }

        $optionValueList = $this->getCustomField()->Parameters;
        $optionValues = $this->getBaseCustomFieldHelper()->extractDropdownOptionsFromString($optionValueList);

        $options = [];
        foreach ($optionValues as $i => $value) {
            $options[$value] = $optionNames[$i] ?? $value;
        }
        $control = new QListBox($this->getParentObject(), $this->getControlId());

        if (!$this->isMobileUi()) {
            $control->AddItem('-Select-');
        } else {
            $control->AddItem($fieldName . ($this->getCustomField()->Required ? ' *' : ''));
        }

        foreach ($options as $value => $name) {
            $control->AddItem($name, $value);
        }
        $this->setControl($control);
        return $this;
    }

    /**
     * Initialize custom field controls with data
     *
     * @return static
     */
    public function init(): static
    {
        $this->getControl()->SelectedValue = $this->getCustomData()->Text;
        return $this;
    }

    /**
     * Check, if control is not filled
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        $isEmpty = $this->getControl()->SelectedValue === null;
        return $isEmpty;
    }

    /**
     * Validate custom field editing controls.
     *
     * @return bool
     */
    public function validate(): bool
    {
        $this->setInputValue($this->getControl()->SelectedValue);
        return parent::validate();
    }

    /**
     * Save file-type custom field data
     *
     * @return void
     */
    public function save(): void
    {
        $this->getCustomData()->Text = (string)$this->getControl()->SelectedValue;
        parent::save();
    }
}
