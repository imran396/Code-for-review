<?php
/**
 * Escape feed content according selected escape option
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

namespace Sam\Details\Core\Render;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Csv\CsvTransformer;

/**
 * Class EscapingTool
 * @package Sam\Details
 */
class EscapingTool extends CustomizableClass implements EscapingToolInterface
{

    /**
     * @var int|null
     */
    protected ?int $escapingType = null;
    /**
     * @var string
     */
    protected string $encoding = '';
    /**
     * @var bool
     */
    protected bool $isProfiling = false;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * {@inheritDoc}
     */
    public function escape(mixed $value): string
    {
        $value = (string)$value;
        $ts = microtime(true);
        switch ($this->getEscapingType()) {
            case Constants\Feed::ESC_HTMLENTITIES:
                $value = htmlentities($value, ENT_COMPAT, $this->getEncoding());
                break;

            case Constants\Feed::ESC_CSV_EXCEL:
                $delimiter = ",";
                $quoteChar = '"';
                $lineTerminator = "\r\n";
                $specialChars = str_split($lineTerminator);
                $specialChars[] = $quoteChar;
                $specialChars[] = $delimiter;
                $isSpecialChar = false;
                foreach ($specialChars as $char) {
                    if (strpos($value, $char)) {
                        $isSpecialChar = true;
                        break;
                    }
                }
                if ($isSpecialChar) {
                    $value = CsvTransformer::new()->escapeDoubleQuotesForCsv($value);
                    $value = '"' . $value . '"';
                }
                break;

            case Constants\Feed::ESC_URL:
                $value = urlencode($value);
                break;

            case Constants\Feed::ESC_RTF:
                $value = str_replace(['\\', '{', '}'], ['\\\\', '\{', '\}'], $value);
                $value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
                break;

            case Constants\Feed::ESC_JSON_ENCODE:
                $value = json_encode($value);
                break;
        }

        if ($this->isProfiling) {
            log_trace('Value escaped per ' . ((microtime(true) - $ts) * 1000) . 'ms');
        }

        return (string)$value;
    }

    public function getEscapingType(): ?int
    {
        return $this->escapingType;
    }

    public function setEscapingType(?int $escapingType): static
    {
        $this->escapingType = $escapingType;
        return $this;
    }

    public function getEncoding(): string
    {
        if (!$this->encoding) {
            $this->encoding = 'UTF-8';
        }
        return $this->encoding;
    }

    public function setEncoding(string $encoding): static
    {
        $this->encoding = $encoding;
        return $this;
    }

    public function enableProfiling(bool $enabled): static
    {
        $this->isProfiling = $enabled;
        return $this;
    }
}
