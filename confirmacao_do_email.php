<?php
include "conexao.php";

$mensagem = "";

if (isset($_POST['verificar'])) {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "<p class='erro'>Digite um e-mail válido.</p>";
    } else {
        $stmt = $conexao->prepare("SELECT emailcelularcpf FROM tarabalhohugo WHERE emailcelularcpf = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            header("Location: teste.php");
            exit;
        } else {
            $mensagem = "<p class='erro'>E-mail não encontrado.</p>";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar E-mail</title>
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
        <h2>Verificar E-mail</h2>
        <?php echo $mensagem; ?>
        <label>E-mail</label>
        <input name="email" type="email" placeholder="exemplo@email.com" autocomplete="off"
            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
        <button type="submit" name="verificar">Verificar</button>
        <a href="Login vs1.0.php">Na verdade não tenho conta :(</a>
    </form>
</div>
</body>
</html>