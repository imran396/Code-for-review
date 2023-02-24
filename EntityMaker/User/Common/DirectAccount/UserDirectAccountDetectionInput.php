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

namespace Sam\EntityMaker\User\Common\DirectAccount;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\ValueResolver;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;

/**
 * Class UserDirectAccountDetectionInput
 * @package Sam\EntityMaker\User\Common\Account
 */
class UserDirectAccountDetectionInput extends CustomizableClass
{
    /** @var int|null */
    public ?int $targetUserId;
    /** @var int|null */
    public ?int $inputAccountId;
    /** @var int */
    public int $systemAccountId;
    /** @var bool */
    public bool $isBidderOrConsignor;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $targetUserId null when creating new user.
     * @param int|null $inputAccountId null means account id is not explicitly defined on input.
     * @param int $systemAccountId account of visiting domain.
     * @param bool $isBidderOrConsignor has sense for newly created user only.
     * @return $this
     */
    public function construct(
        ?int $targetUserId,
        ?int $inputAccountId,
        int $systemAccountId,
        bool $isBidderOrConsignor
    ): static {
        $this->targetUserId = $targetUserId;
        $this->inputAccountId = $inputAccountId;
        $this->systemAccountId = $systemAccountId;
        $this->isBidderOrConsignor = $isBidderOrConsignor;
        return $this;
    }

    /**
     * @param UserMakerInputDto $inputDto
     * @param UserMakerConfigDto $configDto
     * @return $this
     */
    public function fromMakerDto(
        UserMakerInputDto $inputDto,
        UserMakerConfigDto $configDto
    ): static {
        /**
         * Since $isBidderOrConsignor has sense only for newly created user ($targetUserId = null),
         * thus we can consider only input values.
         */
        $isBidderOrConsignor = ValueResolver::new()->isTrue($inputDto->bidder)
            || ValueResolver::new()->isTrue($inputDto->consignor);
        return $this->construct(
            Cast::toInt($inputDto->id),
            Cast::toInt($inputDto->accountId),
            $configDto->systemAccountId,
            $isBidderOrConsignor
        );
    }

    /**
     * @return array
     */
    public function logData(): array
    {
        return [
            'target u' => $this->targetUserId,
            'input acc' => $this->inputAccountId,
            'system acc' => $this->systemAccountId,
            'is bidder or consignor' => $this->isBidderOrConsignor,
        ];
    }
}
