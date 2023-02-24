<?php
/**
 * SAM-5608: Refactor lot custom field file download for web
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           07/01/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Type\File\Download\Internal\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\CustomField\Lot\Type\File\Download\Internal\Load\DataLoaderAwareTrait;
use Sam\CustomField\Lot\Type\File\Path\LotCustomFieldFilePathResolverCreateTrait;
use Sam\User\Access\LotAccessCheckerAwareTrait;

/**
 * Class Validator
 * @package Sam\CustomField\Lot\Type\File\Download
 * @internal
 */
class Validator extends CustomizableClass
{
    use DataLoaderAwareTrait;
    use EditorUserAwareTrait;
    use LotAccessCheckerAwareTrait;
    use LotCustomFieldFilePathResolverCreateTrait;
    use ResultStatusCollectorAwareTrait;

    public const ERR_LOT_ITEM_ID_ABSENT = 1;
    public const ERR_FILENAME_ABSENT = 2;
    public const ERR_FILENAME_WITH_PATH = 3;
    public const ERR_FILENAME_WRONG_CHARS = 4;
    public const ERR_LOT_ITEM_ABSENT = 5;
    public const ERR_CUSTOM_DATA_ABSENT = 6;
    public const ERR_ACCESS_REJECTED = 7;
    public const ERR_FILENAME_WRONG = 8;
    public const ERR_FILE_NOT_FOUND = 9;

    protected ?int $lotItemId;
    protected ?int $lotCustomFieldId;
    protected string $fileName;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * @param int|null $editorUserId
     * @param int|null $lotItemId
     * @param int|null $lotCustomFieldId
     * @param string $filename
     * @return $this
     */
    public function construct(
        ?int $editorUserId,
        ?int $lotItemId,
        ?int $lotCustomFieldId,
        string $filename
    ): static {
        $this->setEditorUserId($editorUserId);
        $this->lotItemId = $lotItemId;
        $this->lotCustomFieldId = $lotCustomFieldId;
        $this->fileName = $filename;
        return $this;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $this->initResultStatusCollector();
        $collector = $this->getResultStatusCollector();

        if (!$this->lotItemId) {
            $collector->addError(self::ERR_LOT_ITEM_ID_ABSENT);
            return false;
        }

        if (!$this->fileName) {
            $collector->addError(self::ERR_FILENAME_ABSENT);
            return false;
        }

        if ($this->fileName !== basename($this->fileName)) {
            $collector->addError(self::ERR_FILENAME_WITH_PATH);
            return false;
        }

        if (preg_match('/(\\|\/|:|\*|\?|"|<|>|\||\s|\t)/', $this->fileName)) {
            $collector->addError(self::ERR_FILENAME_WRONG_CHARS);
            return false;
        }

        $dataLoader = $this->getDataLoader();

        $lotItem = $dataLoader->loadLotItem($this->lotItemId);
        if (!$lotItem) {
            $collector->addError(self::ERR_LOT_ITEM_ABSENT);
            return false;
        }

        [$text, $access] = $dataLoader->loadCustomDataWithAccess($this->lotCustomFieldId, $this->lotItemId);
        if (!$text) {
            $collector->addError(self::ERR_CUSTOM_DATA_ABSENT);
            return false;
        }

        $auctionLots = $dataLoader->loadAuctionLotsByLotItemId($this->lotItemId);
        foreach ($auctionLots as $auctionLot) {
            $accessRoles = $this->getLotAccessChecker()->detectRoles(
                $auctionLot->LotItemId,
                $auctionLot->AuctionId,
                $this->getEditorUserId(),
                true
            );

            if (!in_array($access, $accessRoles, true)) {
                $collector->addError(self::ERR_ACCESS_REJECTED);
                return false;
            }
        }

        $fileNames = $this->parseFileNamesFromText($text);
        if (!in_array($this->fileName, $fileNames, true)) {
            $collector->addError(self::ERR_FILENAME_WRONG);
            return false;
        }

        $fileRootPath = $this->createLotCustomFieldFilePathResolver()
            ->detectFileRootPath($lotItem->AccountId, $this->fileName);
        if (!file_exists($fileRootPath)) {
            $collector->addError(self::ERR_FILE_NOT_FOUND);
            return false;
        }

        return true;
    }

    /**
     * return first error code
     * @return int|null
     */
    public function errorCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstErrorCode();
    }

    /**
     * @param string $text
     * @return array
     */
    protected function parseFileNamesFromText(string $text): array
    {
        $fileNames = explode('|', $text);
        return $fileNames;
    }

    /**
     * Initialize ResultStatusCollector
     */
    protected function initResultStatusCollector(): void
    {
        // ResultStatusCollector default error messages for error codes
        $errorMessages = [
            self::ERR_LOT_ITEM_ID_ABSENT => 'LotItemId is absent',
            self::ERR_FILENAME_ABSENT => 'Filename is absent',
            self::ERR_FILENAME_WITH_PATH => 'Only the filename accepted. No path',
            self::ERR_FILENAME_WRONG_CHARS => 'Invalid filename',
            self::ERR_LOT_ITEM_ABSENT => 'LotItem is absent',
            self::ERR_CUSTOM_DATA_ABSENT => 'CustomData is absent',
            self::ERR_ACCESS_REJECTED => 'Access denied',
            self::ERR_FILENAME_WRONG => 'Wrong filename',
            self::ERR_FILE_NOT_FOUND => 'File not found',
        ];
        $this->getResultStatusCollector()->construct($errorMessages);
    }
}
