<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Justfun\Core\Interfaces;

/**
 *
 * @author Pironato Francesco pironato.f@gmail.com
 */
interface statusInterface {
    const STATUS_OK = 'ok';
    const STATUS_ERR = 'err';

    function getStatus();

    function setStatus($status);

    function getErrors();
}
