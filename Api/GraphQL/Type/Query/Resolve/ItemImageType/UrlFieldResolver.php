<?php
/**
 * SAM-10957: GraphQL item image extension
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve\ItemImageType;

use GraphQL\Type\Definition\ResolveInfo;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverInterface;
use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class UrlFieldResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\ItemImageType
 */
class UrlFieldResolver extends CustomizableClass implements FieldResolverInterface
{
    use UrlBuilderAwareTrait;

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
        return ['id', 'account_id'];
    }

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): ?string
    {
        return $this->getUrlBuilder()->build(
            LotImageUrlConfig::new()->construct((int)$objectValue['id'], $args['size'], (int)$objectValue['account_id'])
        );
    }
}
