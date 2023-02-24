<?php
/**
 * Date-type custom field editing component
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
 * @method QDateTimePicker getControl()
 */

namespace Sam\CustomField\Base\Qform\Component;

use DateTime;
use QDateTimePicker;
use Sam\Date\DateHelperAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class DateEdit
 * @package Sam\CustomField\Base\Qform\Component
 * @method QDateTimePicker getControl()
 */
class DateEdit extends BaseEdit
{
    use DateHelperAwareTrait;
    use SystemAccountAwareTrait;

    protected string $format = '';

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
        $format = $this->isMobileUi()
            ? $this->getDateHelper()->getDateDisplayFormat($this->getSystemAccountId(), true)
            : $this->getDateHelper()->getDateTimeDisplayFormat();

        $this->setFormat($format);
        $control = new QDateTimePicker($this->getParentObject(), $this->getControlId(), $this->format);
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
        if ($this->getCustomData()->Numeric !== null) {
            $this->getControl()->Text = (new DateTime())
                ->setTimestamp((int)$this->getCustomData()->Numeric)
                ->format($this->format);
        }
        return $this;
    }

    /**
     * Check, if control is not filled
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->getControl()->Text === '';
    }

    /**
     * Validate custom field editing controls.
     *
     * @return bool
     */
    public function validate(): bool
    {
        if ($this->getControl()->Text) {
            $dt = $this->getControl()->convertToDatetime();
            $this->setInputValue($dt?->getTimestamp());
        }
        return parent::validate();
    }

    /**
     * Save custom field data
     *
     * @return void
     */
    public function save(): void
    {
        if ($this->getControl()->Text) {
            $dt = $this->getControl()->convertToDatetime();
            $this->getCustomData()->Numeric = $dt?->getTimestamp();
        }
        parent::save();
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return static
     */
    public function setFormat(string $format): static
    {
        $this->format = trim($format);
        return $this;
    }
}
