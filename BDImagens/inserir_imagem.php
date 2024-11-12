<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserir Nova Postagem</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Inserir Nova Postagem</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo" required>
            
            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" rows="4"></textarea>
            
            <label for="imagem">Imagem:</label>
            <input type="file" name="imagem" id="imagem">
            
            <input type="submit" name="submit" value="Postar">
        </form>
        
        <div class="button-container">
            <button><a href="exibir_imagens.php" class="link">Ver Postagens</a></button>
        </div>

        <?php
        if (isset($_POST['submit'])) {
            // Conexão ao banco de dados
            $host = "localhost";
            $usuario = "root";
            $senha = "";
            $banco = "sapos";
            $conn = new mysqli($host, $usuario, $senha, $banco);

            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
            }

            // Dados do formulário
            $titulo = $_POST['titulo'];
            $descricao = $_POST['descricao'];
            $path_imagem = '';

            // Verifica e processa o upload da imagem
            if (!empty($_FILES['imagem']['name'])) {
                $diretorio = "uploads/";
                if (!is_dir($diretorio)) {
                    mkdir($diretorio, 0755, true);
                }
                $path_imagem = $diretorio . basename($_FILES['imagem']['name']);
                move_uploaded_file($_FILES['imagem']['tmp_name'], $path_imagem);
            }

            // Inserção dos dados no banco
            $stmt = $conn->prepare("INSERT INTO post (titulo, descricao, path_imagem) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $titulo, $descricao, $path_imagem);
            $stmt->execute();
            $stmt->close();

            echo "<p class='success'>Postagem adicionada com sucesso!</p>";
            $conn->close();
        }
        ?>
    </div>
</body>
</html>