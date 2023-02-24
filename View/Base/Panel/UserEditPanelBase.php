<?php
/**
 * Base panel for user fields edit.
 * Implements mutual functionality for personal/billing/shipping panels, i.e. user custom field editing methods
 *
 * @author          Igors Kotlevskis
 * @since           Dec 04, 2013 (repeated code extracted to this class)
 * @copyright       2018 Bidpath, Inc.
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Note: may be we will need to separate create and init custom fields methods, remove them from constructor.
 * I united them to simplify usage cases (and added @property bool $shouldShowCustomFields for this)
 */

namespace Sam\View\Base\Panel;

use QCallerException;
use QCheckBox;
use QControl;
use QDateTimePicker;
use QForm;
use QListBox;
use QPanel;
use Sam\CustomField\User\Help\UserCustomFieldHelper;
use Sam\CustomField\User\Qform\EditControls;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use UserCustField;

/**
 * Class UserEditPanelBase
 */
abstract class UserEditPanelBase extends QPanel
{
    use UserAwareTrait;

    public EditControls $userCustomFieldEditControlsManager;
    protected bool $shouldShowCustomFields = true;

    /**
     * UserEditPanelBase constructor.
     * @param QForm|QControl $parentObject
     * @param string $controlId
     */
    public function __construct($parentObject, string $controlId)
    {
        try {
            parent::__construct($parentObject, $controlId);
        } catch (QCallerException $exception) {
            $exception->IncrementOffset();
            throw $exception;
        }

        if ($this->shouldShowCustomFields) {
            $this->createCustomFields();
            $this->initCustomFields();
        }
    }

    /** *********************************
     * User Custom Field related methods
     */
    /**
     * Create controls for user custom fields
     */
    public function createCustomFields(): void
    {
        $this->userCustomFieldEditControlsManager->create();
    }

    /**
     * Initialize user custom field controls with data
     */
    public function initCustomFields(): void
    {
        $this->userCustomFieldEditControlsManager->init();
    }

    /**
     * Save user custom field data
     *
     * @param int $userId
     */
    public function saveCustomFields(int $userId): void
    {
        $this->userCustomFieldEditControlsManager->setUserId($userId);
        $this->userCustomFieldEditControlsManager->save();
    }

    /**
     * Return HTML for user custom fields controls
     *
     * @return string
     */
    public function getCustomFieldsHtml(): string
    {
        $output = '';
        if ($this->shouldShowCustomFields) {
            $output = $this->userCustomFieldEditControlsManager->getHtml();
        }
        return $output;
    }

    /**
     * Validate user custom field values
     *
     * @return bool
     */
    public function validateCustomFields(): bool
    {
        return $this->userCustomFieldEditControlsManager->validate();
    }

    /**
     * @return string[]
     */
    public function getValidationErrors(): array
    {
        if (is_a($this->userCustomFieldEditControlsManager, \Sam\CustomField\User\Qform\Mobile\EditControls::class)) {
            return $this->userCustomFieldEditControlsManager->getErrors();
        }
        return [];
    }

    /**
     * "Add file" button click handler (for file-type custom fields)
     *
     * @param string $formId
     * @param string $controlId
     * @param string $parameter
     * @noinspection PhpUnusedParameterInspection
     */
    public function handleBtnAddFilePanelClick(string $formId, string $controlId, string $parameter): void
    {
        if (!preg_match('/\d+/', $parameter)) {
            return;
        }
        $userCustomFieldId = (int)$parameter;
        $this->userCustomFieldEditControlsManager->setParentObject($this);
        $this->userCustomFieldEditControlsManager->createFilePanel($userCustomFieldId);
    }

    /**
     * Delete file panel button click handler (for file-type custom fields)
     *
     * @param string $formId
     * @param string $controlId
     * @param string $parameter
     * @noinspection PhpUnusedParameterInspection
     */
    public function handleBtnDeleteFilePanelClick(string $formId, string $controlId, string $parameter): void
    {
        $params = explode(',', $parameter);
        $filePanelControlId = $params[0] ?? '';
        $userCustomFieldId = (int)($params[1] ?? null);
        // $fileName = $params[2] ? $params[2] : null;
        $this->userCustomFieldEditControlsManager->removeFilePanel($filePanelControlId, $userCustomFieldId);
    }

    /**
     * Parse customFields to [name, value] pairs
     * @return array customFields
     */
    public function getCustomFieldValues(): array
    {
        $customFieldValues = [];
        foreach ($this->GetChildControls() as $control) {
            if (preg_match("/(UsrCustFldEdt|pnlGeneralFile)(\d+)/", $control->ControlId, $matches)) {
                $customField = $this->getCustomFieldById((int)$matches[2]);
                if ($customField) {
                    $value = null;
                    if ($control instanceof QListBox) {
                        $value = $control->SelectedValue;
                    } elseif ($control instanceof QCheckBox) {
                        $value = (string)$control->Checked;
                    } elseif ($control instanceof QPanel) {
                        foreach ($control->GetChildControls() as $childControl) {
                            $value = $childControl->txtFileName->Text ?? $childControl->lblFileName->Text;
                        }
                    } elseif ($control instanceof QDateTimePicker) {
                        $value = '';
                        if ($control->Text) {
                            $value = $control->renderAsIso();
                        }
                    } else {
                        $value = $control->Text;
                    }

                    $key = lcfirst(UserCustomFieldHelper::new()->makeSoapTagByName($customField->Name));
                    $customFieldValues[$key] = $value;
                }
            }
        }
        return $customFieldValues;
    }

    /**
     * @param int $userCustomFieldId
     * @return UserCustField|null
     */
    public function getCustomFieldById(int $userCustomFieldId): ?UserCustField
    {
        foreach ($this->userCustomFieldEditControlsManager->getUserCustFields() as $customField) {
            if ($customField->Id === $userCustomFieldId) {
                return $customField;
            }
        }
        return null;
    }

    /**
     * User custom field methods ended
     * *******************************/
}
