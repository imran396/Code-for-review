<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 22, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve\AuctionType;

use GraphQL\Type\Definition\ResolveInfo;
use Sam\Application\Url\Build\Config\Image\AuctionImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverInterface;

/**
 * Class MainImageUrlFieldResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\AuctionType
 */
class MainImageUrlFieldResolver extends CustomizableClass implements FieldResolverInterface
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
        return ['image_id', 'account_id'];
    }

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): ?string
    {
        if (!$objectValue['image_id']) {
            return null;
        }
        return $this->getUrlBuilder()->build(
            AuctionImageUrlConfig::new()->construct((int)$objectValue['image_id'], $args['size'], (int)$objectValue['account_id'])
        );
    }
}
