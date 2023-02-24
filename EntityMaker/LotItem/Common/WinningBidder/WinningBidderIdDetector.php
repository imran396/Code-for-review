<?php
/**
 * SAM-10106: Supply lot winning info correspondence for winning auction and winning bidder fields
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Common\WinningBidder;

use Sam\Core\Service\CustomizableClass;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class WinningBidderIdDetector
 * @package Sam\EntityMaker\LotItem
 */
class WinningBidderIdDetector extends CustomizableClass
{
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function detectFromInput(WinningBidderInput $input, ?int $syncNamespaceId, int $accountId, bool $isReadOnlyDb = false): ?int
    {
        if ($input->id) {
            return $input->id;
        }
        if (
            $input->syncKey
            && $syncNamespaceId
        ) {
            $bidder = $this->getUserLoader()->loadBySyncKey(
                $input->syncKey,
                $syncNamespaceId,
                $accountId,
                $isReadOnlyDb
            );
            return $bidder->Id ?? null;
        }
        if ($input->name) {
            $bidder = $this->getUserLoader()->loadByUsername($input->name, $isReadOnlyDb);
            return $bidder->Id ?? null;
        }
        return null;
    }
}
