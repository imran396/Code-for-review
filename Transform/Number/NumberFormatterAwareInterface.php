<?php

namespace Sam\Transform\Number;

interface NumberFormatterAwareInterface
{
    public function setNumberFormatter(NumberFormatterInterface $numberFormatter);
}
