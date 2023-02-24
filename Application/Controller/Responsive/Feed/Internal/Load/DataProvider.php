<?php
/**
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 13, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Feed\Internal\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\Feed\FeedReadRepositoryCreateTrait;
use Sam\User\Auth\Credentials\Validate\CredentialsCheckerCreateTrait;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;

/**
 * Class DataProvider
 * @package Sam\Application\Controller\Responsive\Feed\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use AuthIdentityManagerCreateTrait;
    use CredentialsCheckerCreateTrait;
    use FeedReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadFeedConfig(string $slug, int $accountId, bool $isReadOnlyDb = false): ?FeedConfig
    {
        if ($slug === Constants\Feed::CATEGORY_SLUG) {
            return FeedConfig::new()->construct(Constants\Feed::TYPE_CATEGORY, false);
        }

        $row = $this->createFeedReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->filterSlug($slug)
            ->select(['feed_type', 'include_in_reports'])
            ->loadRow();
        return $row
            ? FeedConfig::new()->construct($row['feed_type'], (bool)$row['include_in_reports'])
            : null;
    }

    public function loadUserIdByCredentials(string $username, string $password): ?int
    {
        if (!$username || !$password) {
            return null;
        }

        $credentialsChecker = $this->createCredentialsChecker()->construct($username, $password);
        if (!$credentialsChecker->verify()) {
            return null;
        }

        return $credentialsChecker->getUserId();
    }

    public function detectLoggedInUserId(): ?int
    {
        return $this->createAuthIdentityManager()->getUserId();
    }
}
