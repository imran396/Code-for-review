<?php
/**
 * SAM-5673: Refactor data loader for Account List datagrid at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 11, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AccountListForm\Render;

use Sam\Application\Url\Domain\AccountDomainDetectorCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Admin\Form\AccountListForm\Load\AccountListDto;

/**
 * Class AuctionListRenderer
 * @package Sam\View\Admin\Form\AccountListForm\Render
 */
class AuctionListRenderer extends CustomizableClass
{
    use AccountDomainDetectorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AccountListDto $dto
     * @return string
     */
    public function renderId(AccountListDto $dto): string
    {
        return (string)$dto->id;
    }

    /**
     * @param AccountListDto $dto
     * @return string
     */
    public function renderName(AccountListDto $dto): string
    {
        return ee($dto->name);
    }

    /**
     * @param AccountListDto $dto
     * @return string
     */
    public function renderCompany(AccountListDto $dto): string
    {
        return ee($dto->companyName);
    }

    /**
     * @param AccountListDto $dto
     * @return string
     */
    public function renderAccountUrl(AccountListDto $dto): string
    {
        $url = $this->createAccountDomainDetector()->detectByValues($dto->id, $dto->urlDomain, $dto->name);
        return '<a href="http://' . $url . '" target="_blank" >' . $url . '</a>';
    }
}
