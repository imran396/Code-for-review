<?php
/**
 * SAM-5925: Allow to set installation timezone and User preferred timezone
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Timezone\Save;

use RuntimeException;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\Timezone\TimezoneWriteRepositoryAwareTrait;
use Sam\Timezone\ApplicationTimezoneProviderAwareTrait;
use Timezone;

/**
 * Class TimezoneProducer
 * @package Sam\Timezone\Save
 */
class TimezoneProducer extends CustomizableClass
{
    use ApplicationTimezoneProviderAwareTrait;
    use EntityFactoryCreateTrait;
    use TimezoneWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * Create and fill active Timezone entity without saving in DB.
     * @param string $location
     * @return Timezone
     */
    public function create(string $location): Timezone
    {
        if (!$this->getApplicationTimezoneProvider()->isTimezoneAvailable($location)) {
            throw new RuntimeException(sprintf('Timezone "%s" is not available in system', $location));
        }

        $timezone = $this->createEntityFactory()->timezone();
        $timezone->Location = $location;
        $timezone->Active = true;
        return $timezone;
    }

    /**
     * Create and fill active Timezone entity and save in DB.
     * @param string $location
     * @param int $editorUserId
     * @return Timezone
     */
    public function createPersisted(string $location, int $editorUserId): Timezone
    {
        $timezone = $this->create($location);
        $this->getTimezoneWriteRepository()->saveWithModifier($timezone, $editorUserId);
        return $timezone;
    }
}
