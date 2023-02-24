<?php
/**
 * SAM-3736: Additional Signup Confirmation repository and manager
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/17/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Signup\AdditionalConfirmation\Load;

use AdditionalSignupConfirmation;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\AdditionalSignupConfirmation\AdditionalSignupConfirmationReadRepositoryCreateTrait;

/**
 * Class AdditionalSignupConfirmationLoader
 * @package Sam\User\Signup\AdditionalConfirmation\Load
 */
class AdditionalSignupConfirmationLoader extends EntityLoaderBase
{
    use AdditionalSignupConfirmationReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return AdditionalSignupConfirmation[]
     */
    public function loadAll(): array
    {
        $additionalSignupConfirmations = $this->createAdditionalSignupConfirmationReadRepository()
            ->orderById()
            ->loadEntities();
        return $additionalSignupConfirmations;
    }

    /**
     * @param int $additionalConfirmId
     * @param bool $isReadOnlyDb
     * @return AdditionalSignupConfirmation|null
     */
    public function loadById(int $additionalConfirmId, bool $isReadOnlyDb = false): ?AdditionalSignupConfirmation
    {
        $additionalSignupConfirmation = $this->createAdditionalSignupConfirmationReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($additionalConfirmId)
            ->loadEntity();

        return $additionalSignupConfirmation;
    }
}
