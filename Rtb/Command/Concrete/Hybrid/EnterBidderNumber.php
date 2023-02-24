<?php

namespace Sam\Rtb\Command\Concrete\Hybrid;

use Sam\Rtb\Hybrid\HelpersAwareTrait;
use Sam\Rtb\Command\Helper\Hybrid\HybridRtbCommandHelperAwareTrait;

/**
 * Class EnterBidderNumber
 * @package Sam\Rtb\Command\Concrete\Hybrid
 */
class EnterBidderNumber extends \Sam\Rtb\Command\Concrete\Base\EnterBidderNumber
{
    use HelpersAwareTrait;
    use HybridRtbCommandHelperAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function execute(): void
    {
        parent::execute();
        $this->getResponseHelper()->applyAdditionalInfo($this);
    }
}
