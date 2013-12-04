<?php
require_once(dirname(__FILE__).'/autoload.php');
protegeArquivo(basename(__FILE__));
class services extends base{
    public function __construct($campos=array()) {
        parent::__construct();
        $this->tabela ="services";
        if(sizeof($campos)<=0){
            $this->campos_valores = array(
                "name" => NULL,
                "description" => NULL,
                "datecreate" => NULL,
                "dateupdate" => NULL,
                "price" => NULL,
                "image" => NULL,
                "status" => NULL
            );
        }else{
            $this->campos_valores = $campos;
        }
        $this->campopk = "id";
    }//end construct

    
    function existeRegistro($campo=NULL,$valor=NULL) {
        if($campo!=NULL && $valor!=NULL){
            is_numeric($valor) ? $valor =$valor : $valor = "'".$valor."'";
            $this->extras_select = "WHERE $campo=$valor";
            $this->selecionaTudo($this);
            if($this->linhasafetadas>0){
                return TRUE;
            }else{
                return FALSE;
            }
        }  else {
            $this->trataerro(__FILE__,__FUNCTION__,NULL,'Faltam parâmetros para executar a função',TRUE);
        }
    }
}
