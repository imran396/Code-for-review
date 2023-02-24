<?php
/**
 * SAM-9809:  Refactor Action Queue Module
 * https://bidpath.atlassian.net/browse/SAM-9809
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\ActionQueue\Internal\Save;

use ActionQueue;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\ActionQueue\ActionQueueWriteRepositoryAwareTrait;

/**
 * Class DataSaver
 * @package Sam\ActionQueue;
 */
class DataSaver extends CustomizableClass
{
    use ActionQueueWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function saveWithSystemModifier(ActionQueue $actionQueue): void
    {
        $this->getActionQueueWriteRepository()->saveWithSystemModifier($actionQueue);
    }

    public function deleteWithSystemModifier(ActionQueue $actionQueue): void
    {
        $this->getActionQueueWriteRepository()->deleteWithSystemModifier($actionQueue);
    }
}
