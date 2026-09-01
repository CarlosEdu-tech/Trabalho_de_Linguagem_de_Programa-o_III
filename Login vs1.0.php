<?php
session_start();
include "conexao.php";

$mensagem = "";

if (isset($_POST['entrar'])) {
    $emailcelular = trim($_POST['emailcelularcpf']);
    $senha = trim($_POST['senha']);
    $stmt = $conexao->prepare("SELECT emailcelularcpf, senha FROM tarabalhohugo WHERE emailcelularcpf = ?");
    $stmt->bind_param("s", $emailcelular);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_login'] = $usuario['emailcelularcpf'];
            $stmt->close();
            header("Location: Site_principal.php");
            exit;
        } else {
            $mensagem = "<p class='erro'>Senha incorreta.</p>";
        }
    } else {
        $mensagem = "<p class='erro'>Usuário não encontrado.</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Login</title>
<style>

body {
    background: #f5ecd9;
    font-family: "Times New Roman", serif;
}

.container {
    width: 380px;
    margin: 120px auto;
}

form {
    background: #fffaf0;
    padding: 35px;
    border: 3px solid #5a3b1c;
    box-shadow: 4px 4px 10px rgba(0,0,0,0.3);
}

h2 {
    text-align: center;
    color: #3b2a1a;
    margin-bottom: 25px;
}

label {
    font-weight: bold;
    color: #3b2a1a;
}

input {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 18px;
    border: 1px solid #5a3b1c;
    background: #fffdf7;
    font-family: "Times New Roman", serif;
    box-sizing: border-box;
}

button {
    width: 100%;
    padding: 12px;
    background: #5a3b1c;
    color: white;
    border: none;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    background: #3b2a1a;
}

.erro {
    color: #8b0000;
    font-weight: bold;
    text-align: center;
    margin-bottom: 10px;
}

</style>
</head>
<body>

<div class="container">
    <form method="post">
        <h2>Login</h2>
        <?php echo $mensagem; ?>
        <label>E-mail, Celular ou CPF</label>
        <input name="emailcelularcpf" placeholder="exemplo@email.com"  type="text" autocomplete="off" required>
        <label>Senha</label>
        <input name="senha" placeholder="Senh@123" type="password" required>
        <button type="submit" name="entrar">Entrar</button>
    <a href="Cadastro vs1.0.php">NÃO TENHO CONTA</a><br>
        <a href="confirmacao_do_email.php">Esqueci minha senha</a><br>
    </form>
</div>

</body>
</html>
