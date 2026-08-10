<?php
session_start();

include "conexao.php";

$mensagem      = '';
$mensagem_tipo = '';
$registro      = null;

/* ── DELETAR ── */
if (isset($_POST['deletar']) && !empty($_POST['busca'])) {

    $busca = trim($_POST['busca']);

    if (ctype_digit($busca)) {
        $id  = (int) $busca;
        $sql = "DELETE FROM loja WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $id);
    } else {
        $sql = "DELETE FROM loja WHERE Produtos = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $busca);
    }

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $mensagem      = '🗑️ Produto deletado com sucesso!';
        $mensagem_tipo = 'sucesso';
    } else {
        $mensagem      = '⚠️ Nenhum registro encontrado para deletar.';
        $mensagem_tipo = 'erro';
    }

    $stmt->close();

/* ── SALVAR EDIÇÃO ── */
} elseif (isset($_POST['salvar']) && !empty($_POST['busca'])) {

    $busca       = trim($_POST['busca']);
    $produtoNome = trim($_POST['produto'] ?? '');
    $produtoVal  = isset($_POST['valor']) ? str_replace(',', '.', trim($_POST['valor'])) : '';

    if ($produtoNome === '' || $produtoVal === '' || !is_numeric($produtoVal)) {
        $mensagem      = '⚠️ Preencha nome e preço corretamente!';
        $mensagem_tipo = 'erro';
        $registro = [
            'busca'    => $busca,
            'id'       => ctype_digit($busca) ? (int) $busca : null,
            'Produtos' => $produtoNome,
            'Valor'    => $produtoVal,
        ];
    } else {

        $valorFloat = (float) $produtoVal;

        if (ctype_digit($busca)) {
            $id  = (int) $busca;
            $sql = "UPDATE loja SET Produtos = ?, Valor = ? WHERE id = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("sdi", $produtoNome, $valorFloat, $id);
        } else {
            $sql = "UPDATE loja SET Produtos = ?, Valor = ? WHERE Produtos = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("sds", $produtoNome, $valorFloat, $busca);
        }

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $mensagem      = '✅ Produto atualizado com sucesso!';
            $mensagem_tipo = 'sucesso';
        } else {
            $mensagem      = '⚠️ Nenhuma alteração detectada.';
            $mensagem_tipo = 'erro';
        }

        $registro = [
            'busca'    => ctype_digit($busca) ? $busca : $produtoNome,
            'id'       => ctype_digit($busca) ? (int) $busca : null,
            'Produtos' => $produtoNome,
            'Valor'    => $produtoVal,
        ];

        $stmt->close();
    }

/* ── BUSCAR REGISTRO ── */
} elseif (isset($_POST['buscar']) && !empty($_POST['busca'])) {

    $busca = trim($_POST['busca']);

    if (ctype_digit($busca)) {
        $id  = (int) $busca;
        $stmt = $conexao->prepare("SELECT id, Produtos, Valor FROM loja WHERE id = ?");
        $stmt->bind_param("i", $id);
    } else {
        $like = "%$busca%";
        $stmt = $conexao->prepare("SELECT id, Produtos, Valor FROM loja WHERE Produtos LIKE ? LIMIT 1");
        $stmt->bind_param("s", $like);
    }

    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $registro = [
            'busca'    => $busca,
            'id'       => $row['id'],
            'Produtos' => $row['Produtos'],
            'Valor'    => $row['Valor'],
        ];
    } else {
        $mensagem      = '⚠️ Nenhum produto encontrado!';
        $mensagem_tipo = 'erro';
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Produto – NexusDigital</title>

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
}

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}

.page-header h2 {
    font-size: 26px;
    color: #3b2a1a;
}

.btn-novo {
    padding: 10px 22px;
    background: #5a3b1c;
    color: white;
    border: none;
    font-family: "Times New Roman", serif;
    font-size: 14px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    box-shadow: 0 4px 0 #3b2a1a;
    transition: .2s;
}

.btn-novo:hover {
    background: #3b2a1a;
    transform: translateY(-2px);
}

.filter-panel {
    background: #fffaf0;
    border: 2px solid #5a3b1c;
    box-shadow: 4px 4px 10px rgba(0,0,0,0.18);
    padding: 22px 28px;
    margin-bottom: 28px;
}

.filter-panel form {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    align-items: flex-end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 160px;
    flex: 1;
}

.filter-group label {
    font-size: 12px;
    font-weight: bold;
    color: #3b2a1a;
    text-transform: uppercase;
}

.filter-group .dica {
    font-size: 10px;
    text-transform: none;
    color: #a07850;
    font-weight: normal;
    margin-left: 4px;
}

.filter-group input {
    padding: 9px 11px;
    border: 1px solid #5a3b1c;
    background: #fffdf7;
    font-family: "Times New Roman", serif;
    font-size: 14px;
    color: #3b2a1a;
}

.filter-actions {
    display: flex;
    gap: 10px;
}

.btn-filtrar {
    padding: 10px 22px;
    background: #5a3b1c;
    color: white;
    border: none;
    font-family: "Times New Roman", serif;
    font-size: 14px;
    cursor: pointer;
}

.btn-filtrar:hover {
    background: #3b2a1a;
}

.btn-limpar {
    padding: 10px 16px;
    background: transparent;
    color: #5a3b1c;
    border: 2px solid #5a3b1c;
    font-family: "Times New Roman", serif;
    font-size: 13px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.btn-deletar {
    padding: 10px 22px;
    background: transparent;
    color: #a03b1c;
    border: 2px solid #a03b1c;
    font-family: "Times New Roman", serif;
    font-size: 14px;
    cursor: pointer;
}

.btn-deletar:hover {
    background: #a03b1c;
    color: white;
}

.mensagem {
    padding: 12px 18px;
    margin-bottom: 20px;
    font-size: 14px;
    border: 2px solid;
}

.mensagem.sucesso {
    background: rgba(90,59,28,0.08);
    border-color: #5a3b1c;
    color: #3b2a1a;
}

.mensagem.erro {
    background: rgba(160,59,28,0.1);
    border-color: #a03b1c;
    color: #a03b1c;
}

.id-badge {
    display: inline-block;
    background: #5a3b1c;
    color: #f5ecd9;
    padding: 3px 11px;
    font-size: 13px;
    font-weight: bold;
    margin-bottom: 18px;
    letter-spacing: 1px;
}

.card-form {
    background: #fffaf0;
    border: 2px solid #5a3b1c;
    box-shadow: 5px 5px 12px rgba(0,0,0,0.22);
    padding: 26px 30px;
}

.card-form h3 {
    color: #3b2a1a;
    font-size: 18px;
    margin-bottom: 18px;
    border-bottom: 1px solid rgba(90,59,28,0.2);
    padding-bottom: 10px;
}

.form-row {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 18px;
}

.form-row .filter-group {
    min-width: 200px;
}

.separador {
    border: none;
    border-top: 1px solid rgba(90,59,28,0.2);
    margin: 22px 0;
}

.empty-state {
    text-align: center;
    padding: 50px 20px;
    color: #5a3b1c;
}

.empty-state .icon {
    font-size: 48px;
    margin-bottom: 12px;
}

.empty-state p {
    font-size: 16px;
    font-style: italic;
}

@media (max-width: 768px) {
    body { flex-direction: column; }
    .sidebar { width: 100%; position: relative; min-height: auto; }
    .main { margin-left: 0; padding: 20px; }
    .filter-panel form, .form-row { flex-direction: column; }
    .filter-group { min-width: 100%; }
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

        <div class="nav-item">
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

        <div class="nav-item active">
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

    <div class="page-header">
        <h2>Editar Produto</h2>
        <a href="Listar.php" class="btn-novo">← Voltar para Listar</a>
    </div>

    <?php if (!empty($mensagem)): ?>
        <div class="mensagem <?= $mensagem_tipo ?>">
            <?= $mensagem ?>
        </div>
    <?php endif; ?>

    <div class="filter-panel">
        <form method="post">
            <div class="filter-group">
                <label>Buscar produto <span class="dica">(ID numérico ou parte do nome)</span></label>
                <input
                    type="text"
                    name="busca"
                    placeholder="Ex: 3 ou camiseta"
                    autocomplete="off"
                    value="<?= isset($registro['busca']) ? htmlspecialchars($registro['busca']) : '' ?>"
                >
            </div>
            <div class="filter-actions">
                <button type="submit" name="buscar" class="btn-filtrar">🔍 Buscar</button>
                <a href="Editar.php" class="btn-limpar">Limpar</a>
            </div>
        </form>
    </div>

    <?php if ($registro === null): ?>

        <div class="card-form">
            <div class="empty-state">
                <div class="icon">✏️</div>
                <p>Busque um produto acima para editar ou deletar.</p>
            </div>
        </div>

    <?php else: ?>

        <div class="card-form">

            <?php if (!empty($registro['id'])): ?>
                <span class="id-badge"># <?= str_pad($registro['id'], 4, '0', STR_PAD_LEFT) ?></span>
            <?php endif; ?>

            <h3>Dados do produto</h3>

            <form method="post">
                <input type="hidden" name="busca" value="<?= htmlspecialchars($registro['busca']) ?>">

                <div class="form-row">
                    <div class="filter-group">
                        <label>Nome do produto</label>
                        <input
                            type="text"
                            name="produto"
                            autocomplete="off"
                            value="<?= htmlspecialchars($registro['Produtos']) ?>"
                        >
                    </div>

                    <div class="filter-group" style="max-width:160px;">
                        <label>Preço (R$)</label>
                        <input
                            type="number"
                            name="valor"
                            step="0.01"
                            min="0"
                            value="<?= htmlspecialchars($registro['Valor']) ?>"
                        >
                    </div>
                </div>

                <div class="filter-actions">
                    <button type="submit" name="salvar" class="btn-filtrar">💾 Salvar Alteração</button>
                </div>
            </form>

            <hr class="separador">

            <form method="post" onsubmit="return confirm('⚠️ Tem certeza que deseja deletar este produto? Essa ação não pode ser desfeita.');">
                <input type="hidden" name="busca" value="<?= htmlspecialchars($registro['busca']) ?>">
                <button type="submit" name="deletar" class="btn-deletar">🗑️ Deletar Produto</button>
            </form>

        </div>

    <?php endif; ?>

</main>

</body>
</html>