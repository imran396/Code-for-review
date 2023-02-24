<?php
/**
 * SAM-5397: Token Link SSO
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/20/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Load;

use InvalidArgumentException;
use RuntimeException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Sso\TokenLink\Config\TokenLinkConfiguratorAwareTrait;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepository;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserBilling\UserBillingReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserCustData\UserCustDataReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserCustField\UserCustFieldReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserInfo\UserInfoReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserShipping\UserShippingReadRepositoryCreateTrait;
use UserCustField;

/**
 * Class DataLoader
 * @package
 */
class TokenLinkDataLoader extends CustomizableClass
{
    use TokenLinkConfiguratorAwareTrait;
    use UserBillingReadRepositoryCreateTrait;
    use UserCustDataReadRepositoryCreateTrait;
    use UserCustFieldReadRepositoryCreateTrait;
    use UserInfoReadRepositoryCreateTrait;
    use UserReadRepositoryCreateTrait;
    use UserShippingReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $table
     * @param string $column
     * @return bool
     */
    public function isColumnExist(string $table, string $column): bool
    {
        if (!$this->isTableExist($table)) {
            return false;
        }
        if ($table === 'user_cust_field') {
            return $this->createUserCustFieldReadRepository()->filterName($column)->exist();
        }
        return $this->getRepositoryByName($table)->existColumn($column);
    }

    /**
     * @param string|null $table
     * @return bool
     * @throws InvalidArgumentException
     */
    public function isTableExist(?string $table): bool
    {
        try {
            $this->getRepositoryByName($table);
            return true;
        } catch (InvalidArgumentException) {
            return false;
        }
    }

    /**
     * @param string $username
     * @return array $data = [
     *  0 => int, // user.id
     *  1 => int,   // user.flag
     *  2 => string // secret field
     * ]
     * @throws RuntimeException
     */
    public function loadUserData(string $username): array
    {
        [$secretTable, $secretColumn] = $this->getTokenLinkConfigurator()->getSecretTableAndColumn();
        $secretColumnExpr = $this->getTableAliasByName($secretTable) . '.'
            . $this->detectSecretColumn($secretTable, $secretColumn) . ' secret';
        $userRepo = $this->createUserReadRepository()
            ->select(
                [
                    'u.id',
                    'u.flag',
                    $secretColumnExpr,
                ]
            )
            ->joinBidder()
            ->filterUserStatusId(Constants\User::US_ACTIVE)
            ->filterUsername([$username]);
        $userRepo = $this->joinSecretTableRepo($userRepo);

        $row = $userRepo->loadRow();
        if (!$row) {
            throw new RuntimeException('User does not exist');
        }
        return [(int)$row['id'], (int)$row['flag'], (string)$row['secret']];
    }

    /**
     * Fetch from db secret field value
     * @param string $username
     * @return string
     */
    public function loadSecret(string $username): string
    {
        [, , $secret] = $this->loadUserData($username);
        return $secret;
    }

    /**
     * @param string $secretTable
     * @param string $secretColumn
     * @return string
     */
    protected function detectSecretColumn(string $secretTable, string $secretColumn): string
    {
        if ($secretTable !== 'user_cust_field') {
            return $secretColumn;
        }

        $userCustomField = $this->getUserCustomFieldByName($secretColumn);
        $column = in_array($userCustomField->Type, Constants\CustomField::$numericTypes, true)
            ? 'numeric'
            : 'text';
        return $column;
    }

    /**
     * @param string $name
     * @return UserCustField
     * @throws RuntimeException
     */
    protected function getUserCustomFieldByName(string $name): UserCustField
    {
        $userCustomField = $this->createUserCustFieldReadRepository()
            ->filterActive(true)
            ->filterName($name)
            ->loadEntity();
        if (!$userCustomField) {
            throw new RuntimeException('Unknown custom field as source of signature secret');
        }
        return $userCustomField;
    }

    /**
     * @param UserReadRepository $userRepo
     * @return UserReadRepository
     */
    protected function joinSecretTableRepo(UserReadRepository $userRepo): UserReadRepository
    {
        [$secretTable, $secretColumn] = $this->getTokenLinkConfigurator()->getSecretTableAndColumn();
        switch ($secretTable) {
            case 'user_cust_field':
                $userCustomField = $this->getUserCustomFieldByName($secretColumn);
                $userRepo->joinUserCustDataFilterUserCustFieldId($userCustomField->Id);
                break;
            case 'user_info':
                $userRepo->joinUserInfo();
                break;
            case 'user_billing':
                $userRepo->joinUserBilling();
                break;
            case 'user_shipping':
                $userRepo->joinUserShipping();
                break;
        }
        return $userRepo;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getTableAliasByName(string $name): string
    {
        return $this->getRepositoryByName($name)->getAlias();
    }

    /**
     * @param string|null $name
     * @return ReadRepositoryBase
     */
    protected function getRepositoryByName(?string $name): ReadRepositoryBase
    {
        $userRepositories = [
            'user' => $this->createUserReadRepository(),
            'user_billing' => $this->createUserBillingReadRepository(),
            'user_cust_field' => $this->createUserCustDataReadRepository(),
            'user_info' => $this->createUserInfoReadRepository(),
            'user_shipping' => $this->createUserShippingReadRepository(),
        ];
        if (!isset($userRepositories[$name])) {
            throw new InvalidArgumentException(sprintf('Repository cannot be found by name "%s"', $name));
        }
        return $userRepositories[$name];
    }
}
