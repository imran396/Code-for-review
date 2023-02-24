<?php
/**
 * Special to auction Seo Url template parser
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Feb 16, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\SeoUrl;

use Sam\Core\Transform\Text\TextTransformer;
use Sam\Details\Core\Placeholder\Placeholder;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class TemplateParser
 * @package Sam\Details
 */
class TemplateParser extends \Sam\Details\Auction\Base\Render\TemplateParser
{
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Placeholder[] $placeholders
     */
    public function parse(string $template, ?array $placeholders, array $row): string
    {
        $output = parent::parse($template, $placeholders, $row);

        $textTransformer = TextTransformer::new();
        $output = $textTransformer->toSeoFriendlyUrl($output);
        return $textTransformer->cut($output, $this->cfg()->get('core->auction->seoUrl->lengthLimit'));
    }
}
