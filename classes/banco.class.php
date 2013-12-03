<?php
require_once(dirname(__FILE__).'/autoload.php');
protegeArquivo(basename(__FILE__));
abstract class banco{
    //propriedades
    public $servidor        = "localhost";
    public $usuario         = "root";
    public $senha           = "";
    public $nomedobanco     = "aulas";
    public $conexao         = NULL;
    public $dataset         = NULL;
    public $linhasafetadas  = -1;
    
    public function __construct() {
        $this->conecta();
        
    }//construct
    
    public function __destruct() {
        if($this->conexao != NULL){
            mysql_close($this->conexao);
        }
    }//destruct
    
    
    //metodos
    public function conecta(){
        $this->conexao = mysql_connect($this->servidor, $this->usuario, $this->senha, TRUE) 
                or die($this->trataerro(__FILE__, __FUNCTION__, mysql_errno(), mysql_error(), TRUE));
        mysql_select_db($this->nomedobanco) or die($this->trataerro(__FILE__, __FUNCTION__, mysql_errno(), mysql_error(), TRUE));
        mysql_query("SET NAMES 'utf8'");
        mysql_query("SET caracter_set_connection=utf8");
        mysql_query("SET caracter_set_client=utf8");
        mysql_query("SET caracter_set_results=utf8");
    }//fim conecta
    public function inserir($objeto) {
        $sql = "INSERT INTO " .$objeto->tabela. " (";
        for($i=0; $i<count($objeto->campos_valores); $i++){
            $sql .=key($objeto->campos_valores);
            if ($i < count($objeto->campos_valores)-1){
                $sql .= ", ";
            }
            else{
                $sql .= ") ";
            }
            next($objeto->campos_valores);
        }//end for
        reset($objeto->campos_valores);
        $sql .= "VALUES (";
        for($i=0; $i<count($objeto->campos_valores); $i++){
            $sql .= is_numeric($objeto->campos_valores[key($objeto->campos_valores)]) ?
                    $objeto->campos_valores[key($objeto->campos_valores)] :
                    "'".$objeto->campos_valores[key($objeto->campos_valores)]."'";
            if ($i < count($objeto->campos_valores)-1){
                $sql .= ", ";
            }
            else{
                $sql .= ") ";
            }
            next($objeto->campos_valores);
        }//end for
        return $this->executaSQL($sql);
    }//end inserir
    public function atualizar($objeto) {
        $sql = "UPDATE " .$objeto->tabela. " SET ";
        for($i=0; $i<count($objeto->campos_valores); $i++){
            $sql .=key($objeto->campos_valores)." = ";
            $sql .= is_numeric($objeto->campos_valores[key($objeto->campos_valores)]) ?
                    $objeto->campos_valores[key($objeto->campos_valores)] :
                    "'".$objeto->campos_valores[key($objeto->campos_valores)]."'";
            if ($i < (count($objeto->campos_valores)-1)){
                $sql .= ", ";
            }//end if
            else{
                $sql .= " ";
            }//end else
            next($objeto->campos_valores);
        }//end for
        $sql .= "WHERE ".$objeto->campopk."=";
        $sql .= is_numeric($objeto->valorpk) ? $objeto->valorpk : "'".$objeto->valorpk."'";
        return $this->executaSQL($sql);
    }//end update
    public function deletar($objeto) {
        $sql = "DELETE FROM " .$objeto->tabela;
        
        $sql .= " WHERE ".$objeto->campopk."=";
        $sql .= is_numeric($objeto->valorpk) ? $objeto->valorpk : "'".$objeto->valorpk."'";
        return $this->executaSQL($sql);
      }//end delete 
    public function selecionaTudo($objeto) {
        $sql = "SELECT * FROM ".$objeto->tabela;
        if($objeto->extras_select!=NULL){
            $sql .= " ".$objeto->extras_select;
        }
        return $this->executaSQL($sql);
    }
    public function selecionaCampos($objeto) {
        $sql = "SELECT ";
        for($i=0; $i<count($objeto->campos_valores); $i++){
            $sql .=key($objeto->campos_valores);
            if ($i < count($objeto->campos_valores)-1){
                $sql .= ", ";
            }
            else{
                $sql .= " ";
            }
            next($objeto->campos_valores);
        }//end for
        
        $sql .= " FROM ".$objeto->tabela;
        if($objeto->extras_select!=NULL){
            $sql .= " ".$objeto->extras_select;
        }
        return $this->executaSQL($sql);
    }//end selecionaCampos
    

    public function executaSQL($sql=NULL) {
        if($sql != NULL){
            $query = mysql_query($sql) or $this->trataerro(__FILE__, __FUNCTION__);
            $this->linhasafetadas = mysql_affected_rows($this->conexao);
            if(substr(trim(strtolower($sql)),0,6)=='select'){
                $this->dataset = $query;
                return $query;
            }else{
                return $this->linhasafetadas;
            }
        }else{
            $this->trataerro(__FILE__,__FUNCTION__,NULL, 'Comando SQL nao informado na rotina', FALSE);
        }
        
    }//end executa
    public function retornaDados($tipo=NULL) {
        switch (strtolower($tipo)){
            case "array":
                return mysql_fetch_array($this->dataset);
                break;
            case "assoc":
                return mysql_fetch_assoc($this->dataset);
                break;
            case "object":
                return mysql_fetch_object($this->dataset);
                break;
            default :
                return mysql_fetch_object($this->dataset);
            
        }
    }
    
    public function trataerro($arquivo=NULL, $rotina=NULL, $numerro=NULL, $msgerro=NULL, $geraexcep=FALSE) {
        if($arquivo==NULL) {$arquivo = 'Nao Informado';}
        if($rotina==NULL) {$rotina = 'Nao informado';}
        if($numerro==NULL) {$numerro =  mysql_errno ($this->conexao);}
        if($msgerro==NULL) {$msgerro = mysql_error ($this->conexao);} 
        $resultado = 'Ocorreu um erro com os seguintes detalhes:<br />
                <strong>Arquivo:</strong> '.$arquivo. '<br />
                <strong>Rotina:</strong> '.$rotina. '<br />
                <strong>Codigo:</strong> '.$numerro. '<br />
                <strong>Erro:</strong> '.$msgerro;
        if($geraexcep==FALSE){
            echo($resultado);
        }    
        else{
            die($resultado);
        }
    }    
    
}//fim da classe banco

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

