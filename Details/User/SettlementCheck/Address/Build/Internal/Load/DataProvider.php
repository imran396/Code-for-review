<?php
/**
 * SAM-10136: Implement conditional logic in print check template field Payee
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\User\SettlementCheck\Address\Build\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Details\Core\DataProviderInterface;
use Sam\Details\User\Base\FieldMapping\UserReadRepositoryFieldMapperCreateTrait;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepository;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;

/**
 * Class DataProvider
 * @package Sam\Details\User\SettlementCheck\Address
 */
class DataProvider extends CustomizableClass implements DataProviderInterface
{
    use UserReadRepositoryCreateTrait;
    use UserReadRepositoryFieldMapperCreateTrait;

    protected UserReadRepository $repository;

    /**
     * Class instantiation method
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(int $userId, array $fields, bool $isReadOnlyDb = false): static
    {
        $this->repository = $this->createUserReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($userId);
        $this->repository = $this->createUserReadRepositoryFieldMapper()->mapToRepository($fields, $this->repository);
        return $this;
    }

    public function load(): array
    {
        return $this->repository->loadRow();
    }
}
