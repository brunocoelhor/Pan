<?php
require_once (dirname(__FILE__).'/autoload.php');
protegeArquivo(basename(__FILE__));
abstract class base extends banco{
    //propriedades
    public $tabela = "";
    public $campos_valores = array();
    public $campopk = NULL;
    public $valorpk = NULL;
    public $extras_select = "";
    
    //metodos
    public function addCampo($campo=NULL, $valor=NULL) {
        if($campo!=NULL){
            $this->campos_valores[$campo]= $valor;
        }
    }//end addCampo
    public function delcampo($campo=NULL) {
        if(array_key_exists($campo, $this->campos_valores)){
            unset($this->campos_valores[$campo]);
        }
    }//end delcampo
    public function setValor($campo=NULL, $valor=NULL) {
        if($campo!=NULL && $valor!=NULL){
            $this->campos_valores[$campo] = $valor;
        }
    }//endsetValor
    public function getValor($campo=NULL) {
        if($campo != NULL && array_key_exists($campo, $this->campos_valores)){
            return $this->campos_valores[$campo];
        }else{
            return FALSE;
        }
    }//getValor
}//end base class


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

