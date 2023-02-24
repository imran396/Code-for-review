<?php

namespace Sam\Lot\Category\Order;

use ActionQueue;
use Exception;
use Sam\ActionQueue\Base\ActionQueueHandlerInterface;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * It processes global category order update Queue task.
 *
 * Related tickets:
 *
 * SAM-3365 : Refresh global order numbers of categories using ActionQueue.
 *
 * @author        Imran Rahman
 * Filename       GlobalOrderUpdateActionHandler.php
 * @version       SAM 2.0
 * @since         july 10, 2016
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package       com.swb.sam2.api
 *
 */
class GlobalOrderUpdateActionHandler extends CustomizableClass implements ActionQueueHandlerInterface
{
    use LotCategoryOrdererAwareTrait;
    use SettingsManagerAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Returns instance of GlobalOrderUpdateActionHandler
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Refresh global category order
     * @param ActionQueue $actionQueue
     * @return bool
     */
    public function process(ActionQueue $actionQueue): bool
    {
        try {
            $data = unserialize($actionQueue->Data);
            $accountId = (int)$data['AccountId'];
            $isLotCategoryGlobalOrderAvailable = (bool)$this->getSettingsManager()
                ->get(Constants\Setting::LOT_CATEGORY_GLOBAL_ORDER_AVAILABLE, $accountId);
            if (!$isLotCategoryGlobalOrderAvailable) {
                $editorUserId = $this->getUserLoader()->loadSystemUserId();
                $this->getLotCategoryOrderer()->refreshGlobalOrders($editorUserId);
                return true;
            }
        } catch (Exception $e) {
            log_error($e->getMessage());
        }
        return false;
    }
}
