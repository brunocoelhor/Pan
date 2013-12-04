<?php
require_once(dirname(dirname(__FILE__))."/funcoes.php");
protegeArquivo(basename(__FILE__));
loadjs('jquery-validate');
loadjs('jquery-validate-messages');
switch ($tela){
    case 'incluir':
        echo '<h2>Cadastro de Serviços</h2>';
        if(isset($_POST['cadastrar'])){
            $user = new services(array(
               'name'=>$_POST['name'],
               'description'=>$_POST['description'],
               'dateupdate'=>$_POST['dateupdate'],
               'price'=>$_POST['price'],
               'image'=>$_POST['image'],
               'status'=>$_POST['status'],
                                
            ));
            if($user->existeRegistro('name', $_POST['name'])){
                printMSG('Este serviço já está cadastrado, escolha outro nome de usuário.','erro');
                $duplicado = TRUE;
            }
            if ($duplicado!=TRUE){
                $user->inserir ($user);
                if($user->linhasafetadas==1){
                    printMSG('Dados inseridos com sucesso. <a href="'.ADMURL.'?m=services&t=listar">Exibir Cadastros</a>');
                    unset($_POST);
                }
            }
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".userform").validate({
                    rules:{
                        name:{required:true, minlength:3},
                        }
                });
            });
        </script>
        
        <form class="userform" method="POST" action="">
            <fieldset>
                <legend> Informe os dados para cadastro.</legend>
                <ul>
                    <li><label for="name">Nome:</label>
                    <input type="text" size="50" name="name" autofocus="autofocus" value="<?php echo $_POST['name']?>"/></li>
                    <li><label for="description">Descrição:</label>
                    <input type="text" size="50" name="description" value="<?php echo $_POST['description']?>"/></li>
                    <li><label for="price">Preço:</label>
                    <input type="text" size="50" name="price" value="<?php echo $_POST['price']?>"/></li>
                    
                    <li><label for="image">Imagem:</label>
                    <input type="text" size="50" name="image" value="<?php echo $_POST['image']?>"/></li>
                    
                    <li><label for="status">Ativo:</label>
                        <input type="checkbox" name="status" <?php if(!isADM()){ echo 'disabled="disabled"'; } 
                        if($resbd->status=='s') {     echo 'checked="checked"'; }  ?> />Ativar ou desativar o serviço</li>
                    <li class="center"><input type="button" onclick="location.href='?m=services&t=listar'" value="Cancelar"/>
                        <input type="submit" name="cadastrar" value="Salvar Dados"/> </li>
                
                    </ul>
            </fieldset>
        </form>
        
        
        <?php
        break;
    
    default :
        echo '<p>Tela solicitada não existe.</p>';
        break;
}
