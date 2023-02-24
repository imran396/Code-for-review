<?php
/**
 * SAM-7912: Refactor \LotImage_Orderer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Associate\Order;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Image\Order\LotImageOrderAdviserCreateTrait;

/**
 * Class LotImageAssociateOrderAdviser
 * @package Sam\Lot\Image\BucketImport\Associate\Order
 */
class LotImageAssociateOrderAdviser extends CustomizableClass
{
    use LotImageOrderAdviserCreateTrait;

    /**
     * @var int
     */
    protected int $strategy = Constants\LotImageImport::INSERT_STRATEGY_APPEND;
    /**
     * @var array
     */
    protected array $lotImageMaxOrderCache = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $strategy
     * @return static
     */
    public function construct(int $strategy): static
    {
        $this->strategy = $strategy;
        return $this;
    }

    /**
     * @param int $lotItemId
     * @return int
     */
    public function suggest(int $lotItemId): int
    {
        if (!isset($this->lotImageMaxOrderCache[$lotItemId])) {
            $order = $this->detectInitialOrder($lotItemId);
            $this->lotImageMaxOrderCache[$lotItemId] = $order;
        } else {
            $order = ++$this->lotImageMaxOrderCache[$lotItemId];
        }
        return $order;
    }

    /**
     * @param int $lotItemId
     * @return int
     */
    protected function detectInitialOrder(int $lotItemId): int
    {
        $order = $this->isStrategyAppend()
            ? $this->createLotImageOrderAdviser()->suggest($lotItemId)
            : 1;
        return $order;
    }

    /**
     * @return bool
     */
    protected function isStrategyAppend(): bool
    {
        return $this->strategy === Constants\LotImageImport::INSERT_STRATEGY_APPEND;
    }
}
