<?php
/**
 * SAM-8831: Apply DTOs fro Account List at admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AccountListForm\Load;


use Sam\Core\Service\CustomizableClass;

/**
 * Class AccountListDto
 * @package Sam\View\Admin\Form\AccountListForm\Load
 */
class AccountListDto extends CustomizableClass
{
    public string $companyName = '';
    public int $id = 0;
    public string $name = '';
    public string $urlDomain = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $companyName
     * @param int $id
     * @param string $name
     * @param string $urlDomain
     * @return $this
     */
    public function construct(
        string $companyName,
        int $id,
        string $name,
        string $urlDomain
    ): static {
        $this->companyName = $companyName;
        $this->id = $id;
        $this->name = $name;
        $this->urlDomain = $urlDomain;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (string)$row['company_name'],
            (int)$row['id'],
            (string)$row['name'],
            (string)$row['url_domain']
        );
    }
}
