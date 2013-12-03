<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$pathlocal = dirname(__FILE__);
require_once (dirname($pathlocal)."/funcoes.php");
function __autoload($classe){
    $classe = str_replace('..', '', $classe);
    require_once ($pathlocal."/$classe.class.php");
}

