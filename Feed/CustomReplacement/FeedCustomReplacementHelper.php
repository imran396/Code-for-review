<?php
/**
 * SAM-4440: Refactor feed management logic to \Sam\Feed namespace
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/15/2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Feed\CustomReplacement;

use FeedCustomReplacement;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\FeedCustomReplacement\FeedCustomReplacementReadRepositoryCreateTrait;

/**
 * Class FeedCustomReplacementHelper
 * @package Sam\Feed\CustomReplacement
 */
class FeedCustomReplacementHelper extends CustomizableClass
{
    use FeedCustomReplacementReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Count all custom replacements for a feed
     *
     * @param int $feedId
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function countForFeed(int $feedId, bool $isReadOnlyDb = false): int
    {
        return $this->createFeedCustomReplacementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterFeedId($feedId)
            ->count();
    }

    /**
     * Load all custom replacements for a feed
     *
     * @param int $feedId
     * @param bool $isReadOnlyDb
     * @return FeedCustomReplacement[] of feed custom replacement objects
     */
    public function loadForFeed(int $feedId, bool $isReadOnlyDb = false): array
    {
        return $this->createFeedCustomReplacementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterFeedId($feedId)
            ->loadEntities();
    }

    /**
     * Checks whether order value exists in feed
     *
     * @param float $order
     * @param int $feedId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existOrderInFeed(float $order, int $feedId, bool $isReadOnlyDb = false): bool
    {
        return $this->createFeedCustomReplacementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterFeedId($feedId)
            ->filterOrder($order)
            ->exist();
    }

    /**
     * Checks whether original value exists in feed
     *
     * @param string $original
     * @param int $feedId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existOriginalInFeed(string $original, int $feedId, bool $isReadOnlyDb = false): bool
    {
        return $this->createFeedCustomReplacementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterFeedId($feedId)
            ->filterOriginal($original)
            ->exist();
    }

    /**
     * Suggest next order value for new custom replacement line
     *
     * @param int $feedId
     * @param bool $isReadOnlyDb
     * @return int order next value
     */
    public function suggestNextOrder(int $feedId, bool $isReadOnlyDb = false): int
    {
        $row = $this->createFeedCustomReplacementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterFeedId($feedId)
            ->select(['MAX(fcr.`order`) AS max_order'])
            ->loadRow();
        $order = $row ? floor($row['max_order'] ?? 0) + 1 : 1;
        return $order;
    }
}
