<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/30/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Base;

/**
 * Trait ActionNameAwareTrait
 * @package Sam\Application\Controller\Base
 */
trait ActionNameAwareTrait
{
    /**
     * @var string
     */
    protected string $actionName = '';

    /**
     * @return string
     */
    public function getActionName(): string
    {
        return $this->actionName;
    }

    /**
     * @param string $actionName
     * @return static
     */
    public function setActionName(string $actionName): static
    {
        $this->actionName = trim($actionName);
        return $this;
    }
}
