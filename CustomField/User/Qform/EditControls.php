<?php
/**
 * Helper class for QCodo controllers (drafts), which work with editing controls for custom user fields.
 * Front end: registration and profile pages
 * Back end: user edit page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: EditControls.php 21360 2015-05-27 18:04:14Z SWB\igors $
 * @since           Nov 02, 2012
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Qform;

use QControl;
use QForm;
use RuntimeException;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\Qform\Component\BaseEdit;
use Sam\CustomField\Base\Qform\Component\FileEdit;
use Sam\CustomField\Base\Render\Css\CustomFieldCssClassMakerCreateTrait;
use Sam\CustomField\User\Help\UserCustomFieldHelperAwareTrait;
use Sam\CustomField\User\Load\UserCustomDataLoaderAwareTrait;
use Sam\CustomField\User\Load\UserCustomFieldLoaderAwareTrait;
use Sam\CustomField\User\Qform\Component\UserCustomFieldComponentBuilderAwareTrait;
use Sam\CustomField\User\Translate\UserCustomFieldTranslationManagerAwareTrait;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use UserCustField;

/**
 * Class EditControls
 * @package Sam\CustomField\User\Qform
 */
class EditControls extends CustomizableClass
{
    use CustomFieldCssClassMakerCreateTrait;
    use EditorUserAwareTrait;
    use FormStateLongevityAwareTrait;
    use UserCustomDataLoaderAwareTrait;
    use UserCustomFieldComponentBuilderAwareTrait;
    use UserCustomFieldHelperAwareTrait;
    use UserCustomFieldLoaderAwareTrait;
    use UserCustomFieldTranslationManagerAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * These options are externally defined
     * Parent QForm or QControl that is responsible for rendering this control
     */
    protected QForm|QControl|null $parentObject = null;
    /**
     * user_cust_field.on_profile
     */
    protected ?bool $isFilterOnProfile = null;
    /**
     * user_cust_field.on_registration
     */
    protected ?bool $isFilterOnRegistration = null;
    /**
     * user_cust_field.on_add_new_bidder
     */
    protected ?bool $isFilterOnAddNewBidder = null;
    /**
     * user_cust_field.panel
     * @var int[]
     */
    protected array $panels = [];
    /**
     * Determine some rendering options, e.g. at public we translate labels, at private do not.
     */
    protected bool $isTranslating = true;
    /**
     * To find control values related to the user (user_cust_data.user_id)
     * null in case of anonymous user
     */
    protected ?int $userId = null;
    protected bool $isEditMode = true;
    /**
     * Method of parent control, which should be called on "Add file" button click
     */
    protected ?string $addFilePanelMethodName = null;
    /**
     * Method of parent control, which should be called on delete control button click
     */
    protected ?string $deleteFilePanelMethodName = null;
    /**
     * Determine rendering control differences, mobile is on for public = true,
     * and vice versa at the current moment of 2019
     */
    protected bool $isMobileUi = false;
    /**
     * Field types that should be skipped on save, if empty
     * @var int[]
     */
    protected array $skipEmptyOnSaveFieldTypes = [];
    /**
     * We may want have difference between public and admin side rendering
     * Eg. we don't want to render file names of user custom fields at public side and render at admin side
     */
    protected bool $isPublic = true;

    // these options not need to define externally, they are dependent on the options above
    /**
     * Although, we still can define this property externally too
     * @var UserCustField[]|null
     */
    protected ?array $userCustomFields = null;
    /**
     * @var BaseEdit[]
     */
    protected array $components = [];
    protected ?QForm $form = null;
    /**
     * When this flag is enabled and custom field's "Required" property is enabled, then we should check input value to be filled
     */
    protected bool $shouldConsiderConstraintToBeRequired = true;

    /**
     * Class instantiation method
     * @return static or customized class extending EditControls
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Create and return array of controls for user custom fields editing
     *
     * @return void
     */
    public function create(): void
    {
        /** @var QForm|QControl $parentObject */
        $parentObject = $this->getParentObject();
        foreach ($this->getUserCustFields() as $userCustomField) {
            $controlId = 'UsrCustFldEdt' . $userCustomField->Id;
            $userCustomData = $this->getUserCustomDataLoader()->loadOrCreate(
                $userCustomField,
                $this->getUserId(),
                $this->isTranslating()
            );
            $createMethod = $this->getUserCustomFieldHelper()->makeCustomMethodName($userCustomField->Name, "Create"); // SAM-1573
            if (method_exists($parentObject, $createMethod)) {
                $component = $parentObject->$createMethod(
                    $parentObject,
                    $controlId,
                    $userCustomField,
                    $userCustomData,
                    $this->isEditMode(),
                    $this->isMobileUi()
                );
            } else {
                $component = $this->getUserCustomFieldComponentBuilder()
                    ->createComponent($userCustomField->Type);
                $component
                    ->enableEditMode($this->isEditMode())
                    ->enableMobileUi($this->isMobileUi())
                    ->enablePublic($this->isPublic())
                    ->enableTranslating($this->isTranslating())
                    ->setControlId($controlId)
                    ->setCustomData($userCustomData)
                    ->setCustomField($userCustomField)
                    ->setEditorUserId($this->detectModifierUserId())
                    ->setParentObject($parentObject)
                    ->setRelatedEntityId($this->getUserId());
                if ($userCustomField->Type === Constants\CustomField::TYPE_FILE) {
                    /** @var FileEdit $component */
                    $component->setAddFilePanelMethodName($this->getAddFilePanelMethodName());
                    $component->setDeleteFilePanelMethodName($this->getDeleteFilePanelMethodName());
                    $width = $this->isPublic() ? '170px' : '280px';
                    $component->setWidth($width);
                }
                $component->enableConsiderConstraintToBeRequired($this->shouldConsiderConstraintToBeRequired());
                $component->create();
            }
            $this->components[$userCustomField->Id] = $component;
        }
    }

    /**
     * Initialize user custom field controls with data
     *
     * @return void
     */
    public function init(): void
    {
        /** @var QForm|QControl $parentObject */
        $parentObject = $this->getParentObject();
        foreach ($this->getUserCustFields() as $userCustomField) {
            $component = $this->components[$userCustomField->Id];
            $initMethod = $this->getUserCustomFieldHelper()->makeCustomMethodName($userCustomField->Name, "Init");    // SAM-1573
            if (method_exists($parentObject, $initMethod)) {
                $parentObject->$initMethod($component);
            } else {
                $component->init();
            }
        }
    }

    /**
     * Save user custom field data
     *
     * @return void
     */
    public function save(): void
    {
        if (!$this->isEditMode()) {
            return;
        }
        /** @var QForm|QControl $parentObject */
        $parentObject = $this->getParentObject();
        foreach ($this->getUserCustFields() as $userCustomField) {
            $component = $this->components[$userCustomField->Id];
            $shouldSkip = in_array($userCustomField->Type, $this->getSkipEmptyOnSaveFieldTypes(), true);
            $component->enableSkipEmptyOnSave($shouldSkip);
            $saveMethod = $this->getUserCustomFieldHelper()->makeCustomMethodName($userCustomField->Name, "Save");    // SAM-1573
            if (method_exists($parentObject, $saveMethod)) {
                $parentObject->$saveMethod($component);
            } else {
                $component->save();
            }
        }
    }

    /**
     * Upload and save custom field by file type
     *
     * @return void
     */
    public function uploadFiles(): void
    {
        foreach ($this->getUserCustFields() as $userCustomField) {
            if ($userCustomField->Type === Constants\CustomField::TYPE_FILE) {
                $component = $this->components[$userCustomField->Id];
                // In case if auction was created - custom field were loaded without ids for EditControls, so load them again
                $userCustomData = $this->getUserCustomDataLoader()->loadOrCreate(
                    $userCustomField,
                    $this->userId
                );
                $component->setCustomData($userCustomData);
                $component->save();
            }
        }
    }

    /**
     * Return HTML for user custom fields controls
     *
     * @return string
     */
    public function getHtml(): string
    {
        $output = '';
        /** @var QForm|QControl $parentObject */
        $parentObject = $this->getParentObject();
        foreach ($this->getUserCustFields() as $userCustomField) {
            // $name
            if ($this->isTranslating()) {
                if ($userCustomField->Type === Constants\CustomField::TYPE_LABEL) {
                    $name = '';
                } else {
                    $name = $this->getUserCustomFieldTranslationManager()->translateName($userCustomField);
                    $name .= ':';
                }
            } else {
                $name = ee($userCustomField->Name);
            }
            if (
                $this->shouldConsiderConstraintToBeRequired()
                && $userCustomField->Required
            ) {
                $name .= '<span class="req">*</span>';
            }

            // $controlHtml
            $component = $this->components[$userCustomField->Id];
            $renderMethod = $this->getUserCustomFieldHelper()->makeCustomMethodName($userCustomField->Name, "Render");    // SAM-1573
            if (method_exists($parentObject, $renderMethod)) {
                $controlHtml = $parentObject->$renderMethod($component);
            } else {
                $controlHtml = $component->render();
            }

            // final HTML rendering
            $cssClass = $this->createCustomFieldCssClassMaker()->makeCssClassByUserCustomField($userCustomField);
            $output .=
                '<div class="group solo">' . "\n" .
                '<div class="group solo">' . "\n" .
                '<div class="label-input ' . $cssClass . '">' . "\n" .
                '<div class="label">' . $name . '</div>' . "\n" .
                '<div class="input">' . $controlHtml . '</div>' . "\n" .
                '</div>' . "\n" .
                '</div>' . "\n" .
                '</div>' . "\n";
        }
        return $output;
    }

    /**
     * Validate user custom fields editing controls.
     * Before validation we clear old warnings in custom field controls
     *
     * @return bool
     */
    public function validate(): bool
    {
        if (!$this->isEditMode()) {
            return false;
        }

        foreach ($this->components as $component) {
            $component->clearWarning();
        }
        $hasError = false;
        /** @var QForm|QControl $parentObject */
        $parentObject = $this->getParentObject();
        foreach ($this->getUserCustFields() as $userCustomField) {
            $component = $this->components[$userCustomField->Id];
            $validateMethod = $this->getUserCustomFieldHelper()->makeCustomMethodName($userCustomField->Name, "Validate");    // SAM-1573
            if (method_exists($parentObject, $validateMethod)) {
                if (!$parentObject->$validateMethod($component)) {
                    $hasError = true;
                }
            } elseif (!$component->validate()) {
                $hasError = true;
            }
        }
        return !$hasError;
    }

    /**
     * Load array of user custom fields, basing on defined panels
     * @return UserCustField[]
     */
    public function getUserCustFields(): array
    {
        if ($this->userCustomFields === null) {
            $this->userCustomFields = $this->getUserCustomFieldLoader()
                ->loadEntities(
                    $this->getPanels(),
                    $this->isFilterOnRegistration(),
                    $this->isFilterOnProfile(),
                    $this->isFilterOnAddNewBidder()
                );
        }
        return $this->userCustomFields;
    }

    /**
     * @param UserCustField[] $userCustomFields
     * @return static
     */
    public function setUserCustomFields(array $userCustomFields): static
    {
        $this->userCustomFields = $userCustomFields;
        return $this;
    }

    /**
     * Create custom field file panel
     *
     * @param int $userCustomFieldId user_cust_field.id
     * @param string $fileName
     */
    public function createFilePanel(int $userCustomFieldId, string $fileName = ''): void
    {
        /** @var FileEdit $component */
        $component = $this->components[$userCustomFieldId];
        $component->setParentObject($this->getParentObject());
        $component->createFilePanel($fileName);
    }

    /**
     * Remove custom field file panel
     *
     * @param string $filePanelControlId File panel control id
     * @param int $userCustomFieldId auction_cust_field.id
     */
    public function removeFilePanel(string $filePanelControlId, int $userCustomFieldId): void
    {
        if (!isset($this->components[$userCustomFieldId])) {
            throw new RuntimeException(
                'User custom field component not found'
                . composeSuffix(['id' => $userCustomFieldId])
            );
        }
        /** @var FileEdit $component */
        $component = $this->components[$userCustomFieldId];
        $component->setParentObject($this->getParentObject());
        $component->removeFilePanel($filePanelControlId);
    }

    /**
     * @return int[]
     */
    public function getSkipEmptyOnSaveFieldTypes(): array
    {
        return $this->skipEmptyOnSaveFieldTypes;
    }

    /**
     * @param int[] $skipEmptyOnSaveFieldTypes
     * @return static
     */
    public function setSkipEmptyOnSaveFieldTypes(array $skipEmptyOnSaveFieldTypes): static
    {
        $this->skipEmptyOnSaveFieldTypes = ArrayCast::castInt($skipEmptyOnSaveFieldTypes);
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
     * @return int[]
     */
    public function getPanels(): array
    {
        return $this->panels;
    }

    /**
     * @param int $panel
     * @return static
     */
    public function setPanel(int $panel): static
    {
        $this->setPanels([$panel]);
        return $this;
    }

    /**
     * @param int[] $panels
     * @return static
     */
    public function setPanels(array $panels): static
    {
        $this->panels = $panels;
        return $this;
    }

    /**
     * @return QControl|QForm
     */
    public function getParentObject(): QControl|QForm
    {
        if (!$this->parentObject) {
            throw new RuntimeException('ParentObject not defined');
        }
        return $this->parentObject;
    }

    /**
     * @param QControl|QForm $parentObject
     * @return static
     */
    public function setParentObject(QControl|QForm $parentObject): static
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
     * @return bool|null - null means no filtering
     */
    public function isFilterOnProfile(): ?bool
    {
        return $this->isFilterOnProfile;
    }

    /**
     * @param bool|null $isOnProfile
     * @return static
     */
    public function enableFilterOnProfile(?bool $isOnProfile): static
    {
        $this->isFilterOnProfile = $isOnProfile;
        return $this;
    }

    /**
     * @return bool|null - null means no filtering
     */
    public function isFilterOnRegistration(): ?bool
    {
        return $this->isFilterOnRegistration;
    }

    /**
     * @param bool|null $isOnRegistration
     * @return static
     */
    public function enableFilterOnRegistration(?bool $isOnRegistration): static
    {
        $this->isFilterOnRegistration = $isOnRegistration;
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
     * We may want have difference between public and admin side rendering
     * Eg. we don't want to render file names of user custom fields at public side and render at admin side
     *
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
        return $this;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int|null $userId null in case of anonymous user
     * @return static
     */
    public function setUserId(?int $userId): static
    {
        $userId = Cast::toInt($userId, Constants\Type::F_INT_POSITIVE);
        foreach ($this->components as $component) {
            $component->getCustomData()->UserId = $userId;
            $component->setRelatedEntityId($userId);
            if (!$component->getCustomData()->Id) {    // Reload CustData, if it wasn't previously loaded (used at "Add new bidder", when registering existing user)
                $userCustomData = $this->getUserCustomDataLoader()->loadOrCreate(
                    $component->getCustomField(),
                    $userId,
                    $this->isTranslating()
                );
                $component->setCustomData($userCustomData);
            }
            if ($component->getType() === Constants\CustomField::TYPE_FILE) {
                /** @var FileEdit $component */
                $component->refreshFilePath();
            }
        }
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddFilePanelMethodName(): ?string
    {
        return $this->addFilePanelMethodName;
    }

    /**
     * @param string $addFilePanelMethodName
     * @return static
     */
    public function setAddFilePanelMethodName(string $addFilePanelMethodName): static
    {
        $this->addFilePanelMethodName = trim($addFilePanelMethodName);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDeleteFilePanelMethodName(): ?string
    {
        return $this->deleteFilePanelMethodName;
    }

    /**
     * @param string $deleteFilePanelMethodName
     * @return static
     */
    public function setDeleteFilePanelMethodName(string $deleteFilePanelMethodName): static
    {
        $this->deleteFilePanelMethodName = trim($deleteFilePanelMethodName);
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
     * @return bool|null - null means no filtering
     */
    public function isFilterOnAddNewBidder(): ?bool
    {
        return $this->isFilterOnAddNewBidder;
    }

    /**
     * @param bool|null $enable
     * @return static
     */
    public function enableFilterOnAddNewBidder(?bool $enable): static
    {
        $this->isFilterOnAddNewBidder = $enable;
        return $this;
    }

    /**
     * @return bool
     */
    public function shouldConsiderConstraintToBeRequired(): bool
    {
        return $this->shouldConsiderConstraintToBeRequired;
    }

    /**
     * @param bool $enable
     * @return $this
     */
    public function enableConsiderConstraintToBeRequired(bool $enable): static
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
