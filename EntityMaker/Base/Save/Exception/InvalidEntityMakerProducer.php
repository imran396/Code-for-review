<?php
/**
 * SAM-5015: Unite sync tables data scheme
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\EntityMaker\Base\Save\Exception;

use LogicException;

class InvalidEntityMakerProducer extends LogicException
{
    public static function withClassOf($instance): self
    {
        return new self(sprintf('Unexpected entity-maker producer of class "%s"', get_class($instance)));
    }
}
