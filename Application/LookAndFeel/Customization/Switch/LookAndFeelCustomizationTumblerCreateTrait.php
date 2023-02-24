<?php
/**
 * SAM-11612: Tech support tool to easily and temporarily disable installation look and feel customizations
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\LookAndFeel\Customization\Switch;

/**
 * Trait LookAndFeelCustomizationTumblerCreateTrait
 * @package Sam\Application\LookAndFeel\Customization\Switch
 */
trait LookAndFeelCustomizationTumblerCreateTrait
{
    protected ?LookAndFeelCustomizationTumbler $lookAndFeelCustomizationTumbler = null;

    /**
     * @return LookAndFeelCustomizationTumbler
     */
    protected function createLookAndFeelCustomizationTumbler(): LookAndFeelCustomizationTumbler
    {
        return $this->lookAndFeelCustomizationTumbler ?: LookAndFeelCustomizationTumbler::new();
    }

    /**
     * @param LookAndFeelCustomizationTumbler $lookAndFeelCustomizationTumbler
     * @return $this
     * @internal
     */
    public function setLookAndFeelCustomizationTumbler(LookAndFeelCustomizationTumbler $lookAndFeelCustomizationTumbler
    ): self {
        $this->lookAndFeelCustomizationTumbler = $lookAndFeelCustomizationTumbler;
        return $this;
    }
}
