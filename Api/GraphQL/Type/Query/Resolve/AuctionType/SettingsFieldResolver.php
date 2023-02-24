<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve\AuctionType;

use GraphQL\Type\Definition\ResolveInfo;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverInterface;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\Dev\Mapping\NamingStrategy\NamingStrategyAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class SettingsFieldResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\AuctionType
 */
class SettingsFieldResolver extends CustomizableClass implements FieldResolverInterface
{
    use NamingStrategyAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function dependentOnDataFields(array $referencedFieldNodes): array
    {
        return ['account_id'];
    }

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): array
    {
        $settingsManager = $this->getSettingsManager();
        $values = [];
        foreach (array_keys($info->getFieldSelection()) as $fieldName) {
            $settingName = $this->getNamingStrategy()->columnToPropertyName($fieldName);
            $values[$fieldName] = $settingsManager->get($settingName, (int)$objectValue['account_id']);
        }
        return $values;
    }
}
