<?php
/**
 * SAM-6489: Rtb console control rendering at server side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 06, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Control\Render;

use Sam\Core\Service\CustomizableClass;

/**
 * Class RtbControlCollection
 * @package Sam\Rtb\Control\Render
 */
class RtbControlCollection extends CustomizableClass
{
    protected array $controls = [];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $control TODO: make DTO or VO
     * @return static
     */
    public function add(array $control): static
    {
        $this->controls[$control['id']] = $control;
        return $this;
    }

    /**
     * @param string $controlId
     * @return array
     */
    public function get(string $controlId): array
    {
        return $this->controls[$controlId];
    }
}
