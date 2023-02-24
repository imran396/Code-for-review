<?php
/**
 * Integer-type custom field editing component
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
 * @method QTextBox getControl()
 */

namespace Sam\CustomField\Base\Qform\Component;

use QTextBox;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class IntegerEdit
 * @package Sam\CustomField\Base\Qform\Component
 * @method QTextBox getControl()
 */
class IntegerEdit extends BaseEdit
{
    use NumberFormatterAwareTrait;

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
        $this->getControl()->Text = $this->getCustomData()->Numeric;
        return $this;
    }

    /**
     * Check, if control is not filled
     * @return bool
     */
    public function isEmpty(): bool
    {
        $isEmpty = $this->getControl()->Text === '';
        return $isEmpty;
    }

    /**
     * Validate custom field editing controls.
     * @return bool
     */
    public function validate(): bool
    {
        $this->setInputValue($this->getControl()->Text);
        return parent::validate();
    }

    /**
     * Save custom field data
     * @return void
     */
    public function save(): void
    {
        $value = $this->getNumberFormatter()->removeFormat($this->getControl()->Text);
        $this->getCustomData()->Numeric = is_numeric($value) ? (int)$value : null;
        parent::save();
    }
}
