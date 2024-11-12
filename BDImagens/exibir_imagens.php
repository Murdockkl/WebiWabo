<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exibir Imagens</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Postagens</h1>
        <div class="button-container">
            <button><a href="inserir_imagem.php" class="link">Inserir Nova Postagem</a></button>
        </div>
        <div class="gallery">
            <?php
            // Conexão ao banco de dados
            $host = "localhost";
            $usuario = "root";
            $senha = "";
            $banco = "sapos";
            $conn = new mysqli($host, $usuario, $senha, $banco);

            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
            }

            // Lógica de exclusão
            if (isset($_GET['delete'])) {
                $id = $_GET['delete'];
                $sql = "SELECT path_imagem FROM post WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $file_path = $row['path_imagem'];
                    if ($file_path && file_exists($file_path)) {
                        unlink($file_path);
                    }
                }

                $delete_sql = "DELETE FROM post WHERE id = ?";
                $delete_stmt = $conn->prepare($delete_sql);
                $delete_stmt->bind_param("i", $id);
                $delete_stmt->execute();
                $delete_stmt->close();
                echo "<p class='success'>Post excluído com sucesso!</p>";
            }

            // Exibição das postagens
            $sql = "SELECT id, titulo, path_imagem, descricao FROM post ORDER BY id DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='post'>";
                    echo "<h2>" . htmlspecialchars($row['titulo']) . "</h2>";
                    if ($row['path_imagem']) {
                        echo "<img src='" . htmlspecialchars($row['path_imagem']) . "' alt='" . htmlspecialchars($row['titulo']) . "'>";
                    }
                    echo "<p>" . htmlspecialchars($row['descricao']) . "</p>";
                    echo "<a href='?delete=" . $row['id'] . "' onclick='return confirm(\"Deseja realmente excluir esta postagem?\")'>Excluir</a>";
                    echo "</div>";
                }
            } else {
                echo "<p>Nenhuma postagem encontrada.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>