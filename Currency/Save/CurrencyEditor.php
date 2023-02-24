<?php
/**
 *
 * SAM-4652: Currency editor service
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Currency\Save;

use Currency;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Currency\Validate\CurrencyExistenceCheckerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Storage\WriteRepository\Entity\Currency\CurrencyWriteRepositoryAwareTrait;

/**
 * Class CurrencyEditor
 * @package Sam\Currency\Save
 */
class CurrencyEditor extends CustomizableClass
{
    use AccountAwareTrait;
    use CurrencyExistenceCheckerAwareTrait;
    use CurrencyLoaderAwareTrait;
    use CurrencyWriteRepositoryAwareTrait;
    use CurrentDateTrait;
    use EditorUserAwareTrait;
    use EntityFactoryCreateTrait;
    use ResultStatusCollectorAwareTrait;

    public const ERR_NAME_REQUIRED = 1;
    public const ERR_NAME_EXIST = 2;
    public const ERR_SIGN_REQUIRED = 3;
    public const ERR_SIGN_EXIST = 4;
    public const ERR_EXRATE_REQUIRED = 5;
    public const ERR_EXRATE_NUMERIC = 6;
    public const ERR_SIGN_INVALID_CHAR = 7;

    /** @var int[] */
    protected array $nameErrors = [self::ERR_NAME_REQUIRED, self::ERR_NAME_EXIST];
    /** @var int[] */
    protected array $signErrors = [self::ERR_SIGN_REQUIRED, self::ERR_SIGN_EXIST, self::ERR_SIGN_INVALID_CHAR];
    /** @var int[] */
    protected array $exRateErrors = [self::ERR_EXRATE_REQUIRED, self::ERR_EXRATE_NUMERIC];

    protected string $code = '';
    protected ?Currency $currency = null;
    protected ?int $currencyId = null;
    protected string $exRate = '';
    protected string $name = '';
    protected string $sign = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Update Currency
     */
    public function update(): void
    {
        $currency = $this->getCurrency();
        if (!$currency) {
            $currency = $this->createEntityFactory()->currency();
            $currency->AccountId = $this->getAccountId();
        }

        $currency->Name = $this->getName();
        $currency->Sign = $this->getSign();
        $currency->ExRate = (float)$this->getExRate();
        $currency->Code = $this->getCode();
        $currency->Active = true;
        $this->getCurrencyWriteRepository()->saveWithModifier($currency, $this->getEditorUserId());
    }

    /**
     * Validate currency input
     * @return bool
     */
    public function validate(): bool
    {
        $this->initResultStatusCollector();
        $this->validateName();
        $this->validateSign();
        $this->validateExRate();
        $isValid = !$this->getResultStatusCollector()->hasError();
        return $isValid;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return static
     */
    public function setCode(string $code): static
    {
        $this->code = trim($code);
        return $this;
    }

    /**
     * @return Currency|null
     */
    public function getCurrency(): ?Currency
    {
        if ($this->currency === null) {
            $this->currency = $this->getCurrencyLoader()->load($this->getCurrencyId());
        }
        return $this->currency;
    }

    /**
     * @param Currency|null $currency
     * @return static
     */
    public function setCurrency(?Currency $currency): static
    {
        $this->currency = $currency;
        $this->setCurrencyId($currency->Id ?? null);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCurrencyId(): ?int
    {
        return $this->currencyId;
    }

    /**
     * @param int|null $currencyId
     * @return static
     */
    public function setCurrencyId(?int $currencyId): static
    {
        $this->currencyId = $currencyId;
        return $this;
    }

    /**
     * @return string
     */
    public function getExRate(): string
    {
        return $this->exRate;
    }

    /**
     * @param string $exRate
     * @return static
     */
    public function setExRate(string $exRate): static
    {
        $this->exRate = trim($exRate);
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set Name property input and normalize value to be ready for saving after validation
     * @param string $name
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = trim($name);
        return $this;
    }

    /**
     * @return string
     */
    public function getSign(): string
    {
        return $this->sign;
    }

    /**
     * Set Sign property and normalize value to be ready for saving after validation
     * @param string $sign
     * @return static
     */
    public function setSign(string $sign): static
    {
        $this->sign = TextTransformer::new()->cut(trim($sign), 3);
        return $this;
    }

    /**
     * Get Name Error Message
     * @return string
     */
    public function nameErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($this->nameErrors);
    }

    /**
     * @return bool
     */
    public function hasNameError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->nameErrors);
    }

    /**
     * Get Sign Error Message
     * @return string
     */
    public function signErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($this->signErrors);
    }

    /**
     * @return bool
     */
    public function hasSignError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->signErrors);
    }

    /**
     * Get ExRate Error Message
     * @return string
     */
    public function exRateErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($this->exRateErrors);
    }

    /**
     * @return bool
     */
    public function hasExRateError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->exRateErrors);
    }

    protected function initResultStatusCollector(): void
    {
        $errorMessages = [
            self::ERR_NAME_REQUIRED => 'Name required',
            self::ERR_NAME_EXIST => 'Name exists',
            self::ERR_SIGN_REQUIRED => 'Sign required',
            self::ERR_SIGN_EXIST => 'Sign exists',
            self::ERR_EXRATE_REQUIRED => 'Exchange Rate required',
            self::ERR_EXRATE_NUMERIC => 'Exchange Rate should be numeric',
            self::ERR_SIGN_INVALID_CHAR => 'Invalid char in sign name',
        ];
        $this->getResultStatusCollector()->construct($errorMessages);
    }

    /**
     * Validate currency name input
     */
    protected function validateName(): void
    {
        $collector = $this->getResultStatusCollector();
        $name = $this->getName();
        if ($name === '') {
            $collector->addError(self::ERR_NAME_REQUIRED);
        } else {
            $currency = $this->getCurrency();
            if (
                $currency
                && $currency->Name !== $name
                && $this->getCurrencyExistenceChecker()->existByName($name)
            ) {
                $collector->addError(self::ERR_NAME_EXIST);
            } elseif (
                !$currency
                && $this->getCurrencyExistenceChecker()->existByName($name)
            ) {
                $collector->addError(self::ERR_NAME_EXIST);
            }
        }
    }

    /**
     * Validate currency sign input
     */
    protected function validateSign(): void
    {
        $collector = $this->getResultStatusCollector();
        $sign = $this->getSign();
        if ($sign === '') {
            $collector->addError(self::ERR_SIGN_REQUIRED);
        } elseif (str_contains($sign, '%')) {
            $collector->addError(self::ERR_SIGN_INVALID_CHAR);
        } else {
            $currency = $this->getCurrency();
            if (
                $currency
                && $currency->Sign !== $sign
                && $this->getCurrencyExistenceChecker()->existBySign($sign)
            ) {
                $collector->addError(self::ERR_SIGN_EXIST);
            } elseif (
                !$currency
                && $this->getCurrencyExistenceChecker()->existBySign($sign)
            ) {
                $collector->addError(self::ERR_SIGN_EXIST);
            }
        }
    }

    /**
     * Validate currency Exchange Rate input
     */
    protected function validateExRate(): void
    {
        $collector = $this->getResultStatusCollector();
        $exRate = $this->getExRate();
        if ($exRate === '') {
            $collector->addError(self::ERR_EXRATE_REQUIRED);
        } elseif (!NumberValidator::new()->isRealPositive($exRate)) {
            $collector->addError(self::ERR_EXRATE_NUMERIC);
        }
    }
}
