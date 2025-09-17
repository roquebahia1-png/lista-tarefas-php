<?php
include "conexao.php";

// Inserir tarefa via AJAX
if (isset($_POST['descricao_ajax']) && trim($_POST['descricao_ajax']) !== "") {
    $descricao = $_POST['descricao_ajax'];
    $stmt = $conn->prepare("INSERT INTO tarefas (descricao) VALUES (?)");
    $stmt->bind_param("s", $descricao);
    $stmt->execute();
    $id = $stmt->insert_id;
    $stmt->close();
    echo json_encode(['id' => $id, 'descricao' => $descricao]);
    exit();
}

// Remover tarefa via AJAX
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM tarefas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => true]);
    exit();
}

// Buscar todas as tarefas
$resultado = $conn->query("SELECT * FROM tarefas");
$tarefas = [];
while($row = $resultado->fetch_assoc()) {
    $tarefas[] = $row;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Tarefas AJAX</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Minha Lista de Tarefas</h1>

    <form id="form-tarefa">
        <input type="text" name="descricao" placeholder="Digite uma tarefa">
        <button type="submit">Adicionar</button>
    </form>

    <p id="contador">Tarefas pendentes: <?php echo count($tarefas); ?></p>

    <ul id="lista-tarefas">
        <?php foreach($tarefas as $tarefa): ?>
            <li data-id="<?php echo $tarefa['id']; ?>" class="pendente">
                <?php echo htmlspecialchars($tarefa['descricao']); ?>
                <span class="delete">[X]</span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<script src="script.js"></script>
</body>
</html>
