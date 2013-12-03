<?php include 'header.php';

if(isset($_GET['m'])){
    $modulo = $_GET['m'];
    }
if(isset($_GET['t'])){
    $tela = $_GET['t'];
    }
?>

<div id="content">
<?php
    if($modulo && $tela){
        loadmodulo($modulo,$tela);
    }else{
        echo '<p>Escolha uma opção de menu ao lado.</p>';
    }
?>
    </div>
<?php include 'sidebar.php'; ?>
<?php include 'footer.php'; ?>



<!--/*require_once 'funcoes.php';
verificaLogin();
echo 'Eu sou o Painel.PHP';?>
<p><a href="?logoff=true">Sair</a></p>
<p><?php
$sessao = new sessao();
$sessao->printALL();
?></p>-->


