<?php
require_once(dirname(dirname(__FILE__))."/funcoes.php");
protegeArquivo(basename(__FILE__));
loadjs('jquery-validate');
loadjs('jquery-validate-messages');
switch ($tela){
    case 'login':
        $sessao = new sessao();
        if($sessao->getNvars()>0 || $sessao->getVar('logado')==TRUE || $sessao->getVar('ip')==$_SERVER['REMOTE_ADDR']){
            redireciona('painel.php');
        }   
        if(isset($_POST['logar'])){
            $user = new usuarios();
            $user->setValor('login', $_POST['usuario']);
            $user->setValor('senha', codificaSenha($_POST['senha']));
            if($user->doLogin($user)){
                redireciona('painel.php');
            }else{
                redireciona('?erro=2');
                
            }
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".userform").validate({
                    rules:{
                        usuario:{required:true, minlength:3},
                        senha:{required:true, rangelength:[4,10]},
                    }
                });
            });
        </script>
        <div id="loginform">
            <form class="userform" id ="userform" method="POST" action="">
                <fieldset>
                    <legend>Acesso restrito, identifique-se</legend>
                    <ul>
                        <li>
                            <label for="usuario">Usuário</label>
                            <input type="text" size="35" name="usuario" value="<?php echo $_POST['usuario']; ?>"/>
                        </li>                
                        <li>
                            <label for="senha">Senha:</label>
                            <input type="text" size="35" name="senha" value="<?php echo $_POST['senha']; ?>"/>
                        </li>
                        <li class="center"><input type="submit" name="logar" value="Login"/></li>
                    </ul>
                    <?php
                    $erro = $_GET['erro'];
                    switch ($erro){
                        case 1:
                            echo '<div class="sucesso">Você fez logof do sistema.</div>';
                            break;
                        case 2:
                            echo '<div class="erro">Dados incorretos ou usuário inativo.</div>';
                            break;
                        case 3:
                            echo '<div class="erro">Faça o login antes de acessar a página solicitada.</div>';
                            break;
                        }
                    
                    ?>
                </fieldset>
            </form>
        </div>
        <?php
        break;
    case 'incluir':
        echo '<h2>Cadastro de Usuários</h2>';
        if(isset($_POST['cadastrar'])){
            $user = new usuarios(array(
               'nome'=>$_POST['nome'],
               'email'=>$_POST['email'],
               'login'=>$_POST['login'],
               'senha'=>  codificaSenha($_POST['senha']),
               'administrador'=>$_POST['adm']=='on'? 's' : 'n',
            ));
            if($user->existeRegistro('login', $_POST['login'])){
                printMSG('Este login já está cadastrado, escolha outro nome de usuário.','erro');
                $duplicado = TRUE;
            }
            if($user->existeRegistro('email', $_POST['email'])){
                printMSG('Este email já está cadastrado, escolha outro endereço.','erro');
                $duplicado = TRUE;
            }
            if ($duplicado!=TRUE){
                $user->inserir ($user);
                if($user->linhasafetadas==1){
                    printMSG('Dados inseridos com sucesso. <a href="'.ADMURL.'?m=usuarios&t=listar">Exibir Cadastros</a>');
                    unset($_POST);
                }
            }
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".userform").validate({
                    rules:{
                        nome:{required:true, minlength:3},
                        email:{required:true, email:true},
                        login:{required:true, minlength:5},
                        senha:{required:true, rangelength:[4,10]},
                        senhaconf:{required:true, equalTo:"#senha"},
                    }
                });
            });
        </script>
        
        <form class="userform" method="POST" action="">
            <fieldset>
                <legend> Informe os dados para cadastro.</legend>
                <ul>
                    <li><label for="nome">Nome:</label>
                    <input type="text" size="50" name="nome" value="<?php echo $_POST['nome']?>"/></li>
                    <li><label for="email">Email:</label>
                    <input type="text" size="50" name="email" value="<?php echo $_POST['email']?>"/></li>
                    <li><label for="login">Login:</label>
                    <input type="text" size="50" name="login" value="<?php echo $_POST['login']?>"/></li>
                    <li><label for="senha">Senha:</label>
                        <input type="password" size="25" name="senha" id="senha" value="<?php echo $_POST['senha']?>"/></li>
                    <li><label for="senhaconf">Repita a Senha:</label>
                    <input type="password" size="25" name="senhaconf" value="<?php echo $_POST['senhaconf']?>"/></li>
                    <li><label for="adm">Administrador:</label>
                        <input type="checkbox" name="adm" <?php if(!isADM()){ echo 'disabled="disabled"'; } if($_POST['adm']) {     echo 'checked="checked"'; }  ?> />Dar controle total ao usuário</li>
                    <li class="center"><input type="button" onclick="location.href='?m=usuarios&t=listar'" value="Cancelar"/>
                        <input type="submit" name="cadastrar" value="Salvar Dados"/> </li>
                
                    </ul>
            </fieldset>
        </form>
        
        
        <?php
        break;
    case 'listar':
        echo '<h2>Usuários Cadastrados</h2>';
        loadcss('data-table',NULL,TRUE);
        loadjs('jquery-datatable');
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#listausers").dataTable({
                    "sScrollY": "400px",
                    "bPaginate": false,
                    "aaSorting": [[0, "desc"]]
                });
            });
        </script>
        <table cellspacing="0" cellpadding="0" border="0" class="display" id="listausers">
            <thead>
                <tr>
                    <th>Nome</th><th>Email</th><th>Login</th><th>Ativo/Adm</th><th>Cadastro</th><th>Ações</th>
                </tr>
            </thead>
            <tbody>
                    <?php
                    //aula 10 para arrumar e traduzir as tabelas 19:30 min 
                    $user = new usuarios();
                    $user->selecionaTudo($user);
                    while ($res = $user->retornaDados()){
                        echo '<tr>';
                        printf('<td>%s</td>', $res->nome);
                        printf('<td>%s</td>', $res->email);
                        printf('<td>%s</td>', $res->login);
                        printf('<td class="center">%s/%s</td>',  strtoupper($res->ativo), strtoupper($res->administrador));
                        printf('<td class="center">%s</td>', date("d/m/y", strtotime($res->datacad)));
                        printf('<td class="center"><a href="?m=usuarios&t=incluir" title="Novo Cadastro">'
                                . '<img src="images/add.png" alt="Novo Cadastro" /></a>'
                                . '<a href="?m=usuarios&t=editar&id=%s" title="Editar">'
                                . '<img src="images/edit.png" alt="Editar" /></a>'
                                . '<a href="?m=usuarios&t=senha&id=%s" title="Mudar Senha">'
                                . '<img src="images/pass.png" alt="Mudar Senha" /></a>'
                                . '<a href="?m=usuarios&t=excluir&id=%s" title="Excluir">'
                                . '<img src="images/delete.png" alt="Excluir" /></a></td>', $res->id, $res->id, $res->id);
                        echo '</tr>';
                    }
                    ?>
            </tbody>
        </table>
        
        <?php
        break;
    case 'editar':
        echo '<h2>Edição de usuários</h2>';
        $sessao = new sessao();
        if(isADM()==TRUE || $sessao->getVar('iduser')==$_GET['id']){
            if(isset($_GET['id'])){
                $id = $_GET['id'];
                if (isset($_POST['editar'])){
                    $user = new usuarios(array(
                       'nome'=>$_POST['nome'],
                       'email'=>$_POST['email'],
                       'ativo'=>($_POST['ativo']=='on') ? 's':'n',
                       'administrador'=>($_POST['adm']=='on') ? 's':'n',  
                    ));
                    $user->valorpk = $id;
                    $user->extras_select = "WHERE id=$id";
                    $user->selecionaTudo($user);
                    $res=$user->retornaDados();
                    if($res->email != $_POST['email']){
                        if($user->existeRegistro('email', $_POST['email'])){
                            printMSG('Este email já existe no sistema, escolha outro endereço.');
                            $duplicado = TRUE;
                        }
                    }
                    if($duplicado!=TRUE){
                        $user->atualizar($user);
                        if($user->linhasafetadas==1){
                            printMSG('Dados alterados com sucesso. <a href"?m=usuarios&t=listar">Exibir Cadastros</a>');
                            unset($_POST);
                        }else{
                            printMSG('Nenhum dado foi alterado. <a href"?m=usuarios&t=listar">Exibir Cadastros</a>','alerta');
                        }
                    }
                }
                
                $userbd = new usuarios();
                $userbd->extras_select = "WHERE id=$id";
                $userbd->selecionaTudo($userbd); 
                $resbd = $userbd->retornaDados();                
            }else{
                printMSG('Usuário não definido, <a href="?m=usuarios&t=listar">escolha um usuário para alterar</a>', 'erro');
            }
        
        ?>
        <script type="text/javascript">
           $(document).ready(function(){
               $(".userform").validate({
                   rules:{
                       nome:{required:true, minlength:3},
                       email:{required:true, email:true},
                   }
               });
           });
       </script>
        
        <form class="userform" method="POST" action="">
            <fieldset>
                <legend> Informe os dados para alteração de cadastro.</legend>
                <ul>
                    <li><label for="nome">Nome:</label>
                    <input type="text" size="50" name="nome" value="<?php if($resbd){ echo $resbd->nome; }?>"/></li>
                    <li><label for="email">Email:</label>
                    <input type="text" size="50" name="email" value="<?php if($resbd){ echo $resbd->email; }?>"/></li>
                    <li><label for="login">Login:</label>
                        <input type="text" size="50"  disabled="disabled" name="login" value="<?php if($resbd){ echo $resbd->login; }?>"/></li>
                    <li><label for="adm">Ativo:</label>
                        <input type="checkbox" name="ativo" <?php if(!isADM()){ echo 'disabled="disabled"'; } 
                        if($resbd->ativo=='s') {     echo 'checked="checked"'; }  ?> />Ativar ou desativar o usuário</li>
                    <li><label for="adm">Administrador:</label>
                        <input type="checkbox" name="adm" <?php if(!isADM()){ echo 'disabled="disabled"'; } 
                        if($resbd->administrador=='s') {     echo 'checked="checked"'; }  ?> />Dar controle total ao usuário</li>
                    <li class="center"><input type="button" onclick="location.href='?m=usuarios&t=listar'" value="Cancelar"/>
                        <input type="submit" name="editar" value="Salvar Alterações"/> </li>
                
                    </ul>
            </fieldset>
        </form>
        
        
        <?php
        }else{
            printMSG('Você não tem permissão para acessa esta página., <a href="#" onclick="history.back()">Voltar</a>', 'erro');
        }
        break;
    default :
        echo '<p>Tela solicitada não existe.</p>';
        break;
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

