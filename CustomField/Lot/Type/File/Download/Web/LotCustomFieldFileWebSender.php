<?php
/**
 * Published API for lot custom field download module intended to be used in
 *
 * SAM-5721: Refactor lot custom field file download for web
 *
 * @copyright   2018 Bidpath, Inc.
 * @author      Maxim Lyubetskiy
 * @package     com.swb.sam2
 * @version     SVN: $Id$
 * @since       June 29, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Type\File\Download\Web;

use Laminas\Http\Response;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Type\File\Download\Internal\Load\DataLoaderAwareTrait;
use Sam\CustomField\Lot\Type\File\Download\Internal\Render\Renderer;
use Sam\CustomField\Lot\Type\File\Download\Internal\Save\ActionRegistrator;
use Sam\CustomField\Lot\Type\File\Download\Internal\Validate\Validator;
use Sam\CustomField\Lot\Type\File\Path\LotCustomFieldFilePathResolverCreateTrait;
use Sam\Lot\Load\Exception\CouldNotFindLotItem;
use Sam\User\Access\LotAccessCheckerAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * @package Sam\CustomField\Lot\Type\File\Download
 */
class LotCustomFieldFileWebSender extends CustomizableClass
{
    use DataLoaderAwareTrait;
    use LotAccessCheckerAwareTrait;
    use LotCustomFieldFilePathResolverCreateTrait;
    use ServerRequestReaderAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function run(?int $lotItemId, ?int $lotCustomFieldId, ?string $fileName, ?int $editorUserId): void
    {
        // validate
        if (
            !$lotItemId
            || !$lotCustomFieldId
            || !$fileName
            || !$this->validate($editorUserId, $lotItemId, $lotCustomFieldId, $fileName)
        ) {
            return;
        }

        // processing
        $dataLoader = $this->getDataLoader();
        $lotItem = $dataLoader->loadLotItem($lotItemId); // Existence guaranteed by validator
        if (!$lotItem) {
            throw CouldNotFindLotItem::withId($lotItemId);
        }

        ActionRegistrator::new()
            ->construct($editorUserId, $lotItem, $fileName)
            ->setDataLoader($dataLoader)
            ->register();

        $fileRootPath = $this->createLotCustomFieldFilePathResolver()
            ->detectFileRootPath($lotItem->AccountId, $fileName);
        $this->sendFileResponse($fileName, $fileRootPath);
    }

    /**
     * @param int|null $editorUserId
     * @param int $lotItemId
     * @param int $lotCustomFieldId
     * @param string $fileName
     * @return bool
     */
    protected function validate(?int $editorUserId, int $lotItemId, int $lotCustomFieldId, string $fileName): bool
    {
        $validator = Validator::new()
            ->construct($editorUserId, $lotItemId, $lotCustomFieldId, $fileName)
            ->setDataLoader($this->getDataLoader());
        $success = $validator->validate();
        if (!$success) {
            $errorCode = $validator->errorCode();
            $this->processError($errorCode, $lotItemId, $fileName);
        }
        return $success;
    }

    /**
     * @param string $fileName
     * @param string $fileRootPath
     */
    protected function sendFileResponse(string $fileName, string $fileRootPath): void
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $fileName);
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($fileRootPath));
        readfile($fileRootPath);
    }

    /**
     * @param int $responseCode
     */
    protected function showError(int $responseCode): never
    {
        http_response_code($responseCode);
        echo Renderer::new()->renderError($responseCode);
        exit(Constants\Cli::EXIT_GENERAL_ERROR);
    }

    /**
     * @param int $errorCode
     * @param int $lotItemId
     * @param string $fileName
     */
    protected function processError(int $errorCode, int $lotItemId, string $fileName): void
    {
        if ($errorCode === Validator::ERR_FILENAME_WITH_PATH) {
            $warningMessage = sprintf(
                '%s trying to access (%s) %s',
                $this->getServerRequestReader()->remoteAddr(),
                $lotItemId,
                $fileName
            );
            log_warning($warningMessage);
        }
        $responseCode = in_array($errorCode, [Validator::ERR_FILENAME_WITH_PATH, Validator::ERR_FILE_NOT_FOUND], true)
            ? Response::STATUS_CODE_500
            : Response::STATUS_CODE_403;
        $this->showError($responseCode);
    }
}
