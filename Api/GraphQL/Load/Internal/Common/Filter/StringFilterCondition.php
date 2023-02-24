<?php
/**
 * SAM-10719: SAM 3.7 Taxes. Add Search/Filter panel at Account Location List page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal\Common\Filter;

use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class StringFilterCondition
 * @package Sam\Api\GraphQL\Load\Internal\Common\Filter
 */
class StringFilterCondition extends CustomizableClass
{
    public readonly string $contain;
    /** @var string[] */
    public readonly array $in;
    /** @var string[] */
    public readonly array $notIn;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(string $contain, array $in, array $notIn): static
    {
        $this->contain = $contain;
        $this->in = ArrayCast::makeStringArray($in);
        $this->notIn = ArrayCast::makeStringArray($notIn);
        return $this;
    }

    public function fromArgs(array $args): static
    {
        return self::new()->construct(
            contain: $args['contain'] ?? '',
            in: $args['in'] ?? [],
            notIn: $args['notIn'] ?? []
        );
    }
}
