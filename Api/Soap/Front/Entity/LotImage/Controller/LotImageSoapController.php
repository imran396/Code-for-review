<?php
/**
 * Help methods for image handling in soap calls
 * SAM-4435: Refactor logic for "CacheImages" SOAP call to new approach
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 25, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Front\Entity\LotImage\Controller;

use InvalidArgumentException;
use LotItem;
use Sam\Api\Soap\Front\Entity\Base\Controller\SoapControllerBase;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Lot\Image\Queue\LotImageQueueCreateTrait;
use Sam\Lot\Image\Validate\LotImagesValidator;
use Sam\Lot\ItemNo\Parse\LotItemNoParserCreateTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\CachedQueue\CachedQueueReadRepositoryCreateTrait;

/**
 * Class LotImage
 * @package Sam\Soap
 */
class LotImageSoapController extends SoapControllerBase
{
    use CachedQueueReadRepositoryCreateTrait;
    use LotImageLoaderAwareTrait;
    use LotImageQueueCreateTrait;
    use LotItemLoaderAwareTrait;
    use LotItemNoParserCreateTrait;

    public const LOT_ITEM_NOT_FOUND = 'Not found lot item';

    public const NAMESPACE_ID = 'SAM lot_item.id';
    public const NAMESPACE_ITEM_NUM = 'SAM lot_item.item_num';
    /**
     * @var string[]
     */
    protected array $defaultNamespaces = [
        self::NAMESPACE_ID,
        self::NAMESPACE_ITEM_NUM,
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Cache lotItem Images
     *
     * @param object $data
     * @return int
     * @throws InvalidArgumentException
     */
    public function cacheImages(object $data): int
    {
        $lotItemIds = $this->getLotItemIdsByNamespace($data);

        $validator = LotImagesValidator::new()->setAccountId($this->editorUserAccountId);
        if (!$validator->validate($lotItemIds)) {
            $message = $validator->getErrorMessages()[0];
            log_warning($message);
            throw new InvalidArgumentException($message);
        }

        // Add to cached_queue the Lot_Images
        $lotImageQueue = $this->createLotImageQueue();
        foreach ($lotItemIds as $lotItemId) {
            $lotImages = $this->getLotImageLoader()->loadForLot($lotItemId);
            foreach ($lotImages as $lotImage) {
                $lotImageQueue->addToCached($lotImage->Id, $this->editorUserId);
            }
        }
        // Count of pending images for caching
        return $this->createCachedQueueReadRepository()
            ->betweenCached(-2, 0)
            ->count();
    }

    /**
     * @param object $data
     * @return array
     * @throws InvalidArgumentException
     */
    protected function getLotItemIdsByNamespace(object $data): array
    {
        $syncKeys = $this->parseLotItemIds($data);

        if ($this->namespace === self::NAMESPACE_ID) {
            return $syncKeys;
        }

        $lotItemIds = [];
        foreach ($syncKeys as $syncKey) {
            $lotItem = $this->loadLotItemByNamespace($syncKey);
            if (!$lotItem) {
                log_warning(self::LOT_ITEM_NOT_FOUND);
                throw new InvalidArgumentException(self::LOT_ITEM_NOT_FOUND);
            }
            $lotItemIds[] = $lotItem->Id;
        }
        return $lotItemIds;
    }

    /**
     * @param string|null $syncKey
     * @return LotItem|null
     */
    protected function loadLotItemByNamespace(?string $syncKey): ?LotItem
    {
        switch ($this->namespace) {
            case self::NAMESPACE_ITEM_NUM:
                $itemNoParsed = $this->createLotItemNoParser()
                    ->construct()
                    ->parse((string)$syncKey);
                return $this->getLotItemLoader()->loadByItemNoParsed($itemNoParsed, $this->editorUserAccountId);
            default:
                return $this->getLotItemLoader()->loadBySyncKey((string)$syncKey, $this->namespaceId, $this->editorUserAccountId);
        }
    }

    /**
     * Parse lot item ids from Items tag
     * @param object $data
     * @return array
     */
    private function parseLotItemIds(object $data): array
    {
        return is_array($data->Item) ? $data->Item : [$data->Item];
    }
}
