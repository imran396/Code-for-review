<?php
/**
 * SAM-8107: Issues related to Validation and Values of Location
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 21, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Validate\Internal\Location;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Validate\AuctionMakerValidator;
use Sam\EntityMaker\Location\Dto\LocationMakerDtoFactory;
use Sam\EntityMaker\Location\Validate\LocationMakerValidator;
use Sam\EntityMaker\LotItem\Validate\LotItemMakerValidator;
use Sam\EntityMaker\User\Validate\UserMakerValidator;

/**
 * Class LocationValidationIntegrator
 * @package
 */
class LocationValidationIntegrator extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(
        AuctionMakerValidator|LotItemMakerValidator|UserMakerValidator $entityMakerValidator,
        ?object $field,
        int $error,
        int $type,
        ?int $serviceAccountId = null
    ): void {
        if (!$field) {
            return;
        }
        $inputDto = $entityMakerValidator->getInputDto();
        $configDto = $entityMakerValidator->getConfigDto();

        $serviceAccountId = $serviceAccountId ?: $configDto->serviceAccountId;
        [$locationInputDto, $locationConfigDto] = LocationMakerDtoFactory::new()
            ->createDtos($configDto->mode, $configDto->editorUserId, $serviceAccountId, $configDto->systemAccountId);
        $locationInputDto->setArray((array)$field);
        $locationConfigDto->entityId = $inputDto->id;
        $locationConfigDto->entityType = $type;

        $validator = LocationMakerValidator::new()->construct($locationInputDto, $locationConfigDto);
        if (!$validator->validate()) {
            $entityMakerValidator->addError($error, $entityMakerValidator->getErrorMessage($error) . ': ' . implode(', ', $validator->getErrorMessages()));
        }
    }
}
