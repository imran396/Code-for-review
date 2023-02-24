<?php
/**
 * SAM-4697: Feed entity editor
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 10, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Feed\Edit\Save;

use Sam\Account\Validate\AccountExistenceCheckerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Validate\CurrencyExistenceCheckerAwareTrait;
use Sam\Feed\Edit\Internal\Load\DataProviderAwareTrait;
use Sam\Feed\Edit\Internal\Normalize\NormalizerAwareTrait;
use Sam\Feed\Edit\Internal\Normalize\NormalizerBase;
use Sam\Feed\Edit\Internal\Validate\FeedEditorConstants;
use Sam\Feed\Edit\Internal\Validate\ValidationHelperCreateTrait;
use Sam\Feed\Validate\FeedExistenceCheckerAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Locale\LocaleProviderCreateTrait;
use Sam\Settings\Encoding\EncodingHelperAwareTrait;

/**
 * Class Validator
 * @package Sam\Feed\Edit
 */
class Validator extends CustomizableClass
{
    use AccountExistenceCheckerAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrencyExistenceCheckerAwareTrait;
    use DataProviderAwareTrait;
    use EditorUserAwareTrait;
    use EncodingHelperAwareTrait;
    use FeedExistenceCheckerAwareTrait;
    use LocaleProviderCreateTrait;
    use NormalizerAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use ValidationHelperCreateTrait;

    /**
     * @var Dto
     */
    protected Dto $dto;
    /**
     * Name length limit.
     * @var int
     */
    protected int $nameLength;
    /**
     * Slug length limit.
     * @var int
     */
    protected int $slugLength;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Dto $dto
     * @param int $editorUserId
     * @param NormalizerBase $normalizer
     * @param array $options = [
     *  'nameLength' => xx // Name length limit.
     *  'slugLength' => xx // Slug length limit.
     * ]
     * @return $this
     */
    public function construct(
        Dto $dto,
        int $editorUserId,
        NormalizerBase $normalizer,
        array $options = []
    ): Validator {
        $this->dto = $dto;
        $this->setEditorUserId($editorUserId);
        $this->setNormalizer($normalizer);
        $this->nameLength = $options['nameLength'] ?? $this->cfg()->get('core->feed->name->lengthLimit');
        $this->slugLength = $options['slugLength'] ?? $this->cfg()->get('core->feed->slug->lengthLimit');
        return $this;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $this->initResultStatusCollector();

        $dto = $this->dto;
        if (!$dto->isNew()) {
            $feedId = $this->getNormalizer()->toInt($dto->feedId);
            $validationHelper = $this->createValidationHelper()
                ->construct($this->getResultStatusCollector());
            $success = $validationHelper->validateEntity($feedId)
                && $validationHelper->validateAccess($feedId, $this->getEditorUserId());
            if (!$success) {
                $this->setResultStatusCollector($validationHelper->getResultStatusCollector()); // JIC
                return false;
            }
        }

        $success = $this->validateInput();
        return $success;
    }

    /**
     * @return bool
     */
    public function hasEntityError(): bool
    {
        $hasError = $this->getResultStatusCollector()
            ->hasConcreteError(FeedEditorConstants::ENTITY_ERRORS);
        return $hasError;
    }

    /**
     * @return string
     */
    public function entityErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes(FeedEditorConstants::ENTITY_ERRORS);
        return $errorMessage;
    }

    /**
     * @return bool
     */
    public function hasAccessError(): bool
    {
        $hasError = $this->getResultStatusCollector()
            ->hasConcreteError(FeedEditorConstants::ACCESS_ERRORS);
        return $hasError;
    }

    /**
     * @return string
     */
    public function accessErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes(FeedEditorConstants::ACCESS_ERRORS);
    }

    /**
     * @return bool
     */
    public function hasAccountError(): bool
    {
        $hasError = $this->getResultStatusCollector()
            ->hasConcreteError(FeedEditorConstants::ACCOUNT_ERRORS);
        return $hasError;
    }

    /**
     * @return string
     */
    public function accountErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes(FeedEditorConstants::ACCOUNT_ERRORS);
        return $errorMessage;
    }

    /**
     * @return bool
     */
    public function hasCacheTimeoutError(): bool
    {
        $hasError = $this->getResultStatusCollector()
            ->hasConcreteError(FeedEditorConstants::CACHE_TIMEOUT_ERRORS);
        return $hasError;
    }

    /**
     * @return string
     */
    public function cacheTimeoutErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes(FeedEditorConstants::CACHE_TIMEOUT_ERRORS);
        return $errorMessage;
    }

    /**
     * @return bool
     */
    public function hasCurrencyError(): bool
    {
        $hasError = $this->getResultStatusCollector()
            ->hasConcreteError(FeedEditorConstants::CURRENCY_ERRORS);
        return $hasError;
    }

    /**
     * @return string
     */
    public function currencyErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes(FeedEditorConstants::CURRENCY_ERRORS);
        return $errorMessage;
    }

    /**
     * @return bool
     */
    public function hasEncodingError(): bool
    {
        $hasError = $this->getResultStatusCollector()
            ->hasConcreteError(FeedEditorConstants::ENCODING_ERRORS);
        return $hasError;
    }

    /**
     * @return string
     */
    public function encodingErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes(FeedEditorConstants::ENCODING_ERRORS);
        return $errorMessage;
    }

    /**
     * @return bool
     */
    public function hasEscapingError(): bool
    {
        $hasError = $this->getResultStatusCollector()
            ->hasConcreteError(FeedEditorConstants::ESCAPING_ERRORS);
        return $hasError;
    }

    /**
     * @return string
     */
    public function escapingErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes(FeedEditorConstants::ESCAPING_ERRORS);
        return $errorMessage;
    }

    /**
     * @return bool
     */
    public function hasItemsPerPageError(): bool
    {
        $hasError = $this->getResultStatusCollector()
            ->hasConcreteError(FeedEditorConstants::ITEMS_PER_PAGE_ERRORS);
        return $hasError;
    }

    /**
     * @return string
     */
    public function itemsPerPageErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes(FeedEditorConstants::ITEMS_PER_PAGE_ERRORS);
        return $errorMessage;
    }

    /**
     * @return bool
     */
    public function hasLocaleError(): bool
    {
        $hasError = $this->getResultStatusCollector()
            ->hasConcreteError(FeedEditorConstants::LOCALE_ERRORS);
        return $hasError;
    }

    /**
     * @return string
     */
    public function localeErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes(FeedEditorConstants::LOCALE_ERRORS);
    }

    /**
     * @return bool
     */
    public function hasNameError(): bool
    {
        $hasError = $this->getResultStatusCollector()
            ->hasConcreteError(FeedEditorConstants::NAME_ERRORS);
        return $hasError;
    }

    /**
     * @return string
     */
    public function nameErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes(FeedEditorConstants::NAME_ERRORS);
        return $errorMessage;
    }

    /**
     * @return bool
     */
    public function hasSlugError(): bool
    {
        $hasError = $this->getResultStatusCollector()
            ->hasConcreteError(FeedEditorConstants::SLUG_ERRORS);
        return $hasError;
    }

    /**
     * @return string
     */
    public function slugErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes(FeedEditorConstants::SLUG_ERRORS);
        return $errorMessage;
    }

    /**
     * @return bool
     */
    public function hasTypeError(): bool
    {
        $hasError = $this->getResultStatusCollector()
            ->hasConcreteError(FeedEditorConstants::TYPE_ERRORS);
        return $hasError;
    }

    /**
     * @return string
     */
    public function typeErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes(FeedEditorConstants::TYPE_ERRORS);
        return $errorMessage;
    }

    /**
     * @return string
     */
    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    /**
     * @return int[]
     * @internal
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    protected function initResultStatusCollector(): void
    {
        $errorMessages = [
            FeedEditorConstants::ERR_FEED_ABSENT => 'Feed absent',
            FeedEditorConstants::ERR_FEED_DELETED => 'Feed deleted',
            FeedEditorConstants::ERR_USER_ABSENT => 'Editor user absent',
            FeedEditorConstants::ERR_NO_ACCESS_BY_PRIVILEGE => 'Not enough privileges to access',
            FeedEditorConstants::ERR_NO_ACCESS_BY_ACCOUNT => 'Access rejected to feed of another account',
            FeedEditorConstants::ERR_ACCOUNT_REQUIRED => 'Account required',
            FeedEditorConstants::ERR_ACCOUNT_INVALID => 'Account invalid',
            FeedEditorConstants::ERR_ACCOUNT_ABSENT => 'Account absent',
            FeedEditorConstants::ERR_CACHE_TIMEOUT_REQUIRED => 'Cache timeout required',
            FeedEditorConstants::ERR_CACHE_TIMEOUT_INVALID => 'Cache timeout invalid',
            FeedEditorConstants::ERR_CURRENCY_REQUIRED => 'Currency required',
            FeedEditorConstants::ERR_CURRENCY_INVALID => 'Currency invalid',
            FeedEditorConstants::ERR_CURRENCY_ABSENT => 'Currency absent',
            FeedEditorConstants::ERR_ENCODING_REQUIRED => 'Encoding required',
            FeedEditorConstants::ERR_ENCODING_INVALID => 'Encoding invalid',
            FeedEditorConstants::ERR_ESCAPING_REQUIRED => 'Escaping required',
            FeedEditorConstants::ERR_ESCAPING_INVALID => 'Escaping invalid',
            FeedEditorConstants::ERR_ITEMS_PER_PAGE_REQUIRED => 'Items per page required',
            FeedEditorConstants::ERR_ITEMS_PER_PAGE_INVALID => 'Items per page must be positive integer',
            FeedEditorConstants::ERR_LOCALE_REQUIRED => 'Locale required',
            FeedEditorConstants::ERR_LOCALE_INVALID => 'Locale invalid',
            FeedEditorConstants::ERR_NAME_REQUIRED => 'Name required',
            FeedEditorConstants::ERR_NAME_LENGTH => "{$this->nameLength} symbols length is name limit",
            FeedEditorConstants::ERR_SLUG_REQUIRED => 'Slug required',
            FeedEditorConstants::ERR_SLUG_LENGTH => "{$this->slugLength} symbols length is slug limit",
            FeedEditorConstants::ERR_SLUG_EXIST => 'Slug exists',
            FeedEditorConstants::ERR_TYPE_REQUIRED => 'Type required',
            FeedEditorConstants::ERR_TYPE_INVALID => 'Type invalid',
        ];
        $this->getResultStatusCollector()->construct($errorMessages);
    }

    /**
     * @return bool
     */
    protected function validateInput(): bool
    {
        $this->validateAccount();
        $this->validateCacheTimeout();
        $this->validateCurrency();
        $this->validateEncoding();
        $this->validateEscaping();
        $this->validateItemPerPage();
        $this->validateLocale();
        $this->validateName();
        $this->validateSlug();
        $this->validateType();
        $success = !$this->getResultStatusCollector()->hasError();
        if (!$success) {
            log_debug(
                'Feed validation failed'
                . composeSuffix(['errors' => $this->getResultStatusCollector()->getConcatenatedErrorMessage('; ')])
            );
        }
        return $success;
    }

    /**
     * @return bool
     */
    protected function validateAccount(): bool
    {
        $dto = $this->dto;
        $collector = $this->getResultStatusCollector();
        $normalizer = $this->getNormalizer();
        if (isset($dto->accountId)) {
            if (!$normalizer->isIntPositive($dto->accountId)) {
                $collector->addError(FeedEditorConstants::ERR_ACCOUNT_INVALID);
                return false;
            }
            if (!$this->getAccountExistenceChecker()->existById($normalizer->toInt($dto->accountId), true)) {
                $collector->addError(FeedEditorConstants::ERR_ACCOUNT_ABSENT);
                return false;
            }
        } else {
            if ($dto->isNew()) {
                $collector->addError(FeedEditorConstants::ERR_ACCOUNT_REQUIRED);
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function validateCacheTimeout(): bool
    {
        $dto = $this->dto;
        $collector = $this->getResultStatusCollector();
        $normalizer = $this->getNormalizer();
        if (isset($dto->cacheTimeout)) {
            if (!$normalizer->isIntPositive($dto->cacheTimeout)) {
                $collector->addError(FeedEditorConstants::ERR_CACHE_TIMEOUT_INVALID);
                return false;
            }
        } else {
            if ($dto->isNew()) {
                $collector->addError(FeedEditorConstants::ERR_CACHE_TIMEOUT_REQUIRED);
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function validateCurrency(): bool
    {
        $dto = $this->dto;
        $collector = $this->getResultStatusCollector();
        $normalizer = $this->getNormalizer();
        if (isset($dto->currencyId)) {
            if (!$normalizer->isIntPositive($dto->currencyId)) {
                $collector->addError(FeedEditorConstants::ERR_CURRENCY_INVALID);
                return false;
            }
            if (!$this->getCurrencyExistenceChecker()->existById($normalizer->toInt($dto->currencyId), true)) {
                $collector->addError(FeedEditorConstants::ERR_CURRENCY_ABSENT);
                return false;
            }
        } else {
            if ($dto->isNew()) {
                $collector->addError(FeedEditorConstants::ERR_CURRENCY_REQUIRED);
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function validateEncoding(): bool
    {
        $dto = $this->dto;
        $collector = $this->getResultStatusCollector();
        if (isset($dto->encoding)) {
            if (!$this->getEncodingHelper()->isAvailable($dto->encoding, false)) {
                $collector->addError(FeedEditorConstants::ERR_ENCODING_INVALID);
                return false;
            }
        } else {
            if ($dto->isNew()) {
                $collector->addError(FeedEditorConstants::ERR_ENCODING_REQUIRED);
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function validateEscaping(): bool
    {
        $dto = $this->dto;
        $collector = $this->getResultStatusCollector();
        $normalizer = $this->getNormalizer();
        if (isset($dto->escaping)) {
            $escaping = $normalizer->toInt($dto->escaping);
            if (!in_array($escaping, Constants\Feed::$escapings, true)) {
                $collector->addError(FeedEditorConstants::ERR_ESCAPING_INVALID);
                return false;
            }
        } else {
            if ($dto->isNew()) {
                $collector->addError(FeedEditorConstants::ERR_ESCAPING_REQUIRED);
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function validateItemPerPage(): bool
    {
        $dto = $this->dto;
        $collector = $this->getResultStatusCollector();
        $normalizer = $this->getNormalizer();
        if (isset($dto->itemsPerPage)) {
            if (!$dto->itemsPerPage) {
                $collector->addError(FeedEditorConstants::ERR_ITEMS_PER_PAGE_REQUIRED);
                return false;
            }
            if (!$normalizer->isIntPositive($dto->itemsPerPage)) {
                $collector->addError(FeedEditorConstants::ERR_ITEMS_PER_PAGE_INVALID);
                return false;
            }
        } else {
            if ($dto->isNew()) {
                $collector->addError(FeedEditorConstants::ERR_ITEMS_PER_PAGE_REQUIRED);
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function validateLocale(): bool
    {
        $dto = $this->dto;
        $collector = $this->getResultStatusCollector();
        if (isset($dto->locale)) {
            if (!$dto->locale) {
                $collector->addError(FeedEditorConstants::ERR_LOCALE_REQUIRED);
                return false;
            }
            if (!$this->createLocaleProvider()->exist($dto->locale)) {
                $collector->addError(FeedEditorConstants::ERR_LOCALE_INVALID);
                return false;
            }
        } else {
            if ($dto->isNew()) {
                $collector->addError(FeedEditorConstants::ERR_LOCALE_REQUIRED);
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function validateName(): bool
    {
        $dto = $this->dto;
        $collector = $this->getResultStatusCollector();
        if (isset($dto->name)) {
            if (!$dto->name) {
                $collector->addError(FeedEditorConstants::ERR_NAME_REQUIRED);
                return false;
            }
            if (strlen($dto->name) > $this->nameLength) {
                $collector->addError(FeedEditorConstants::ERR_NAME_LENGTH);
                return false;
            }
        } else {
            if ($dto->isNew()) {
                $collector->addError(FeedEditorConstants::ERR_NAME_REQUIRED);
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function validateSlug(): bool
    {
        $dto = $this->dto;
        $collector = $this->getResultStatusCollector();
        $normalizer = $this->getNormalizer();

        if (isset($dto->slug)) {
            if (!$dto->slug) {
                $collector->addError(FeedEditorConstants::ERR_SLUG_REQUIRED);
                return false;
            }

            if (strlen($dto->slug) > $this->slugLength) {
                $collector->addError(FeedEditorConstants::ERR_SLUG_LENGTH);
                return false;
            }

            $isNew = $dto->isNew();
            $existenceChecker = $this->getFeedExistenceChecker();
            $accountId = $this->determineFeedAccountId();
            if ((
                    $isNew
                    && $existenceChecker->existBySlug($dto->slug, $accountId)
                ) || (
                    !$isNew
                    && $existenceChecker->existBySlug($dto->slug, $accountId, [$normalizer->toInt($dto->feedId)])
                )
            ) {
                $collector->addError(FeedEditorConstants::ERR_SLUG_EXIST);
                return false;
            }
        } else {
            if ($dto->isNew()) {
                $collector->addError(FeedEditorConstants::ERR_SLUG_REQUIRED);
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function validateType(): bool
    {
        $dto = $this->dto;
        $collector = $this->getResultStatusCollector();
        if (isset($dto->feedType)) {
            if (!$dto->feedType) {
                $collector->addError(FeedEditorConstants::ERR_TYPE_REQUIRED);
                return false;
            }
            if (!in_array($dto->feedType, Constants\Feed::$types, true)) {
                $collector->addError(FeedEditorConstants::ERR_TYPE_INVALID);
                return false;
            }
        } else {
            if ($dto->isNew()) {
                $collector->addError(FeedEditorConstants::ERR_TYPE_REQUIRED);
                return false;
            }
        }
        return true;
    }

    /**
     * @return int|null
     */
    protected function determineFeedAccountId(): ?int
    {
        $dto = $this->dto;
        $normalizer = $this->getNormalizer();
        $accountId = null;
        if (isset($dto->accountId)) {
            $accountId = $normalizer->toInt($dto->accountId);
        } elseif (!$dto->isNew()) {
            $feedId = $normalizer->toInt($dto->feedId);
            $feed = $this->getDataProvider()->loadFeedById($feedId);
            $accountId = $feed->AccountId ?? null;
        }
        return $accountId;
    }
}
