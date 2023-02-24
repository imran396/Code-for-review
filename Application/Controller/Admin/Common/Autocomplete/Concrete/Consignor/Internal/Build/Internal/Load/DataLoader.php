<?php
/**
 * SAM-10099: Distinguish consignor autocomplete data loading end-points for different pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Consignor\Internal\Build\Internal\Load;

use Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Query\QueryBuildingHelperCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;

/**
 * Class DataLoader
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Consignor\Internal\Build\Internal\Load
 */
class DataLoader extends CustomizableClass
{
    use QueryBuildingHelperCreateTrait;
    use UserReadRepositoryCreateTrait;

    // 1 record takes 1Kb, 100000 records approximately 100Mb
    protected const LIMIT = 100000;

    /** @var string[] */
    protected const NUMERIC_SEARCH_FIELDS = [
        "u.customer_no",
        "ub.zip",
        "us.zip"
    ];

    /** @var string[] */
    protected const TEXT_SEARCH_FIELDS = [
        "u.email",
        "u.username",
        "ui.company_name",
        "ui.first_name",
        "ui.last_name",
        "ub.zip",
        "us.zip",
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Prepare conditions and load consignor data.
     * @param string $searchKeyword
     * @param int|null $filterAccountId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function load(string $searchKeyword, ?int $filterAccountId, bool $isReadOnlyDb = false): array
    {
        $selects = array_merge(self::TEXT_SEARCH_FIELDS, self::NUMERIC_SEARCH_FIELDS, ['u.id']);
        $repo = $this->createUserReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterUserStatusId([Constants\User::US_ACTIVE])
            ->groupById() // avoid duplication because of join with `user_account`
            ->innerJoinConsignor()
            ->joinUserAccount()
            ->joinUserBilling()
            ->joinUserInfo()
            ->joinUserShipping()
            ->limit(self::LIMIT)
            ->select($selects);

        if ($filterAccountId) {
            $repo->inlineCondition("u.account_id = {$filterAccountId} OR ua.account_id = {$filterAccountId}");
        }

        $likeCondition = $this->createQueryBuildingHelper()
            ->makeTypeDependentSearchCondition($searchKeyword, self::TEXT_SEARCH_FIELDS, self::NUMERIC_SEARCH_FIELDS);

        if ($likeCondition) {
            $repo->inlineCondition($likeCondition);
        }

        return $repo->loadRows();
    }

}
