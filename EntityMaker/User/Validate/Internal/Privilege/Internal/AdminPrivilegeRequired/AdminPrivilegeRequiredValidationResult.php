<?php
/**
 * SAM-9520: Important Security user privilege issue
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\AdminPrivilegeRequired;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Contains admin privilege required validation errors
 *
 * Class AdminPrivilegeRequiredValidationResult
 * @package Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\AdminPrivilegeRequired
 */
class AdminPrivilegeRequiredValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_NO_ONE_ADMIN_PRIVILEGES_SELECTED = 1;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_NO_ONE_ADMIN_PRIVILEGES_SELECTED => 'No one admin privileges are selected',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES);
        return $this;
    }

    public function addError(int $errorCode): static
    {
        $this->getResultStatusCollector()->addError($errorCode);
        return $this;
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }
}
