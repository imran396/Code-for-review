<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 21, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Save\Internal\BuyersPremium;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\Mode;

/**
 * Class BuyersPremiumSavingInput
 * @package Sam\EntityMaker\LotItem
 */
class BuyersPremiumSavingInput extends CustomizableClass
{
    public readonly Mode $mode;
    public readonly ?string $buyersPremiumString;
    public readonly ?array $buyersPremiumDataRows;
    public readonly int $editorUserId;
    public readonly int $lotItemId;
    public readonly int $lotItemAccountId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Mode $mode
     * @param string|null $buyersPremiumString
     * @param array|null $buyersPremiumDataRows
     * @param int $editorUserId
     * @param int $lotItemId
     * @param int $lotItemAccountId
     * @return $this
     */
    public function construct(
        Mode $mode,
        ?string $buyersPremiumString,
        ?array $buyersPremiumDataRows,
        int $editorUserId,
        int $lotItemId,
        int $lotItemAccountId
    ): static {
        $this->mode = $mode;
        $this->buyersPremiumString = $buyersPremiumString;
        $this->buyersPremiumDataRows = $buyersPremiumDataRows;
        $this->editorUserId = $editorUserId;
        $this->lotItemId = $lotItemId;
        $this->lotItemAccountId = $lotItemAccountId;
        return $this;
    }

}
