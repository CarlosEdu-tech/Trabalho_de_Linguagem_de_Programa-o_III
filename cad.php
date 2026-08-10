<?php
session_start();
include "conexao.php";

$mensagem = '';
$erro = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto = trim($_POST['produto'] ?? '');
    $valor   = $_POST['valor'] ?? '';

    if ($produto === '' || $valor === '') {
        $mensagem = 'Preencha todos os campos.';
        $erro = true;
    } else {
        $valor = (float) $valor;

        $stmt = $conexao->prepare(
            "INSERT INTO loja (Produtos, Valor) VALUES (?, ?)"
        );
        $stmt->bind_param("sd", $produto, $valor);

        if ($stmt->execute()) {
            $mensagem = 'Produto cadastrado com sucesso!';
        } else {
            $mensagem = 'Erro ao cadastrar: ' . $stmt->error;
            $erro = true;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastrar Produto – NexusDigital</title>

<style>

*, *::before, *::after {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: #f5ecd9;
    font-family: "Times New Roman", serif;
    min-height: 100vh;
    display: flex;
    overflow-x: hidden;
    position: relative;
}

body::before {
    content: "";
    position: absolute;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(90,59,28,0.06) 10%, transparent 60%);
    animation: moveBg 25s linear infinite;
    pointer-events: none;
    z-index: 0;
}

@keyframes moveBg {
    from { transform: translate(0,0); }
    to   { transform: translate(-60px,-60px); }
}

.sidebar {
    width: 250px;
    min-height: 100vh;
    background: #5a3b1c;
    border-right: 3px solid #3b2a1a;
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    z-index: 100;
    box-shadow: 6px 0 15px rgba(0,0,0,0.25);
}

.logo-area {
    padding: 16px 12px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    background: #3b2a1a;
}

.logo-img {
    width: 220px;
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
}

.welcome {
    padding: 16px;
    font-size: 12px;
    color: #f5ecd9;
}

.welcome strong {
    display: block;
    font-size: 22px;
    color: white;
    margin-top: 4px;
}

nav {
    padding: 10px 0;
    flex: 1;
}

.nav-item {
    margin: 4px 8px;
    border-radius: 10px;
    overflow: hidden;
    transition: .2s;
    border-left: 4px solid transparent;
}

.nav-item:hover {
    background: rgba(255,255,255,0.08);
    transform: translateX(4px);
    border-left-color: #f5ecd9;
}

.nav-item.active {
    background: rgba(245,236,217,0.12);
    border-left-color: #f5ecd9;
}

.nav-item a {
    text-decoration: none;
    display: block;
    padding: 12px 14px;
}

.label {
    color: white;
    font-size: 14px;
    font-weight: bold;
}

.sublabel {
    color: #f5ecd9;
    font-size: 11px;
    margin-top: 2px;
}

.main {
    margin-left: 250px;
    padding: 35px;
    flex: 1;
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.container {
    width: 100%;
    max-width: 550px;
    background: #fffaf0;
    border: 2px solid #5a3b1c;
    padding: 35px;
    box-shadow: 6px 6px 15px rgba(0,0,0,.20);
}

.page-header {
    margin-bottom: 28px;
}

.page-header h2 {
    font-size: 24px;
    color: #3b2a1a;
}

.page-header p {
    font-size: 13px;
    color: #a07850;
    margin-top: 4px;
    font-style: italic;
}

.mensagem {
    padding: 12px 16px;
    margin-bottom: 20px;
    font-size: 14px;
    border-left: 4px solid;
}

.mensagem.sucesso {
    background: #f0faf0;
    border-color: #4a7c4a;
    color: #2d5a2d;
}

.mensagem.erro {
    background: #fdf0f0;
    border-color: #7c4a4a;
    color: #5a2d2d;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: bold;
    color: #3b2a1a;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .5px;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #5a3b1c;
    font-size: 15px;
    font-family: "Times New Roman", serif;
    background: #fffdf7;
    color: #3b2a1a;
    transition: border-color .2s, box-shadow .2s;
}

.form-group input:focus {
    outline: none;
    border-color: #3b2a1a;
    box-shadow: 0 0 0 3px rgba(90,59,28,0.12);
}

.hint {
    font-size: 11px;
    color: #a07850;
    margin-top: 4px;
    font-style: italic;
}

.actions {
    display: flex;
    gap: 10px;
    margin-top: 24px;
}

.btn-salvar {
    flex: 1;
    background: #5a3b1c;
    color: white;
    border: none;
    padding: 13px;
    cursor: pointer;
    font-size: 15px;
    font-family: "Times New Roman", serif;
    box-shadow: 0 4px 0 #3b2a1a;
    transition: .2s;
}

.btn-salvar:hover {
    background: #3b2a1a;
    transform: translateY(-2px);
}

.btn-limpar {
    padding: 13px 20px;
    background: transparent;
    color: #5a3b1c;
    border: 2px solid #5a3b1c;
    font-family: "Times New Roman", serif;
    font-size: 15px;
    cursor: pointer;
    transition: .2s;
}

.btn-limpar:hover {
    background: #5a3b1c;
    color: white;
}

@media (max-width: 768px) {
    body { flex-direction: column; }
    .sidebar { width: 100%; position: relative; min-height: auto; }
    .main { margin-left: 0; padding: 20px; }
}

</style>
</head>

<body>

<aside class="sidebar">

    <div class="logo-area">
        <img
            class="logo-img"
            src="https://clubdofilme.com.br/wp-content/uploads/2022/07/7f6a0ef3-the-boys-1170x658.jpg"
            alt="Logo"
        >
    </div>

    <div class="welcome">
        Seja bem-vindo ao
        <strong>NexusDigital</strong>
    </div>

    <nav>

        <div class="nav-item">
            <a href="Site_principal.php">
                <div class="label">Início</div>
                <div class="sublabel">Página principal</div>
            </a>
        </div>

        <div class="nav-item active">
            <a href="Cad.php">
                <div class="label">Cadastrar Produtos</div>
                <div class="sublabel">Adicionar novos produtos</div>
            </a>
        </div>

        <div class="nav-item">
            <a href="Listar.php">
                <div class="label">Listar Produtos</div>
                <div class="sublabel">Visualizar cadastrados</div>
            </a>
        </div>

        <div class="nav-item">
            <a href="Editar.php">
                <div class="label">Editar Produto</div>
                <div class="sublabel">Alterar informações</div>
            </a>
        </div>

        <div class="nav-item">
            <a href="Login.php">
                <div class="label">Sair</div>
                <div class="sublabel">Encerrar sessão</div>
            </a>
        </div>

    </nav>

</aside>

<main class="main">

    <div class="container">

        <div class="page-header">
            <h2>Cadastrar Produto</h2>
            <p>Preencha os campos abaixo para adicionar um novo produto.</p>
        </div>

        <?php if ($mensagem !== ''): ?>
            <div class="mensagem <?= $erro ? 'erro' : 'sucesso' ?>">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="Cad.php">

            <div class="form-group">
                <label>Nome do Produto</label>
                <input
                    type="text"
                    name="produto"
                    placeholder="Ex: Acer Nitro V15"
                    value="<?= isset($_POST['produto']) && $erro ? htmlspecialchars($_POST['produto']) : '' ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label>Preço (R$)</label>
                <input
                    type="number"
                    name="valor"
                    step="0.01"
                    min="0"
                    placeholder="Ex: 6800.00"
                    value="<?= isset($_POST['valor']) && $erro ? htmlspecialchars($_POST['valor']) : '' ?>"
                    required
                >
                <span class="hint">Use ponto como separador decimal. Ex: 49.90</span>
            </div>

            <div class="actions">
                <button type="submit" class="btn-salvar">Cadastrar Produto</button>
                <button type="reset" class="btn-limpar">Limpar</button>
            </div>

        </form>

    </div>

</main>

</body>
</html>