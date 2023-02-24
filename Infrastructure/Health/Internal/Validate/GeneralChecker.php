<?php
/**
 * A general health checker, checks db and session access
 *
 * SAM-7956: Create a basic health check endpoint /health
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Health\Internal\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Health\Internal\Validate\Concrete\DbChecker;
use Sam\Infrastructure\Health\Internal\Validate\Concrete\SessionChecker;

/**
 * Class DbChecker
 * @package Sam\Infrastructure\Health
 */
class GeneralChecker extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_ADAPTER_INCORRECT = 1;
    public const ERR_DBAL_DISABLED = 2;
    public const ERR_DB_UNAVAILABLE = 3;
    public const ERR_SESSION_UNAVAILABLE = 4;

    /** @var string[] */
    public const ERROR_MESSAGES = [
        self::ERR_ADAPTER_INCORRECT => 'Incorrect DB adapter',
        self::ERR_DBAL_DISABLED => 'DBAL disabled by config',
        self::ERR_DB_UNAVAILABLE => 'DB not available',
        self::ERR_SESSION_UNAVAILABLE => 'Php native session unavailable',
    ];

    protected ?DbChecker $dbChecker = null;
    protected ?SessionChecker $sessionChecker = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $collector = $this->getResultStatusCollector();
        $collector->initAllErrors(static::ERROR_MESSAGES);

        $dbChecker = $this->createDbChecker();
        if (!$dbChecker->validate()) {
            if ($dbChecker->isAdapterIncorrect) {
                $collector->addError(self::ERR_ADAPTER_INCORRECT);
                return false;
            }
            if ($dbChecker->isDisabled) {
                $collector->addError(self::ERR_DBAL_DISABLED);
                return false;
            }
            $collector->addError(self::ERR_DB_UNAVAILABLE);
            return false;
        }

        $sessionChecker = $this->createSessionChecker();
        if (!$sessionChecker->isActive()) {
            $collector->addError(self::ERR_SESSION_UNAVAILABLE);
            return false;
        }

        return true;
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage(', ');
    }

    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function setDbChecker(DbChecker $dbChecker): static
    {
        $this->dbChecker = $dbChecker;
        return $this;
    }

    protected function createDbChecker(): DbChecker
    {
        return $this->dbChecker ?: DbChecker::new();
    }

    public function setSessionChecker(SessionChecker $sessionChecker): static
    {
        $this->sessionChecker = $sessionChecker;
        return $this;
    }

    protected function createSessionChecker(): SessionChecker
    {
        return $this->sessionChecker ?: SessionChecker::new();
    }
}
