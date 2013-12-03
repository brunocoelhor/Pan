<?php
require_once(dirname(__FILE__).'/autoload.php');
protegeArquivo(basename(__FILE__));
class sessao{
    protected $id;
    protected $nvars;
    
    public function __construct($inicia=TRUE) {
        if($inicia==TRUE){
            $this->start();
        }
    }
    
    public function start() {
        session_start();
        $this->id = session_id();
        $this-> setNvars();
    }
    
    public function setNvars() {
        $this->nvars = sizeof($_SESSION);
    }
    
    public function getNvars() {
        return $this->nvars;
    }
    
    public function setVar($var, $valor) {
        $_SESSION[$var] = $valor;
        $this->setNvars();
    }
    
    public function unsetVar($var) {
        unset($_SESSION[$var]);
        $this->setNvars();        
    }
    
    public function getVar($var) {
         if(isset($_SESSION[$var])){
            return $_SESSION[$var];
         }else{
             return NULL;
         }
    }
    
    public function destroy($inicia=FALSE) {
        session_unset();
        session_destroy();
        $this->setNvars();
        if ($inicia==TRUE) {
            $this->start();
        }
    }
    
    public function printALL() {
        foreach ($_SESSION as $k => $v){
            printf("%s = %s<br />", $k, $v);
        }
    }
    
   
    
    
    
    
    
    
    
    
    
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

