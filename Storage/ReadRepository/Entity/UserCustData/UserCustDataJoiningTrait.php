<?php
/**
 * Trait to allow joining user custom data records in the Repository classes
 * Requires the following properties in the using class
 * alias: alias name of the table to join the user_cust_data records
 * userCustDataJoinFieldName name of the field to join the user_cust_data.user_id on
 * NOTE: There may be limitations to the number of joins Mysql allows to execute
 * Limiting the number of custom fields that can be included in the query
 * This trait is very low level. It does not determine field types or enforce
 * access privilege requirements, etc
 */

namespace Sam\Storage\ReadRepository\Entity\UserCustData;

use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\Storage\ReadRepository\Entity\UserCustField\UserCustFieldReadRepository;

/**
 * Trait UserCustDataJoiningTrait
 * @package Sam\Storage\Repository
 */
trait UserCustDataJoiningTrait
{
    /**
     * When initiated user custom data records available to query
     * @return static
     */
    public function addUserCustomDataOptionsToMapping(): static
    {
        $userCustomFields = UserCustFieldReadRepository::new()
            ->filterActive(true)
            ->orderByOrder()
            ->loadEntities();

        $dbTransformer = DbTextTransformer::new();
        foreach ($userCustomFields as $userCustomField) {
            $joinName = sprintf('ucf_%s', $dbTransformer->toDbColumn($userCustomField->Name));
            $join = 'JOIN user_cust_data ' . $joinName
                . ' ON ' . $joinName . '.active'
                . ' AND ' . $joinName . '.user_cust_field_id = ' . $userCustomField->Id
                . ' AND ' . $joinName . '.user_id = ' . $this->alias . '.' . $this->userCustDataJoinFieldName;

            if (!array_key_exists($joinName, $this->joins)) {
                $this->joins[$joinName] = $join;
            }
        }
        return $this;
    }

    /**
     * Join specific user custom data table for custom field
     * Requires call of initiateUserCustDataJoins before using
     * @param string $name custom field name (lower-cased and underscored, no special characters)
     * @return static
     * @throws \RuntimeException
     */
    public function joinUserCustDataByName(string $name): static
    {
        $joinName = 'ucf_' . $name;
        if (!array_key_exists($joinName, $this->joins)) {
            $message = composeLogData(['Custom field does not exist' => $name]);
            log_error($message);
            throw new \RuntimeException($message);
        }

        $this->join($joinName);

        return $this;
    }

}
