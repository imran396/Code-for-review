<?php
/**
 * Project        sam
 * @version       SVN: $Id: custom_field_file_panel.php 15388 2013-12-04 17:31:52Z SWB\igors $
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 * Note: moved from /admin/views/drafts/custom_field_file_panel.php
 */

namespace Sam\CustomField\Base\Qform\Component;

use Exception;
use QControl;
use QFileAssetType;
use QForm;
use QHiddenInput;
use QLabel;
use QLinkButton;
use QPanel;
use QTextBox;
use Sam\CustomField\Base\FileType\Validate\CustomFieldFileValidatorCreateTrait;

/**
 * Class FileEditPanel
 * @package Sam\CustomField\Base\Qform\Component
 */
class FileEditPanel extends QPanel
{
    use CustomFieldFileValidatorCreateTrait;

    protected const CID_FILE_EDIT_ASSET_TPL = 'fileEditAsset_%s';

    public ?QLinkButton $btnDelete = null;
    public ?FileEditAsset $flaFile = null;
    public ?QTextBox $txtFileName = null;    // used at admin side
    public ?QLabel $lblFileName = null;    // used at public side
    public ?QHiddenInput $hidTmpFileName = null; // used at confirm shipping page
    protected string $allowedTypes = '';
    protected bool $isPublic = false;
    protected string $rootPath = '';
    // Rendering attributes
    /** @var string */
    protected $width = '';
    /** @var string */
    protected $height = '';
    protected string $display = '';
    protected string $downloadLink = '';

    /**
     * FileEditPanel constructor.
     *
     * @param QForm|QControl $parentObject
     * @param string|null $controlId
     * @param FileEdit $fileEdit
     */
    public function __construct(
        $parentObject,
        ?string $controlId,
        FileEdit $fileEdit
    ) {
        parent::__construct($parentObject, $controlId);

        $this->Template = __DIR__ . '/FileEditPanel.tpl.php';

        $this->setAllowedTypes($fileEdit->getAllowedTypes());
        $this->enablePublic($fileEdit->isPublic());
        $this->setRootPath($fileEdit->getFilePath());

        $this->createFileLabel();
        $this->createTmpFileName();
        $this->createBtnDelete();
        $this->createFlaFile();
        $this->setDisplay('block');
    }

    /**
     * @param string $fileName
     * @return static
     */
    public function setFileNameInFileAsset(string $fileName): static
    {
        if ($fileName) {
            try {
                $this->flaFile->File = $this->getRootPath() . '/' . $fileName;
            } catch (Exception $e) {
                log_warning($e->getMessage());
            }
        }
        return $this;
    }

    /**
     * @param string $fileName
     * @return static
     */
    public function setFileNameInFileLabel(string $fileName): static
    {
        if ($this->isPublic()) {
            $this->lblFileName->Text = $fileName;
        } else {
            $this->txtFileName->Text = $fileName;
        }
        return $this;
    }

    public function setTmpFileName(string $fileName): static
    {
        if ($this->isPublic()) {
            $this->hidTmpFileName->Text = $fileName;
        }
        return $this;
    }

    /**
     * @param bool $isEnabled
     * @return static
     */
    public function enableFileLabel(bool $isEnabled): static
    {
        if (!$this->isPublic()) {
            $this->txtFileName->Enabled = $isEnabled;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function renderFileName(): string
    {
        if ($this->isPublic()) {
            $output = $this->lblFileName->RenderWithError(false);
        } else {
            $output = $this->txtFileName->RenderWithError(false);
        }
        return $output;
    }

    /**
     * @return string
     */
    public function renderTmpFileName(): string
    {
        return $this->isPublic() ? $this->hidTmpFileName->RenderWithError(false) : '';
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $isSuccess = true;
        if (!$this->flaFile->isFileUploaded($this->getRootPath())) {   // not uploaded to temporary folder, means uploaded in permanent location
            /** @var QTextBox|QLabel $mixFileName */
            $mixFileName = $this->isPublic() ? $this->lblFileName : $this->txtFileName;
            $fileName = $mixFileName->Text;
            if (!$this->createCustomFieldFileValidator()->isFileExist($this->getRootPath() . '/' . $fileName, false)) {
                $mixFileName->Warning = 'File does not exist.';
                $isSuccess = false;
            } elseif (!$this->createCustomFieldFileValidator()->isValidFileType($fileName, $this->getAllowedTypes())) {
                $mixFileName->Warning = str_replace(
                    '{ext}',
                    str_replace('|', ', ', $this->getAllowedTypes()),
                    'You are trying to upload an invalid file format. Allowed format' .
                    (substr_count($this->getAllowedTypes(), '|') + 1 > 1 ? 's are' : ' is') .
                    ': <div style="margin-bottom:5px;"><strong>{ext}</strong></div>'
                );
                $isSuccess = false;
            }
        } elseif (
            $this->flaFile->File
            && $this->flaFile->FileName
        ) {
            if (!$this->createCustomFieldFileValidator()->isValidFileType($this->flaFile->File, $this->getAllowedTypes())) {
                $this->flaFile->Warning = str_replace(
                    '{ext}',
                    str_replace('|', ', ', $this->getAllowedTypes()),
                    'You are trying to upload an invalid file format. Allowed format' .
                    (substr_count($this->getAllowedTypes(), '|') + 1 > 1 ? 's are' : ' is') .
                    ': <div style="margin-bottom:5px;"><strong>{ext}</strong></div>'
                );
                $isSuccess = false;
            }
        }
        return $isSuccess;
    }

    /**
     * @return void
     */
    protected function createFileLabel(): void
    {
        if ($this->isPublic()) {
            $this->lblFileName = new QLabel($this);
        } else {
            $this->txtFileName = new QTextBox($this);
        }
    }

    protected function createTmpFileName(): void
    {
        if ($this->isPublic()) {
            $this->hidTmpFileName = new QHiddenInput($this);
            $this->hidTmpFileName->CssClass = 'temp-custom-field-file';
            $this->hidTmpFileName->TabIndex = (int)str_replace('pnlGeneralFile', '', $this->ParentControl->controlId);
        }
    }

    /**
     * @return void
     */
    protected function createBtnDelete(): void
    {
        $this->btnDelete = new QLinkButton($this);
        $this->btnDelete->Text = 'Delete';
        $this->btnDelete->CssClass = 'deletelink';
        $this->btnDelete->ToolTip = 'Delete';
    }

    /**
     * @return void
     */
    protected function createFlaFile(): void
    {
        $controlId = sprintf(self::CID_FILE_EDIT_ASSET_TPL, $this->controlId);
        $this->flaFile = new FileEditAsset($this, $controlId, $this->getAllowedTypes());
        $this->flaFile->ClickToView = false;
        $this->flaFile->FileAssetType = QFileAssetType::Document;
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

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    /**
     * @param bool $isPublic
     * @return static
     */
    public function enablePublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;
        return $this;
    }

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    /**
     * @param string|null $rootPath
     * @return static
     */
    public function setRootPath(?string $rootPath): static
    {
        $this->rootPath = $rootPath ?? '';
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
     * @param string|null $width
     * @return static
     */
    public function setWidth(?string $width): static
    {
        $this->width = $width ?? '';
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
     * @param string|null $height
     * @return static
     */
    public function setHeight(?string $height): static
    {
        $this->height = $height ?? '';
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
     * @param string|null $display
     * @return static
     */
    public function setDisplay(?string $display): static
    {
        $this->display = $display ?? '';
        return $this;
    }

    /**
     * @param string|null $link
     * @return static
     */
    public function setDownloadLink(?string $link): static
    {
        $this->downloadLink = $link ?? '';
        return $this;
    }

    /**
     * @return string
     */
    public function getDownloadLink(): string
    {
        return $this->downloadLink;
    }
}
