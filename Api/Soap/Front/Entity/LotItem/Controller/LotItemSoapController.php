<?php

namespace Sam\Api\Soap\Front\Entity\LotItem\Controller;

use InvalidArgumentException;
use RuntimeException;
use Sam\Api\Soap\Front\Entity\Base\Controller\SoapControllerBase;
use Sam\Core\Constants;
use Sam\Date\CurrentDateTrait;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\LotItem\Common\Access\LotItemMakerAccessCheckerAwareTrait;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerConfigDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerDtoFactory;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputMeta;
use Sam\EntityMaker\LotItem\Lock\LotItemMakerLocker;
use Sam\EntityMaker\LotItem\Save\LotItemMakerProducer;
use Sam\EntityMaker\LotItem\Validate\LotItemMakerValidator;
use Sam\Lot\Delete\Access\LotItemDeleteAccessCheckerAwareTrait;
use Sam\Lot\Delete\LotItemDeleterCreateTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;

/**
 * Class LotItem
 * @package Sam\Soap
 */
class LotItemSoapController extends SoapControllerBase
{
    use CurrentDateTrait;
    use LotItemDeleteAccessCheckerAwareTrait;
    use LotItemDeleterCreateTrait;
    use LotItemLoaderAwareTrait;
    use LotItemMakerAccessCheckerAwareTrait;

    protected const ACCESS_DENIED = 'Access denied to lot item';

    protected array $defaultNamespaces = [
        'SAM lot_item.id',
        'SAM lot_item.item_num'
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete Lot
     * @param string $key Can be Id, ItemNum or the synchronization key
     */
    public function delete(string $key): void
    {
        $lotItemNamespaceAdapter = new LotItemNamespaceAdapter(
            (object)['Key' => $key],
            $this->namespace,
            $this->namespaceId,
            $this->editorUserAccountId
        );
        $lotItem = $lotItemNamespaceAdapter->getEntity();

        $canDeleteResult = $this->getLotItemDeleteAccessChecker()
            ->canDeleteLotItem($lotItem, $this->editorUserId);
        if ($canDeleteResult->hasError()) {
            log_error('Access denied to delete lot item' . composeSuffix($canDeleteResult->logData()));
            throw new InvalidArgumentException(self::ACCESS_DENIED);
        }
        $this->updateLastSyncIn($key, Constants\EntitySync::TYPE_LOT_ITEM);
        $this->createLotItemDeleter()->delete($lotItem, $this->editorUserId);
    }

    /**
     * Create or update a Lot
     * Missing fields keep their content, empty fields will remove the field content
     * @param LotItemMakerInputMeta $data
     * @return int LotItem id
     */
    public function save($data): int
    {
        $lotItemNamespaceAdapter = new LotItemNamespaceAdapter(
            $data,
            $this->namespace,
            $this->namespaceId,
            $this->editorUserAccountId
        );
        $dataObj = $lotItemNamespaceAdapter->toObject();

        $this->parseBuyersPremiums($dataObj);
        $this->parseCategoriesNames($dataObj);
        $this->parseCustomFields($dataObj, 'LotCustomFields');
        $this->parseImages($dataObj);
        $this->parseIncrements($dataObj);
        $this->parseRanges($dataObj, 'ConsignorCommissionRanges');
        $this->parseRanges($dataObj, 'ConsignorUnsoldFeeRanges');
        $this->parseRanges($dataObj, 'ConsignorSoldFeeRanges');
        $this->parseTaxStates($data);

        /**
         * @var LotItemMakerInputDto $lotItemInputDto
         * @var LotItemMakerConfigDto $lotItemConfigDto
         */
        [$lotItemInputDto, $lotItemConfigDto] = LotItemMakerDtoFactory::new()
            ->createDtos(Mode::SOAP, $this->editorUserId, $this->editorUserAccountId, $this->editorUserAccountId);
        $lotItemInputDto->setArray((array)$dataObj);
        $lockingResult = LotItemMakerLocker::new()->lock($lotItemInputDto, $lotItemConfigDto); // #li-lock-3
        if (!$lockingResult->isSuccess()) {
            throw new RuntimeException($lockingResult->message());
        }

        $validator = LotItemMakerValidator::new()->construct($lotItemInputDto, $lotItemConfigDto);
        if ($validator->validate()) {
            try {
                $producer = LotItemMakerProducer::new()->construct($lotItemInputDto, $lotItemConfigDto);
                $producer->produce();
            } catch (RuntimeException $e) {
                throw $e;
            } finally {
                LotItemMakerLocker::new()->unlock($lotItemConfigDto); // #li-lock-3, unlock after success or failed save
            }
            return $producer->getLotItem()->Id;
        }

        LotItemMakerLocker::new()->unlock($lotItemConfigDto); // #li-lock-3, unlock after failed validation
        $logData = ['li' => $data->Id ?? '', 'editor u' => $this->editorUserId];
        $errorMessages = array_merge($validator->getMainErrorMessages(), $validator->getCustomFieldsErrors());
        log_debug(implode("\n", $errorMessages) . composeSuffix($logData));
        throw new InvalidArgumentException(implode("\n", $errorMessages));
    }

    /**
     * Parse Categories, Category tags to categories names array
     * @param \stdClass $data
     */
    private function parseCategoriesNames($data): void
    {
        if (isset($data->Categories)) {
            $categories = $data->Categories->Category ?? null;
            $data->categoriesNames = (array)$categories;
            unset($data->Categories);
        }
    }

    /**
     * Parse images from images, image tags
     * @param $data
     */
    private function parseImages($data): void
    {
        $images = $data->Images->Image ?? null;
        if ($images) {
            $data->Images = (array)$images;
        }
    }
}
