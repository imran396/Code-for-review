<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Nov 6, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Base;

/**
 * Trait ControllerNameAwareTrait
 * @package Sam\Application\Controller\Base
 */
trait ControllerNameAwareTrait
{
    protected ?string $controllerName = null;

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    /**
     * @param string $controllerName
     * @return static
     */
    public function setControllerName(string $controllerName): static
    {
        $this->controllerName = trim($controllerName);
        return $this;
    }
}
