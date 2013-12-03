<?php 
require_once 'funcoes.php';
protegeArquivo(basename(__FILE__));
verificaLogin();
$sessao = new sessao;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Painel Administrativo</title>
        <?php
        loadcss('reset');
        loadcss('style');
        loadjs('jquery');
        loadjs('geral');
        ?>
       
    </head>
    <body class="painel">
        <div id="wrapper">
            <div id="header">
                <h1>Painel de Administração</h1>
            </div><!--end header-->
            <div id="wrap-content">