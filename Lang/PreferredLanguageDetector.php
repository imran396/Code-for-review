<?php
/**
 * SAM-4667: Preferred language detector
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/1/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lang;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\UserInfo\UserInfoReadRepositoryCreateTrait;

/**
 * Class PreferredLanguageDetector
 * @package Sam\Lang
 */
class PreferredLanguageDetector extends CustomizableClass
{
    use UserInfoReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Result with view language defined in user's profile
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    public function detectForUser(?int $userId, bool $isReadOnlyDb = false): ?int
    {
        if (!$userId) {
            return null;
        }
        $row = $this->createUserInfoReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->select(['ui.view_language'])
            ->filterUserId($userId)
            ->loadRow();
        return Cast::toInt($row['view_language'] ?? null);
    }

}
