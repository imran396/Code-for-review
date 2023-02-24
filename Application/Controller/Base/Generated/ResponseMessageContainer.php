<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: common/response_message_container.proto

namespace Sam\Application\Controller\Base\Generated;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>sam.common.ResponseMessageContainer</code>
 */
class ResponseMessageContainer extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>repeated string debug = 1;</code>
     */
    private $debug;
    /**
     * Generated from protobuf field <code>repeated string info = 2;</code>
     */
    private $info;
    /**
     * Generated from protobuf field <code>repeated string warn = 3;</code>
     */
    private $warn;
    /**
     * Generated from protobuf field <code>repeated string errors = 4;</code>
     */
    private $errors;
    /**
     * Generated from protobuf field <code>repeated string success = 5;</code>
     */
    private $success;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string[]|\Google\Protobuf\Internal\RepeatedField $debug
     *     @type string[]|\Google\Protobuf\Internal\RepeatedField $info
     *     @type string[]|\Google\Protobuf\Internal\RepeatedField $warn
     *     @type string[]|\Google\Protobuf\Internal\RepeatedField $errors
     *     @type string[]|\Google\Protobuf\Internal\RepeatedField $success
     * }
     */
    public function __construct($data = NULL) {
        \Sam\Application\Controller\Base\Generated\Internal\Metadata\ResponseMessageContainer::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>repeated string debug = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * Generated from protobuf field <code>repeated string debug = 1;</code>
     * @param string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setDebug($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->debug = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated string info = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Generated from protobuf field <code>repeated string info = 2;</code>
     * @param string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setInfo($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->info = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated string warn = 3;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getWarn()
    {
        return $this->warn;
    }

    /**
     * Generated from protobuf field <code>repeated string warn = 3;</code>
     * @param string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setWarn($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->warn = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated string errors = 4;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Generated from protobuf field <code>repeated string errors = 4;</code>
     * @param string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setErrors($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->errors = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated string success = 5;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * Generated from protobuf field <code>repeated string success = 5;</code>
     * @param string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setSuccess($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->success = $arr;

        return $this;
    }

}

