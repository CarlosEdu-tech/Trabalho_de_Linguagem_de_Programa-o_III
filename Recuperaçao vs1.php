<?php
session_start();
include "conexao.php";

$mensagem = "";

if (isset($_POST['trocar'])) {
    $email         = trim($_POST['email']);
    $novaSenha     = trim($_POST['nova_senha']);
    $confirmaSenha = trim($_POST['confirma_senha']);
    $erro = false;

    // Verifica se o email existe no banco
    $check = $conexao->prepare("SELECT emailcelularcpf FROM tarabalhohugo WHERE emailcelularcpf = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();
    if ($check->num_rows === 0) {
        $mensagem .= "<p class='erro'>E-mail não encontrado.</p>";
        $erro = true;
    }
    $check->close();

    // Valida nova senha
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $novaSenha)) {
        $mensagem .= "<p class='erro'>A senha deve ter no mínimo 8 caracteres, com letra maiúscula, minúscula, número e símbolo.</p>";
        $erro = true;
    }

    // Verifica confirmação
    if ($novaSenha !== $confirmaSenha) {
        $mensagem .= "<p class='erro'>As senhas não coincidem.</p>";
        $erro = true;
    }

    if (!$erro) {
        $novaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $upd = $conexao->prepare("UPDATE tarabalhohugo SET senha = ? WHERE emailcelularcpf = ?");
        $upd->bind_param("ss", $novaHash, $email);
        if ($upd->execute() && $upd->affected_rows > 0) {
            $mensagem = "<p class='sucesso'>Senha alterada com sucesso!>";header("Location: Login vs1.0.php");
        } else {
            $mensagem = "<p class='erro'>Erro ao alterar senha: " . $upd->error . "</p>";
        }
        $upd->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trocar Senha</title>
    <style>
        body {
            background: #f5ecd9;
            font-family: "Times New Roman", serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        body::before {
            content: "";
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(90,59,28,0.06) 10%, transparent 60%);
            animation: moveBg 25s linear infinite;
            pointer-events: none;
        }

        @keyframes moveBg {
            from { transform: translate(0,0); }
            to { transform: translate(-60px, -60px); }
        }

        .container {
            width: 400px;
            margin: 120px auto;
            position: relative;
            z-index: 1;
        }

        form {
            background: #fffaf0;
            padding: 35px;
            border: 3px solid #5a3b1c;
            box-shadow: 4px 4px 10px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
            animation: fadeIn 1s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        form::after {
            content: "";
            position: absolute;
            top: -100%;
            left: -50%;
            width: 200%;
            height: 300%;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,0.15), transparent);
            transform: rotate(25deg);
            animation: shine 6s infinite;
            pointer-events: none;
        }

        @keyframes shine {
            0%   { top: -100%; }
            100% { top: 100%; }
        }

        h2 {
            text-align: center;
            color: #3b2a1a;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            color: #3b2a1a;
            display: block;
            margin-bottom: 4px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 18px;
            border: 1px solid #5a3b1c;
            background: #fffdf7;
            font-family: "Times New Roman", serif;
            font-size: 15px;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border-color: #3b2a1a;
            box-shadow: 0 0 0 2px rgba(90,59,28,0.2);
        }

        button {
            width: 100%;
            padding: 12px;
            background: #5a3b1c;
            color: white;
            border: none;
            font-size: 16px;
            font-family: "Times New Roman", serif;
            cursor: pointer;
        }

        button:hover { background: #3b2a1a; }

        .erro {
            color: #8b0000;
            font-weight: bold;
            text-align: center;
            margin-bottom: 8px;
        }

        .sucesso {
            color: green;
            font-weight: bold;
            text-align: center;
            margin-bottom: 8px;
        }

        .sucesso a {
            color: #3b2a1a;
        }
    </style>
</head>
<body>
<div class="container">
    <form method="post">
        <h2>Trocar Senha</h2>

        <?php echo $mensagem; ?>

        <label>E-mail</label>
        <input type="email" name="email" placeholder="exemplo@email.com"
            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
            autocomplete="off" required>

        <label>Nova Senha</label>
        <input type="password" name="nova_senha" placeholder="Senh@Nova123" required>

        <label>Confirmar Nova Senha</label>
        <input type="password" name="confirma_senha" placeholder="Repita a nova senha" required>

        <button type="submit" name="trocar">Alterar Senha</button>
    </form>
</div>
</body>
</html>