<?php
/**
 *
 * SAM-4748: Mailing List Template management classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-06
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\MailingList\Save;

use Sam\Auction\Validate\AuctionExistenceCheckerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Constants\MailingListTemplate;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Date\CurrentDateTrait;
use Sam\Report\MailingList\Delete\MailingListTemplateDeleterAwareTrait;
use Sam\Report\MailingList\Load\MailingListTemplateLoaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\MailingListTemplateAwareTrait;
use Sam\Storage\WriteRepository\Entity\MailingListTemplateCategories\MailingListTemplateCategoriesWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\MailingListTemplates\MailingListTemplatesWriteRepositoryAwareTrait;

/**
 * Class MailingListTemplateEditor
 * @package Sam\Report\MailingList\Save
 */
class MailingListTemplateEditor extends CustomizableClass
{
    use AccountAwareTrait;
    use AuctionAwareTrait;
    use AuctionExistenceCheckerAwareTrait;
    use CurrentDateTrait;
    use EditorUserAwareTrait;
    use EntityFactoryCreateTrait;
    use FilterDatePeriodAwareTrait;
    use MailingListTemplateAwareTrait;
    use MailingListTemplateCategoriesWriteRepositoryAwareTrait;
    use MailingListTemplateDeleterAwareTrait;
    use MailingListTemplateLoaderAwareTrait;
    use MailingListTemplatesWriteRepositoryAwareTrait;
    use ResultStatusCollectorAwareTrait;

    public const ERR_NAME_REQUIRED = 1;
    public const ERR_MONEY_SPENT_RANGE_INVALID_INCLUSIVE = 2;
    public const ERR_AUCTION_OR_DATE_RANGE_REQUIRED = 3;
    public const ERR_MONEY_SPENT_TO_INVALID_NUMBER = 4;
    public const ERR_MONEY_SPENT_FROM_INVALID_NUMBER = 5;
    public const ERR_USER_TYPE_INCORRECT = 6;

    public const OK_SAVED = 1;

    /** @var int[] */
    protected array $nameErrors = [self::ERR_NAME_REQUIRED];
    /** @var int[] */
    protected array $moneySpentErrors = [
        self::ERR_MONEY_SPENT_RANGE_INVALID_INCLUSIVE,
        self::ERR_MONEY_SPENT_TO_INVALID_NUMBER,
        self::ERR_MONEY_SPENT_FROM_INVALID_NUMBER,
    ];
    /** @var int[] */
    protected array $periodErrors = [self::ERR_AUCTION_OR_DATE_RANGE_REQUIRED];
    /** @var int[] */
    protected array $userTypeErrors = [self::ERR_USER_TYPE_INCORRECT];
    protected ?float $moneySpentFrom = null;
    protected ?float $moneySpentTo = null;
    protected ?string $name = null;
    protected ?int $userType = null;
    /** @var int[] */
    protected array $lotCategoryIds = [];
    private bool $isNew = false;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $errorMessages = [
            self::ERR_NAME_REQUIRED => 'Name is required',
            self::ERR_MONEY_SPENT_RANGE_INVALID_INCLUSIVE => '"Money Spent From" can not be greater than money spent to',
            self::ERR_AUCTION_OR_DATE_RANGE_REQUIRED => 'You must specify either an auction, or a date range',
            self::ERR_MONEY_SPENT_TO_INVALID_NUMBER => '"Money Spent To" Should be positive number',
            self::ERR_MONEY_SPENT_FROM_INVALID_NUMBER => '"Money Spent From" Should be positive number',
            self::ERR_USER_TYPE_INCORRECT => 'Incorrect user type',
        ];
        $successMessages = [
            self::OK_SAVED => 'Changes saved!',
        ];
        $this->getResultStatusCollector()->construct($errorMessages, $successMessages);
        return $this;
    }

    public function update(): void
    {
        $this->isNew = false;
        $mailingListTemplate = $this->getMailingListTemplate();
        if (!$mailingListTemplate) {
            $mailingListTemplate = $this->createEntityFactory()->mailingListTemplates();
            $this->isNew = true;
        }
        $mailingListTemplate->AccountId = $this->getAccountId();
        $mailingListTemplate->AuctionId = $this->getAuctionId();
        $mailingListTemplate->Deleted = false;
        $mailingListTemplate->MoneySpentFrom = $this->getMoneySpentFrom();
        $mailingListTemplate->MoneySpentTo = $this->getMoneySpentTo();
        $mailingListTemplate->Name = $this->getName();
        $mailingListTemplate->PeriodEnd = $this->getFilterEndDateUtc();
        $mailingListTemplate->PeriodStart = $this->getFilterStartDateUtc();
        $mailingListTemplate->UserType = $this->getUserType();
        $this->getMailingListTemplatesWriteRepository()->saveWithModifier($mailingListTemplate, $this->getEditorUserId());
        $this->setMailingListTemplate($mailingListTemplate);
        $this->updateCategories();
        $this->getResultStatusCollector()->addSuccess(self::OK_SAVED);
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $this->getResultStatusCollector()->clear();
        $this->validateName();
        $this->validateMoneySpent();
        $this->validateAuctionAndDatePeriod();
        $this->validateUserType();
        $isValid = !$this->getResultStatusCollector()->hasError();
        return $isValid;
    }

    /**
     * @return float|null
     */
    protected function getMoneySpentFrom(): ?float
    {
        return $this->moneySpentFrom;
    }

    /**
     * @param int|float|string $moneySpentFrom
     * @return static
     */
    public function setMoneySpentFrom(int|float|string $moneySpentFrom): static
    {
        $this->moneySpentFrom = Cast::toFloat($moneySpentFrom, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return float|null
     */
    protected function getMoneySpentTo(): ?float
    {
        return $this->moneySpentTo;
    }

    /**
     * @param int|float|string $moneySpentTo
     * @return static
     */
    public function setMoneySpentTo(int|float|string $moneySpentTo): static
    {
        $this->moneySpentTo = Cast::toFloat($moneySpentTo, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = trim($name);
        return $this;
    }

    /**
     * @return int
     */
    protected function getUserType(): int
    {
        return $this->userType;
    }

    /**
     * @param int $userType
     * @return static
     */
    public function setUserType(int $userType): static
    {
        $this->userType = $userType;
        return $this;
    }

    /**
     * @return int[]
     */
    protected function getLotCategoryIds(): array
    {
        return $this->lotCategoryIds;
    }

    /**
     * @param int[] $lotCategoryIds
     * @return static
     */
    public function setLotCategoryIds(array $lotCategoryIds): static
    {
        $this->lotCategoryIds = ArrayCast::makeIntArray($lotCategoryIds);
        return $this;
    }

    /**
     * @return string
     */
    public function successMessage(): string
    {
        $message = $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
        return $message;
    }

    /**
     * @return bool
     */
    public function hasNameError(): bool
    {
        $has = $this->getResultStatusCollector()->hasConcreteError($this->nameErrors);
        return $has;
    }

    /**
     * @return string
     */
    public function nameErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes($this->nameErrors);
        return $errorMessage;
    }

    /**
     * @param array|null $errors null leads to check for all moneySpentErrors
     * @return bool
     */
    public function hasMoneySpentError(?array $errors = null): bool
    {
        $moneySpentErrors = $errors ?: $this->moneySpentErrors;
        $has = $this->getResultStatusCollector()->hasConcreteError($moneySpentErrors);
        return $has;
    }

    /**
     * @return bool
     */
    public function hasMoneySpentRangeInvalidError(): bool
    {
        $has = $this->hasMoneySpentError([self::ERR_MONEY_SPENT_RANGE_INVALID_INCLUSIVE]);
        return $has;
    }

    /**
     * @return bool
     */
    public function hasMoneySpentToError(): bool
    {
        $has = $this->hasMoneySpentError([self::ERR_MONEY_SPENT_TO_INVALID_NUMBER]);
        return $has;
    }

    /**
     * @return bool
     */
    public function hasMoneySpentFromError(): bool
    {
        $has = $this->hasMoneySpentError([self::ERR_MONEY_SPENT_FROM_INVALID_NUMBER]);
        return $has;
    }

    /**
     * @param array|null $errors null leads to output first existing error message among all moneySpentErrors
     * @return string
     */
    protected function findMoneySpentErrorMessage(?array $errors = null): string
    {
        $moneySpentErrors = $errors ?: $this->moneySpentErrors;
        $errorMessage = (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes($moneySpentErrors);
        return $errorMessage;
    }

    /**
     * @return string
     */
    public function moneySpentRangeInvalidErrorMessage(): string
    {
        return $this->findMoneySpentErrorMessage([self::ERR_MONEY_SPENT_RANGE_INVALID_INCLUSIVE]);
    }

    /**
     * @return string
     */
    public function moneySpentToErrorMessage(): string
    {
        return $this->findMoneySpentErrorMessage([self::ERR_MONEY_SPENT_TO_INVALID_NUMBER]);
    }

    /**
     * @return string
     */
    public function moneySpentFromErrorMessage(): string
    {
        return $this->findMoneySpentErrorMessage([self::ERR_MONEY_SPENT_FROM_INVALID_NUMBER]);
    }

    /**
     * @return bool
     */
    public function hasPeriodError(): bool
    {
        $has = $this->getResultStatusCollector()->hasConcreteError($this->periodErrors);
        return $has;
    }

    /**
     * @return string
     */
    public function periodErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes($this->periodErrors);
        return $errorMessage;
    }

    /**
     * @return bool
     */
    public function hasUserTypeError(): bool
    {
        $has = $this->getResultStatusCollector()->hasConcreteError($this->userTypeErrors);
        return $has;
    }

    /**
     * @return string
     */
    public function userTypeErrorMessage(): string
    {
        $errorMessage = (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes($this->userTypeErrors);
        return $errorMessage;
    }

    /**
     * Update Categories
     */
    protected function updateCategories(): void
    {
        $mailingListTemplateId = $this->getMailingListTemplateId();
        if (!$this->isNew) {
            $this->getMailingListTemplateDeleter()->deleteCategoriesByMailingListTemplateId($mailingListTemplateId);
        }
        $lotCategoryIds = $this->getLotCategoryIds();
        foreach ($lotCategoryIds as $lotCategoryId) {
            $mailingListCategory = $this->getMailingListTemplateLoader()
                ->loadTemplateCategory($mailingListTemplateId, $lotCategoryId, true);
            if (!$mailingListCategory) {
                $mailingListCategory = $this->createEntityFactory()->mailingListTemplateCategories();
            }
            $mailingListCategory->CategoryId = $lotCategoryId;
            $mailingListCategory->MailingListId = $mailingListTemplateId;
            $this->getMailingListTemplateCategoriesWriteRepository()->saveWithModifier($mailingListCategory, $this->getEditorUserId());
        }
    }

    /**
     * Validate Name
     */
    protected function validateName(): void
    {
        $collector = $this->getResultStatusCollector();
        if ($this->getName() === '') {
            $collector->addError(self::ERR_NAME_REQUIRED);
        }
    }

    /**
     * Validate MoneySpent
     */
    protected function validateMoneySpent(): void
    {
        $collector = $this->getResultStatusCollector();

        $moneySpentFrom = $this->getMoneySpentFrom();
        $moneySpentTo = $this->getMoneySpentTo();

        if (
            $this->isMoneySpentToFilled()
            && !NumberValidator::new()->isRealPositiveOrZero($moneySpentTo)
        ) {
            $collector->addError(self::ERR_MONEY_SPENT_TO_INVALID_NUMBER);
        }

        if (
            $this->isMoneySpentFromFilled()
            && !NumberValidator::new()->isRealPositiveOrZero($moneySpentFrom)
        ) {
            $collector->addError(self::ERR_MONEY_SPENT_FROM_INVALID_NUMBER);
        }

        if (
            !$collector->findErrorResultStatusesByCodes(
                [self::ERR_MONEY_SPENT_TO_INVALID_NUMBER, self::ERR_MONEY_SPENT_FROM_INVALID_NUMBER]
            )
        ) {
            if (
                $this->isMoneySpentPeriodFilled()
                && Floating::gt($moneySpentFrom, $moneySpentTo)
            ) {
                $collector->addError(self::ERR_MONEY_SPENT_RANGE_INVALID_INCLUSIVE);
            }
        }
    }

    /**
     * Validate Period and Auction
     */
    protected function validateAuctionAndDatePeriod(): void
    {
        $isAuctionFound = $this->getAuctionExistenceChecker()->existById($this->getAuctionId(), true);
        if (
            !$isAuctionFound
            && !$this->isMoneySpentPeriodFilled()
        ) {
            $this->getResultStatusCollector()->addError(self::ERR_AUCTION_OR_DATE_RANGE_REQUIRED);
        }
    }

    /**
     * Validate UserType
     */
    protected function validateUserType(): void
    {
        $collector = $this->getResultStatusCollector();
        if (!in_array($this->getUserType(), MailingListTemplate::$userTypes, true)) {
            $collector->addError(self::ERR_USER_TYPE_INCORRECT);
        }
    }

    /**
     * @return bool
     */
    private function isMoneySpentPeriodFilled(): bool
    {
        $isFilled = $this->isMoneySpentToFilled()
            && $this->isMoneySpentFromFilled();
        return $isFilled;
    }

    /**
     * @return bool
     */
    private function isMoneySpentToFilled(): bool
    {
        $isFilled = $this->getMoneySpentTo() !== null;
        return $isFilled;
    }

    /**
     * @return bool
     */
    private function isMoneySpentFromFilled(): bool
    {
        $isFilled = $this->getMoneySpentFrom() !== null;
        return $isFilled;
    }
}
