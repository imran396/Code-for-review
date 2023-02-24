<?php
/**
 * Feed Deleter
 *
 * SAM-5885: Refactor feed list management at admin side
 * SAM-5454: Extract data loading from form classes
 * SAM-4697: Feed entity editor
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

namespace Sam\Feed\Edit\Internal\Validate;

use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollector;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Feed\Edit\Internal\Exception\CouldNotFindFeed;
use Sam\Feed\Edit\Internal\Load\DataProviderAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class Validate
 */
class ValidationHelper extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DataProviderAwareTrait;
    use EditorUserAwareTrait;
    use ResultStatusCollectorAwareTrait;

    /**
     * Service configuration options
     * @var array
     */
    protected array $options = [];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param ResultStatusCollector $collector
     * @param array $options = [
     *     'isMultipleTenant' => bool, // by config defined
     * ]
     * @return $this
     */
    public function construct(ResultStatusCollector $collector, array $options = []): ValidationHelper
    {
        $this->setResultStatusCollector($collector);
        $this->options = $options;
        return $this;
    }

    /**
     * @param int $feedId
     * @return bool
     */
    public function validateEntity(int $feedId): bool
    {
        $collector = $this->getResultStatusCollector();
        try {
            $feed = $this->getDataProvider()->loadFeedById($feedId);
        } catch (CouldNotFindFeed) {
            $collector->addError(FeedEditorConstants::ERR_FEED_ABSENT);
            return false;
        }

        if (!$feed->Active) {
            $collector->addError(FeedEditorConstants::ERR_FEED_DELETED);
            return false;
        }

        return true;
    }

    /**
     * Check editor user access privilege to feed entity, including cross-account entity checking
     * @param int $feedId
     * @param int|null $editorUserId null leads to false and error message
     * @return bool
     */
    public function validateAccess(int $feedId, ?int $editorUserId): bool
    {
        $collector = $this->getResultStatusCollector();
        $this->setEditorUserId($editorUserId);
        if (!$this->getEditorUser()) {
            $collector->addError(FeedEditorConstants::ERR_USER_ABSENT);
            return false;
        }

        $checker = $this->getEditorUserAdminPrivilegeChecker();

        if (
            !$this->hasEditorUserAdminRole()
            || !$checker->hasPrivilegeForManageSettings()
        ) {
            $collector->addError(FeedEditorConstants::ERR_NO_ACCESS_BY_PRIVILEGE);
            return false;
        }

        if ($this->isMultipleTenant()) {
            $feed = $this->getDataProvider()->loadFeedById($feedId);
            if (
                $feed->AccountId !== $this->getEditorUser()->AccountId
                && !$checker->hasPrivilegeForSuperadmin()
            ) {
                $collector->addError(FeedEditorConstants::ERR_NO_ACCESS_BY_ACCOUNT);
                return false;
            }
        }

        return true;
    }

    /**
     * @return int[]
     * @internal
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * @return bool
     */
    protected function isMultipleTenant(): bool
    {
        return (bool)($this->options['isMultipleTenant'] ?? $this->cfg()->get('core->portal->enabled'));
    }
}
