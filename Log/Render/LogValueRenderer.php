<?php
/**
 * SAM-6729: Improve logging - entity dump attribute logging options
 * SAM-10338: Redact sensitive information in Soap error.log
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Log\Render;

use DateTimeInterface;
use Sam\Core\Service\CustomizableClass;
use Sam\Log\Render\Config\RenderConfig;
use Sam\Log\Render\Config\RenderMode;
use Sam\Core\Constants;

/**
 * Class LogValueRenderer
 * @package Sam\Log\Render
 */
class LogValueRenderer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function render(mixed $value, RenderConfig $config): string
    {
        if ($config->trim && is_string($value)) {
            $value = trim($value);
        }
        $value = $this->normalize($value);
        $valueRendered = $this->renderValue($value, $config->renderMode);
        if ($config->mask) {
            $valueRendered = $this->mask(
                $value,
                $config->mask->start,
                $config->mask->length,
                $config->maxLength,
                $config->mask->replacement
            );
        } else {
            $valueRendered = $this->cut($valueRendered, $config->maxLength);
        }
        if ($config->crc32) {
            $valueRendered .= ' (crc32 ' . crc32($value) . ')';
        }
        return $valueRendered;
    }

    /* @param string $value
     * @param int $start
     * @param int|null $length
     * @param int $maxLength
     * @param string $replacement
     * @return string
     */
    public function mask(
        string $value,
        int $start = 0,
        ?int $length = null,
        int $maxLength = 32,
        string $replacement = 'x'
    ): string {
        if ($value === '') {
            return '';
        }
        $mask = str_repeat($replacement, min(strlen($value), $maxLength));
        if (is_null($length)) {
            $mask = substr($mask, $start);
            $value = substr_replace($value, $mask, $start);
        } else {
            $mask = substr($mask, $start, $length);
            $value = substr_replace($value, $mask, $start, $length);
        }
        return $value;
    }

    /**
     * @param string $value
     * @param int $length
     * @return string
     */
    public function cut(string $value, int $length): string
    {
        if ($value === '') {
            return '';
        }
        $length = $length < 3 ? 3 : $length;
        if (strlen($value) <= $length) {
            return $value;
        }
        return substr($value, 0, $length - 3) . '...';
    }

    /**
     * @param string $value
     * @param RenderMode $mode
     * @return string
     */
    protected function renderValue(string $value, RenderMode $mode): string
    {
        $renderedValue = match ($mode) {
            RenderMode::PLAIN => $value,
            RenderMode::BASE64 => base64_encode($value),
            RenderMode::HEX => bin2hex($value),
            default => '',
        };
        return $renderedValue;
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected function normalize(mixed $value): string
    {
        $value = is_null($value) ? 'NULL' : $value;
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }
        if ($value instanceof DateTimeInterface) {
            $value = $value->format(Constants\Date::ISO);
        }
        return (string)$value;
    }
}
