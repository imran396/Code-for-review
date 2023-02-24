<?php
/**
 * SAM-10384: Implement a GraphQL prototype for a list of lots
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 06, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Lot\Sort\Internal;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\CustomField\Lot\Validate\LotCustomFieldExistenceCheckerCreateTrait;
use Sam\User\Access\UnknownContextAccessCheckerAwareTrait;

/**
 * Class SortFieldConfigurationFactory
 * @package Sam\Api\GraphQL\Type\Query\Definition\Lot\Sort\Internal
 */
class SortFieldConfigurationFactory extends CustomizableClass
{
    use BaseCustomFieldHelperAwareTrait;
    use LotCustomFieldExistenceCheckerCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use UnknownContextAccessCheckerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function create(array $keys, ?int $editorUserId): array
    {
        $values = [];
        foreach ($keys as $key) {
            if (
                $key === Constants\GraphQL::LOT_LIST_DISTANCE_SORT
                && !$this->isExistGeolocationCustomField()
            ) {
                continue;
            }
            $values[$key] = ['value' => $this->detectSortValue($key)];
        }

        $dbTransformer = DbTextTransformer::new();
        $accessRoles = $this->getUnknownContextAccessChecker()->detectRoles($editorUserId)[0];
        $customFields = $this->createLotCustomFieldLoader()->loadByRole($accessRoles, true);
        foreach ($customFields as $customField) {
            $nameNormalized = $dbTransformer->toDbColumn($customField->Name);
            $fieldAlias = $this->getBaseCustomFieldHelper()->makeFieldAlias($nameNormalized);
            $values['CUSTOM_FIELD_' . strtoupper($nameNormalized) . '_ASC'] = [
                'value' => $fieldAlias . ' asc'
            ];
            $values['CUSTOM_FIELD_' . strtoupper($nameNormalized) . '_DESC'] = [
                'value' => $fieldAlias . ' desc'
            ];
        }
        return $values;
    }

    /**
     * @param Constants\GraphQL::LOT_LIST_*_SORT $key
     * @return string|null
     */
    protected function detectSortValue(string $key): ?string
    {
        return match ($key) {
            Constants\GraphQL::LOT_LIST_ORDER_NUM_SORT => 'order_num',
            Constants\GraphQL::LOT_LIST_TIME_LEFT_SORT => 'timeleft',
            Constants\GraphQL::LOT_LIST_TIME_LEFT_DESC_SORT => 'timeleft desc',
            Constants\GraphQL::LOT_LIST_LOT_NUM_SORT => 'lot_num',
            Constants\GraphQL::LOT_LIST_LOT_NAME_SORT => 'lot_name',
            Constants\GraphQL::LOT_LIST_AUCTION_SORT => 'auction',
            Constants\GraphQL::LOT_LIST_NEWEST_SORT => 'newest',
            Constants\GraphQL::LOT_LIST_HIGHEST_PRICE_SORT => 'highest',
            Constants\GraphQL::LOT_LIST_LOWEST_PRICE_SORT => 'lowest',
            Constants\GraphQL::LOT_LIST_BIDS_SORT => 'bids desc',
            Constants\GraphQL::LOT_LIST_VIEWS_SORT => 'views desc',
            Constants\GraphQL::LOT_LIST_DISTANCE_SORT => 'distance',
        };
    }

    protected function isExistGeolocationCustomField(): bool
    {
        return $this->createLotCustomFieldExistenceChecker()
            ->existByTypeAmongSearchFields(Constants\CustomField::TYPE_POSTALCODE, true);
    }
}
