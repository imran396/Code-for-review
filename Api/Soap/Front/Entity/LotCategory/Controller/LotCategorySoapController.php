<?php

namespace Sam\Api\Soap\Front\Entity\LotCategory\Controller;

use Exception;
use InvalidArgumentException;
use RuntimeException;
use Sam\Api\Soap\Front\Entity\Base\Controller\SoapControllerBase;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\LotCategory\Dto\LotCategoryMakerConfigDto;
use Sam\EntityMaker\LotCategory\Dto\LotCategoryMakerDtoFactory;
use Sam\EntityMaker\LotCategory\Dto\LotCategoryMakerInputDto;
use Sam\EntityMaker\LotCategory\Save\LotCategoryMakerProducer;
use Sam\EntityMaker\LotCategory\Validate\LotCategoryMakerValidator;
use Sam\Lot\Category\Access\Management\LotCategoryManagementAccessCheckerCreateTrait;
use Sam\Lot\Category\Delete\LotCategoryDeleter;
use Sam\Lot\Category\Order\LotCategoryOrdererAwareTrait;

/**
 * Class LotCategory
 * @package Sam\Soap
 */
class LotCategorySoapController extends SoapControllerBase
{
    use LotCategoryManagementAccessCheckerCreateTrait;
    use LotCategoryOrdererAwareTrait;

    /**
     * @var string[]
     */
    protected array $defaultNamespaces = [
        'SAM lot_category.id',
        'SAM lot_category.name'
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete LotCategory
     * @param string $key
     * @throws RuntimeException
     */
    public function delete(string $key): void
    {
        $lotCategoryNamespaceAdapter = new LotCategoryNamespaceAdapter(
            (object)['Key' => $key],
            $this->namespace,
            $this->namespaceId
        );
        $lotCategory = $lotCategoryNamespaceAdapter->getEntity();
        $deleteAccessCheckResult = $this->createLotCategoryManagementAccessChecker()
            ->checkAccessForDelete(
                $lotCategory->Id,
                $this->editorUserId,
                $this->editorUserAccountId,
                true
            );
        if ($deleteAccessCheckResult->hasError()) {
            $logData = [
                'lc' => $lotCategory->Id,
                'system acc' => $this->editorUserAccountId,
                'editor u' => $this->editorUserAccountId,
                'error' => $deleteAccessCheckResult->errorMessage()
            ];
            $errorMessage = 'Access denied to lot category deleting' . composeSuffix($logData);
            log_error($errorMessage);
            throw new InvalidArgumentException($errorMessage);
        }
        LotCategoryDeleter::new()->deleteCategoryWithDescendants($lotCategory->Id, $this->editorUserId);
    }

    /**
     * Create or update a Lot Category
     * Missing fields keep their content, empty fields will remove the field content
     *
     * @param \Sam\EntityMaker\LotCategory\Dto\LotCategoryMakerInputMeta $data
     * @return int LotCategoryId
     */
    public function save($data): int
    {
        $accessCheckResult = $this->createLotCategoryManagementAccessChecker()
            ->checkAccess($this->editorUserId, $this->editorUserAccountId, true);
        if ($accessCheckResult->hasError()) {
            $logData = [
                'system acc' => $this->editorUserAccountId,
                'editor u' => $this->editorUserId,
                'error' => $accessCheckResult->errorMessage()
            ];
            $errorMessage = 'Access denied to lot category management' . composeSuffix($logData);
            log_error($errorMessage);
            throw new InvalidArgumentException($errorMessage);
        }

        $lotCategoryNamespaceAdapter = new LotCategoryNamespaceAdapter($data, $this->namespace, $this->namespaceId);
        $dataObj = $lotCategoryNamespaceAdapter->toObject();
        /**
         * @var LotCategoryMakerInputDto $lotCategoryInputDto
         * @var LotCategoryMakerConfigDto $lotCategoryConfigDto
         */
        [$lotCategoryInputDto, $lotCategoryConfigDto] = LotCategoryMakerDtoFactory::new()->createDtos(
            Mode::SOAP,
            $this->editorUserId,
            $this->editorUserAccountId,
            $this->editorUserAccountId
        );
        $lotCategoryInputDto->setArray((array)$dataObj);
        $validator = LotCategoryMakerValidator::new()
            ->construct($lotCategoryInputDto, $lotCategoryConfigDto);
        if ($validator->validate()) {
            $lotCategoryProducer = LotCategoryMakerProducer::new()
                ->construct($lotCategoryInputDto, $lotCategoryConfigDto);
            $lotCategoryProducer->produce();
            return $lotCategoryProducer->getLotCategory()->Id;
        }

        $logData = ['lc' => $data->Id ?? 0, 'editor u' => $this->editorUserId];
        $errorMessages = array_merge($validator->getMainErrorMessages(), $validator->getCustomFieldsErrors());
        log_debug(implode("\n", $errorMessages) . composeSuffix($logData));
        throw new InvalidArgumentException(implode("\n", $errorMessages));
    }


    /**
     * @param string $orderBy
     * @return bool
     * @throws InvalidArgumentException
     */
    public function reorderLotCategories(string $orderBy): bool
    {
        try {
            if ($orderBy === 'Name') {
                $this->getLotCategoryOrderer()->orderAllByName($this->editorUserId);
            } elseif ($orderBy === '' || $orderBy === 'Sibling') {
                $this->getLotCategoryOrderer()->orderAllBySiblingOrder($this->editorUserId);
            } else {
                throw new InvalidArgumentException('Invalid OrderBy parameter');
            }
            return true;
        } catch (Exception) {
            throw new InvalidArgumentException('Error of reordering lot categories');
        }
    }
}
