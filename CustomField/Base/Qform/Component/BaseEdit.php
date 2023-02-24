<?php
/**
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: EditControls.php 13797 2013-07-09 15:26:18Z SWB\igors $
 * @since           Jul 20, 2013
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Base\Qform\Component;

use AuctionCustData;
use AuctionCustField;
use LotItemCustData;
use LotItemCustField;
use QControl;
use QForm;
use RuntimeException;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Auction\Save\AuctionCustomDataUpdater;
use Sam\CustomField\Auction\Translate\AuctionCustomFieldTranslationManager;
use Sam\CustomField\Auction\Validate\Web\AuctionCustomWebDataValidator;
use Sam\CustomField\User\Save\UserCustomDataUpdater;
use Sam\CustomField\User\Translate\UserCustomFieldTranslationManager;
use Sam\CustomField\User\Validate\Web\UserCustomFieldWebDataValidator;
use Sam\User\Load\UserLoaderAwareTrait;
use UserCustData;
use UserCustField;

/**
 * Class BaseEdit
 * @package Sam\CustomField\Base\Qform\Component
 */
abstract class BaseEdit extends CustomizableClass
{
    use EditorUserAwareTrait;
    use UserLoaderAwareTrait;

    // these options are externally defined
    /**
     * Set in child class to define custom field type
     */
    protected ?int $type = null;
    protected ?string $controlId = null;
    /**
     * Parent QForm or QControl that is responsible for rendering this control
     */
    protected QForm|QControl|null $parentObject = null;
    /**
     * We detect QForm here
     */
    protected ?QForm $form = null;
    /**
     * Determine some rendering options, e.g. at public we translate labels, at private do not.
     */
    protected bool $isTranslating = true;
    protected bool $isPublic = false;
    /**
     * Control is in "Edit" mode, not "Read only"
     */
    protected bool $isEditMode = true;
    /**
     * Id of related entity, like auction or user
     */
    protected ?int $relatedEntityId = null;
    /**
     * Qcodo control
     */
    protected ?QControl $control = null;
    /**
     * Control value, that we want to validate
     */
    protected int|string|bool|null $inputValue = null;
    /**
     * Custom Field instance
     */
    protected AuctionCustField|LotItemCustField|UserCustField|null $customField = null;
    /**
     * Custom Data instance
     */
    protected AuctionCustData|LotItemCustData|UserCustData|null $customData = null;
    /**
     * Don't save empty values
     */
    protected bool $isSkipEmptyOnSave = false;
    /**
     * Determine rendering control differences, mobile is on for public = true,
     * and vice versa at the current moment of 2019
     */
    protected bool $isMobileUi = false;
    protected UserCustomFieldWebDataValidator|AuctionCustomWebDataValidator|null $dataValidator = null;
    protected UserCustomDataUpdater|AuctionCustomDataUpdater|null $dataUpdater = null;
    protected AuctionCustomFieldTranslationManager|UserCustomFieldTranslationManager|null $translator = null;
    protected ?string $filePath = null;
    protected string $width = '';
    protected string $height = '';
    protected string $display = '';
    protected bool $isDownloadLink = false;
    /**
     * When this flag is enabled and custom field's "Required" property is enabled, then we should check input value to be filled
     */
    private bool $shouldConsiderConstraintToBeRequired = true;

    /**
     * Create controls for custom field editing
     */
    abstract public function create();

    /**
     * Initialize custom field editing controls
     */
    abstract public function init();

    /**
     * Check, if control is not filled
     * @return bool
     */
    abstract public function isEmpty(): bool;

    /**
     * Return HTML for custom field controls
     *
     * @return string
     */
    public function render(): string
    {
        if (!$this->isMobileUi()) {
            return $this->getControl()->RenderWithError(false);
        }
        return $this->getControl()->Render(false);
    }

    /**
     * Validate custom field editing controls.
     *
     * @return bool
     */
    public function validate(): bool
    {
        $hasError = false;
        $inputValue = $this->getInputValue();

        /**
         * When input is empty and we don't need to consider "Required" option of the custom field,
         * then we can end validation with success
         */
        if (
            !$this->shouldConsiderConstraintToBeRequired()
            && (string)$inputValue === ''
        ) {
            return true;
        }

        if (!$this->dataValidator->validate($this->getCustomField(), $inputValue)) {
            if ($this->dataValidator->hasCheckboxError()) {
                $this->getControl()->Warning = $this->dataValidator->checkboxErrorMessage();
            } elseif ($this->dataValidator->hasDateError()) {
                $this->getControl()->Warning = $this->dataValidator->dateErrorMessage();
            } elseif ($this->dataValidator->hasEncodingError()) {
                $this->getControl()->Warning = $this->dataValidator->encodingErrorMessage();
            } elseif ($this->dataValidator->hasIntegerError()) {
                $this->getControl()->Warning = $this->dataValidator->integerErrorMessage();
            } elseif ($this->dataValidator->hasNumberFormatError()) {
                $this->getControl()->Warning = $this->dataValidator->numberFormatErrorMessage();
            } elseif ($this->dataValidator->hasNumericError()) {
                $this->getControl()->Warning = $this->dataValidator->numericErrorMessage();
            } elseif ($this->dataValidator->hasRequiredError()) {
                $this->getControl()->Warning = $this->dataValidator->requiredErrorMessage();
            } elseif ($this->dataValidator->hasOptionError()) {
                $this->getControl()->Warning = $this->dataValidator->optionErrorMessage();
            } elseif ($this->dataValidator->hasThousandSeparatorError()) {
                $this->getControl()->Warning = $this->dataValidator->thousandSeparatorErrorMessage();
            }

            if (!$this->isMobileUi()) {
                $this->getControl()->Blink();
                $this->getControl()->Focus();
            }

            $hasError = true;
        }
        return !$hasError;
    }

    /**
     * Clear error warning
     */
    public function clearWarning(): void
    {
        $this->getControl()->Warning = '';
    }

    /**
     * @param AuctionCustomDataUpdater|UserCustomDataUpdater $dataUpdater
     * @return static
     */
    public function setDataUpdater(AuctionCustomDataUpdater|UserCustomDataUpdater $dataUpdater): static
    {
        $this->dataUpdater = $dataUpdater;
        return $this;
    }

    public function getDataUpdater(): AuctionCustomDataUpdater|UserCustomDataUpdater|null
    {
        return $this->dataUpdater;
    }

    /**
     * Save custom field data
     */
    public function save(): void
    {
        if (
            $this->isSkipEmptyOnSave
            && $this->isEmpty()
        ) {
            return;
        }
        $this->dataUpdater->save($this->getCustomData(), $this->getCustomField(), $this->detectModifierUserId());
    }

    /**
     * Set data validator
     *
     * @param UserCustomFieldWebDataValidator|AuctionCustomWebDataValidator $dataValidator
     * @return static
     */
    public function setDataValidator(UserCustomFieldWebDataValidator|AuctionCustomWebDataValidator $dataValidator): static
    {
        $this->dataValidator = $dataValidator;
        return $this;
    }

    /**
     * Set translation manager
     *
     * @param AuctionCustomFieldTranslationManager|UserCustomFieldTranslationManager $translator
     * @return static
     */
    public function setTranslator(AuctionCustomFieldTranslationManager|UserCustomFieldTranslationManager $translator): static
    {
        $this->translator = $translator;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enablePublic(bool $enable): static
    {
        $this->isPublic = $enable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTranslating(): bool
    {
        return $this->isTranslating;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableTranslating(bool $enable): static
    {
        $this->isTranslating = $enable;
        $this->dataValidator?->enableTranslating($enable);
        return $this;
    }

    /**
     * @return bool
     */
    public function isEditMode(): bool
    {
        return $this->isEditMode;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableEditMode(bool $enable): static
    {
        $this->isEditMode = $enable;
        return $this;
    }

    /**
     * @return QControl
     */
    public function getControl(): QControl
    {
        if (!$this->control) {
            throw new RuntimeException('Control not defined');
        }
        return $this->control;
    }

    /**
     * @param QControl $control
     * @return static
     */
    public function setControl(QControl $control): static
    {
        $this->control = $control;
        return $this;
    }

    /**
     * @return string
     */
    public function getControlId(): string
    {
        return $this->controlId;
    }

    /**
     * @param string $controlId
     * @return static
     */
    public function setControlId(string $controlId): static
    {
        $this->controlId = $controlId;
        return $this;
    }

    /**
     * @return AuctionCustField|LotItemCustField|UserCustField|null
     */
    public function getCustomField(): AuctionCustField|LotItemCustField|UserCustField|null
    {
        return $this->customField;
    }

    /**
     * @param AuctionCustField|LotItemCustField|UserCustField $customField
     * @return static
     */
    public function setCustomField(AuctionCustField|LotItemCustField|UserCustField $customField): static
    {
        $this->customField = $customField;
        return $this;
    }

    /**
     * @return AuctionCustData|LotItemCustData|UserCustData|null
     */
    public function getCustomData(): AuctionCustData|LotItemCustData|UserCustData|null
    {
        return $this->customData;
    }

    /**
     * @param AuctionCustData|LotItemCustData|UserCustData $customData
     * @return static
     */
    public function setCustomData(AuctionCustData|LotItemCustData|UserCustData $customData): static
    {
        $this->customData = $customData;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSkipEmptyOnSave(): bool
    {
        return $this->isSkipEmptyOnSave;
    }

    /**
     * @param bool $isSkipEmptyOnSave
     * @return static
     */
    public function enableSkipEmptyOnSave(bool $isSkipEmptyOnSave): static
    {
        $this->isSkipEmptyOnSave = $isSkipEmptyOnSave;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMobileUi(): bool
    {
        return $this->isMobileUi;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableMobileUi(bool $enable): static
    {
        $this->isMobileUi = $enable;
        return $this;
    }

    /**
     * @return QForm|QControl|null
     */
    public function getParentObject(): QForm|QControl|null
    {
        return $this->parentObject;
    }

    /**
     * @param QForm|QControl $parentObject
     * @return static
     */
    public function setParentObject(QForm|QControl $parentObject): static
    {
        if ($parentObject instanceof QForm) {
            $this->form = $parentObject;
        } else {
            $this->form = $parentObject->Form;
        }
        $this->parentObject = $parentObject;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilePath(): ?string
    {
        if ($this->filePath === null) {
            log_warning('FilePath not set');
        }
        return $this->filePath;
    }

    /**
     * @param string $filePath
     * @return static
     */
    public function setFilePath(string $filePath): static
    {
        $this->filePath = trim($filePath);
        log_trace("Set custom field original file " . composeLogData(['path' => $this->filePath]));
        return $this;
    }

    /**
     * @return string
     */
    public function getWidth(): string
    {
        return $this->width;
    }

    /**
     * @param string $width
     * @return static
     */
    public function setWidth(string $width): static
    {
        $this->width = trim($width);
        return $this;
    }

    /**
     * @return string
     */
    public function getHeight(): string
    {
        return $this->height;
    }

    /**
     * @param string $height
     * @return static
     */
    public function setHeight(string $height): static
    {
        $this->height = trim($height);
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplay(): string
    {
        return $this->display;
    }

    /**
     * @param string $display
     * @return static
     */
    public function setDisplay(string $display): static
    {
        $this->display = trim($display);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getRelatedEntityId(): ?int
    {
        return $this->relatedEntityId;
    }

    /**
     * @param int|null $relatedEntityId can be null when user is anonymous
     * @return static
     */
    public function setRelatedEntityId(?int $relatedEntityId): static
    {
        $this->relatedEntityId = Cast::toInt($relatedEntityId, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return static
     */
    public function setType(int $type): static
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int|string|bool|null can return int|string|bool|null values depending of custom field type.
     */
    public function getInputValue(): int|string|bool|null
    {
        return $this->inputValue;
    }

    /**
     * @param int|string|bool|null $inputValue can accept int|string|bool|null values depending of custom field type.
     * @return static
     */
    public function setInputValue(int|string|bool|null $inputValue): static
    {
        $this->inputValue = trim($inputValue ?? '');
        return $this;
    }

    /**
     * @param bool $enabled
     * @return static
     */
    public function enableDownloadLink(bool $enabled): static
    {
        $this->isDownloadLink = $enabled;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDownloadLink(): bool
    {
        return $this->isDownloadLink;
    }

    /**
     * @return bool
     */
    public function shouldConsiderConstraintToBeRequired(): bool
    {
        return $this->shouldConsiderConstraintToBeRequired;
    }

    /**
     * @param $enable
     * @return static
     */
    public function enableConsiderConstraintToBeRequired($enable): static
    {
        $this->shouldConsiderConstraintToBeRequired = $enable;
        return $this;
    }

    /**
     * Return entity modifier user - he is either authorized user, or system user when the current user is anonymous or not defined.
     * @return int
     */
    protected function detectModifierUserId(): int
    {
        return $this->getEditorUserId() ?: $this->getUserLoader()->loadSystemUserId();
    }
}
