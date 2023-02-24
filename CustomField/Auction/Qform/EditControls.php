<?php
/**
 * Helper class for QCodo controllers (drafts), which work with editing controls for custom auction fields.
 * Back end: auction edit page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: EditControls.php 13797 2013-07-09 15:26:18Z SWB\igors $
 * @since           Jul 11, 2013
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\Qform;

use AuctionCustField;
use QControl;
use QForm;
use RuntimeException;
use Sam\Auction\FieldConfig\Provider\AuctionFieldConfigProviderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Auction\Help\AuctionCustomFieldHelperAwareTrait;
use Sam\CustomField\Auction\Load\AuctionCustomDataLoaderAwareTrait;
use Sam\CustomField\Auction\Load\AuctionCustomFieldLoaderAwareTrait;
use Sam\CustomField\Auction\Qform\Component\AuctionCustomFieldComponentBuilderAwareTrait;
use Sam\CustomField\Auction\Save\AuctionCustomDataProducerCreateTrait;
use Sam\CustomField\Auction\Translate\AuctionCustomFieldTranslationManagerAwareTrait;
use Sam\CustomField\Base\Qform\Component\BaseEdit;
use Sam\CustomField\Base\Qform\Component\FileEdit;
use Sam\CustomField\Base\Render\Css\CustomFieldCssClassMakerCreateTrait;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;

/**
 * Class EditControls
 * FormStateLongevityAwareTrait is used JIC this service will be used in QForm context.
 * JIC - because now this service is used in QPanel, and we don't clean panel object state.
 */
class EditControls extends CustomizableClass
{
    use AuctionCustomDataLoaderAwareTrait;
    use AuctionCustomDataProducerCreateTrait;
    use AuctionCustomFieldComponentBuilderAwareTrait;
    use AuctionCustomFieldHelperAwareTrait;
    use AuctionCustomFieldLoaderAwareTrait;
    use AuctionCustomFieldTranslationManagerAwareTrait;
    use AuctionFieldConfigProviderAwareTrait;
    use CustomFieldCssClassMakerCreateTrait;
    use FormStateLongevityAwareTrait;

    // these options are externally defined
    /**
     * Parent QForm or QControl that is responsible for rendering this control
     */
    protected QForm|QControl $parentObject;
    /**
     * To find control values related to the auction (auction_cust_data.auction_id)
     */
    protected ?int $auctionId = null;
    protected ?int $auctionAccountId = null;
    protected ?int $editorUserId = null;
    /**
     * Determine some rendering options, e.g. at public we translate labels, at private do not.
     */
    protected bool $isTranslating = true;
    /**
     * Method of parent control, which should be called on "Add file" button click
     */
    protected string $addFilePanelMethodName = '';
    /**
     * Method of parent control, which should be called on delete control button click
     */
    protected string $deleteFilePanelMethodName = '';
    /**
     * Auction is cloned from $this->AuctionId
     */
    protected bool $isCloned = false;
    /**
     * We may want have difference between public and admin side rendering
     * But we don't manage auction custom fields at public side at the moment
     */
    protected bool $isPublic = false;

    // these options not need to define externally, they are dependent on the options above
    /**
     * Although, we still can define this property externally too
     * @var AuctionCustField[]|null
     */
    protected ?array $auctionCustomFields = null;
    /**
     * @var BaseEdit[]
     */
    protected array $components = [];
    protected ?QForm $form = null;

    /**
     * Class instantiation method
     * @return static or customized class extending EditControls
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Create and return array of controls for auction custom fields editing
     * @return void
     */
    public function create(): void
    {
        foreach ($this->getAuctionCustomFields() as $auctionCustomField) {
            $controlId = 'AucCustFldEdt' . $auctionCustomField->Id;
            $auctionCustomData = $this->getAuctionCustomDataLoader()->loadOrCreate(
                $auctionCustomField,
                $this->auctionId,
                $this->editorUserId,
                $this->isTranslating()
            );
            if ($this->isCloned()) {
                $auctionCustomDataOrig = $auctionCustomData;
                $auctionCustomData = $this->createAuctionCustomDataProducer()
                    ->construct(false)
                    ->produce($auctionCustomField, null, $this->editorUserId, $this->isTranslating());
                if ($auctionCustomField->Clone) {
                    $auctionCustomData->AuctionCustFieldId = $auctionCustomDataOrig->AuctionCustFieldId;
                    $auctionCustomData->AuctionId = null;
                    $auctionCustomData->Numeric = $auctionCustomDataOrig->Numeric;
                    $auctionCustomData->Text = $auctionCustomDataOrig->Text;
                }
            }
            $createMethod = $this->getAuctionCustomFieldHelper()->makeCustomMethodName($auctionCustomField->Name, "Create");
            if (method_exists($this->getParentObject(), $createMethod)) {
                $component = $this->getParentObject()->$createMethod(
                    $this->getParentObject(),
                    $controlId,
                    $auctionCustomField,
                    $auctionCustomData
                );
            } else {
                $component = $this->getAuctionCustomFieldComponentBuilder()
                    ->createComponent($auctionCustomField->Type);
                $component
                    ->enableTranslating($this->isTranslating())
                    ->enablePublic($this->isPublic())
                    ->setControlId($controlId)
                    ->setCustomData($auctionCustomData)
                    ->setCustomField($auctionCustomField)
                    ->setParentObject($this->getParentObject())
                    ->setRelatedEntityId($this->auctionId);
                if ($auctionCustomField->Type === Constants\CustomField::TYPE_FILE) {
                    /** @var FileEdit $component */
                    $component->setAddFilePanelMethodName($this->getAddFilePanelMethodName());
                    $component->setDeleteFilePanelMethodName($this->getDeleteFilePanelMethodName());
                }
                $component->create();
            }
            $this->components[$auctionCustomField->Id] = $component;
            log_trace(
                'Control Id \'' . $controlId .
                '\' for ' . $this->getAuctionCustomFieldHelper()->makeTypeName($auctionCustomField->Type)
            );
        }
    }

    /**
     * Initialize auction custom field controls with data
     *
     * @return void
     */
    public function init(): void
    {
        foreach ($this->getAuctionCustomFields() as $auctionCustomField) {
            $component = $this->components[$auctionCustomField->Id];
            $initMethod = $this->getAuctionCustomFieldHelper()->makeCustomMethodName($auctionCustomField->Name, "Init");
            if (method_exists($this->getParentObject(), $initMethod)) {
                $this->getParentObject()->$initMethod($component);
            } else {
                $component->init();
            }
        }
    }

    /**
     * Save auction custom field data
     *
     * @return void
     */
    public function save(): void
    {
        foreach ($this->getAuctionCustomFields() as $auctionCustomField) {
            $component = $this->components[$auctionCustomField->Id];
            $saveMethod = $this->getAuctionCustomFieldHelper()->makeCustomMethodName($auctionCustomField->Name, "Save");
            if (method_exists($this->getParentObject(), $saveMethod)) {
                $this->getParentObject()->$saveMethod($component);
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
        foreach ($this->getAuctionCustomFields() as $auctionCustomField) {
            if ($auctionCustomField->Type === Constants\CustomField::TYPE_FILE) {
                $component = $this->components[$auctionCustomField->Id];
                // In case if auction was created - custom field were loaded without ids for EditControls, so load them again
                $auctionCustomData = $this->getAuctionCustomDataLoader()->loadOrCreate(
                    $auctionCustomField,
                    $this->auctionId,
                    $this->editorUserId
                );
                $component->setCustomData($auctionCustomData);
                $component->save();
            }
        }
    }

    public function render(AuctionCustField $customField): string
    {
        // $name
        if ($this->isTranslating()) {
            if ($customField->Type === Constants\CustomField::TYPE_LABEL) {
                $name = '';
            } else {
                $name = $this->getAuctionCustomFieldTranslationManager()->translateName($customField);
                $name .= ':';
            }
        } else {
            $name = ee($customField->Name);
        }
        $isRequired = $this->getAuctionFieldConfigProvider()->isRequiredCustomField(
            $customField->Id,
            $this->auctionAccountId
        );
        if ($isRequired) {
            $name .= '<span class="req">*</span>';
        }

        // $controlHtml
        $component = $this->components[$customField->Id];
        $renderMethod = $this->getAuctionCustomFieldHelper()->makeCustomMethodName($customField->Name, "Render");
        $parentObject = $this->getParentObject();
        if (method_exists($parentObject, $renderMethod)) {
            $controlHtml = $parentObject->$renderMethod($component);
        } else {
            $controlHtml = $component->render();
        }

        // final HTML rendering
        $cssClass = $this->createCustomFieldCssClassMaker()->makeCssClassByAuctionCustomField($customField);
        return
            '<div class="group solo"><div class="group solo duo-md"><div class="label-input ' . $cssClass . '">' . "\n" .
            '<div class="label">' . $name . '</div>' . "\n" .
            '<div class="input">' . $controlHtml . '</div><br>' . "\n" .
            '</div></div></div>';
    }

    /**
     * Validate auction custom fields editing controls.
     * Before validation we clear old warnings in custom field controls
     * @return bool
     */
    public function validate(): bool
    {
        foreach ($this->components as $component) {
            $component->clearWarning();
        }
        $hasError = false;
        foreach ($this->getAuctionCustomFields() as $auctionCustomField) {
            $component = $this->components[$auctionCustomField->Id];
            $validateMethod = $this->getAuctionCustomFieldHelper()->makeCustomMethodName($auctionCustomField->Name, "Validate");
            if (method_exists($this->getParentObject(), $validateMethod)) {
                if (!$this->getParentObject()->$validateMethod($component)) {
                    $hasError = true;
                }
            } elseif (!$component->validate()) {
                $hasError = true;
            }
        }
        return !$hasError;
    }

    /**
     * File-type custom field related functionality
     */

    /**
     * Create custom field file panel
     *
     * @param int $auctionCustomFieldId auction_cust_field.id
     * @param string $fileName
     */
    public function createFilePanel(int $auctionCustomFieldId, string $fileName = ''): void
    {
        /** @var FileEdit $component */
        $component = $this->components[$auctionCustomFieldId];
        $component->createFilePanel($fileName);
    }

    /**
     * Remove custom field file panel
     *
     * @param string $filePanelControlId File panel control id
     * @param int $auctionCustomFieldId auction_cust_field.id
     */
    public function removeFilePanel(string $filePanelControlId, int $auctionCustomFieldId): void
    {
        if (!isset($this->components[$auctionCustomFieldId])) {
            throw new RuntimeException(
                'Auction custom field component not found'
                . composeSuffix(['id' => $auctionCustomFieldId])
            );
        }
        /** @var FileEdit $component */
        $component = $this->components[$auctionCustomFieldId];
        $component->removeFilePanel($filePanelControlId);
    }

    /**
     * Load array of auction custom fields
     *
     * @return AuctionCustField[]
     */
    public function getAuctionCustomFields(): array
    {
        if ($this->auctionCustomFields === null) {
            $this->auctionCustomFields = [];
            $auctionCustomFields = $this->getAuctionCustomFieldLoader()->loadAll();
            foreach ($auctionCustomFields as $customField) {
                $isVisible = $this->getAuctionFieldConfigProvider()->isVisibleCustomField(
                    $customField->Id,
                    $this->auctionAccountId
                );
                if ($isVisible) {
                    $this->auctionCustomFields[] = $customField;
                }
            }
        }
        return $this->auctionCustomFields;
    }

    /**
     * @param AuctionCustField[] $auctionCustomFields
     * @return static
     * @noinspection PhpUnused
     */
    public function setAuctionCustomFields(array $auctionCustomFields): static
    {
        $this->auctionCustomFields = $auctionCustomFields;
        return $this;
    }

    /**
     * @return QForm|QControl
     */
    public function getParentObject(): QForm|QControl
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
     * @return int|null
     */
    public function getAuctionId(): ?int
    {
        return $this->auctionId;
    }

    /**
     * @param int|null $auctionId null when creating new auction
     * @return static
     */
    public function setAuctionId(?int $auctionId): static
    {
        // components are not created at moment of $this initialization
        // next logic when we save new auction and get know auction.id
        foreach ($this->components as $component) {
            $component->getCustomData()->AuctionId = $auctionId;
            $component->setRelatedEntityId($auctionId);
            if ($component->getType() === Constants\CustomField::TYPE_FILE) {
                /** @var FileEdit $component */
                $component->refreshFilePath();
            }
        }
        $this->auctionId = $auctionId;
        return $this;
    }

    public function setAuctionAccountId(int $accountId): static
    {
        $this->auctionAccountId = $accountId;
        return $this;
    }

    /**
     * @param int $editorUserId
     * @return static
     */
    public function setEditorUserId(int $editorUserId): static
    {
        $this->editorUserId = $editorUserId;
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
     * @param bool $isTranslating
     * @return static
     */
    public function enableTranslating(bool $isTranslating): static
    {
        $this->isTranslating = $isTranslating;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddFilePanelMethodName(): string
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
     * @return string
     */
    public function getDeleteFilePanelMethodName(): string
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
    public function isCloned(): bool
    {
        return $this->isCloned;
    }

    /**
     * @param bool $isCloned
     * @return static
     */
    public function enableCloned(bool $isCloned): static
    {
        $this->isCloned = $isCloned;
        return $this;
    }
}
