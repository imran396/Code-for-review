<?php
/**
 * Parse uploaded csv file by parts
 * File: /var/tmp/<type>*.csv
 * Upload step: cfg()->core->csv->uploadStep
 * Pointer: $_SESSION[<type>]['pointer']
 *
 * SAM-5263: CSV upload process change for better UX and reverse proxy timeout handling
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Apr 14, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\PartialUpload;

use Sam\Core\Service\CustomizableClass;
use Sam\Import\PartialUpload\Internal\Model\PartialUploadDto;
use Sam\Import\PartialUpload\Internal\Store\CacherAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class Dto
 * @package Sam\Upload
 */
class PartialUploadManager extends CustomizableClass
{
    use CacherAwareTrait;
    use ConfigRepositoryAwareTrait;

    public const TYPE_AUCTION_LOTS = 'AuctionLots';
    public const TYPE_BIDDERS = 'Bidders';
    public const TYPE_BIDS = 'Bids';
    public const TYPE_INCREMENTS = 'Increments';
    public const TYPE_USERS = 'Users';
    public const TYPE_LOCATIONS = 'Locations';

    /**
     * UploadType
     */
    protected string $type;
    protected int|string|null $identifier;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $type
     * @param int|string|null $identifier
     * @return $this
     */
    public function construct(string $type, int|string|null $identifier = null): static
    {
        $this->type = $type;
        $this->identifier = $identifier;
        $this->getCacher()->setType($this->type . $this->identifier);
        return $this;
    }

    public function clear(): static
    {
        $this->getCacher()->delete();

        if (file_exists($this->getFile())) {
            unlink($this->getFile());
        }
        if ($this->isExistFilesZip()) {
            unlink($this->getFilesZipFile());
        }
        if ($this->isExistImagesZip()) {
            unlink($this->getImagesZipFile());
        }
        return $this;
    }

    /**
     * @param int $total
     * @param string $csvFilePath
     * @param object $option
     * @param string|null $imageZipFilepath
     * @param string|null $filesZipFilepath
     * @return $this
     */
    public function start(
        int $total,
        string $csvFilePath,
        object $option,
        ?string $imageZipFilepath = null,
        ?string $filesZipFilepath = null
    ): static {
        $dto = PartialUploadDto::new();
        $dto->total = $total;
        $dto->pointer = 0;
        $dto->option = $option;
        $this->setDto($dto);

        $this->uploadFile($csvFilePath);
        if ($imageZipFilepath) {
            $this->uploadImagesZip($imageZipFilepath);
        }
        if ($filesZipFilepath) {
            $this->uploadFilesZip($filesZipFilepath);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function finish(): static
    {
        $dto = $this->getDto();
        $dto->pointer = $this->getDto()->total + 1;
        $this->setDto($dto);
        return $this;
    }

    public function persistProgressData(object $progressData): static
    {
        if ($this->isActive()) {
            $dto = $this->getDto();
            $dto->progressData = $progressData;
            $this->setDto($dto);
        } else {
            log_warning('Partial upload is not in active state');
        }
        return $this;
    }

    public function getProgressData(): ?object
    {
        return $this->getDto()->progressData;
    }

    public function getOption(): ?object
    {
        return $this->getDto()->option;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return path()->temporary() . '/' . $this->type . 'Upload' . session_id() . '.csv';
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return ($this->isActive() ? $this->getPointer() : 0) + 1;
    }

    /**
     * @return int
     */
    public function getPointer(): int
    {
        return $this->getDto()->pointer;
    }

    /**
     * @return int
     */
    public function getNextPointer(): int
    {
        $nextPointer = $this->getPointer() + $this->cfg()->get('core->csv->uploadStep');
        if ($this->getPointer() + $this->cfg()->get('core->csv->uploadStep') > $this->getTotalRows()) {
            $nextPointer = $this->getTotalRows();
        }
        return $nextPointer;
    }

    /**
     * @return int
     */
    public function getTotalRows(): int
    {
        return $this->getDto()->total;
    }

    /**
     * @return int
     */
    public function increasePointer(): int
    {
        $dto = $this->getDto();
        $dto->pointer = $this->getNextPointer();
        $this->setDto($dto);
        return $dto->pointer;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return file_exists($this->getFile()) && $this->isInitialized();
    }

    /**
     * @return bool
     */
    public function isEmptyFile(): bool
    {
        return $this->getTotalRows() === 0;
    }

    /**
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->getPointer() > 0
            && $this->getPointer() + $this->cfg()->get('core->csv->uploadStep') > $this->getTotalRows();
    }

    /**
     * @param $identifier
     * @return $this
     */
    public function changeIdentifier($identifier): static
    {
        $dto = null;
        if ($this->getCacher()->has()) {
            $dto = $this->getCacher()->get();
            $this->getCacher()->delete();
        }
        $this->identifier = $identifier;
        $this->getCacher()->setType($this->type . $this->identifier);
        if ($dto) {
            $this->getCacher()->set($dto);
        }
        return $this;
    }

    /**
     * @param object $option
     * @return $this
     */
    public function persistOption(object $option): static
    {
        $dto = $this->getDto();
        $dto->option = $option;
        $this->setDto($dto);
        return $this;
    }

    /**
     * @param string $file
     */
    public function uploadFile(string $file): void
    {
        copy($file, $this->getFile());
    }

    /**
     * @return string
     */
    public function getFilesZipFile(): string
    {
        return path()->temporary() . '/' . $this->type . $this->identifier . 'UploadFilesZip' . session_id() . '.tmp';
    }

    /**
     * @return string
     */
    public function getImagesZipFile(): string
    {
        return path()->temporary() . '/' . $this->type . $this->identifier . 'UploadImagesZip' . session_id() . '.tmp';
    }

    /**
     * @return bool
     */
    public function isExistFilesZip(): bool
    {
        return file_exists($this->getFilesZipFile());
    }

    /**
     * @return bool
     */
    public function isExistImagesZip(): bool
    {
        return file_exists($this->getImagesZipFile());
    }

    /**
     * @param string $file
     */
    public function uploadFilesZip(string $file): void
    {
        move_uploaded_file($file, $this->getFilesZipFile());
    }

    /**
     * @param string $file
     */
    public function uploadImagesZip(string $file): void
    {
        move_uploaded_file($file, $this->getImagesZipFile());
    }

    /**
     * @return PartialUploadDto
     */
    protected function getDto(): PartialUploadDto
    {
        return $this->getCacher()->get();
    }

    protected function isInitialized(): bool
    {
        return $this->getCacher()->has();
    }

    /**
     * @param PartialUploadDto $dto
     */
    protected function setDto(PartialUploadDto $dto): void
    {
        $this->getCacher()->set($dto);
    }
}
