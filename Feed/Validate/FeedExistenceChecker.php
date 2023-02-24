<?php
/**
 * SAM-4440: Refactor feed management logic to \Sam\Feed namespace
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/15/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Feed\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Filter\EntityLoader\FeedAllFilterTrait;

/**
 * Class FeedExistenceChecker
 * @package Sam\Feed\Validate
 */
class FeedExistenceChecker extends CustomizableClass
{
    use FeedAllFilterTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->initFilter();
        return $this;
    }

    /**
     * Check whether a feed with slug exists for account
     *
     * @param string $slug Feed slug
     * @param int|null $accountId account.id
     * @param int[] $skipIds optional feed.id
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existBySlug(
        string $slug,
        ?int $accountId = null,
        array $skipIds = [],
        bool $isReadOnlyDb = false
    ): bool {
        $feed = $this->prepareRepository($isReadOnlyDb)
            ->filterSlug($slug);
        if ($accountId) {
            $feed->filterAccountId($accountId);
        }
        if ($skipIds) {
            $feed->skipId($skipIds);
        }
        $isFound = $feed->exist();
        return $isFound;
    }
}
