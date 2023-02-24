<?php
/**
 * It is created for disable and enable buttons and extend from QButton
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Pyotr Vorobyov
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           06 May, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 */

namespace Sam\Qform;

use QButton;
use QClickEvent;
use QJavaScriptAction;

/**
 * Class Button
 * @package Sam\Qform
 */
class Button extends QButton
{
    /**
     * The function returns class for base tag
     * @return string
     */
    public function getControlCssClass(): string
    {
        $class = strtolower(get_parent_class() . '-ctl');
        return $class;
    }

    /**
     * Block  button after clicking
     */
    public function blockMultipleClick(): void
    {
        $this->AddAction(new QClickEvent(), new QJavaScriptAction("this.disabled=true"));
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableClicking(bool $enable): static
    {
        $this->Enabled = $enable;
        return $this;
    }
}
