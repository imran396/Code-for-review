<?php
/**
 * File-type custom field editing component
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: EditControls.php 13797 2013-07-09 15:26:18Z SWB\igors $
 * @since           Jul 20, 2013
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * @method QHiddenInput getControl()
 */

namespace Sam\CustomField\Base\Qform\Component;

use QAjaxAction;
use QAjaxControlAction;
use QButton;
use QClickEvent;
use QControl;
use QForm;
use QHiddenInput;
use QPanel;
use Sam\CustomField\User\Qform\ViewControls;
use Sam\File\FilePathHelperAwareTrait;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;

/**
 * Class FileEdit
 * @package Sam\CustomField\Base\Qform\Component
 * @method QHiddenInput getControl()
 */
class FileEdit extends BaseEdit
{
    use FileManagerCreateTrait;
    use FilePathHelperAwareTrait;

    // these options are externally defined
    /** Method of parent control, which should be called on "Add file" button click */
    protected string $addFilePanelMethodName = '';
    /** Method of parent control, which should be called on delete control button click */
    protected string $deleteFilePanelMethodName = '';

    // for custom fields with file type
    /** Limit count of files for custom field */
    protected ?int $fileMaxCount = null;
    /** Count of uploaded files for custom field */
    protected ?int $fileCount = null;
    /** Allowed file types */
    protected string $allowedTypes = '';

    // rendering attributes
    protected string $width = '330px';
    protected string $display = 'block';

    /**
     * Define FilePath property
     * We need to overwrite it in parent classes
     * @return bool
     */
    public function refreshFilePath(): bool
    {
        return false;
    }

    /**
     * Class instantiation method
     * @return static or customized class extending File_Edit
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Create controls for file-type custom field editing
     *
     * @return static
     */
    public function create(): static
    {
        // Hidden input contain file names separated by pipe
        $control = new QHiddenInput($this->getParentObject(), $this->getControlId());
        $control->Visible = false;
        $this->setControl($control);
        // Init variables for restrictions
        $this->fileCount = 0;
        preg_match('/^([^;]+);(\d+)$/', $this->getCustomField()->Parameters, $matches);
        $allowedTypes = $matches[1] ?? null;
        $this->setAllowedTypes($allowedTypes);
        $this->fileMaxCount = isset($matches[2]) ? (int)$matches[2] : null;
        // Create general container for separate file panels
        $pnlGeneralFilePanel = new QPanel($this->getParentObject(), 'pnlGeneralFile' . $this->getCustomField()->Id);
        $pnlGeneralFilePanel->AutoRenderChildren = true;
        // Create "Add file" button
        $btnAddFile = new QButton($this->getParentObject(), 'btnAddFile' . $this->getCustomField()->Id);
        $btnAddFile->ActionParameter = $this->getCustomField()->Id;
        $btnAddFile->Text = 'Add file';
        $btnAddFile->CssClass = 'addlink';
        $btnAddFile->ToolTip = 'Add';
        $btnAddFile->Name = $this->getCustomField()->Name;
        if ($this->getParentObject() instanceof QControl) {
            $btnAddFile->AddAction(
                new QClickEvent(),
                new QAjaxControlAction($this->getParentObject(), $this->getAddFilePanelMethodName())
            );
        } else {
            $btnAddFile->AddAction(
                new QClickEvent(),
                new QAjaxAction($this->getAddFilePanelMethodName())
            );
        }
        return $this;
    }

    /**
     * Initialize file-type custom field controls with data
     *
     * @return static
     */
    public function init(): static
    {
        $this->getControl()->Text = $this->getCustomData()->Text;
        if ($this->getControl()->Text) {
            $fileNames = explode('|', $this->getCustomData()->Text);
            foreach ($fileNames as $fileName) {
                $this->createFilePanel($fileName);
            }
            $this->validate();
        }
        return $this;
    }

    /**
     * Check, if control is not filled
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        $isEmpty = !$this->fileCount;
        return $isEmpty;
    }

    /**
     * Return HTML for file-type custom field controls
     *
     * @return string
     */
    public function render(): string
    {
        $output = $this->getControl()->RenderWithError(false);
        /** @var QPanel|null $pnlGeneralFilePanel */
        $pnlGeneralFilePanel = $this->getForm()->GetControl('pnlGeneralFile' . $this->getCustomField()->Id);
        if (
            $pnlGeneralFilePanel
            && !$pnlGeneralFilePanel->Rendered
        ) {
            $output .= ' ' . $pnlGeneralFilePanel->RenderWithError(false);
        }
        /** @var QButton|null $btnAddFile */
        $btnAddFile = $this->getForm()->GetControl('btnAddFile' . $this->getCustomField()->Id);
        if (
            $btnAddFile
            && !$btnAddFile->Rendered
        ) {
            $output .= ' ' . $btnAddFile->RenderWithError(false);
        }
        return $output;
    }

    /**
     * Validate file-type custom field editing controls.
     *
     * @return bool
     */
    public function validate(): bool
    {
        $success = true;
        /** @var QPanel $pnlGeneralFilePanel */
        $pnlGeneralFilePanel = $this->getForm()->GetControl('pnlGeneralFile' . $this->getCustomField()->Id);
        /** @var FileEditPanel[] $fileEditPanels */
        $fileEditPanels = $pnlGeneralFilePanel->GetChildControls();
        if ($this->getCustomField()->Required && !count($fileEditPanels)) {
            $success = false;
            $pnlGeneralFilePanel->Warning = 'Required';
        }
        foreach ($fileEditPanels as $fileEditPanel) {
            if (!$fileEditPanel->validate()) {
                $success = false;
            }
        }
        return $success;
    }

    /**
     * Save file-type custom field data
     *
     * @return void
     * @throws FileException
     */
    public function save(): void
    {
        if (
            $this->isSkipEmptyOnSave
            && $this->isEmpty()
        ) {
            return;
        }
        $this->saveUploadedFiles();
        $oldFileNames = explode('|', $this->getCustomData()->Text);
        $actualFileNames = $this->getActualFileNames();
        if ($this->isTranslating) {
            // Registered file names aren't editable at public side, so we operate with names from file assets for save in custom field data
            $deleteFileNames = array_diff($oldFileNames, $actualFileNames);
            log_trace(
                'Saving data for File type custom field at Public side'
                . composeSuffix(
                    [
                        'Name' => $this->getCustomField()->Name,
                        'Id' => $this->getCustomField()->Id,
                        'Actual files' => $actualFileNames,
                        'Files to delete' => $deleteFileNames,
                    ]
                )
            );
            $this->getCustomData()->Text = implode('|', $actualFileNames);
        } else {
            // We can edit file names at admin side, we operate with that data, when save custom field data
            $enteredFileNames = $this->getEnteredFileNames();
            $deleteFileNames = array_diff($oldFileNames, $actualFileNames, $enteredFileNames);
            log_trace(
                'Saving data for File type custom field at Admin side'
                . composeSuffix(
                    [
                        'Name' => $this->getCustomField()->Name,
                        'Id' => $this->getCustomField()->Id,
                        'In Db files' => $oldFileNames,
                        'Actual files' => $actualFileNames,
                        'Entered names' => $enteredFileNames,
                        'Files to delete' => $deleteFileNames,
                    ]
                )
            );
            $this->getCustomData()->Text = implode('|', $enteredFileNames);
        }
        $this->deleteFiles($deleteFileNames);
        $this->removeEmptyPanels();
        parent::save();
    }

    /**
     * Create custom field file panel
     *
     * @param string $fileName
     */
    public function createFilePanel(string $fileName = ''): void
    {
        $btnAddFile = $this->getForm()->GetControl('btnAddFile' . $this->getCustomField()->Id);
        // Disable button if limit exceeded
        if ($this->fileMaxCount === $this->fileCount) {
            if ($btnAddFile) {
                $btnAddFile->Enabled = false;
            }
            return;
        }
        // Create custom field file panel
        /** @var QPanel $pnlGeneralFilePanel */
        $pnlGeneralFilePanel = $this->getForm()->GetControl('pnlGeneralFile' . $this->getCustomField()->Id);
        $fileEditPanel = new FileEditPanel($pnlGeneralFilePanel, null, $this);
        $fileEditPanel->setFileNameInFileLabel($fileName);
        $fileEditPanel->setTmpFileName($fileName);
        $fileEditPanel->setFileNameInFileAsset($fileName);

        if ($this->isDownloadLink()) {
            $link = ViewControls::new()->renderFileLink($fileName, $this->getCustomData()->UserId);
            $fileEditPanel->setDownloadLink($link);
        }

        $fileEditPanel->btnDelete->ActionParameter = $fileEditPanel->ControlId . ',' . $this->getCustomField()->Id . ',' . $fileName;
        if ($this->getParentObject() instanceof QControl) {
            $fileEditPanel->btnDelete->AddAction(
                new QClickEvent(),
                new QAjaxControlAction($this->getParentObject(), $this->getDeleteFilePanelMethodName())
            );
        } else {
            $fileEditPanel->btnDelete->AddAction(
                new QClickEvent(),
                new QAjaxAction($this->getDeleteFilePanelMethodName())
            );
        }
        $fileEditPanel->setWidth($this->getWidth());
        $fileEditPanel->setHeight($this->getHeight());
        $fileEditPanel->setDisplay($this->getDisplay());
        $this->fileCount++;
        // Disable button if limit exceeded
        if (
            $this->fileMaxCount === $this->fileCount
            && $btnAddFile
        ) {
            $btnAddFile->Visible = false;
        }
    }

    /**
     * Remove panels without assigned file
     */
    protected function removeEmptyPanels(): void
    {
        /** @var QPanel $pnlGeneralFilePanel */
        $pnlGeneralFilePanel = $this->getForm()->GetControl('pnlGeneralFile' . $this->getCustomField()->Id);
        foreach ($pnlGeneralFilePanel->GetChildControls() as $childControl) {
            /** @var FileEditPanel $childControl */
            if (!$childControl->flaFile->File) {
                $this->removeFilePanel($childControl->ControlId);
            }
        }
    }

    /**
     * Remove custom field file panel
     *
     * @param string $filePnlControlId File panel control id
     */
    public function removeFilePanel(string $filePnlControlId): void
    {
        /** @var QPanel|null $pnlGeneralFilePanel */
        $pnlGeneralFilePanel = $this->getForm()->GetControl('pnlGeneralFile' . $this->getCustomField()->Id);
        if ($pnlGeneralFilePanel) {
            $pnlGeneralFilePanel->RemoveChildControl($filePnlControlId, true);
            $btnAddFile = $this->getForm()->GetControl('btnAddFile' . $this->getCustomField()->Id);
            if ($btnAddFile) {
                $btnAddFile->Visible = true;
            }
            $this->fileCount--;
        }
    }

    /**
     * Move uploaded files from temporary folder to permanent location
     *
     * @return void
     * @throws FileException
     */
    public function saveUploadedFiles(): void
    {
        /** @var QPanel $pnlGeneralFilePanel */
        $pnlGeneralFilePanel = $this->getForm()->GetControl('pnlGeneralFile' . $this->getCustomField()->Id);
        /** @var FileEditPanel $childControl */
        foreach ($pnlGeneralFilePanel->GetChildControls() as $childControl) {
            $permanentPath = substr($this->getFilePath(), strlen(path()->sysRoot()));
            if ($childControl->flaFile->isFileUploaded($this->getFilePath())) {
                $fileName = $this->getFilePathHelper()->toFilename($childControl->flaFile->FileName);
                $extension = '';
                $filePath = $permanentPath . '/' . $fileName;
                if ($this->createFileManager()->exist($filePath)) {
                    do {
                        $segArray = explode('.', $fileName);
                        if (count($segArray) > 1) {
                            $extension = mb_strtolower(array_pop($segArray));
                        }
                        $baseFileName = implode('.', $segArray);
                        $fileName = $baseFileName . '__' . $this->getRelatedEntityId() . '.' . $extension;
                        $filePath = $permanentPath . '/' . $fileName;
                    } while ($this->createFileManager()->exist($filePath));
                }
                $filePath = str_replace(' ', '', utf8_encode($filePath));
                $sourceFilePath = substr($childControl->flaFile->File, strlen(path()->sysRoot()));
                $this->createFileManager()->move($sourceFilePath, $filePath);
                $childControl->setFileNameInFileLabel($fileName);
                $childControl->setTmpFileName($fileName);
                $childControl->setFileNameInFileAsset($fileName);
            }
        }
    }

    /**
     * Return file names, that are currently linked with rendered file panels.
     * These are real names of physical files. We use them to detect, which files should be deleted.
     *
     * @return array
     */
    protected function getActualFileNames(): array
    {
        $fileNames = [];
        /** @var QPanel $pnlGeneralFilePanel */
        $pnlGeneralFilePanel = $this->getForm()->GetControl('pnlGeneralFile' . $this->getCustomField()->Id);
        foreach ($pnlGeneralFilePanel->GetChildControls() as $childControl) {
            $fileName = $childControl->txtFileName->Text ?? $childControl->lblFileName->Text;
            if ($fileName) {
                $fileNames[] = $fileName;
            }
        }
        return $fileNames;
    }

    /**
     * Return file names, that are currently entered in file name text boxes.
     * These files can absent. Available at admin side only. We save them in custom field data.
     * @return array
     */
    protected function getEnteredFileNames(): array
    {
        $fileNames = [];
        /** @var QPanel $pnlGeneralFilePanel */
        $pnlGeneralFilePanel = $this->getForm()->GetControl('pnlGeneralFile' . $this->getCustomField()->Id);
        foreach ($pnlGeneralFilePanel->GetChildControls() as $childControl) {
            $fileNames[] = $childControl->txtFileName->Text;
        }
        return $fileNames;
    }

    /**
     * Physically delete files
     *
     * @param $fileNames
     */
    protected function deleteFiles($fileNames): void
    {
        log_trace('Deleting files ' . implode(', ', $fileNames));
        foreach ($fileNames as $fileName) {
            $filePath = substr($this->getFilePath(), strlen(path()->sysRoot())) . '/' . $fileName;
            try {
                $this->createFileManager()->delete($filePath);
            } catch (FileException) {
                // file lost in unknown reasons
            }
        }
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
        $this->addFilePanelMethodName = $addFilePanelMethodName;
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
        $this->deleteFilePanelMethodName = $deleteFilePanelMethodName;
        return $this;
    }

    /**
     * @return string
     */
    public function getAllowedTypes(): string
    {
        return $this->allowedTypes;
    }

    /**
     * @param string|null $allowedTypes
     * @return static
     */
    public function setAllowedTypes(?string $allowedTypes): static
    {
        $this->allowedTypes = $allowedTypes ?? '';
        return $this;
    }

    protected function getForm(): QForm
    {
        $container = $this->getParentObject();
        return ($container instanceof QForm) ? $container : $container->Form;
    }
}
