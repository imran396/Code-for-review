<?php

namespace Sam\Api\Soap\Front\Entity\Location\Controller;

use InvalidArgumentException;
use Sam\Api\Soap\Front\Entity\Base\Controller\SoapControllerBase;
use Sam\Core\Constants;
use Sam\Date\CurrentDateTrait;
use Sam\EntityMaker\Location\Dto\LocationMakerConfigDto;
use Sam\EntityMaker\Location\Dto\LocationMakerDtoFactory;
use Sam\EntityMaker\Location\Dto\LocationMakerInputDto;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\Location\Save\LocationMakerProducer;
use Sam\EntityMaker\Location\Validate\LocationMakerValidator;
use Sam\Location\Delete\LocationDeleter;

/**
 * Class Location
 * @package Sam\Soap
 */
class LocationSoapController extends SoapControllerBase
{
    use CurrentDateTrait;

    protected array $defaultNamespaces = [
        'SAM location.id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete Location
     * @param string $key Key is the synchronization key, location.id or location_sync_key
     */
    public function delete(string $key): void
    {
        $locationNamespaceAdapter = new LocationNamespaceAdapter(
            (object)['Key' => $key],
            $this->namespace,
            $this->namespaceId,
            $this->editorUserAccountId
        );
        $location = $locationNamespaceAdapter->getEntity();
        $this->updateLastSyncIn($key, Constants\EntitySync::TYPE_LOCATION);
        LocationDeleter::new()->delete($location, $this->editorUserId);
    }

    /**
     * Save a Location
     *
     * Missing fields keep their content,
     * Empty fields will remove the field content
     *
     * @param object $data
     * @return int
     */
    public function save($data): int
    {
        $locationNamespaceAdapter = new LocationNamespaceAdapter(
            $data,
            $this->namespace,
            $this->namespaceId,
            $this->editorUserAccountId
        );
        $data = $locationNamespaceAdapter->toObject();

        /**
         * @var LocationMakerInputDto $locationInputDto
         * @var LocationMakerConfigDto $locationConfigDto
         */
        [$locationInputDto, $locationConfigDto] = LocationMakerDtoFactory::new()
            ->createDtos(Mode::SOAP, $this->editorUserId, $this->editorUserAccountId, $this->editorUserAccountId);
        $locationInputDto->setArray((array)$data);

        $validator = LocationMakerValidator::new()->construct($locationInputDto, $locationConfigDto);
        if ($validator->validate()) {
            $producer = LocationMakerProducer::new()->construct($locationInputDto, $locationConfigDto);
            $producer->produce();
            return $producer->resultLocation()->Id;
        }

        $logData = ['loc' => $data->Id ?? 0, 'editor u' => $this->editorUserId];
        $errorMessages = $validator->getMainErrorMessages();
        log_debug(implode("\n", $errorMessages) . composeSuffix($logData));
        throw new InvalidArgumentException(implode("\n", $errorMessages));
    }
}
