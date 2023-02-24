<?php
/**
 * SAM-5397: Token Link SSO
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/19/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Sso\TokenLink\Config\TokenLinkConfiguratorAwareTrait;
use Sam\Sso\TokenLink\Load\TokenLinkDataLoaderCreateTrait;

/**
 * Class TokenLinkFeatureAvailabilityValidator
 * @package
 */
class TokenLinkFeatureAvailabilityValidator extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;
    use TokenLinkDataLoaderCreateTrait;
    use TokenLinkConfiguratorAwareTrait;

    public const ERR_FEATURE_DISABLED = 1;
    public const ERR_PASSPHRASE_EMPTY = 2;
    public const ERR_SECRET_ATTRIBUTE_UNSET = 3;
    public const ERR_SECRET_ATTRIBUTE_WRONG_FORMAT = 4;
    public const ERR_SECRET_ATTRIBUTE_WRONG_TABLE = 5;
    public const ERR_SECRET_ATTRIBUTE_WRONG_COLUMN = 6;
    public const ERR_CACHE_TYPE_WRONG = 7;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * To initialize instance properties
     */
    public function initInstance(): static
    {
        $errorMessages = [
            self::ERR_FEATURE_DISABLED => 'Feature disabled in installation config',
            self::ERR_PASSPHRASE_EMPTY => 'Passphrase is not set',
            self::ERR_SECRET_ATTRIBUTE_UNSET => 'Token link SSO secretUserAttribute is not set',
            self::ERR_SECRET_ATTRIBUTE_WRONG_FORMAT => 'Secret has wrong format, true format: {table}.{attribute}',
            self::ERR_SECRET_ATTRIBUTE_WRONG_TABLE => 'Secret uses wrong table',
            self::ERR_SECRET_ATTRIBUTE_WRONG_COLUMN => 'Secret uses wrong column',
            self::ERR_CACHE_TYPE_WRONG => 'Wrong cacheType config value',
        ];
        $this->getResultStatusCollector()->initAllErrors($errorMessages);
        return $this;
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        $collector = $this->getResultStatusCollector();
        $configurator = $this->getTokenLinkConfigurator();

        if (!$configurator->isFeatureEnabled()) {
            $collector->addError(self::ERR_FEATURE_DISABLED);
        }

        if (!$configurator->getPassphrase()) {
            $collector->addError(self::ERR_PASSPHRASE_EMPTY);
        }

        if (!$configurator->getSecretUserAttribute()) {
            $collector->addError(self::ERR_SECRET_ATTRIBUTE_UNSET);
        }

        if (!str_contains($configurator->getSecretUserAttribute(), Constants\TokenLink::TOKEN_SEPARATOR)) {
            $collector->addError(self::ERR_SECRET_ATTRIBUTE_WRONG_FORMAT);
        }

        $secretTable = $configurator->getSecretTable();
        if (!$this->createTokenLinkDataLoader()->isTableExist($secretTable)) {
            $collector->addError(self::ERR_SECRET_ATTRIBUTE_WRONG_TABLE);
        }

        $secretColumn = $configurator->getSecretColumn();
        if (!$this->createTokenLinkDataLoader()->isColumnExist((string)$secretTable, (string)$secretColumn)) {
            $collector->addError(self::ERR_SECRET_ATTRIBUTE_WRONG_COLUMN);
        }

        if (!$configurator->getCacheType()) {
            $collector->addError(self::ERR_CACHE_TYPE_WRONG);
        }

        $success = !$this->getResultStatusCollector()->hasError();
        return $success;
    }

    /**
     * @return string[]
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * @param array $errorCodes
     * @return string|null
     * @internal
     */
    public function findFirstErrorMessageAmongCodes(array $errorCodes): ?string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($errorCodes);
    }
}
