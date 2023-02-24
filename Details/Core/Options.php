<?php
/**
 * Store and prepare options for auction details data provider in general
 * But values from Options could be used in other parts of code, eg. Options->languageId
 * See available properties in parent.
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

namespace Sam\Details\Core;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class Options
 * @package Sam\Details
 */
class Options extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * @var array
     */
    protected array $options = [];

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function fingerprint(): string
    {
        return md5(strtolower(trim(json_encode($this->options))));
    }

    /**
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->options[$name] ?? null;
    }

    /**
     * @return static
     */
    public function __set(string $name, mixed $value)
    {
        $this->options[$name] = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function __isset(string $name)
    {
        return array_key_exists($name, $this->options);
    }
}
