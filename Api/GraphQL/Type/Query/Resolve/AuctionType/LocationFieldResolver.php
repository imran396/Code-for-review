<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve\AuctionType;

use GraphQL\Executor\Promise\Promise;
use GraphQL\Type\Definition\ResolveInfo;
use Sam\Core\Service\CustomizableClass;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverHelperCreateTrait;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverInterface;
use Sam\Api\GraphQL\Type\Query\Resolve\Internal\FieldDataMapperCreateTrait;

/**
 * Class LocationFieldResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\AuctionType
 */
class LocationFieldResolver extends CustomizableClass implements FieldResolverInterface
{
    use FieldDataMapperCreateTrait;
    use FieldResolverHelperCreateTrait;

    public const EVENT_LOCATION = 'event_location';
    public const INVOICE_LOCATION = 'invoice_Location';

    protected const EVENT_LOCATION_TO_DATA_FIELD_MAP = [
        'id' => 'event_location_id',
        'address' => 'event_location_address',
        'logo' => 'event_location_logo',
        'name' => 'event_location_name',
        'country' => 'event_location_country',
        'county' => 'event_location_county',
        'state' => 'event_location_state',
        'city' => 'event_location_city',
        'zip' => 'event_location_zip',
    ];

    protected const INVOICE_LOCATION_TO_DATA_FIELD_MAP = [
        'id' => 'invoice_location_id',
        'address' => 'invoice_location_address',
        'logo' => 'invoice_location_logo',
        'name' => 'invoice_location_name',
        'country' => 'invoice_location_country',
        'county' => 'invoice_location_county',
        'state' => 'invoice_location_state',
        'city' => 'invoice_location_city',
        'zip' => 'invoice_location_zip',
    ];

    protected string $locationType;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(string $locationType): static
    {
        $this->locationType = $locationType;
        return $this;
    }

    public function forEventLocation(): static
    {
        return $this->construct(self::EVENT_LOCATION);
    }

    public function forInvoiceLocation(): static
    {
        return $this->construct(self::INVOICE_LOCATION);
    }

    public function dependentOnDataFields(array $referencedFieldNodes): array
    {
        $dependentOnFields = [$this->getIdFieldName()];
        $referencedFields = array_keys($referencedFieldNodes);
        $dataMapper = $this->createFieldDataMapper();
        $mapping = $this->getMapping();
        if ($dataMapper->hasMappingForAllReferencedFields($referencedFields, $mapping)) {
            $dependentOnFields = array_merge(
                $dependentOnFields,
                $dataMapper->collectDataFields($referencedFields, $mapping)
            );
            $dependentOnFields = array_unique($dependentOnFields);
        }
        return $dependentOnFields;
    }

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): array|Promise|null
    {
        $idFieldName = $this->getIdFieldName();
        if ($objectValue[$idFieldName] === null) {
            return null;
        }

        $location = $this->createFieldDataMapper()->mapDataToType($objectValue, $this->getMapping());
        if ($this->createFieldResolverHelper()->hasEnoughDataToResolve($info, $location)) {
            return $location;
        }
        return $appContext->dataLoader->loadLocation((int)$objectValue[$idFieldName]);
    }

    protected function getIdFieldName(): string
    {
        return $this->getMapping()['id'];
    }

    protected function getMapping(): array
    {
        if ($this->locationType === self::INVOICE_LOCATION) {
            return self::INVOICE_LOCATION_TO_DATA_FIELD_MAP;
        }
        return self::EVENT_LOCATION_TO_DATA_FIELD_MAP;
    }
}
