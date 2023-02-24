<?php
/**
 * It is created for disable and enable link buttons and extend from QLinkButton
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

/**
 * @property string $Text
 */
class LinkButton extends \QLinkButton
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
     * Block link button after clicking
     */
    public function blockMultipleClick(): void
    {
        $js = "onclick=this.href='javascript:void(0)';this.disabled=1";
        $this->AddAction(
            new \QClickEvent(),
            new \QJavaScriptAction($js)
        );
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableClicking(bool $enable): static
    {
        if ($enable) {
            $this->LinkUrl = '#';
        }
        return $this;
    }
}
