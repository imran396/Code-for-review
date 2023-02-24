<?php
/**
 * @version       SVN: $Id$
 * @since         March 17, 2011
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\CustomField\Base\Qform\Component;

use Qform_FileAsset;
use Sam\CustomField\Base\FileType\Validate\CustomFieldFileValidatorCreateTrait;

/**
 * Class FileEditAsset
 * @package Sam\CustomField\Base\Qform\Component
 * @property FileEditPanel $objParentControl
 */
class FileEditAsset extends Qform_FileAsset
{
    use CustomFieldFileValidatorCreateTrait;

    public string $uploadHtml;
    public string $modifyHtml;
    private string $allowedTypes;

    /**
     * FileEditAsset constructor.
     *
     * @param FileEditPanel $parentObject
     * @param string $controlId
     * @param string $allowedTypes
     */
    public function __construct(FileEditPanel $parentObject, $controlId = null, string $allowedTypes = '')
    {
        parent::__construct($parentObject, $controlId);
        $this->uploadHtml = '<img src="' . path()::QCODO_DOCROOT_IMAGE_ASSETS . '/add.png" alt="Upload" />';
        $this->modifyHtml = '<img src="' . path()::QCODO_DOCROOT_IMAGE_ASSETS . '/add.png" alt="Modify" />';
        $this->btnUpload->Text = $this->uploadHtml;
        $this->imgFileIcon->SetCustomStyle('display', 'block');
        $this->btnDelete->Text = '<img src="' . path()::QCODO_DOCROOT_IMAGE_ASSETS . '/delete.png" alt="Delete" />';
        $this->DialogBoxHtml = '<h1>Upload a File</h1><p>Please select a file to upload.</p>';
        $this->allowedTypes = $allowedTypes;
    }

    /** @noinspection PhpMethodNamingConventionInspection */
    /**
     * @param int $fileAssetType
     */
    protected function SetFileAssetType($fileAssetType)
    {
        $this->intFileAssetType = $fileAssetType;
        $this->strAcceptibleMimeArray = null;   // we don't want to check mime type of uploaded file
        return $fileAssetType;
    }

    /** @noinspection PhpMethodNamingConventionInspection */
    /**
     * File upload handler
     * @return void
     */
    public function dlgFileAsset_Upload(): void
    {
        // File Not Uploaded
        if (
            !$this->dlgFileAsset->flcFileAsset->Size
            || !file_exists($this->dlgFileAsset->flcFileAsset->File)
        ) {
            $this->dlgFileAsset->ShowError($this->strUnacceptableMessage);
            return;
        }

        if (!$this->createCustomFieldFileValidator()->isValidFileType($this->dlgFileAsset->flcFileAsset->FileName, $this->allowedTypes)) {
            $this->dlgFileAsset->ShowError(
                str_replace(
                    '{ext}',
                    str_replace('|', ', ', $this->allowedTypes),
                    'You are trying to upload an invalid file format. Allowed format' .
                    (substr_count($this->allowedTypes, '|') + 1 > 1 ? 's are' : ' is') .
                    ': <div style="margin-bottom:5px;"><strong>{ext}</strong></div>'
                )
            );
            return;
        }

        parent::dlgFileAsset_Upload();

        $fileManager = $this->createFileManager();
        $filePath = substr($this->File, strlen(path()->sysRoot()));
        $fileManager->put($this->File, $filePath);

        $this->getParentControl()->setTmpFileName($this->FileName . ':' . $filePath);
        $this->getParentControl()->setFileNameInFileLabel((string)$this->FileName); // render file name right after file has been uploaded in temporary location
        $this->getParentControl()->enableFileLabel(false);
        $this->btnUpload->Text = $this->modifyHtml;
    }

    /**
     * @return FileEditPanel
     */
    public function getParentControl(): FileEditPanel
    {
        return $this->objParentControl;
    }
}
