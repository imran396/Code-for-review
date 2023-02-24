<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/17/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\Account;

use QListBox;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Account\Render\AccountRendererAwareTrait;
use Sam\Application\Url\Domain\AccountDomainDetectorCreateTrait;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class AccountListBox
 * @package Sam\Qform\Account
 */
class AccountListBox extends QListBox
{
    use AdminTranslatorAwareTrait;
    use AccountDomainDetectorCreateTrait;
    use AccountLoaderAwareTrait;
    use AccountRendererAwareTrait;

    private bool $isAllAccountOption = true;

    /**
     * AccountListBox constructor.
     * @param $parentObject
     * @param string|null $controlId
     * @param string|null $language - null leads to no translations for default selected ("ALL") option. if you want to translate it, pass the language code.
     */
    public function __construct($parentObject, string $controlId = null, string $language = null)
    {
        parent::__construct($parentObject, $controlId);
        $this->populateOptions($language);
    }

    protected function populateOptions(?string $language): void
    {
        if ($this->isAllAccountOption()) {
            $allAccountOption = $language ? $this->getAdminTranslator()->trans('all', [], 'admin', $language) : 'All';
            $this->AddItem($allAccountOption, null, true);
        }

        $rows = $this->getAccountLoader()
            ->loadAllSelected(['id', 'name', 'company_name', 'url_domain']);
        foreach ($rows as $row) {
            $name = static::makeSelectOption(
                $row['name'],
                $row['company_name'],
                $this->createAccountDomainDetector()->detectByValues((int)$row['id'], (string)$row['url_domain'], $row['name'])
            );
            $this->AddItem($name, (int)$row['id']);
        }
    }

    /** @noinspection PhpMethodNamingConventionInspection */
    /**
     * @return string
     */
    protected function GetControlHtml(): string
    {
        if (!$this->isAllAccountOption()) {
            foreach ($this->GetAllItems() as $key => $control) {
                if ($control->Value === null) {
                    $this->RemoveItem($key);
                }
            }
        }
        return parent::GetControlHtml();
    }

    /**
     * @param string $name
     * @param string $companyName
     * @param string $urlDomain
     * @return string
     */
    public static function makeSelectOption(string $name, string $companyName, string $urlDomain): string
    {
        $values = array_filter([$name, $companyName, $urlDomain]);
        $output = implode(', ', $values);
        return $output;
    }

    /**
     * @return bool
     */
    public function isAllAccountOption(): bool
    {
        return $this->isAllAccountOption;
    }

    /**
     * @param bool $isAllAccountOption
     * @return static
     */
    public function enableAllAccountOption(bool $isAllAccountOption): static
    {
        $this->isAllAccountOption = $isAllAccountOption;
        return $this;
    }
}
