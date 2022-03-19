<?php
    include("../bd/conexao.php");
    
    if(isset($_POST["inserir"])){
            $produto = $_POST['produto'];
            $preco = $_POST['preco'];
            $descricao = $_POST['descricao'];
            $sku = $_POST['sku'];
            $descricao = $_POST['descricao'];
            $estoque = $_POST['estoque'];
            $tipo_variacao = $_POST['tipo_variacao'];
            $descricao_variacao = $_POST['descricao_variacao'];
            $foto = $_FILES["foto"];
           
            if($produto == "" || $preco == "" || $descricao == "" || $sku == "" || empty($foto["name"])){
                $alert = "<script> alert('Preencha todos os campos!');";
                $alert .= "window.location.replace('../index.php');</script>";
                echo $alert;
            }
            else{
                if (!empty($foto["name"])) {
            
                    // Largura máxima em pixels
                    $largura = 1500;
                    // Altura máxima em pixels
                    $altura = 1800;
                    // Tamanho máximo do arquivo em bytes
                    $tamanho = 1000000;

                    $error = array();
                    // Verifica se o arquivo é uma imagem
                    if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $foto["type"])){
                        $error[1] = "Isso não é uma imagem.";
                        } 
                
                    // Pega as dimensões da imagem
                    $dimensoes = getimagesize($foto["tmp_name"]);
                
                    // Verifica se a largura da imagem é maior que a largura permitida
                    if($dimensoes[0] > $largura) {
                        $error[2] = "A largura da imagem não deve ultrapassar ".$largura." pixels";
                    }
                    // Verifica se a altura da imagem é maior que a altura permitida
                    if($dimensoes[1] > $altura) {
                        $error[3] = "Altura da imagem não deve ultrapassar ".$altura." pixels";
                    }
                    
                    // Verifica se o tamanho da imagem é maior que o tamanho permitido
                    if($foto["size"] > $tamanho) {
                            $error[4] = "A imagem deve ter no máximo ".$tamanho." bytes";
                    }
                    // Se não houver nenhum erro
                    if (count($error) == 0) {
                    
                        // Pega extensão da imagem
                        preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);
                        // Gera um nome único para a imagem
                        $nome_imagem = md5(uniqid(time())) . "." . $ext[1];
                        // Verifica se existe a pasta e coloca o caminho que a imagem vai ficar
                        $diretorio = "../imagens/".$sku;
                        if(is_dir($diretorio)){
                            $caminho_imagem = $diretorio."/" . $nome_imagem;
                        }else{
                            mkdir($diretorio.'/', 0777, true);
                            $caminho_imagem = $diretorio."/" . $nome_imagem;
                        }
                        // Faz o upload da imagem para seu respectivo caminho
                        move_uploaded_file($foto["tmp_name"], $caminho_imagem);
                    
                        // Insere na tabela produtos
                        $sqlProduto = mysqli_query($conectar,"INSERT INTO produto (produto,sku,foto,descricao) 
                            VALUES ('".$produto."', '".$sku."', '".$nome_imagem."','".$descricao."'); ");
                        //Pesquisa o ultimo ID inserido
                        $pesquisaId = mysqli_query($conectar,"SELECT * FROM `produto` WHERE idProduto = LAST_INSERT_ID();");
                        $exibePesquisa = mysqli_fetch_array($pesquisaId);
                        //Insere na tabela variação
                        $sqlVariacao = mysqli_query($conectar,"INSERT INTO variacao (estoque,preco,tipo_variacao,descricao_variacao,idProduto)
                            VALUES ('".$estoque."','".$preco."','".$tipo_variacao."','".$descricao_variacao."',".$exibePesquisa["idProduto"].")");
                        // Se os dados forem inseridos com sucesso
                        if ($sqlVariacao){
                            $alert = "<script> alert('Inserido com sucesso!');";
                            $alert .= "window.location.replace('../index.php');</script>";
                            echo $alert;
                        }
                    }
                
                    // Se houver mensagens de erro, exibe-as
                    if (count($error) != 0) {
                        foreach ($error as $erro) {
                            echo $erro . "<br />";
                        }
                    }
                }
            }
        } else if(isset($_POST['excluir'])){
            $id = $_GET['idProduto'];
            $sku = $_GET['sku'];
            // Selecionando nome da foto do usuário
            $sql = mysqli_query($conectar,"SELECT foto FROM produto WHERE idProduto = '".$id."'");
            $usuario = mysqli_fetch_object($sql);
            // Removendo produto e variacao do banco de dados
            $sqlProduto = mysqli_query($conectar,"DELETE FROM produto WHERE idProduto = '".$id."'");
            $sqlVariacao = mysqli_query($conectar,"DELETE FROM variacao WHERE idProduto = '".$id."'");
            if($sql){
                $alert = "<script> alert('Excluido com sucesso!');";
                $alert .= "window.location.replace('../index.php');</script>";
                echo $alert;
            }else{
                $alert = "<script> alert('Erro ao deletar foto!');";
                $alert .= "window.location.replace('../index.php');</script>";
                echo $alert;
            }
            // Removendo imagem da pasta imagens/
            unlink("../imagens/".$sku."/".$usuario->foto."");
            
        } else if(isset($_POST['alterar'])){
            $idProduto = $_POST['idProduto'];
            $produto = $_POST['produto'];
            $preco = $_POST['preco'];
            $descricao = $_POST['descricao'];
            $sku = $_POST['sku'];
            $descricao = $_POST['descricao'];
            $estoque = $_POST['estoque'];
            $tipo_variacao = $_POST['tipo_variacao'];
            $descricao_variacao = $_POST['descricao_variacao'];
            $img = $_FILES["img"];

            if($produto == "" || $preco == "" || $descricao == "" || $sku == ""){
                $alert = "<script> alert('Preencha todos os campos!');";
                $alert .= "window.location.replace('../index.php');</script>";
                echo $alert;
            }else if(empty($img["name"])){
                $alert = "<script> alert('Selecione uma imagem para continuar!');";
                $alert .= "window.location.replace('../index.php');</script>";
                echo $alert;
            }
                else{
                if (!empty($img["name"])) {
                    $buscarImagem = mysqli_query($conectar,"SELECT idProduto,foto,sku FROM produto WHERE idProduto = '".$idProduto."'");
                    $selecionarImagem = mysqli_fetch_array($buscarImagem);
                    // Largura máxima em pixels
                    $largura = 1500;
                    // Altura máxima em pixels
                    $altura = 1800;
                    // Tamanho máximo do arquivo em bytes
                    $tamanho = 1000000;

                    $error = array();
                    // Verifica se o arquivo é uma imagem
                    if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $img["type"])){
                        $error[1] = "Isso não é uma imagem.";
                        } 
                
                    // Pega as dimensões da imagem
                    $dimensoes = getimagesize($img["tmp_name"]);
                
                    // Verifica se a largura da imagem é maior que a largura permitida
                    if($dimensoes[0] > $largura) {
                        $error[2] = "A largura da imagem não deve ultrapassar ".$largura." pixels";
                    }
                    // Verifica se a altura da imagem é maior que a altura permitida
                    if($dimensoes[1] > $altura) {
                        $error[3] = "Altura da imagem não deve ultrapassar ".$altura." pixels";
                    }
                    
                    // Verifica se o tamanho da imagem é maior que o tamanho permitido
                    if($img["size"] > $tamanho) {
                            $error[4] = "A imagem deve ter no máximo ".$tamanho." bytes";
                    }
                    // Se não houver nenhum erro
                    if (count($error) == 0) {
                    
                        // Pega extensão da imagem
                        preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $img["name"], $ext);
                        // Gera um nome único para a imagem
                        $nome_imagem = md5(uniqid(time())) . "." . $ext[1];
                        // Verifica se existe a pasta e coloca o caminho que a imagem vai ficar
                        $diretorio = "../imagens/".$sku;
                        if(file_exists($diretorio)){
                            $caminho_imagem = $diretorio."/" . $nome_imagem;
                        }else{
                            mkdir($diretorio.'/', 0777, true);
                            $caminho_imagem = $diretorio."/" . $nome_imagem;
                        }
                        //Apaga a imagem dentro da pasta
                        unlink("../imagens/".$selecionarImagem['sku']."/".$selecionarImagem['foto']."");
                        // Faz o upload da imagem para seu respectivo caminho
                        move_uploaded_file($img["tmp_name"], $caminho_imagem);
                    
                        // Atualiza na tabela Produto
                        $sqlProduto = mysqli_query($conectar,"UPDATE produto SET 
                        produto = '".$produto."', 
                        sku = '".$sku."' , 
                        foto = '".$nome_imagem."' ,
                        descricao = '".$descricao."'  
                        WHERE idProduto = ".$idProduto);
                        //Atualiza na tabela Variacao
                        $sqlVariacao = mysqli_query($conectar,"UPDATE variacao SET 
                        estoque = '".$estoque."',
                        preco = '".$preco."',
                        tipo_variacao = '".$tipo_variacao."',
                        descricao_variacao = '".$descricao_variacao."'
                        WHERE idProduto = ".$idProduto);
                        // Se os dados forem inseridos com sucesso
                        if ($sqlVariacao){
                            $alert = "<script> alert('Atualizado com sucesso!');";
                            $alert .= "window.location.replace('../index.php');</script>";
                            echo $alert;
                        }
                    }
                
                    // Se houver mensagens de erro, exibe-as
                    if (count($error) != 0) {
                        foreach ($error as $erro) {
                            echo $erro . "<br />";
                        }
                    }
                }
            }
        }else if(isset($_POST['inserir_novo'])){
            $alert = "<script>window.location.replace('../index.php');</script>";
            echo $alert;
        }
?>