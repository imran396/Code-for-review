<?php
/**
 * SAM-10273: Entity locations: Implementation (Dev)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 7, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Location\Dto;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\Mode;

/**
 * @package Sam\EntityMaker\Location\Dto
 */
class LocationMakerDtoFactory extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Mode $mode
     * @param int|null $editorUserId
     * @param int|null $serviceAccountId
     * @param int $systemAccountId
     * @return array [LocationMakerInputDto, LocationMakerConfigDto]
     */
    public function createDtos(
        Mode $mode,
        ?int $editorUserId,
        ?int $serviceAccountId,
        int $systemAccountId
    ): array {
        $inputDto = LocationMakerInputDto::new();
        $configDto = LocationMakerConfigDto::new()->construct(
            $mode,
            $editorUserId,
            $serviceAccountId,
            $systemAccountId
        );
        return [$inputDto, $configDto];
    }
}
