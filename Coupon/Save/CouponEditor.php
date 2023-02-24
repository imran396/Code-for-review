<?php
/**
 *
 * SAM-4680: Coupon editor
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-09
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Coupon\Save;

use DateTime;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Coupon\Delete\CouponDeleterAwareTrait;
use Sam\Coupon\Load\CouponLoaderAwareTrait;
use Sam\Coupon\Validate\CouponExistenceCheckerAwareTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\CouponAwareTrait;
use Sam\Storage\WriteRepository\Entity\Coupon\CouponWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\CouponAuction\CouponAuctionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\CouponLotCategory\CouponLotCategoryWriteRepositoryAwareTrait;
use Sam\Timezone\ApplicationTimezoneProviderAwareTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;

/**
 * Class CouponEditor
 * @package Sam\Coupon\Save
 */
class CouponEditor extends CustomizableClass
{
    use AccountAwareTrait;
    use ApplicationTimezoneProviderAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CouponAuctionWriteRepositoryAwareTrait;
    use CouponAwareTrait;
    use CouponDeleterAwareTrait;
    use CouponExistenceCheckerAwareTrait;
    use CouponLoaderAwareTrait;
    use CouponLotCategoryWriteRepositoryAwareTrait;
    use CouponWriteRepositoryAwareTrait;
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use EditorUserAwareTrait;
    use EntityFactoryCreateTrait;
    use ResultStatusCollectorAwareTrait;
    use TimezoneLoaderAwareTrait;

    public const ERR_TITLE_REQUIRED = 1;
    public const ERR_TITLE_LENGTH_LIMIT = 2;
    public const ERR_CODE_REQUIRED = 3;
    public const ERR_CODE_LENGTH_LIMIT = 4;
    public const ERR_MIN_PURCHASE_AMOUNT_REQUIRED = 5;
    public const ERR_MIN_PURCHASE_AMOUNT_INVALID = 6;
    public const ERR_START_DATE_REQUIRED = 7;
    public const ERR_END_DATE_REQUIRED = 8;
    public const ERR_DATE_RANGE_INVALID_INCLUSIVE = 9;
    public const ERR_PER_USER_INVALID = 10;
    public const ERR_FIXED_AMOUNT_OFF_REQUIRED = 11;
    public const ERR_FIXED_AMOUNT_OFF_INVALID = 12;
    public const ERR_PERCENTAGE_OFF_REQUIRED = 13;
    public const ERR_PERCENTAGE_OFF_INVALID = 14;
    public const ERR_COUPON_TYPE_REQUIRED = 15;
    public const ERR_TIMEZONE_REQUIRED = 16;
    public const ERR_TIMEZONE_INVALID = 17;

    public const WARN_END_DATE_IN_PAST = 1;

    public const OK_SAVED = 1;

    protected array $codeErrors = [self::ERR_CODE_REQUIRED, self::ERR_CODE_LENGTH_LIMIT];
    protected array $couponTypeErrors = [self::ERR_COUPON_TYPE_REQUIRED];
    protected array $endDateErrors = [self::ERR_END_DATE_REQUIRED, self::ERR_DATE_RANGE_INVALID_INCLUSIVE];
    protected array $fixedAmountOffErrors = [self::ERR_FIXED_AMOUNT_OFF_REQUIRED, self::ERR_FIXED_AMOUNT_OFF_INVALID];
    protected array $minPurchaseAmtErrors = [self::ERR_MIN_PURCHASE_AMOUNT_REQUIRED, self::ERR_MIN_PURCHASE_AMOUNT_INVALID];
    protected array $percentageOffErrors = [self::ERR_PERCENTAGE_OFF_REQUIRED, self::ERR_PERCENTAGE_OFF_INVALID];
    protected array $perUserErrors = [self::ERR_PER_USER_INVALID];
    protected array $startDateErrors = [self::ERR_START_DATE_REQUIRED];
    protected array $titleErrors = [self::ERR_TITLE_REQUIRED, self::ERR_TITLE_LENGTH_LIMIT];
    protected array $timezoneErrors = [self::ERR_TIMEZONE_INVALID, self::ERR_TIMEZONE_REQUIRED];

    /** @var int[] */
    protected array $auctionIds = [];
    /** @var int[] */
    protected array $lotCategoryIds = [];
    /**
     * Input raw value
     */
    protected ?string $fixedAmountOff = null;
    protected bool $isWaiveAdditionalCharges = false;
    protected ?DateTime $endDate = null;
    /**
     * Input raw value
     */
    protected ?string $minPurchaseAmt = null;
    /**
     * Input raw value
     */
    protected ?string $percentageOff = null;
    protected ?string $perUser = null;
    protected ?DateTime $startDate = null;
    protected ?string $timezone = null;
    protected string $title = '';
    /**
     * General error message basically rendered on the top
     */
    protected string $generalErrorMessage = 'Saving your changes failed. Please check the individual settings below for details';
    protected ?int $couponType = null;
    protected string $couponCode = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Update Coupon
     */
    public function update(): void
    {
        $coupon = $this->getCouponLoader()->load($this->getCouponId(), true);
        if (!$coupon) {
            $coupon = $this->createEntityFactory()->coupon();
            $coupon->AccountId = $this->getAccountId();
            $coupon->CouponStatusId = Constants\Coupon::STATUS_ACTIVE;
        }
        $timezone = $this->getTimezoneLoader()->loadByLocationOrCreatePersisted($this->getTimezone());
        $coupon->Code = $this->getCouponCode();
        $coupon->CouponType = $this->getCouponType();
        $coupon->EndDate = $this->getDateHelper()->convertTimezone($this->getEndDate(), $timezone->Location, 'UTC');
        $coupon->MinPurchaseAmt = $this->normalizeMinPurchaseAmt();
        $coupon->PerUser = $this->normalizePerUser();
        $coupon->StartDate = $this->getDateHelper()->convertTimezone($this->getStartDate(), $timezone->Location, 'UTC');
        $coupon->TimezoneId = $timezone->Id;
        $coupon->Title = $this->getTitle();
        $coupon->FixedAmountOff = null;
        $coupon->PercentageOff = null;
        $coupon->WaiveAdditionalCharges = false;
        if ($this->getCouponType() === Constants\Coupon::FREE_SHIPPING) {
            $coupon->WaiveAdditionalCharges = $this->isWaiveAdditionalCharges();
        } elseif ($this->getCouponType() === Constants\Coupon::FIXED_AMOUNT) {
            $coupon->FixedAmountOff = $this->normalizeFixedAmountOff();
        } elseif ($this->getCouponType() === Constants\Coupon::PERCENTAGE) {
            $coupon->PercentageOff = $this->normalizePercentageOff();
        }
        $this->getCouponWriteRepository()->saveWithModifier($coupon, $this->getEditorUserId());

        $this->getCouponDeleter()->deleteAuctions($coupon->Id);

        if (count($this->getAuctionIds()) > 0) {
            foreach ($this->getAuctionIds() as $auctionId) {
                if (!$this->getCouponExistenceChecker()->existForAuction($coupon->Id, $auctionId)) { // Prevent duplicate
                    log_trace('saving coupon auction' . composeSuffix(['a' => $auctionId]));
                    $couponAuction = $this->createEntityFactory()->couponAuction();
                    $couponAuction->CouponId = $coupon->Id;
                    $couponAuction->AuctionId = $auctionId;
                    $this->getCouponAuctionWriteRepository()->saveWithModifier($couponAuction, $this->getEditorUserId());
                }
            }
        }

        $this->getCouponDeleter()->deleteCategories($coupon->Id);
        foreach ($this->getLotCategoryIds() as $lotCategoryId) {
            if (!$this->getCouponExistenceChecker()->existForCategory($coupon->Id, $lotCategoryId)) { // Prevent duplicate
                $couponLotCategory = $this->createEntityFactory()->couponLotCategory();
                $couponLotCategory->CouponId = $coupon->Id;
                $couponLotCategory->LotCategoryId = $lotCategoryId;
                $this->getCouponLotCategoryWriteRepository()->saveWithModifier($couponLotCategory, $this->getEditorUserId());
            }
        }

        $this->produceWarningStatus();
        $this->produceSuccessStatus();
    }

    /**
     * Validate coupon input
     * @return bool
     */
    public function validate(): bool
    {
        $this->initResultStatusCollector();
        $this->validateTitle();
        $this->validateCode();
        $this->validateMinPurchaseAmt();
        $this->validateDates();
        $this->validatePerUser();
        $this->validateCouponTypeRelatedValues();
        $isValid = !$this->getResultStatusCollector()->hasError();
        return $isValid;
    }

    /**
     * Get Code Error Message
     * @return string
     */
    public function codeErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes($this->codeErrors);
    }

    /**
     * Get CouponType Error Message
     * @return string
     */
    public function couponTypeErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes($this->couponTypeErrors);
    }

    /**
     * Get EndDate Error Message
     * @return string
     */
    public function endDateErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes($this->endDateErrors);
    }

    /**
     * Get FixedAmountType Error Message
     * @return string
     */
    public function fixedAmountTypeErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes($this->fixedAmountOffErrors);
    }

    /**
     * Get MinPurchaseAmount Error Message
     * @return string
     */
    public function minPurchaseAmtErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes($this->minPurchaseAmtErrors);
    }

    /**
     * Get PercentageType Error Message
     * @return string
     */
    public function percentageTypeErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes($this->percentageOffErrors);
    }

    /**
     * Get PerUser Error Message
     * @return string
     */
    public function perUserErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes($this->perUserErrors);
    }

    /**
     * Get StartDate Error Message
     * @return string
     */
    public function startDateErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes($this->startDateErrors);
    }

    /**
     * Get Title Error Message
     * @return string
     */
    public function titleErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes($this->titleErrors);
    }

    /**
     * Get Timezone Error Message
     * @return string
     */
    public function timezoneErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes($this->timezoneErrors);
    }

    /**
     * Check code error
     * @return bool
     */
    public function hasCodeError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->codeErrors);
    }

    /**
     * Check has min purchase amount error
     * @return bool
     */
    public function hasMinPurchaseAmountError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->minPurchaseAmtErrors);
    }

    /**
     * Check title error
     * @return bool
     */
    public function hasTitleError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->titleErrors);
    }

    /**
     * Check Start Date Error
     * @return bool
     */
    public function hasStartDateError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->startDateErrors);
    }

    /**
     * Check End Date Error
     * @return bool
     */
    public function hasEndDateError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->endDateErrors);
    }

    /**
     * Check PerUser Error
     * @return bool
     */
    public function hasPerUserError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->perUserErrors);
    }

    /**
     * Check FixedAmountOff Error
     * @return bool
     */
    public function hasFixedAmountTypeError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->fixedAmountOffErrors);
    }

    /**
     * Check PercentageOff Error
     * @return bool
     */
    public function hasPercentageTypeError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->percentageOffErrors);
    }

    /**
     * Check CouponType Error
     * @return bool
     */
    public function hasCouponTypeError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->couponTypeErrors);
    }

    /**
     * Check Timezone Error
     * @return bool
     */
    public function hasTimezoneError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->timezoneErrors);
    }

    /**
     * @return string
     */
    public function generalErrorMessage(): string
    {
        return $this->generalErrorMessage;
    }

    /**
     * @return string
     */
    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    /**
     * @return bool
     */
    public function hasWarning(): bool
    {
        return $this->getResultStatusCollector()->hasWarning();
    }

    /**
     * @return string
     */
    public function warningMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedWarningMessage();
    }

    /**
     * @return bool
     */
    public function isWaiveAdditionalCharges(): bool
    {
        return $this->isWaiveAdditionalCharges;
    }

    /**
     * Enable WaiveAdditionalCharges
     * @param bool $is
     * @return static
     */
    public function enableWaiveAdditionalCharges(bool $is): static
    {
        $this->isWaiveAdditionalCharges = $is;
        return $this;
    }

    /**
     * @return int[]
     */
    public function getAuctionIds(): array
    {
        return $this->auctionIds;
    }

    /**
     * Set Auction Ids
     * @param int[] $auctionIds
     * @return CouponEditor
     */
    public function setAuctionIds(array $auctionIds): CouponEditor
    {
        $this->auctionIds = $auctionIds;
        return $this;
    }

    /**
     * @return string
     */
    public function getCouponCode(): string
    {
        return $this->couponCode;
    }

    /**
     * @param string $couponCode
     * @return static
     */
    public function setCouponCode(string $couponCode): static
    {
        $this->couponCode = trim($couponCode);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCouponType(): ?int
    {
        return $this->couponType;
    }

    /**
     * Set Coupon Type
     * @param int $couponType
     * @return static
     */
    public function setCouponType(int $couponType): static
    {
        $this->couponType = $couponType;
        return $this;
    }

    /**
     * @return int[]
     */
    public function getLotCategoryIds(): array
    {
        return $this->lotCategoryIds;
    }

    /**
     * Set Category Ids
     * @param int[] $lotCategoryIds
     * @return CouponEditor
     */
    public function setLotCategoryIds(array $lotCategoryIds): CouponEditor
    {
        $this->lotCategoryIds = ArrayCast::makeIntArray($lotCategoryIds);
        return $this;
    }

    /**
     * Coupon end date in system tz
     * @return DateTime|null
     */
    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    /**
     * Set Coupon end date
     * @param DateTime|null $endDate null means empty end date value
     * @return static
     */
    public function setEndDate(?DateTime $endDate): static
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFixedAmountOff(): ?string
    {
        return $this->fixedAmountOff;
    }

    /**
     * @return float|null
     */
    protected function normalizeFixedAmountOff(): ?float
    {
        return Cast::toFloat($this->getFixedAmountOff(), Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
    }

    /**
     * Set Fixed AmountOff
     * @param string $fixedAmountOff
     * @return static
     */
    public function setFixedAmountOff(string $fixedAmountOff): static
    {
        $this->fixedAmountOff = $fixedAmountOff;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPercentageOff(): ?string
    {
        return $this->percentageOff;
    }

    /**
     * @return float|null
     */
    protected function normalizePercentageOff(): ?float
    {
        return Cast::toFloat($this->getPercentageOff(), Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
    }

    /**
     * Set PercentageOff
     * @param string $percentageOff
     * @return static
     */
    public function setPercentageOff(string $percentageOff): static
    {
        $this->percentageOff = $percentageOff;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMinPurchaseAmt(): ?string
    {
        return $this->minPurchaseAmt;
    }

    /**
     * @return float|null
     */
    protected function normalizeMinPurchaseAmt(): ?float
    {
        return Cast::toFloat($this->getMinPurchaseAmt(), Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
    }

    /**
     * Set Min Purchase Amount
     * @param string $minPurchaseAmt
     * @return static
     */
    public function setMinPurchaseAmt(string $minPurchaseAmt): static
    {
        $this->minPurchaseAmt = $minPurchaseAmt;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPerUser(): ?string
    {
        return $this->perUser;
    }

    /**
     * @return int|null
     */
    protected function normalizePerUser(): ?int
    {
        return Cast::toInt($this->getPerUser(), Constants\Type::F_INT_POSITIVE_OR_ZERO);
    }

    /**
     * Set Per User
     * @param string $perUser
     * @return static
     */
    public function setPerUser(string $perUser): static
    {
        $this->perUser = $perUser;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    /**
     * Set Start Date
     * @param DateTime|null $startDate null means empty value of start date
     * @return static
     */
    public function setStartDate(?DateTime $startDate): static
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    /**
     * Set Timezone Location
     * @param string $timezone
     * @return static
     */
    public function setTimezone(string $timezone): static
    {
        $this->timezone = trim($timezone);
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set Title input and normalize value to be ready for validating and saving
     * @param string $title
     * @return static
     */
    public function setTitle(string $title): static
    {
        $this->title = trim($title);
        return $this;
    }

    protected function initResultStatusCollector(): void
    {
        // Init ResultStatusCollector
        $errorMessages = [
            self::ERR_TITLE_REQUIRED => 'Title required',
            self::ERR_TITLE_LENGTH_LIMIT => 'Title length limit reached (' . $this->cfg()->get('core->coupon->title->lengthLimit') . ') characters',
            self::ERR_CODE_REQUIRED => 'Code required',
            self::ERR_CODE_LENGTH_LIMIT => 'Code length limit reached (' . $this->cfg()->get('core->coupon->code->lengthLimit') . ') characters',
            self::ERR_MIN_PURCHASE_AMOUNT_REQUIRED => 'Min. Purchase Amount required',
            self::ERR_MIN_PURCHASE_AMOUNT_INVALID => 'Min. Purchase Amount should be positive number or zero',
            self::ERR_START_DATE_REQUIRED => 'Start date required',
            self::ERR_END_DATE_REQUIRED => 'End date required',
            self::ERR_DATE_RANGE_INVALID_INCLUSIVE => 'Invalid inclusive date',
            self::ERR_PER_USER_INVALID => 'Per User should be positive number or zero',
            self::ERR_FIXED_AMOUNT_OFF_REQUIRED => 'Amount required',
            self::ERR_FIXED_AMOUNT_OFF_INVALID => 'Amount should be positive number or zero',
            self::ERR_PERCENTAGE_OFF_REQUIRED => 'Percentage required',
            self::ERR_PERCENTAGE_OFF_INVALID => 'Percentage should be positive number or zero',
            self::ERR_COUPON_TYPE_REQUIRED => 'Coupon type required',
            self::ERR_TIMEZONE_REQUIRED => 'Timezone is required',
            self::ERR_TIMEZONE_INVALID => 'Unknown timezone'
        ];
        $warningMessages = [
            self::WARN_END_DATE_IN_PAST => 'Saved coupon end date is in the past',
        ];
        $successMessages = [
            self::OK_SAVED => 'Coupon saved',
        ];
        $this->getResultStatusCollector()->construct(
            $errorMessages,
            $successMessages,
            $warningMessages
        );
    }

    /**
     * Produce success status code and message
     */
    protected function produceSuccessStatus(): void
    {
        $this->getResultStatusCollector()->addSuccessWithAppendedMessage(self::OK_SAVED, composeSuffix(['Title' => $this->getTitle()]));
    }

    /**
     * Validate warning conditions and produce warning message
     */
    protected function produceWarningStatus(): void
    {
        $endDate = $this->getEndDate();
        $timezone = $this->getTimezone();
        $couponEndDateUtc = $endDate && $timezone
            ? $this->getDateHelper()->convertTimezone($endDate, $this->getTimezone(), 'UTC')
            : null;
        $currentDateUtc = $this->getCurrentDateUtc();
        if (
            !$couponEndDateUtc
            || $currentDateUtc->getTimestamp() > $couponEndDateUtc->getTimestamp()
        ) {
            $append = composeSuffix(
                [
                    'Title' => $this->getTitle(),
                    'End Date' => $couponEndDateUtc->format(Constants\Date::ISO) . 'UTC'
                ]
            );
            $this->getResultStatusCollector()->addWarningWithAppendedMessage(self::WARN_END_DATE_IN_PAST, $append);
        }
    }

    /**
     * Validate coupon code input
     */
    protected function validateCode(): void
    {
        $collector = $this->getResultStatusCollector();
        if ($this->getCouponCode() === '') {
            $collector->addError(self::ERR_CODE_REQUIRED);
        } elseif (mb_strlen($this->getCouponCode()) > $this->cfg()->get('core->coupon->code->lengthLimit')) {
            $collector->addError(self::ERR_CODE_LENGTH_LIMIT);
        }
    }

    /**
     * Validate Coupon Type related values
     */
    protected function validateCouponTypeRelatedValues(): void
    {
        $collector = $this->getResultStatusCollector();

        if (!in_array($this->getCouponType(), Constants\Coupon::$types, true)) {
            $collector->addError(self::ERR_COUPON_TYPE_REQUIRED);
        } elseif ($this->getCouponType() === Constants\Coupon::FIXED_AMOUNT) {
            $fixedAmountOffRaw = trim((string)$this->getFixedAmountOff());
            if ($fixedAmountOffRaw === '') {
                $collector->addError(self::ERR_FIXED_AMOUNT_OFF_REQUIRED);
            } elseif (!NumberValidator::new()->isRealPositiveOrZero($fixedAmountOffRaw)) {
                $collector->addError(self::ERR_FIXED_AMOUNT_OFF_INVALID);
            }
        } elseif ($this->getCouponType() === Constants\Coupon::PERCENTAGE) {
            $percentageOffRaw = trim((string)$this->getPercentageOff());
            if ($percentageOffRaw === '') {
                $collector->addError(self::ERR_PERCENTAGE_OFF_REQUIRED);
            } elseif (!NumberValidator::new()->isRealPositiveOrZero($percentageOffRaw)) {
                $collector->addError(self::ERR_PERCENTAGE_OFF_INVALID);
            }
        }
    }

    /**
     * Validate coupon date range
     */
    protected function validateDates(): void
    {
        $collector = $this->getResultStatusCollector();
        $timezone = $this->getTimezone();
        if (!$timezone) {
            $collector->addError(self::ERR_TIMEZONE_REQUIRED);
        } elseif (!$this->getApplicationTimezoneProvider()->isTimezoneAvailable($timezone)) {
            $collector->addError(self::ERR_TIMEZONE_INVALID);
        }
        if (!$this->getStartDate()) {
            $collector->addError(self::ERR_START_DATE_REQUIRED);
        }
        if (!$this->getEndDate()) {
            $collector->addError(self::ERR_END_DATE_REQUIRED);
        }
        if (
            $this->getStartDate()
            && $this->getEndDate()
            && $this->getStartDate()->getTimestamp() > $this->endDate->getTimestamp()
        ) {
            $collector->addError(self::ERR_DATE_RANGE_INVALID_INCLUSIVE);
        }
    }

    /**
     * Validate Min Purchase Amount input
     */
    protected function validateMinPurchaseAmt(): void
    {
        $collector = $this->getResultStatusCollector();
        $minPurchaseAmtRaw = trim((string)$this->getMinPurchaseAmt());
        if ($minPurchaseAmtRaw === '') {
            $collector->addError(self::ERR_MIN_PURCHASE_AMOUNT_REQUIRED);
        } elseif (!NumberValidator::new()->isRealPositiveOrZero($minPurchaseAmtRaw)) {
            $collector->addError(self::ERR_MIN_PURCHASE_AMOUNT_INVALID);
        }
    }

    /**
     * Validate "Per User" input value
     */
    protected function validatePerUser(): void
    {
        $collector = $this->getResultStatusCollector();
        $perUserRaw = trim((string)$this->getPerUser());
        if (
            $perUserRaw !== ''
            && !NumberValidator::new()->isIntPositiveOrZero($perUserRaw)
        ) {
            $collector->addError(self::ERR_PER_USER_INVALID);
        }
    }

    /**
     * Validate coupon title input
     */
    protected function validateTitle(): void
    {
        $collector = $this->getResultStatusCollector();
        if ($this->getTitle() === '') {
            $collector->addError(self::ERR_TITLE_REQUIRED);
        } elseif (mb_strlen($this->getTitle()) > $this->cfg()->get('core->coupon->title->lengthLimit')) {
            $collector->addError(self::ERR_TITLE_LENGTH_LIMIT);
        }
    }
}
