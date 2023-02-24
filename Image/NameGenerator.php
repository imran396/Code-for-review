<?php
/**
 * Generate a new name for image file if the name already exists.
 *
 * Image zip upload improvement
 * @see https://bidpath.atlassian.net/browse/SAM-3409
 *
 * @copyright   2018 Bidpath, Inc.
 * @author      Maxim Lyubetskiy
 * @package     com.swb.sam2
 * @version     SVN: $Id$
 * @since       Sep 12, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Image;

use RuntimeException;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;

/**
 * Class NameGenerator
 */
class NameGenerator extends CustomizableClass
{
    use FileManagerCreateTrait;
    use LotImageLoaderAwareTrait;

    public array $imageExtensions = [
        IMAGETYPE_GIF => 'gif',
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG => 'png',
    ];

    /**
     * @var UploadFolderCreator|null
     */
    private ?UploadFolderCreator $uploadFolderCreator = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $fileName
     * @param int $lotItemId lot_item.id
     * @param bool $isReplaceExisting
     * @return string
     * @throws FileException
     */
    public function getNewFileName(string $fileName, int $lotItemId, bool $isReplaceExisting = false): string
    {
        while ($this->isImageExists($fileName, $lotItemId, $isReplaceExisting)) {
            $fileName = preg_replace('/(\.(gif|jpg|jpeg|png))$/i', '__' . $lotItemId . '\1', $fileName);
        }
        return (string)$fileName;
    }

    /**
     * Guess extension by image content
     * @param string $image Image path
     * @return string
     */
    public function guessExtension(string $image): string
    {
        $extension = pathinfo($image, PATHINFO_EXTENSION);
        if (!in_array(strtolower($extension), $this->imageExtensions, true)) {
            $size = @getimagesize($image);

            $extension = isset($size[2], $this->imageExtensions[$size[2]])
                ? $this->imageExtensions[$size[2]]
                : $this->imageExtensions[IMAGETYPE_JPEG];
        }
        return $extension;
    }

    /**
     * @param string $fileName
     * @param int $lotItemId lot_item.id
     * @param bool $isReplaceExisting
     * @return bool
     * @throws FileException
     */
    protected function isImageExists(string $fileName, int $lotItemId, bool $isReplaceExisting): bool
    {
        if ($isReplaceExisting) {
            $lotImages = $this->getLotImageLoader()->loadByImageLink($fileName, [$lotItemId]);
            $isImageExists = (count($lotImages) > 0);
        } else {
            $uploadFolderCreator = $this->getUploadFolderCreator();
            if (!$uploadFolderCreator) {
                throw new RuntimeException("UploadFolderCreator is not set");
            }
            $image = $uploadFolderCreator->getRelativePath() . '/' . $fileName;
            $isImageExists = $this->createFileManager()->exist($image);
        }
        return $isImageExists;
    }

    //<editor-fold desc="Getter\Setter">

    /**
     * @return UploadFolderCreator|null
     */
    public function getUploadFolderCreator(): ?UploadFolderCreator
    {
        return $this->uploadFolderCreator;
    }

    /**
     * @param UploadFolderCreator $uploadFolderCreator
     * @return static
     */
    public function setUploadFolderCreator(UploadFolderCreator $uploadFolderCreator): static
    {
        $this->uploadFolderCreator = $uploadFolderCreator;
        return $this;
    }
    //</editor-fold>
}
