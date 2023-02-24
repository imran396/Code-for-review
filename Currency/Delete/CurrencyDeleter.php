<?php
/**
 * SAM-4722: Currency deleter
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-20
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Currency\Delete;

use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Currency\Validate\CurrencyExistenceCheckerAwareTrait;
use Sam\Storage\WriteRepository\Entity\Currency\CurrencyWriteRepositoryAwareTrait;

/**
 * Class CurrencyDeleter
 * @package Sam\Currency\Delete
 */
class CurrencyDeleter extends CustomizableClass
{
    use CurrencyExistenceCheckerAwareTrait;
    use CurrencyLoaderAwareTrait;
    use CurrencyWriteRepositoryAwareTrait;
    use EditorUserAwareTrait;
    use ResultStatusCollectorAwareTrait;

    public const ERR_ALREADY_IN_USE = 1;

    protected ?int $currencyId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int|null
     */
    public function getCurrencyId(): ?int
    {
        return $this->currencyId;
    }

    /**
     * @param int $currencyId
     * @return static
     */
    public function setCurrencyId(int $currencyId): static
    {
        $this->currencyId = $currencyId;
        return $this;
    }

    /**
     * Check validate
     * @return bool
     */
    public function validate(): bool
    {
        $this->initResultStatusCollector();
        $collector = $this->getResultStatusCollector();

        if ($this->getCurrencyExistenceChecker()->isAlreadyUsed($this->getCurrencyId())) {
            $collector->addError(self::ERR_ALREADY_IN_USE);
        }

        $isValid = !$this->getResultStatusCollector()->hasError();
        return $isValid;
    }

    /**
     * Delete Currency
     */
    public function delete(): void
    {
        $currency = $this->getCurrencyLoader()->load($this->getCurrencyId(), true);

        if (!$currency) {
            return;
        }
        if ($this->getCurrencyLoader()->findPrimaryCurrencyId() === $currency->Id) {
            return;
        }

        $currency->Active = false;
        $this->getCurrencyWriteRepository()->saveWithModifier($currency, $this->getEditorUserId());
    }

    /**
     * Get Error Message
     * @return string
     */
    public function errorMessage(): string
    {
        $searchErrors = [self::ERR_ALREADY_IN_USE];
        $errorMessage = (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($searchErrors);
        return $errorMessage;
    }

    protected function initResultStatusCollector(): void
    {
        $errorMessages = [
            self::ERR_ALREADY_IN_USE => 'Currency is already in use!',
        ];
        $this->getResultStatusCollector()->construct($errorMessages);
    }
}
