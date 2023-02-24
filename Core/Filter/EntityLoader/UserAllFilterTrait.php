<?php
/**
 * User loader and existence checker all common filter trait
 *
 * SAM-4922: Entity Loader and Existence Checker approach integration
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 2, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\EntityLoader;

use Sam\Core\Constants;
use Sam\Core\Filter\Availability\FilterUserAvailabilityAwareTrait;
use Sam\Core\Filter\Conformity\FilterDescriptor;
use Sam\Storage\ReadRepository\Entity\Admin\AdminReadRepository;
use Sam\Storage\ReadRepository\Entity\Admin\AdminReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\Bidder\BidderReadRepository;
use Sam\Storage\ReadRepository\Entity\Bidder\BidderReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\Consignor\ConsignorReadRepository;
use Sam\Storage\ReadRepository\Entity\Consignor\ConsignorReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepository;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserAccountStats\UserAccountStatsReadRepository;
use Sam\Storage\ReadRepository\Entity\UserAccountStats\UserAccountStatsReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserAuthentication\UserAuthenticationReadRepository;
use Sam\Storage\ReadRepository\Entity\UserAuthentication\UserAuthenticationReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserBilling\UserBillingReadRepository;
use Sam\Storage\ReadRepository\Entity\UserBilling\UserBillingReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserInfo\UserInfoReadRepository;
use Sam\Storage\ReadRepository\Entity\UserInfo\UserInfoReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserShipping\UserShippingReadRepository;
use Sam\Storage\ReadRepository\Entity\UserShipping\UserShippingReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserWavebid\UserWavebidReadRepository;
use Sam\Storage\ReadRepository\Entity\UserWavebid\UserWavebidReadRepositoryCreateTrait;

/**
 * Trait UserAllFilterTrait
 * @package Sam\Core\Filter\EntityLoader
 */
trait UserAllFilterTrait
{
    use AdminReadRepositoryCreateTrait;
    use BidderReadRepositoryCreateTrait;
    use ConsignorReadRepositoryCreateTrait;
    use FilterUserAvailabilityAwareTrait;
    use UserAccountStatsReadRepositoryCreateTrait;
    use UserAuthenticationReadRepositoryCreateTrait;
    use UserBillingReadRepositoryCreateTrait;
    use UserInfoReadRepositoryCreateTrait;
    use UserReadRepositoryCreateTrait;
    use UserShippingReadRepositoryCreateTrait;
    use UserWavebidReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public function initFilter(): static
    {
        $this->filterUserStatusId(Constants\User::AVAILABLE_USER_STATUSES);
        return $this;
    }

    /**
     * @return static
     */
    public function clear(): static
    {
        $this->clearFilterUser();
        return $this;
    }

    /**
     * @return FilterDescriptor[]
     */
    public function collectFilterDescriptors(): array
    {
        $descriptors = [];
        if ($this->getFilterUserStatusId()) {
            $descriptors[] = FilterDescriptor::new()->init(\User::class, 'UserStatusId', $this->getFilterUserStatusId());
        }
        return $descriptors;
    }

    /**
     * @template RepositoryTemplate of ReadRepositoryBase
     * @param RepositoryTemplate $repo
     * @param bool $isReadOnlyDb
     * @return RepositoryTemplate
     */
    protected function applyStatusFilter(ReadRepositoryBase $repo, bool $isReadOnlyDb): ReadRepositoryBase
    {
        $repo->enableReadOnlyDb($isReadOnlyDb);
        if ($this->hasFilterUserStatusId()) {
            $repo->joinUserFilterUserStatusId($this->getFilterUserStatusId());
        }
        return $repo;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return AdminReadRepository
     */
    protected function prepareAdminRepository(bool $isReadOnlyDb): AdminReadRepository
    {
        return $this->applyStatusFilter($this->createAdminReadRepository(), $isReadOnlyDb);
    }

    /**
     * @param bool $isReadOnlyDb
     * @return BidderReadRepository
     */
    protected function prepareBidderRepository(bool $isReadOnlyDb): BidderReadRepository
    {
        return $this->applyStatusFilter($this->createBidderReadRepository(), $isReadOnlyDb);
    }

    /**
     * @param bool $isReadOnlyDb
     * @return ConsignorReadRepository
     */
    protected function prepareConsignorRepository(bool $isReadOnlyDb): ConsignorReadRepository
    {
        return $this->applyStatusFilter($this->createConsignorReadRepository(), $isReadOnlyDb);
    }

    /**
     * @param bool $isReadOnlyDb
     * @return UserAuthenticationReadRepository
     */
    protected function prepareUserAuthenticationRepository(bool $isReadOnlyDb): UserAuthenticationReadRepository
    {
        return $this->applyStatusFilter($this->createUserAuthenticationReadRepository(), $isReadOnlyDb);
    }

    /**
     * @param bool $isReadOnlyDb
     * @return UserBillingReadRepository
     */
    protected function prepareUserBillingRepository(bool $isReadOnlyDb): UserBillingReadRepository
    {
        return $this->applyStatusFilter($this->createUserBillingReadRepository(), $isReadOnlyDb);
    }

    /**
     * @param bool $isReadOnlyDb
     * @return UserInfoReadRepository
     */
    protected function prepareUserInfoRepository(bool $isReadOnlyDb): UserInfoReadRepository
    {
        return $this->applyStatusFilter($this->createUserInfoReadRepository(), $isReadOnlyDb);
    }

    /**
     * @param bool $isReadOnlyDb
     * @return UserReadRepository
     */
    protected function prepareUserRepository(bool $isReadOnlyDb): UserReadRepository
    {
        $repo = $this->createUserReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        if ($this->hasFilterUserStatusId()) {
            $repo->filterUserStatusId($this->getFilterUserStatusId());
        }
        return $repo;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return UserShippingReadRepository
     */
    protected function prepareUserShippingRepository(bool $isReadOnlyDb): UserShippingReadRepository
    {
        return $this->applyStatusFilter($this->createUserShippingReadRepository(), $isReadOnlyDb);
    }

    /**
     * @param bool $isReadOnlyDb
     * @return UserWavebidReadRepository
     */
    protected function prepareUserWavebidRepository(bool $isReadOnlyDb): UserWavebidReadRepository
    {
        return $this->applyStatusFilter($this->createUserWavebidReadRepository(), $isReadOnlyDb);
    }

    /**
     * @param bool $isReadOnlyDb
     * @return UserAccountStatsReadRepository
     */
    protected function prepareUserAccountStatsRepository(bool $isReadOnlyDb): UserAccountStatsReadRepository
    {
        return $this->applyStatusFilter($this->createUserAccountStatsReadRepository(), $isReadOnlyDb);
    }
}
