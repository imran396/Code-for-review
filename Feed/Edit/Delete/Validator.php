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

use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Feed\Edit\Internal\Validate\FeedEditorConstants;
use Sam\Feed\Edit\Internal\Validate\ValidationHelperCreateTrait;

/**
 * Class Validate
 * @package Sam\Feed\Edit
 */
class Validator extends CustomizableClass
{
    use EditorUserAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use ValidationHelperCreateTrait;

    /**
     * @var array
     */
    protected const ERROR_MESSAGES = [
        FeedEditorConstants::ERR_FEED_ABSENT => 'Feed absent',
        FeedEditorConstants::ERR_FEED_DELETED => 'Feed already deleted',
        FeedEditorConstants::ERR_USER_ABSENT => 'User absent',
        FeedEditorConstants::ERR_NO_ACCESS_BY_PRIVILEGE => 'Not enough privileges to access',
        FeedEditorConstants::ERR_NO_ACCESS_BY_ACCOUNT => 'Access rejected to feed of another account',
    ];

    /** @var int */
    protected int $feedId;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $feedId
     * @param int $editorUserId
     * @return $this
     */
    public function construct(int $feedId, int $editorUserId): static
    {
        $this->feedId = $feedId;
        $this->setEditorUserId($editorUserId);
        return $this;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $collector = $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES);

        $validationHelper = $this->createValidationHelper()->construct($collector);
        $success = $validationHelper->validateEntity($this->feedId)
            && $validationHelper->validateAccess($this->feedId, $this->getEditorUserId());
        $this->setResultStatusCollector($validationHelper->getResultStatusCollector()); // JIC
        return $success;
    }

    /**
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage("\n");
    }
}
