<?php
/**
 * SAM-4697: Feed entity editor
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           3/21/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Feed\Edit\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Feed;
use Sam\Feed\Edit\Internal\Exception\CouldNotFindFeed;
use Sam\Feed\Load\FeedLoaderAwareTrait;

/**
 * Class DataProvider
 * @package
 */
class DataProvider extends CustomizableClass
{
    use FeedLoaderAwareTrait;

    protected ?Feed $feed = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $feedId
     * @return Feed
     * @throws CouldNotFindFeed
     */
    public function loadFeedById(int $feedId): Feed
    {
        if ($this->feed === null) {
            $this->feed = $this->getFeedLoader()
                ->clear()
                ->load($feedId, true);
        }
        if (!$this->feed) {
            throw CouldNotFindFeed::withId($feedId);
        }
        return $this->feed;
    }
}
