<?php
/**
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           2/25/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Protect\Password\Expiry;

/**
 * Trait PasswordExpiryProtectorCreateTrait
 * @package
 */
trait PasswordExpiryProtectorCreateTrait
{
    /**
     * @var PasswordExpiryProtector|null
     */
    protected ?PasswordExpiryProtector $passwordExpiryProtector = null;

    /**
     * @return PasswordExpiryProtector
     */
    protected function createPasswordExpiryProtector(): PasswordExpiryProtector
    {
        return $this->passwordExpiryProtector ?: PasswordExpiryProtector::new();
    }

    /**
     * @param PasswordExpiryProtector $passwordExpiryProtector
     * @return $this
     * @noinspection PhpUnused
     * @internal
     */
    public function setPasswordExpiryProtector(PasswordExpiryProtector $passwordExpiryProtector): static
    {
        $this->passwordExpiryProtector = $passwordExpiryProtector;
        return $this;
    }
}
