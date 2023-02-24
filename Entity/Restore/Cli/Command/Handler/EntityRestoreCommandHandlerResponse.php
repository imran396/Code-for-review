<?php
/**
 * SAM-6856: Soft-deleted Auction restore
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Entity\Restore\Cli\Command\Handler;

use Sam\Core\Save\ResultStatus\ResultStatusCollector;
use Sam\Core\Service\CustomizableClass;

/**
 * Contains the result data of the restore process for a softly deleted entity
 *
 * Class EntityRestoreCommandHandlerResponse
 * @package Sam\Entity\Restore\Cli\Command\Handler
 */
class EntityRestoreCommandHandlerResponse extends CustomizableClass
{
    /**
     * @var ResultStatusCollector
     */
    protected ResultStatusCollector $resultStatusCollector;


    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param ResultStatusCollector $resultStatusCollector
     * @return static
     */
    public function construct(ResultStatusCollector $resultStatusCollector): static
    {
        $this->resultStatusCollector = $resultStatusCollector;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->resultStatusCollector->hasError();
    }

    /**
     * @return bool
     */
    public function hasSuccess(): bool
    {
        return $this->resultStatusCollector->hasSuccess();
    }

    /**
     * @return bool
     */
    public function hasWarning(): bool
    {
        return $this->resultStatusCollector->hasWarning();
    }

    /**
     * @return bool
     */
    public function hasInfo(): bool
    {
        return $this->resultStatusCollector->hasInfo();
    }

    /**
     * @return string[]
     */
    public function errorMessages(): array
    {
        return $this->resultStatusCollector->getErrorMessages();
    }

    /**
     * @return string[]
     */
    public function successMessages(): array
    {
        return $this->resultStatusCollector->getSuccessMessages();
    }

    /**
     * @return string[]
     */
    public function warningMessages(): array
    {
        return $this->resultStatusCollector->getWarningMessages();
    }

    /**
     * @return string[]
     */
    public function infoMessages(): array
    {
        return $this->resultStatusCollector->getInfoMessages();
    }
}
