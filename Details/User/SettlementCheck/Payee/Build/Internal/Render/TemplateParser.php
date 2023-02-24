<?php
/**
 * SAM-10136: Implement conditional logic in print check template field Payee
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\User\SettlementCheck\Payee\Build\Internal\Render;

use Sam\Details\User\SettlementCheck\Payee\Internal\ConfigManager;

/**
 * Class TemplateParser
 * @package Sam\Details\User\SettlementCheck\Payee\Build\Internal\Render
 */
class TemplateParser extends \Sam\Details\Core\Render\TemplateParser
{
    /**
     * Class instantiation method
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(ConfigManager $configManager, int $systemAccountId, bool $hideEmptyFields = false, ?int $languageId = null): static
    {
        $this->setConfigManager($configManager);
        $this->setLanguageId($languageId);
        $this->setSystemAccountId($systemAccountId);
        $this->enableHideEmptyFields($hideEmptyFields);

        $this->renderer = Renderer::new()
            ->setConfigManager($configManager)
            ->setLanguageId($languageId)
            ->setSystemAccountId($systemAccountId);
        return $this;
    }
}
