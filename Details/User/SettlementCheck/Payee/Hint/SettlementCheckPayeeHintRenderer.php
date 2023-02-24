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

namespace Sam\Details\User\SettlementCheck\Payee\Hint;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Core\Hint\CoreHintRendererAwareTrait;
use Sam\Details\Core\Placeholder\Placeholder;
use Sam\Details\User\SettlementCheck\Payee\Internal\ConfigManager;
use Sam\Details\User\SettlementCheck\Payee\Internal\ConfigManagerCreateTrait;

/**
 * Class SettlementCheckPayeeHintRenderer
 * @package Sam\Details\User\SettlementCheck\Payee\Hint
 */
class SettlementCheckPayeeHintRenderer extends CustomizableClass
{
    use ConfigManagerCreateTrait;
    use CoreHintRendererAwareTrait;

    protected const INLINE_HELP_SECTION = 'admin_user_details';

    /**
     * Class instantiation method
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function render(): string
    {
        $configManager = $this->createConfigManager()->construct();
        $hint = $this->getCoreHintRenderer()
            ->construct($configManager)
            ->render(
                [
                    'inlineHelpSection' => self::INLINE_HELP_SECTION,
                    'isOptionSection' => false,
                ]
            );
        return $hint . $this->renderBeginEndSection($configManager);
    }

    protected function renderHintSection(string $title, string $content): string
    {
        return <<<HTML
<p><h3>{$title}</h3></p>
<p>{$content}</p>
<br />
HTML;
    }

    protected function renderBeginEndSection(ConfigManager $configManager): string
    {
        $key = Constants\UserDetail::PL_FIRST_NAME;
        $begin = $configManager->makeBeginKey($key);
        $begin = Placeholder::decorateView($begin);
        $beginLogicalNot = $configManager->makeBeginKey($key, true);
        $beginLogicalNot = Placeholder::decorateView($beginLogicalNot);
        $end = $configManager->makeEndKey($key);
        $end = Placeholder::decorateView($end);
        $endLogicalNot = $configManager->makeEndKey($key, true);
        $endLogicalNot = Placeholder::decorateView($endLogicalNot);
        $view = Placeholder::decorateView($key);

        $content = <<<HTML
<pre>
{$begin}
First name {$view}
{$end}

{$beginLogicalNot}
First name is empty
{$endLogicalNot}
</pre>
HTML;
        $output = $this->renderHintSection("BEGIN-END block decoration", $content);
        return _hl($output, self::INLINE_HELP_SECTION, 'begin_end_block_decoration', false);
    }
}
