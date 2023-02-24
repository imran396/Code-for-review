<?php
/**
 * SAM-7914: Refactor \LotImage_UploadLotImage
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoForm\Image\Upload;

use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ImageBlob
 * @package Sam\View\Admin\Form\LotInfoForm\Image\Upload
 */
class ImageInfo extends CustomizableClass
{
    use FilesystemCacheManagerAwareTrait;

    public readonly string $blob;
    public readonly string $fileName;
    public readonly string $extension;
    public readonly ?int $order;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(string $blob, string $fileName, string $extension, ?int $order = null): static
    {
        $this->blob = $blob;
        $this->fileName = $fileName;
        $this->extension = $extension;
        $this->order = $order;
        return $this;
    }

    /**
     * @param array $data
     * @param int|null $order
     * @return static
     */
    public function unserialize(array $data, ?int $order = null): static
    {
        $cacheKey = $data['cacheKey'];
        $cacheManager = $this->getFilesystemCacheManager()
            ->setNamespace('lot-images');
        $blobImage = $cacheManager->get($cacheKey, '');
        if (!$blobImage) {
            log_warning('Failed to read tmp image from cache');
        }
        $cacheManager->delete($data['cacheKey']);

        return $this->construct(
            $blobImage,
            $data['info']['filename'],
            $data['info']['extension'] ?? '',
            $order
        );
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        $cacheKey = uniqid($this->fileName);
        $this->getFilesystemCacheManager()
            ->setNamespace('lot-images')
            ->set($cacheKey, $this->blob);
        return [
            'cacheKey' => $cacheKey,
            'info' => [
                'filename' => $this->fileName,
                'extension' => $this->extension,
                'size' => strlen($this->blob),
            ]
        ];
    }

    /**
     * @param int $order
     * @return static
     */
    public function setOrder(int $order): static
    {
        $this->order = $order;
        return $this;
    }
}
