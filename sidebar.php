<?php 
require_once 'funcoes.php';
protegeArquivo(basename(__FILE__));
?>
<div id="sidebar">
    <ul id="accordion">
        <li> <a href="<?php echo BASEURL;?>">Início</a></li>
        <li> <a class="item" href="#">Usuários</a>
            <ul>
                <li> <a href="?m=usuarios&t=incluir">Cadastrar</a>
                <li> <a href="?m=usuarios&t=listar">Exibir</a>
            </ul>
        </li>
        <li> <a class="item" href="#">Permissões</a>
            <ul>
                <li> <a href="?m=usuarios&t=incluir">Cadastrar</a>
                <li> <a href="?m=usuarios&t=listar">Exibir</a>
            </ul>
        </li>
        <li> <a href="?logoff=true">Sair</a></li>
    </ul>
</div><!-- sidebar -->
