<?php
/**
 * Password-type custom field editing component
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
use QTextMode;

/**
 * Class PasswordEdit
 * @package Sam\CustomField\Base\Qform\Component
 */
class PasswordEdit extends TextEditBase
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
        $control = new QTextBox($this->getParentObject(), $this->getControlId());
        if ($this->isTranslating()) {
            $control->TextMode = QTextMode::Password;
        }
        $this->setControl($control);
        return $this;
    }
}
