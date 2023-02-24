<?php
/**
 * Class for image rendering
 *
 * SAM-4690: Refactor image rendering logic
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         $Id$
 * @since           Dec 13, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Image\Render;

use Exception;
use Sam\Account\Image\Path\AccountLogoPathResolverCreateTrait;
use Sam\Application\HttpRequest\ServerRequestReader;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Application\Url\Build\Config\Image\AccountImageUrlConfig;
use Sam\Application\Url\Build\Config\Image\AuctionImageUrlConfig;
use Sam\Application\Url\Build\Config\Image\HeaderImageUrlConfig;
use Sam\Application\Url\Build\Config\Image\LocationImageUrlConfig;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Image\Cache\ImageCacheManagerCreateTrait;
use Sam\Image\ImageHelper;
use Sam\Image\Resize\Resizer;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Location\Image\Path\LocationLogoPathResolverCreateTrait;
use Sam\Location\Load\LocationLoaderAwareTrait;
use Sam\Lot\Image\Path\LotImagePathResolverCreateTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Settings\Layout\Image\Path\LayoutImagePathResolverCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionImage\AuctionImageReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotImage\LotImageReadRepositoryCreateTrait;

/**
 * Class ImageRenderer
 * @package Sam\Image\Render
 */
class ImageRenderer extends CustomizableClass
{
    use AccountLogoPathResolverCreateTrait;
    use ApplicationRedirectorCreateTrait;
    use AuctionImageReadRepositoryCreateTrait;
    use AuctionLoaderAwareTrait;
    use ConfigRepositoryAwareTrait;
    use FileManagerCreateTrait;
    use ImageCacheManagerCreateTrait;
    use LayoutImagePathResolverCreateTrait;
    use LocationLoaderAwareTrait;
    use LocationLogoPathResolverCreateTrait;
    use LotImagePathResolverCreateTrait;
    use LotImageReadRepositoryCreateTrait;
    use LotItemLoaderAwareTrait;
    use ParamFetcherForGetAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * @var string
     */
    protected string $blobImage = '';

    /**
     * @var string
     */
    protected string $staticFilePath = '';

    /**
     * @var bool
     */
    protected bool $resizeEnabled = false;

    /**
     * @var string|null
     */
    protected ?string $size = '';

    /**
     * @var string
     */
    protected string $option = '';

    /**
     * @var string
     */
    protected string $class = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->resizeEnabled = true;
        return $this;
    }

    /**
     * @return string
     */
    protected function getOption(): string
    {
        return $this->option;
    }

    /**
     * @param string $option
     * @return static
     */
    protected function setOption(string $option): static
    {
        $this->option = $option;
        return $this;
    }

    /**
     * @return string
     */
    protected function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class
     * @return static
     */
    protected function setClass(string $class): static
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @param string $blobImage
     * @return static
     */
    protected function setBlobImage(string $blobImage): static
    {
        $this->blobImage = trim($blobImage);
        return $this;
    }

    /**
     * @return string
     */
    protected function getBlobImage(): string
    {
        if (!$this->blobImage) {
            $this->responseInternalServerError('Blob image not set');
        }
        return $this->blobImage;
    }

    /**
     * @param string $staticFilePath
     * @return static
     */
    protected function setTargetFileRootPath(string $staticFilePath): static
    {
        $this->staticFilePath = trim($staticFilePath);
        return $this;
    }

    /**
     * @return string
     */
    protected function getStaticFilePath(): string
    {
        if (!$this->staticFilePath) {
            $this->responseInternalServerError('Static file path not set');
        }
        return $this->staticFilePath;
    }

    /**
     * @param bool $enabled
     * @return static
     */
    public function enableResize(bool $enabled): static
    {
        $this->resizeEnabled = $enabled;
        return $this;
    }

    /**
     * @param string $size
     * @return static
     */
    protected function setSize(string $size): static
    {
        $this->size = Cast::toString($size, Constants\Image::$sizes);
        if ($this->size === null) {
            $this->responseInternalServerError('Invalid size');
        }
        return $this;
    }

    /**
     * @return string
     */
    protected function getSize(): string
    {
        return $this->size;
    }

    /**
     * @param string $class
     * @param string $option
     * @return void
     */
    public function output(string $class, string $option): void
    {
        $this->setClass($class);
        $this->setOption($option);
        if ($class === Constants\Image::AUCTION_IMAGE) {
            $this->prepareAuctionImage($option);
        } elseif ($class === Constants\Image::LOT_ITEM_IMAGE) {
            $this->prepareLotItemImage($option);
        } elseif ($class === Constants\Image::SETTINGS_IMAGE) {
            $this->prepareHeaderLogoImage((int)$option);
        } elseif (
            $class === Constants\Image::SETTLEMENT_IMAGE
            || $class === Constants\Image::INVOICE_IMAGE
        ) {
            $id = $this->getParamFetcherForGet()->getIntPositive('id');
            $this->prepareSettlementOrInvoiceLogo($class, $id);
        } elseif ($class === Constants\Image::LOCATION_LOGO_IMAGE) {
            $this->prepareLocationLogo($option);
        } elseif ($class === Constants\Image::ACCOUNT_IMAGE) {
            $accountId = (int)$option;
            $this->prepareAccountLogoImage($accountId);
        }
        $this->renderBlobImage();
    }

    /**
     * @param string $fileName
     * @return void
     */
    protected function prepareAuctionImage(string $fileName): void
    {
        $fileInfo = pathinfo($fileName);
        $parts = explode('_', $fileInfo['filename']);
        $auctionImageId = (int)current($parts);
        $size = end($parts);
        $this->setSize($size);
        $urlConfig = AuctionImageUrlConfig::new()->construct($auctionImageId, $size);
        $targetFileRootPath = path()->docRoot() . $urlConfig->urlFilled();
        $this->setTargetFileRootPath($targetFileRootPath);
        $auctionImage = $this->createAuctionImageReadRepository()
            ->filterId($auctionImageId)
            ->loadEntity();
        if (!$auctionImage) {
            http_response_code(404);
            die();
        }

        if (filter_var($auctionImage->ImageLink, FILTER_VALIDATE_URL)) {
            $this->checkAndPrepareRemoteImage($auctionImage->ImageLink);
        } else {
            $imagePath = substr(path()->uploadAuctionImage(), strlen(path()->sysRoot()));
            $auction = $this->getAuctionLoader()->load($auctionImage->AuctionId);
            $auctionImagesDir = $auction ? $imagePath . '/' . $auction->AccountId : $imagePath;
            $uploadedFilePath = $auctionImagesDir . '/' . $auctionImage->ImageLink;
            $this->checkAndPrepareInternalImage($uploadedFilePath, $auctionImage->ImageLink);
        }
    }

    /**
     * @param string $fileName
     * @return void
     */
    protected function prepareLotItemImage(string $fileName): void
    {
        $fileInfo = pathinfo($fileName);
        $parts = explode('_', $fileInfo['filename']);
        $lotImageId = (int)current($parts);
        $size = end($parts);
        $this->setSize($size);
        $targetFileRootPath = $this->createLotImagePathResolver()->makeThumbnailAbsolutePath($lotImageId, $size);
        $this->setTargetFileRootPath($targetFileRootPath);
        $lotImage = $this->createLotImageReadRepository()
            ->filterId($lotImageId)
            ->loadEntity();
        if (!$lotImage) {
            $this->createApplicationRedirector()->pageNotFound();
        }

        if (filter_var($lotImage->ImageLink, FILTER_VALIDATE_URL)) {
            $this->checkAndPrepareRemoteImage($lotImage->ImageLink);
        } else {
            $lotItemId = $lotImage->LotItemId;
            $lotItem = $this->getLotItemLoader()->load($lotItemId, true);
            // make sure it does not go outside doc root
            $uploadedFilePath = $this->createLotImagePathResolver()->makeLotImageRelativePath($lotItem->AccountId ?? null, $lotImage->ImageLink);
            $this->checkAndPrepareInternalImage($uploadedFilePath, $lotImage->ImageLink);
        }
    }

    /**
     * @param int $accountId
     * @return void
     */
    protected function prepareHeaderLogoImage(int $accountId): void
    {
        $pageHeader = $this->getSettingsManager()->get(Constants\Setting::PAGE_HEADER, $accountId);
        $urlConfig = HeaderImageUrlConfig::new()->construct($accountId);
        $targetFileRootPath = path()->docRoot() . $urlConfig->urlFilled();
        $this->setTargetFileRootPath($targetFileRootPath);
        $this->setSize(Constants\Image::EXTRA_LARGE);
        if (filter_var($pageHeader, FILTER_VALIDATE_URL)) {
            // Remote image
            $this->checkAndPrepareRemoteImage($pageHeader);
        } else {
            // Internal image
            $filePath = $this->createLayoutImagePathResolver()->detectPageHeaderLogoFilePath($accountId);
            try {
                if ($this->createFileManager()->exist($filePath)) {
                    $blobImage = $this->createFileManager()->read($filePath);
                    $this->setBlobImage($blobImage);
                } else {
                    log_warning('Failed to open file. ' . $filePath . ' does not exist.');
                    $this->createApplicationRedirector()->pageNotFound();
                }
            } catch (FileException) {
                log_warning('Failed to read image: ' . $filePath);
            }
        }
    }

    /**
     * @param string $type
     * @param int $accountId
     * @return void
     */
    protected function prepareSettlementOrInvoiceLogo(string $type, int $accountId): void
    {
        $settlementLogo = $this->createLayoutImagePathResolver()->detectSettlementLogoFileName($accountId);
        $invoiceLogo = $this->createLayoutImagePathResolver()->detectInvoiceLogoFileName($accountId);
        $logo = $type === 'settlement' ? $settlementLogo : $invoiceLogo;
        $subPath = $type === 'settlement' ? '/settings/SL_' : '/settings/IL_';
        if (filter_var($logo, FILTER_VALIDATE_URL)) {
            log_warning('Image ' . $logo . ' is remote.');
            $this->createApplicationRedirector()->pageNotFound();
        }
        // Internal File
        //use the static file, not the uploaded one
        $info = pathinfo($logo);
        $fileName = path()::QCODO_DOCROOT_IMAGE_ASSETS . $subPath . $accountId . '.' . $info['extension'];

        try {
            if ($this->createFileManager()->exist($fileName)) {
                //redirect to static file
                $info = pathinfo($logo);
                $ext = $info['extension'];
                $staticFile = "{$subPath}{$accountId}.{$ext}";
                $this->createApplicationRedirector()->redirect($staticFile);
            } else {
                log_warning('Failed trying to open file ' . $fileName . ' does not exist.');
                $this->createApplicationRedirector()->pageNotFound();
            }
        } catch (FileException) {
            log_warning('Failed to read image: ' . $fileName);
        }
    }

    /**
     * @param string $fileName
     * @return void
     */
    protected function prepareLocationLogo(string $fileName): void
    {
        $fileInfo = pathinfo($fileName);
        $parts = explode('_', $fileInfo['filename']);
        $locationId = (int)current($parts);
        $this->enableResize(false);
        $location = $this->getLocationLoader()->load($locationId, true);
        if (!$location) {
            log_error("Available Location not found, when preparing location logo" . composeSuffix(['id' => $locationId]));
            return;
        }
        $logoFileName = $location->Logo ?? '';
        if ($logoFileName) {
            $logoFilePath = $this->createLocationLogoPathResolver()->makeFilePath($location->AccountId, $logoFileName);
            try {
                if ($this->createFileManager()->exist($logoFilePath)) {
                    $blobImage = $this->createFileManager()->read($logoFilePath);
                    $this->setBlobImage($blobImage);
                    $urlConfig = LocationImageUrlConfig::new()->construct($locationId, $location->AccountId);
                    $targetFileRootPath = path()->docRoot() . $urlConfig->urlFilled();
                    $this->setTargetFileRootPath($targetFileRootPath);
                } else {
                    log_warning('Failed to open file. ' . $logoFilePath . ' does not exist.');
                    $this->createApplicationRedirector()->pageNotFound();
                }
            } catch (FileException) {
                log_warning('Failed to read image: ' . $logoFilePath);
            }
        }
    }

    /**
     * @param int $accountId
     * @return void
     */
    protected function prepareAccountLogoImage(int $accountId): void
    {
        $urlConfig = AccountImageUrlConfig::new()->construct($accountId);
        $targetFileRootPath = path()->docRoot() . $urlConfig->urlFilled();
        $this->setTargetFileRootPath($targetFileRootPath);
        $this->setSize(Constants\Image::EXTRA_LARGE);
        $filePath = $this->createAccountLogoPathResolver()->makeOriginalFileBasePath($accountId);
        try {
            if ($this->createFileManager()->exist($filePath)) {
                $blobImage = $this->createFileManager()->read($filePath);
            } else {
                $filePath = ImageHelper::new()->makeEmptyStubOriginalFilePath();
                $blobImage = $this->createFileManager()->read($filePath);
            }
            $this->setBlobImage($blobImage);
        } catch (FileException) {
            log_warning('Failed to read image: ' . $filePath);
        }
    }

    /**
     * @param int|null $errorCode
     * @param string $url
     * @return string
     */
    protected function createRemoteImageCacheErrorMessage(?int $errorCode, string $url): string
    {
        $errorMessage = match ($errorCode) {
            Constants\Image::INVALID_IMAGE_URL => 'Invalid image url. Url: ' . $url,
            Constants\Image::FAILED_TO_FETCH => 'Stream data is empty or image request timed out. Url: ' . $url,
            Constants\Image::INVALID_CONTENT_TYPE => 'Unsupported content-type. Url: ' . $url,
            Constants\Image::INVALID_HTTP_RESPONSE_CODE => 'Unexpected remote image response code. Url: ' . $url,
            Constants\Image::INVALID_IMAGE_SIZE => 'Remote image too large. Url: ' . $url,
            default => '',
        };
        return $errorMessage;
    }

    /**
     * @param string $uploadedFilePath
     * @param string $imageLink
     * @return bool
     */
    protected function checkImage(string $uploadedFilePath, string $imageLink): bool
    {
        try {
            $imageInfo = $this->createFileManager()->getImageInfo($uploadedFilePath);
        } catch (FileException) {
            log_warning('Failed to get info from image: ' . $uploadedFilePath);
            return false;
        }

        $success = true;
        if (!$imageInfo) {
            log_warning('File is not an image: ' . $imageLink);
            $success = false;
        } elseif (!in_array((int)$imageInfo[2], [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
            log_warning('File is not GIF, JPG or PNG: ' . $imageLink);
            $success = false;
        } elseif (($imageInfo[0] * $imageInfo[1]) > $this->cfg()->get('core->image->maxWidthHeight')) {
            log_warning('Image dimension are too big than the allowed: ' . $imageInfo[0] . 'x' . $imageInfo[1]);
            $success = false;
        }
        return $success;
    }

    /**
     * @param string $uploadedFilePath
     * @param string $imageLink
     */
    protected function checkAndPrepareInternalImage(string $uploadedFilePath, string $imageLink): void
    {
        try {
            if ($this->createFileManager()->exist($uploadedFilePath)) {
                if (!$this->checkImage($uploadedFilePath, $imageLink)) {
                    return;
                }
            } else {
                $uploadedFilePath = ImageHelper::new()->makeEmptyStubOriginalFilePath();
            }

            if ($this->createFileManager()->exist($uploadedFilePath)) {
                $blobImage = $this->createFileManager()->read($uploadedFilePath);
                $this->setBlobImage($blobImage);
            } else {
                log_warning('Failed to open file. ' . $uploadedFilePath . ' does not exist.');
                $this->createApplicationRedirector()->pageNotFound();
            }
        } catch (FileException) {
            log_warning('Failed to read image: ' . $uploadedFilePath);
        }
    }

    /**
     * @param string $url
     */
    protected function checkAndPrepareRemoteImage(string $url): void
    {
        $imageCacheManager = $this->createImageCacheManager();
        try {
            $blobImage = $imageCacheManager
                ->setUrl($url)
                ->load();
            if (!$blobImage) {
                $errorMessage = $this->createRemoteImageCacheErrorMessage($imageCacheManager->getErrorCode(), $imageCacheManager->getUrl())
                    ?: 'Failed to load remote image';
                log_error($errorMessage . composeSuffix($this->getLogData()));
                $this->createApplicationRedirector()->pageNotFound();
            }
            $this->setBlobImage($blobImage);
        } catch (Exception $e) {
            $message = 'Failed to load remote resource '
                . composeLogData([$url => $e->getMessage()]);
            log_error($message);
        }
    }

    /**
     * @return void
     */
    protected function renderBlobImage(): void
    {
        // select dimensions to resize image
        $sizeName = 'size' . strtoupper($this->getSize());
        $thumbnailSizeName = $this->cfg()->get("core->image->thumbnail->{$sizeName}");
        if (
            $this->resizeEnabled
            && isset($thumbnailSizeName)
        ) {
            $width = $this->cfg()->get("core->image->thumbnail->{$sizeName}->width");
            $height = $this->cfg()->get("core->image->thumbnail->{$sizeName}->height");
        } else {
            $width = 0;
            $height = 0;
        }
        $resizer = Resizer::new()
            ->setImage($this->getBlobImage())
            ->setHeight($height)
            ->setWidth($width)
            ->setTargetFileRootPath($this->getStaticFilePath());

        $success = $resizer->resize();
        if ($success) {
            header("content-type: image/jpeg");
            echo($resizer->getImage());
        } else {
            $this->responseInternalServerError('Failed to resize image');
        }
    }

    /**
     * @param string $errorMessage
     */
    protected function responseInternalServerError(string $errorMessage): never
    {
        log_error($errorMessage . composeSuffix($this->getLogData()));
        http_response_code(500);
        header('Content-Type: text/plain');
        echo 'Internal Server Error';
        exit;
    }

    protected function getLogData(): array
    {
        $uri = ServerRequestReader::new()->requestUri();
        return [
            'uri' => $uri,
            'class' => $this->getClass(),
            'option' => $this->getOption(),
        ];
    }
}
