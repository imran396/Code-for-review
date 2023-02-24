<?php
/**
 * Build sitemap XML output, using incoming data
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           18 Dec, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sitemap\Base\Builder;

use Sam\Core\Service\CustomizableClass;

/**
 * Class XmlBuilder
 * @package Sam\Sitemap\Base\Builder
 */
abstract class XmlBuilder extends CustomizableClass implements BuilderInterface
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // We can fetch all data at first and then build XML output,
    // or we can build XML output on the fly while fetching data portions

    /**
     * Return XML Document
     * @return string
     */
    abstract public function build(): string;

}
