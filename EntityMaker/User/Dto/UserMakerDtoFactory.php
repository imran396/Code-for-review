<?php
/**
 * SAM-8841: User entity-maker module structural adjustments for v3-5
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

namespace Sam\EntityMaker\User\Dto;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\Mode;

/**
 * Class UserMakerDtoFactory
 * @package Sam\EntityMaker\User\Dto
 */
class UserMakerDtoFactory extends CustomizableClass
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
     * @param int|null $editorUserId null on sign up new user
     * @param int|null $serviceAccountId null means should be detected inside service, i.e. by user's direct account.
     * @param int $systemAccountId
     * @return array [UserMakerInputDto, UserMakerConfigDto]
     */
    public function createDtos(
        Mode $mode,
        ?int $editorUserId,
        ?int $serviceAccountId,
        int $systemAccountId
    ): array {
        $inputDto = UserMakerInputDto::new();
        $configDto = UserMakerConfigDto::new()->construct(
            $mode,
            $editorUserId,
            $serviceAccountId,
            $systemAccountId
        );
        return [$inputDto, $configDto];
    }

}
