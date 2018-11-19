<?php
        // mall.conf 설정 추가를 위한 XPayClient 확장
        class XPay extends XPayClient
        {
            public function set_config_value($key, $val)
            {
                $this->config[$key] = $val;
            }
        }
