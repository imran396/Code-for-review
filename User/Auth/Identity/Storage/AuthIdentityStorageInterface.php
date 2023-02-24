<?php
/**
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/19/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Auth\Identity\Storage;

use Sam\User\Auth\Identity\Dto\AuthIdentityDto;

/**
 * Interface AuthIdentityStorageInterface
 * @package
 */
interface AuthIdentityStorageInterface
{
    /**
     * @return void
     */
    public function clearIdentity(): void;

    /**
     * @return bool
     */
    public function hasIdentityStored(): bool;

    /**
     * @return AuthIdentityDto|null
     */
    public function readIdentity(): ?AuthIdentityDto;

    /**
     * @return AuthIdentityDto
     */
    public function readIdentityOrCreate(): AuthIdentityDto;

    /**
     * @param AuthIdentityDto|null $dto
     */
    public function writeIdentity(?AuthIdentityDto $dto): void;

    /**
     * @return bool
     */
    public function isApplicable(): bool;
}
