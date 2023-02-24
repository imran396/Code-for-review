<?php
/**
 * Template sample renderer
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         May 9, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\Web;

/**
 * Class TemplateSampler
 * @package Sam\Details
 */
class TemplateSampler
    extends \Sam\Details\Auction\Base\TemplateSampler
{
    use ConfigManagerAwareTrait;

    protected string $newLine = "<br />\n";
    /** @var string */
    protected string $sectionTpl = "<h1>%s<h1><br />\n%s<br />\n<br />\n";

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function render(): string
    {
        $sample = parent::render();
        return <<<HTML
<li>
    <a href="javascript:void(0)">{label_description}</a>
    <section>
        <div class="content ins_cnt description-content" style="word-break:break-all;">
{$sample}
        </div>
        <div class="clear"></div>
    </section>
</li>
<li>
    <a href="javascript:void(0)">{label_shipping_info}</a>
    <section class="tabhide">
        <div class="content ins_cnt shipping-info-content">
            {shipping_info}
        </div>
    </section>
    <div class="clear"></div>
</li>
<li>
    <a href="javascript:void(0)">{label_terms}</a>
    <section class="tabhide">
        <div class="content ins_cnt terms-content">
            {terms}
        </div>
    </section>
    <div class="clear"></div>
</li>
HTML;
    }
}
