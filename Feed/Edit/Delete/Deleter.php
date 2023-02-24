<?php
/**
 * Feed Deleter
 *
 * SAM-4697: Feed entity editor
 * SAM-5885: Refactor feed list management at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 6, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Feed\Edit\Delete;

use Feed;
use LogicException;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Feed\Edit\Internal\Load\DataProviderAwareTrait;
use Sam\Storage\WriteRepository\Entity\Feed\FeedWriteRepositoryAwareTrait;

/**
 * Class Deleter
 */
class Deleter extends CustomizableClass
{
    use DataProviderAwareTrait;
    use EditorUserAwareTrait;
    use FeedWriteRepositoryAwareTrait;

    /** @var int */
    protected int $feedId;
    /** @var Feed|null */
    protected ?Feed $feed = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $id
     * @param int $editorUserId
     * @return $this
     */
    public function construct(int $id, int $editorUserId): static
    {
        $this->feedId = $id;
        $this->setEditorUserId($editorUserId);
        return $this;
    }

    /**
     * Delete feed by id
     */
    public function delete(): void
    {
        $this->feed = $this->getDataProvider()->loadFeedById($this->feedId);
        $this->feed->Active = false;
        $this->getFeedWriteRepository()->saveWithModifier($this->feed, $this->getEditorUserId());
    }

    /**
     * Return deleted feed
     * @return Feed
     */
    public function getResultFeed(): Feed
    {
        if (!$this->feed) {
            throw new LogicException('You should call delete() first to load Feed');
        }
        return $this->feed;
    }
}
