<?php
    include("bd/conexao.php");
?>
<html>
    <header>
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </header>
    <body>

        <!-- FORMULARIO DE PREENCHIMENTO - PRODUTO, SKU, FOTO, PRECO E ESTOQUE -->
        <section id="form">
        <form action="api/index_api.php" method="POST" enctype="multipart/form-data">
            <?php
                //Caso esteja passando o ID pela URL, exibir o layout de edição
                if(!empty($_GET))
                {
                    $selecionarProduto = mysqli_query($conectar,"SELECT * FROM produto AS prod INNER JOIN variacao AS vari ON prod.idProduto = vari.idProduto 
                        WHERE prod.idProduto = ".$_GET["idProduto"].";");
                    $exibeProduto = mysqli_fetch_array($selecionarProduto);
            ?>
                <div class="titulo">
                    Nome do produto:
                    </div>
                    <input type="text" name="produto" id="produto" class="input_padrao" value="<? echo $exibeProduto["produto"] ?>">
                    <div class="titulo">
                        SKU:
                    </div>
                    <input type="text" name="sku" id="sku" class="input_padrao" value="<? echo $exibeProduto["sku"] ?>">
                    <div class="titulo">
                        Foto:
                    </div>
                    <input type="file" name="img" id="img" class="input_padrao" >
                    <div class="titulo">
                        Preço
                    </div>
                    <input type="number" name="preco" id="preco" class="input_padrao" value="<? echo $exibeProduto["preco"] ?>">
                    <div class="titulo">
                        Descrição
                    </div> 
                    <input type="text" name="descricao" id="descricao" class="input_padrao" value="<? echo $exibeProduto["descricao"] ?>">
                    <div class="titulo">
                        Estoque
                    </div> 
                    <input type="text" name="estoque" id="estoque" class="input_padrao" value="<? echo $exibeProduto["estoque"] ?>">
                    <div class="titulo">
                        Variação
                    </div>
                    <select name="tipo_variacao" class="input_padrao">
                        <option value="cor">Cor</option>
                        <option value="tamanho">Tamanho</option>
                        <option value="tamanho e cor">Tamanho e Cor</option>
                    </select>
                    <div class="titulo">
                        Descrição da variação
                    </div> 
                    <input type="text" name="descricao_variacao" id="descricao_variacao" class="input_padrao" value="<? echo $exibeProduto["descricao_variacao"] ?>">
                    <input type="hidden" name="idProduto" value="<?php echo $exibeProduto["idProduto"] ?>">
                    <br>
                    <br>
                    <button type="submit" class="botao_padrao" name="alterar">Alterar</button>
                    <button type="submit" class="botao_padrao" name="inserir_novo">Inserir um novo produto</button>
            <?
                }
                //Caso não esteja passando nada pela URL, exibir o layout de inserção
                else
                {
            ?>
                <div class="titulo">
                Nome do produto:
                </div>
                <input type="text" name="produto" id="produto" class="input_padrao">
                <div class="titulo">
                    SKU:
                </div>
                <input type="text" name="sku" id="sku" class="input_padrao">
                <div class="titulo">
                    Foto:
                </div>
                <input type="file" name="foto" id="foto" class="input_padrao" >
                <div class="titulo">
                    Preço
                </div>
                <input type="number" name="preco" id="preco" class="input_padrao">
                <div class="titulo">
                    Descrição
                </div> 
                <input type="text" name="descricao" id="descricao" class="input_padrao">
                <div class="titulo">
                    Estoque
                </div> 
                <input type="text" name="estoque" id="estoque" class="input_padrao">
                <div class="titulo">
                    Variação
                </div>
                <select name="tipo_variacao" class="input_padrao">
                    <option value="cor">Cor</option>
                    <option value="tamanho">Tamanho</option>
                    <option value="tamanho e cor">Tamanho e Cor</option>
                </select>
                <div class="titulo">
                    Descrição da variação
                </div> 
                <input type="text" name="descricao_variacao" id="descricao_variacao" class="input_padrao">
                <br>
                <br>
                <button type="submit" class="botao_padrao" name="inserir" >Inserir</button>
            </form>
            <?php
                }
            ?>
        </section>
        <!-- TABELA DE EXIBIÇÃO DE PRODUTOS -->
        <section id="table_produtos">
                <!-- Exibição dos produtos -->
                <table border="1">
                    <tr>
                        <td>Foto</td>
                        <td>Produto</td>
                        <td>Preço</td>
                        <td>Estoque</td>
                        <td>Descrição</td>
                        <td>Variação</td>
                        <td>Descrição da variação </td>
                        <td>SKU</td>
                    </tr>
                    <?php
                        $selecionarTudo = "SELECT * FROM produto AS prod INNER JOIN variacao AS vari ON prod.idProduto = vari.idProduto";
                        $executarSelect = mysqli_query($conectar,$selecionarTudo);
                        
                        while($exibeResul = mysqli_fetch_array($executarSelect)){
                    ?>
                    
                        <tr>
                            <td><img src="imagens/<? echo $exibeResul["sku"] ?>/<? echo $exibeResul["foto"] ?>" class="tamanho_img"></td>
                            <td><? echo $exibeResul["produto"] ?></td>
                            <td>R$<? echo number_format($exibeResul["preco"],2,",",".") ?></td>
                            <td><? echo $exibeResul["estoque"] ?></td>
                            <td><? echo $exibeResul["descricao"] ?></td>
                            <td><? echo $exibeResul["tipo_variacao"] ?></td>
                            <td><? echo $exibeResul["descricao_variacao"] ?></td>
                            <td><? echo $exibeResul["sku"] ?></td>
                            <td>
                                <a href="index.php?idProduto=<?php echo $exibeResul["idProduto"] ?>">
                                    <button type="button" class="botao_padrao">Editar</button>
                                </a>
                            </td>
                            <form action="api/index_api.php?idProduto=<?php echo $exibeResul["idProduto"] ?>&sku=<? echo $exibeResul["sku"] ?>" method="POST">
                                <td><button type="submit" class="botao_padrao" name="excluir">Excluir</button></td>
                            </form>
                        </tr>
                    <?php
                        }
                    ?>
                </table>
        </section>
    </body>
</html>