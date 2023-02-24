<?php
/**
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Download\UserCustomField;

use Sam\Application\Controller\Responsive\Download\Internal\ResponseCreateTrait;
use Sam\Application\Controller\Responsive\Download\UserCustomField\Internal\UserCustomFieldDataExistenceCheckerCreateTrait;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Core\Path\PathResolver;
use Sam\Core\Service\CustomizableClass;
use Sam\File\FilePathHelperAwareTrait;
use Sam\File\Manage\LocalFileManagerCreateTrait;

/**
 * Class UserCustomFieldDownloadOutputProducer
 * @package Sam\Application\Controller\Responsive\Download
 */
class UserCustomFieldDownloadOutputProducer extends CustomizableClass
{
    use FilePathHelperAwareTrait;
    use LocalFileManagerCreateTrait;
    use ResponseCreateTrait;
    use ServerRequestReaderAwareTrait;
    use UserCustomFieldDataExistenceCheckerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Output a file content or an error if something went wrong
     *
     * @param string|null $fileName
     * @param int|null $userId
     */
    public function produce(?string $fileName, ?int $userId): void
    {
        $response = $this->createResponse();
        $fileName = $this->getFilePathHelper()->toFilename($fileName);

        if (!$this->isFileExist($fileName, $userId)) {
            $response->notFound();
        }

        $filePath = $this->makeFilePath($fileName, $userId);
        $response->outputFile($fileName, $filePath);
    }

    protected function isFileExist(string $fileName, int $userId): bool
    {
        if ($fileName !== basename($fileName)) {
            $message = sprintf('%s trying to access %s', $this->getServerRequestReader()->remoteAddr(), $fileName);
            log_warning($message);
            return false;
        }

        if (!$this->createUserCustomFieldDataExistenceChecker()->existWithFile($fileName)) {
            return false;
        }

        $filePath = $this->makeFilePath($fileName, $userId);
        $isExist = $this->createLocalFileManager()->exist($filePath);
        return $isExist;
    }

    protected function makeFilePath(string $fileName, int $userId): string
    {
        return PathResolver::UPLOAD_USER_CUSTOM_FIELD_FILE . '/' . $userId . '/' . $fileName;
    }
}
