<?php
/**
 * Base Text-type custom field editing component
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @since           Aug 14, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * @method QTextBox getControl()
 */

namespace Sam\CustomField\Base\Qform\Component;

use QTextBox;

/**
 * Class TextEditBase
 * @package Sam\CustomField\Base\Qform\Component
 * @method QTextBox getControl()
 */
abstract class TextEditBase extends BaseEdit
{
    /**
     * Create controls for custom field editing
     *
     * @return static
     */
    public function create(): static
    {
        $control = new QTextBox($this->getParentObject(), $this->getControlId());
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
        $this->getControl()->Text = $this->getCustomData()->Text;
        return $this;
    }

    /**
     * Check, if control is not filled
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        $isEmpty = $this->getControl()->Text === '';
        return $isEmpty;
    }

    /**
     * Validate custom field editing controls.
     *
     * @return bool
     */
    public function validate(): bool
    {
        $this->setInputValue($this->getControl()->Text);
        return parent::validate();
    }

    /**
     * Save custom field data
     *
     * @return void
     */
    public function save(): void
    {
        $this->getCustomData()->Text = trim($this->getControl()->Text);
        parent::save();
    }
}
