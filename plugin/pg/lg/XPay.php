<?php

class XPay extends XPayClient
{
    public function set_config_value($key, $val)
    {
        $this->config[$key] = $val;
    }
}