<?php
/**
 * Encode feed content according selected charset
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         May 28, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Core\Feed;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class Encoder
 * @package Sam\Details
 */
class Encoder extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    protected string $encoding = '';

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function encode(string $content): string
    {
        if ($this->encoding === '') {
            log_debug('No selected encoding returning original value');
            return $content;
        }

        if (
            $this->cfg()->get('core->feed->checkEncoding')
            && !mb_check_encoding($content, $this->encoding)
        ) {
            $message = 'Not compatible with the target encoding "' . $this->encoding . '"';
            log_debug($message);
            return $message;
        }

        if ($content !== '') {
            $content = mb_convert_encoding($content, $this->encoding, "UTF-8");
        }

        return $content;
    }

    public function getEncoding(): string
    {
        return $this->encoding;
    }

    public function setEncoding(string $encoding): static
    {
        $this->encoding = $encoding;
        return $this;
    }
}
