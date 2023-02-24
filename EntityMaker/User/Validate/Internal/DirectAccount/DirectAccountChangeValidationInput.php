<?php
/**
 * SAM-9177: User entity-maker - Account related issues for v3-4, v3-5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Validate\Internal\DirectAccount;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use User;

/**
 * Class DirectAccountChangeValidationInput
 * @package Sam\EntityMaker\User\Validate\Internal\DirectAccount
 */
class DirectAccountChangeValidationInput extends CustomizableClass
{
    /** @var int|null */
    public ?int $newAccountId;
    /** @var int|null */
    public ?int $oldAccountId;
    /** @var int|null */
    public ?int $editorUserId;
    /** @var int|null */
    public ?int $targetUserId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $targetUserId
     * @param int|null $newAccountId
     * @param int|null $oldAccountId
     * @param int|null $editorUserId
     * @return $this
     */
    public function construct(
        ?int $targetUserId,
        ?int $newAccountId,
        ?int $oldAccountId,
        ?int $editorUserId
    ): static {
        $this->targetUserId = $targetUserId;
        $this->newAccountId = $newAccountId;
        $this->oldAccountId = $oldAccountId;
        $this->editorUserId = $editorUserId;
        return $this;
    }

    public function fromMakerDto(
        UserMakerInputDto $inputDto,
        UserMakerConfigDto $configDto,
        ?User $user
    ): static {
        return $this->construct(
            Cast::toInt($inputDto->id),
            Cast::toInt($inputDto->accountId),
            $user->AccountId ?? null,
            $configDto->editorUserId
        );
    }

    /**
     * @return array
     */
    public function logData(): array
    {
        return [
            'u' => $this->targetUserId,
            'new acc' => $this->newAccountId,
            'old acc' => $this->oldAccountId,
            'editor u' => $this->editorUserId,
        ];
    }
}
