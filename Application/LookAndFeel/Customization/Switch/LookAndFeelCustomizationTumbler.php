<?php
/**
 * SAM-11612: Tech support tool to easily and temporarily disable installation look and feel customizations
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\LookAndFeel\Customization\Switch;


use Sam\Application\LookAndFeel\Customization\Switch\Internal\Cookie\CookieManager;
use Sam\Application\LookAndFeel\Customization\Switch\Internal\Cookie\CookieManagerCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LookAndFeelCustomizationTumbler
 * @package Sam\Application\LookAndFeel\Customization;
 */
class LookAndFeelCustomizationTumbler extends CustomizableClass
{
    use CookieManagerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isOn(): bool
    {
        return $this->createCookieManager()->isLayoutCustomizationEnabled();
    }

    public function isOff(): bool
    {
        return !$this->createCookieManager()->isLayoutCustomizationEnabled();
    }

    public function turn(bool $enable): static
    {
        $this->createCookieManager()->enableLayoutCustomization($enable);
        if ($enable === true) {
            $this->drop();
        }
        return $this;
    }

    public function turnOn(): void
    {
        $this->turn(true);
    }

    public function turnOff(): void
    {
        $this->turn(false);
    }

    protected function drop(): void
    {
        $this->createCookieManager()->deleteLayoutCustomization();
    }
}
