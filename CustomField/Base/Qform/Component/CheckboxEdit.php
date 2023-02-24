<?php

namespace Sam\CustomField\Base\Qform\Component;

/**
 * Checkbox-type custom field editing component
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
 * @method QCheckBox|MobileCheckBox getControl()
 */

use QCheckBox;
use Sam\Qform\Control\MobileCheckBox;

/**
 * Class CheckboxEdit
 * @method BaseEdit getBaseComponent()
 * @method QCheckBox|MobileCheckBox getControl()
 */
class CheckboxEdit extends BaseEdit
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Create controls for custom field editing
     *
     * @return static
     */
    public function create(): static
    {
        if (!$this->isMobileUi()) {
            $control = new QCheckBox($this->getParentObject(), $this->getControlId());
            $this->setControl($control);
        } else {
            $control = new MobileCheckBox($this->getParentObject(), $this->getControlId());
            $this->setControl($control);
        }
        return $this;
    }

    /**
     * Initialize custom field controls with data
     *
     * @return static
     */
    public function init(): static
    {
        if ($this->getCustomData()->Id === null) {
            $isChecked = (int)$this->getCustomField()->Parameters === 1;
        } else {
            $isChecked = (bool)$this->getCustomData()->Numeric;
        }
        $this->getControl()->Checked = $isChecked;
        return $this;
    }

    /**
     * Check, if control is not filled
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        $isEmpty = !$this->getControl()->Checked;
        return $isEmpty;
    }

    /**
     * Validate custom field editing controls.
     *
     * @return bool
     */
    public function validate(): bool
    {
        $this->setInputValue($this->getControl()->Checked);
        return parent::validate();
    }

    /**
     * Save custom field data
     *
     * @return void
     */
    public function save(): void
    {
        $this->getCustomData()->Numeric = (int)$this->getControl()->Checked;
        parent::save();
    }

    /**
     * Return HTML for custom field controls
     *
     * @return string
     */
    public function render(): string
    {
        $control = $this->getControl();
        if (is_a($control, MobileCheckBox::class)) {
            return $control->RenderWithLabel(false);
        }
        return $control->RenderWithError(false);
    }
}
