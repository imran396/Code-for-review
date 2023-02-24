<?php
/**
 * Parses the operation name, tags and complex tags from the SOAP request
 *
 * SAM-5794: SOAP call processing shouldn't ignore incorrect fields
 * SAM-6445: Soap should ignore tags with xsi:nil="true" attribute
 *
 * Project        SOAP Server
 * @author        Victor Pautoff
 * @version       SVN: $Id: $
 * @since         May 04, 2021
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Server\Request\Parse;

use Sam\Core\Service\CustomizableClass;
use SimpleXMLElement;

/**
 * Class SoapRequestHelper
 * @package Sam\Soap
 */
class SoapRequestParser extends CustomizableClass
{
    /** @var string[] */
    public array $complexTags = [];
    /** @var string */
    public string $operation = '';
    /** @var string[] */
    public array $tags = [];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Parses the operation name, tags and complex tags from the request
     * @param string $request
     * @return bool
     */
    public function parseData(string $request): bool
    {
        $xml = @simplexml_load_string($request);
        if ($xml === false) {
            return false;
        }

        $xml->registerXPathNamespace('soap', 's');
        // Operation with/without authentication
        $data = $xml->xpath('//soap:*')[2] ?? ($xml->xpath('//soap:*')[0] ?? null);
        if (!$data) {
            return false;
        }

        $this->operation = ucfirst($this->parseOperation($data));

        // Object fields are wrapped into a Data tag
        $data = $data->Data ?? $data;
        $data = $data[0] ?? $data;

        $this->tags = $this->parseTags($data);
        $this->complexTags = $this->parseComplexTags($data);

        return true;
    }

    protected function parseComplexTags(SimpleXMLElement $data): array
    {
        $complexTags = [];
        foreach ((array)$data as $key => $value) {
            if (
                !is_object($value)
                || $value->count() === 0
            ) {
                continue;
            }
            $complexTags[lcfirst($key)] = $value;
        }
        return $complexTags;
    }

    protected function parseOperation(SimpleXMLElement $data): string
    {
        return $data->getName();
    }

    protected function parseTags(SimpleXMLElement $data): array
    {
        $tags = array_keys((array)$data);
        // Remove commented out tags
        $tags = array_diff($tags, ['comment']);

        return array_map('lcfirst', $tags);
    }
}
