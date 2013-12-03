<?php 
require_once 'funcoes.php';
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
    <body>
        <?php
        loadmodulo('usuarios','login');
        ?>
    </body>
</html>
