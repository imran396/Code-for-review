<?php
/**
 * SAM-8837: Lot item entity maker module structural adjustments for v3-5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotCategory\Dto;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\Mode;

/**
 * @package Sam\EntityMaker\LotCategory\Dto
 */
class LotCategoryMakerDtoFactory extends CustomizableClass
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
     * @param int $serviceAccountId
     * @param int $systemAccountId
     * @return array [LotCategoryMakerInputDto, LotCategoryMakerConfigDto]
     */
    public function createDtos(
        Mode $mode,
        ?int $editorUserId,
        int $serviceAccountId,
        int $systemAccountId
    ): array {
        $inputDto = LotCategoryMakerInputDto::new();
        $configDto = LotCategoryMakerConfigDto::new()->construct(
            $mode,
            $editorUserId,
            $serviceAccountId,
            $systemAccountId
        );
        return [$inputDto, $configDto];
    }

}
