<?php

/**
 * General repository for Feed entity
 *
 * SAM-3678 : Repositories for Feed tables
 * https://bidpath.atlassian.net/browse/SAM-3678
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           02 April, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of buyer group user filtered by criteria
 * $feedRepository = \Sam\Storage\ReadRepository\Entity\Feed\FeedReadRepository::new()
 *      ->enableReadOnlyDb(true)
 *      ->filterId($ids);
 * $isFound = $feedRepository->exist();
 * $count = $feedRepository->count();
 * $feeds = $feedRepository->loadEntities();
 *
 */

namespace Sam\Storage\ReadRepository\Entity\Feed;

/**
 * Class FeedReadRepository
 * @package Sam\Storage\ReadRepository\Entity\Feed
 */
class FeedReadRepository extends AbstractFeedReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'account' => 'JOIN account acc ON acc.id = f.account_id ',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join 'account' table
     * @return $this
     */
    public function joinAccount(): static
    {
        $this->join('account');
        return $this;
    }

    /**
     * Define ordering by acc.name
     * @param bool $ascending
     * @return $this
     */
    public function joinAccountOrderByName(bool $ascending = true): static
    {
        $this->joinAccount();
        $this->order('acc.name', $ascending);
        return $this;
    }
}
