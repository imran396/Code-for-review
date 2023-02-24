<?php
/**
 * SAM-10136: Implement conditional logic in print check template field Payee
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\User\SettlementCheck\Address\Build;

use Sam\Core\Constants;
use Sam\Details\Core\ConfigManagerAwareTrait;
use Sam\Details\User\SettlementCheck\Address\Build\Internal\Load\DataProvider;
use Sam\Details\User\SettlementCheck\Address\Build\Internal\Render\TemplateParser;
use Sam\Details\User\SettlementCheck\Address\Internal\Config\ConfigManager;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class SettlementCheckAddressBuilder
 * @package Sam\Details\User\SettlementCheck\Address\Build
 * @method ConfigManager getConfigManager()
 */
class SettlementCheckAddressBuilder extends \Sam\Details\Core\Builder
{
    use ConfigManagerAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(int $userId, int $accountId, bool $isReadOnlyDb = false): static
    {
        $this->configManager = ConfigManager::new()->construct();
        $this->template = $this->getSettingsManager()->get(Constants\Setting::STLM_CHECK_ADDRESS_TEMPLATE, $accountId);
        $this->dataProvider = $this->createDataProvider($userId, $isReadOnlyDb);
        $this->templateParser = $this->createTemplateParser($accountId);
        return $this;
    }

    public function build(): string
    {
        $template = $this->getTemplate();
        $placeholders = $this->getPlaceholderManager()->getActualPlaceholders();
        $row = $this->dataProvider->load();
        return $this->templateParser->parse($template, $placeholders, $row);
    }

    protected function createDataProvider(int $userId, bool $isReadOnlyDb = false): DataProvider
    {
        $fields = array_map(
            function (string $placeholderKey) {
                return $this->getConfigManager()->getOption($placeholderKey, 'select');
            },
            $this->getPlaceholderManager()->getActualKeys()
        );
        $fields = array_unique(array_merge(...$fields));
        return DataProvider::new()->construct($userId, $fields, $isReadOnlyDb);
    }

    private function createTemplateParser(int $accountId): TemplateParser
    {
        return TemplateParser::new()->construct($this->getConfigManager(), $accountId, $this->shouldHideEmptyFields());
    }
}
