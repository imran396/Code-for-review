<?php
/**
 * SAM-6790: Validations at controller layer for v3.5 - ManageAuctionsController
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           07-02, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Auction\Validate\Internal\Input;

use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionControllerValidationInputDto
 * @package Sam\Application\Controller\Admin\Auction\Validate\Internal\Input
 */
class AuctionControllerValidationInput extends CustomizableClass
{
    public int $systemAccountId;
    public ?int $auctionId;
    public ?int $lotItemId;
    public string $actionName = '';
    public string $controllerName = '';


    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        int $systemAccountId,
        string $actionName,
        string $controllerName,
        ?int $auctionId,
        ?int $lotItemId,
    ): static {
        $this->systemAccountId = $systemAccountId;
        $this->actionName = $actionName;
        $this->controllerName = $controllerName;
        $this->auctionId = $auctionId;
        $this->lotItemId = $lotItemId;
        return $this;
    }
}
