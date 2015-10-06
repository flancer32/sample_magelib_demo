<?php
/**
 * Copyright (c) 2015, Praxigento
 * All rights reserved.
 */
namespace Flancer32\Lib\Service\Customer;
/**
 * User: Alex Gusev <alex@flancer64.com>
 */
class Call {
    public function operation($request) {
        $result = $request + 2;
        return $result;
    }
}