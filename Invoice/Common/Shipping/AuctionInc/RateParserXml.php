<?php
/**
 * SAM-4770 : Refactor Auction Inc modules
 * https://bidpath.atlassian.net/browse/SAM-4770
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/3/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Shipping\AuctionInc;

use Sam\Core\Service\CustomizableClass;
use XMLParser;

/**
 * Class AuctionInc
 * @package Sam\Invoice\Common\Shipping\RateParserXml
 */
abstract class RateParserXml extends CustomizableClass
{
    /**
     * @var resource|XMLParser|null $parser A reference to the XML parser to free
     */
    protected $parser;
    /**
     * @var bool|string|null
     */
    protected bool|string|null $currentTag = null;
    /**
     * @var array
     */
    protected array $dom;
    /**
     * @var bool
     */
    protected bool $errState;
    /**
     * @var int
     */
    protected int $errorCount;

    /**
     * Initialize the parser object
     * @return void
     * @access private
     */
    public function init(): void
    {
        $this->currentTag = false;
        $this->errState = false;
        $this->errorCount = 0;
        $this->dom = [];

        if ($this->parser instanceof XMLParser) {
            @xml_parser_free($this->parser);
        }
        $this->parser = @xml_parser_create();
        xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, $this->startHandler(...), $this->endHandler(...));
        xml_set_character_data_handler($this->parser, $this->cdataHandler(...));
        xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
    }

    /**
     * XML Element Handler (start)
     * @param string $string
     * @return bool|array
     * @access public
     */
    public function parse(string $string)
    {
        if ($this->parser instanceof XMLParser) {
            xml_parse($this->parser, $string, true);
            xml_parser_free($this->parser);
            $this->parser = null;
            return $this->dom;
        }
        return false;
    }

    /**
     * XML Element Handler (start)
     * @param XMLParser $parser xml parser
     * @param string $tag Tag
     * @param array $attribs Attribute Array
     * @return void
     * @access public
     */
    public function startHandler(XMLParser $parser, string $tag, array $attribs): void
    {
        $this->currentTag = $tag;
        // $attribs = array_change_key_case($attribs, CASE_LOWER);
        switch ($tag) {
            case 'ErrorList':
                xml_set_character_data_handler($this->parser, $this->_cdataHandler_Error(...));
                $this->dom = [];
                $this->dom['ErrorList'] = [];
                $this->errState = true;
                break;
            case 'Error':
                $this->dom['ErrorList'][$this->errorCount] = [];
                break;
        }
    }

    /**
     * @param $parser
     * @param string $tag
     */
    public function endHandler($parser, string $tag): void
    {
        $this->currentTag = null; // <<<<< WATCH THIS, cdataHandlers called 3 times: tag space and \n
        switch ($tag) {
            case 'ErrorList':
                break;
            case 'Error':
                $this->errorCount++;
                break;
        }
    }

    /**
     * @param $parser
     * @param $data
     */
    public function cdataHandler($parser, $data): void
    {
    }

    /**
     * XML Character Data Handler
     * @param object $parser xml parser
     * @param $val
     * @return void
     */
    public function _cdataHandler_Error($parser, $val): void
    {
        static $map;
        if (!$this->errState || ($this->currentTag === null) || empty($this->currentTag)) {
            return;
        }
        if (!isset($map)) {
            $map = [
                'code' => 'Code',
                'message' => 'Message',
                'severity' => 'Severity'
            ];
        }
        $this->cdataAdapter($val, $map, $this->errorCount, $this->dom['ErrorList']);
    }

    ### ----------------------------------------------------------------------
    ### Support Methods
    ### ----------------------------------------------------------------------

    /**
     * Used by cdataHandler methods to store the data into the object container in the proper position
     * @param string $data text just parsed from XML
     * @param array $map assocArray  Element list with names mapped
     * @param mixed $nodeName Node name that current tag will be place in the container, blank if
     * @param array $container (ref) reference point of $this->_res where the node will reside
     * @return void
     */
    public function cdataAdapter(string $data, array $map, $nodeName, &$container): void
    {
        $str = strtolower($this->currentTag);
        if (isset($map[$str])) {
            // Get a reference into the container where data should be placed
            if ($nodeName === null) {
                $ref = &$container;
            } else {
                // Double check to see if the node has been created yet
                if (!isset($container[$nodeName])) {
                    $container[$nodeName] = [];
                }
                $ref = &$container[$nodeName];
            }

            // Get the element name from the map
            $key = $map[$str];

            if (isset($ref[$key])) {
                $ref[$key] .= $data;
            } else {
                $ref[$key] = $data;
            }
        }
    }

}
