<?php
/**
 * Data provider class for bidder's Agent feature
 *
 * SAM-3654: User related repositories https://bidpath.atlassian.net/browse/SAM-3654
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           27 Mar, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\Agent\Load;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Filter\Availability\FilterUserAvailabilityAwareTrait;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepository;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;
use User;

/**
 * Class AgentDataProvider
 */
class AgentDataLoader extends EntityLoaderBase
{
    use FilterUserAvailabilityAwareTrait;
    use UserReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * To initialize instance properties
     * @return static
     */
    public function initInstance(): static
    {
        $this->filterUserStatusId(Constants\User::US_ACTIVE);
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
     * Return array of ids (user.id), who are buyers of passed agent
     * @param int $agentId
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadBuyersIds(int $agentId, bool $isReadOnlyDb = false): array
    {
        $rows = $this->prepareRepository($isReadOnlyDb)
            ->joinBidderFilterAgentId($agentId)
            ->skipId($agentId)
            ->select(['u.id'])
            ->loadRows();
        return ArrayCast::arrayColumnInt($rows, 'id');
    }

    /**
     * Returns an array all Agents
     * @param bool $isReadOnlyDb
     * @return User[]
     */
    public function loadAgents(bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterSubqueryIsAgentGreater(0)
            ->orderByUsername()
            ->loadEntities();
    }

    /**
     * @param bool $isReadOnlyDb
     * @return UserReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb): UserReadRepository
    {
        $repo = $this->createUserReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        if ($this->hasFilterUserStatusId()) {
            $repo->filterUserStatusId($this->getFilterUserStatusId());
        }
        return $repo;
    }
}
