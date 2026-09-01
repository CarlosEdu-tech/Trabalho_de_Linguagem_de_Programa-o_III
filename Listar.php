<?php

session_start();

include "conexao.php";


/* =========================================================
   FILTROS
========================================================= */

$busca = isset($_GET['busca'])
    ? trim($_GET['busca'])
    : '';

$ordem = isset($_GET['ordem'])
    ? $_GET['ordem']
    : 'nome_asc';

$precoMin = isset($_GET['preco_min'])
    && $_GET['preco_min'] !== ''
    ? (float) $_GET['preco_min']
    : null;

$precoMax = isset($_GET['preco_max'])
    && $_GET['preco_max'] !== ''
    ? (float) $_GET['preco_max']
    : null;


/* =========================================================
   ORDENAÇÃO
========================================================= */

$orderMap = [

    'nome_asc'   => 'Produtos ASC',

    'nome_desc'  => 'Produtos DESC',

    'preco_asc'  => 'Valor ASC',

    'preco_desc' => 'Valor DESC'

];

$orderSQL = $orderMap[$ordem] ?? 'Produtos ASC';


/* =========================================================
   MONTA QUERY
========================================================= */

$sql = "
    SELECT
        id,
        Produtos,
        Valor
    FROM loja
    WHERE 1=1
";

$params = [];

$types = '';


/* =========================================================
   BUSCA PELO NOME
========================================================= */

if ($busca !== '') {

    $sql .= " AND Produtos LIKE ?";

    $like = '%' . $busca . '%';

    $params[] = &$like;

    $types .= 's';

}


/* =========================================================
   PREÇO MÍNIMO
========================================================= */

if ($precoMin !== null) {

    $sql .= " AND Valor >= ?";

    $params[] = &$precoMin;

    $types .= 'd';

}


/* =========================================================
   PREÇO MÁXIMO
========================================================= */

if ($precoMax !== null) {

    $sql .= " AND Valor <= ?";

    $params[] = &$precoMax;

    $types .= 'd';

}


/* =========================================================
   ORDENAÇÃO
========================================================= */

$sql .= " ORDER BY $orderSQL";


/* =========================================================
   EXECUTA QUERY
========================================================= */

$stmt = $conexao->prepare($sql);

if (!$stmt) {

    die(
        "Erro ao preparar consulta: "
        . $conexao->error
    );

}


if ($types !== '') {

    $bindArgs = array_merge(
        [$types],
        $params
    );

    call_user_func_array(
        [$stmt, 'bind_param'],
        $bindArgs
    );

}


$stmt->execute();

$result = $stmt->get_result();

$produtos = $result->fetch_all(
    MYSQLI_ASSOC
);

$stmt->close();

?>


<!DOCTYPE html>

<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    Listar Produtos – NexusDigital
</title>


<style>

/* ==================================================
   RESET
================================================== */

*,
*::before,
*::after {

    margin: 0;

    padding: 0;

    box-sizing: border-box;

}


/* ==================================================
   BODY
================================================== */

body {

    background: #f5ecd9;

    font-family:
        "Times New Roman",
        serif;

    min-height: 100vh;

    display: flex;

    overflow-x: hidden;

    position: relative;

}


/* ==================================================
   FUNDO ANIMADO
================================================== */

body::before {

    content: "";

    position: absolute;

    width: 200%;

    height: 200%;

    background:
        radial-gradient(
            circle,
            rgba(90,59,28,0.06) 10%,
            transparent 60%
        );

    animation:
        moveBg 25s linear infinite;

    pointer-events: none;

    z-index: 0;

}


@keyframes moveBg {

    from {

        transform:
            translate(0,0);

    }

    to {

        transform:
            translate(-60px,-60px);

    }

}


/* ==================================================
   SIDEBAR
================================================== */

.sidebar {

    width: 250px;

    min-height: 100vh;

    background: #5a3b1c;

    border-right:
        3px solid #3b2a1a;

    display: flex;

    flex-direction: column;

    position: fixed;

    top: 0;

    left: 0;

    bottom: 0;

    z-index: 100;

    box-shadow:
        6px 0 15px rgba(0,0,0,0.25);

}


/* ==================================================
   LOGO
================================================== */

.logo-area {

    padding: 16px 12px;

    border-bottom:
        1px solid rgba(255,255,255,0.1);

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


/* ==================================================
   WELCOME
================================================== */

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


/* ==================================================
   MENU
================================================== */

nav {

    padding: 10px 0;

    flex: 1;

}


.nav-item {

    margin: 4px 8px;

    border-radius: 10px;

    overflow: hidden;

    transition: .2s;

    border-left:
        4px solid transparent;

}


.nav-item:hover {

    background:
        rgba(255,255,255,0.08);

    transform:
        translateX(4px);

    border-left-color:
        #f5ecd9;

}


.nav-item.active {

    background:
        rgba(245,236,217,0.12);

    border-left-color:
        #f5ecd9;

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


/* ==================================================
   CONTEÚDO
================================================== */

.main {

    margin-left: 250px;

    padding: 35px;

    flex: 1;

    position: relative;

    z-index: 1;

}


/* ==================================================
   CABEÇALHO
================================================== */

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


/* ==================================================
   BOTÃO NOVO
================================================== */

.btn-novo {

    padding: 10px 22px;

    background: #5a3b1c;

    color: white;

    border: none;

    font-family:
        "Times New Roman",
        serif;

    font-size: 14px;

    cursor: pointer;

    text-decoration: none;

    display: inline-block;

    box-shadow:
        0 4px 0 #3b2a1a;

    transition: .2s;

}


.btn-novo:hover {

    background: #3b2a1a;

    transform:
        translateY(-2px);

}


/* ==================================================
   FILTROS
================================================== */

.filter-panel {

    background: #fffaf0;

    border:
        2px solid #5a3b1c;

    box-shadow:
        4px 4px 10px rgba(0,0,0,0.18);

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


.filter-group input,
.filter-group select {

    padding: 9px 11px;

    border:
        1px solid #5a3b1c;

    background: #fffdf7;

    font-family:
        "Times New Roman",
        serif;

    font-size: 14px;

    color: #3b2a1a;

}


/* ==================================================
   BOTÕES DOS FILTROS
================================================== */

.filter-actions {

    display: flex;

    gap: 10px;

}


.btn-filtrar {

    padding: 10px 22px;

    background: #5a3b1c;

    color: white;

    border: none;

    font-family:
        "Times New Roman",
        serif;

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

    border:
        2px solid #5a3b1c;

    font-family:
        "Times New Roman",
        serif;

    font-size: 13px;

    text-decoration: none;

}


.btn-limpar:hover {

    background: #5a3b1c;

    color: white;

}


/* ==================================================
   RESULTADO
================================================== */

.result-info {

    font-size: 13px;

    color: #5a3b1c;

    margin-bottom: 12px;

    font-style: italic;

}


/* ==================================================
   TABELA
================================================== */

.table-wrap {

    background: #fffaf0;

    border:
        2px solid #5a3b1c;

    box-shadow:
        5px 5px 12px rgba(0,0,0,0.22);

    overflow: hidden;

}


table {

    width: 100%;

    border-collapse: collapse;

}


thead tr {

    background: #5a3b1c;

    color: white;

}


thead th {

    padding: 14px 18px;

    text-align: left;

    font-size: 13px;

    text-transform: uppercase;

}


thead th.center {

    text-align: center;

}


tbody tr {

    border-bottom:
        1px solid rgba(90,59,28,0.12);

}


tbody tr:nth-child(even) {

    background:
        rgba(245,236,217,0.45);

}


tbody tr:hover {

    background:
        rgba(90,59,28,0.07);

}


td {

    padding: 13px 18px;

    font-size: 14px;

    color: #3b2a1a;

}


td.center {

    text-align: center;

}


/* ==================================================
   PREÇO
================================================== */

.badge-preco {

    display: inline-block;

    background: #5a3b1c;

    color: #f5ecd9;

    padding: 3px 11px;

    font-size: 13px;

    font-weight: bold;

}


/* ==================================================
   NENHUM PRODUTO
================================================== */

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


/* ==================================================
   RESPONSIVO
================================================== */

@media (max-width: 768px) {

    body {

        flex-direction: column;

    }


    .sidebar {

        width: 100%;

        position: relative;

        min-height: auto;

    }


    .main {

        margin-left: 0;

        padding: 20px;

    }


    .filter-panel form {

        flex-direction: column;

    }


    .filter-group {

        min-width: 100%;

        max-width: 100% !important;

    }


    .filter-actions {

        width: 100%;

    }


    .filter-actions button,
    .filter-actions a {

        flex: 1;

        text-align: center;

    }


    .table-wrap {

        overflow-x: auto;

    }


    table {

        min-width: 500px;

    }

}

</style>

</head>


<body>


<!-- =====================================================
     MENU LATERAL
===================================================== -->

<aside class="sidebar">


    <!-- LOGO -->

    <div class="logo-area">

        <img
            class="logo-img"
            src="https://clubdofilme.com.br/wp-content/uploads/2022/07/7f6a0ef3-the-boys-1170x658.jpg"
            alt="Logo NexusDigital"
        >

    </div>


    <!-- BOAS-VINDAS -->

    <div class="welcome">

        Seja bem-vindo ao

        <strong>
            NexusDigital
        </strong>

    </div>


    <!-- MENU -->

    <nav>


        <!-- =================================================
             INÍCIO
        ================================================== -->

        <div class="nav-item">

            <a href="Site_principal.php">

                <div class="label">
                    Início
                </div>

                <div class="sublabel">
                    Página principal
                </div>

            </a>

        </div>


        <!-- =================================================
             CADASTRAR
        ================================================== -->

        <div class="nav-item">

            <a href="Cad.php">

                <div class="label">
                    Cadastrar Produtos
                </div>

                <div class="sublabel">
                    Adicionar novos produtos
                </div>

            </a>

        </div>


        <!-- =================================================
             LISTAR
        ================================================== -->

        <div class="nav-item active">

            <a href="Listar.php">

                <div class="label">
                    Listar Produtos
                </div>

                <div class="sublabel">
                    Visualizar cadastrados
                </div>

            </a>

        </div>


        <!-- =================================================
             EDITAR
        ================================================== -->

        <div class="nav-item">

            <a href="Editar.php">

                <div class="label">
                    Editar Produto
                </div>

                <div class="sublabel">
                    Alterar informações
                </div>

            </a>

        </div>


        <!-- =================================================
             JOGO
        ================================================== -->

        <div class="nav-item">

            <a href="Site_principal.php?pagina=jogo">

                <div class="label">
                    🎮 Jogar
                </div>

                <div class="sublabel">
                    Jogo da Cobrinha
                </div>

            </a>

        </div>


        <!-- =================================================
             SAIR
        ================================================== -->

        <div class="nav-item">

            <a
                href="Login vs1.0.php"
                id="btnSair"
            >

                <div class="label">
                    Sair
                </div>

                <div class="sublabel">
                    Encerrar sessão
                </div>

            </a>

        </div>


    </nav>

</aside>



<!-- =====================================================
     CONTEÚDO
===================================================== -->

<main class="main">


    <!-- =================================================
         CABEÇALHO
    ================================================== -->

    <div class="page-header">

        <h2>
            Produtos Cadastrados
        </h2>


        <a
            href="Cad.php"
            class="btn-novo"
        >

            + Novo Produto

        </a>

    </div>



    <!-- =================================================
         FILTROS
    ================================================== -->

    <div class="filter-panel">


        <form
            method="get"
            action="Listar.php"
        >


            <!-- BUSCA -->

            <div class="filter-group">

                <label>
                    Buscar produto
                </label>

                <input
                    type="text"
                    name="busca"
                    placeholder="Ex: camiseta"
                    value="<?= htmlspecialchars(
                        $busca,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    autocomplete="off"
                >

            </div>



            <!-- PREÇO MÍNIMO -->

            <div
                class="filter-group"
                style="max-width:130px;"
            >

                <label>
                    Preço mínimo
                </label>

                <input
                    type="number"
                    name="preco_min"
                    step="0.01"
                    min="0"
                    value="<?= $precoMin !== null
                        ? $precoMin
                        : '' ?>"
                >

            </div>



            <!-- PREÇO MÁXIMO -->

            <div
                class="filter-group"
                style="max-width:130px;"
            >

                <label>
                    Preço máximo
                </label>

                <input
                    type="number"
                    name="preco_max"
                    step="0.01"
                    min="0"
                    value="<?= $precoMax !== null
                        ? $precoMax
                        : '' ?>"
                >

            </div>



            <!-- ORDENAÇÃO -->

            <div
                class="filter-group"
                style="max-width:190px;"
            >

                <label>
                    Ordenar por
                </label>

                <select name="ordem">

                    <option
                        value="nome_asc"
                        <?= $ordem === 'nome_asc'
                            ? 'selected'
                            : '' ?>
                    >

                        Nome (A → Z)

                    </option>


                    <option
                        value="nome_desc"
                        <?= $ordem === 'nome_desc'
                            ? 'selected'
                            : '' ?>
                    >

                        Nome (Z → A)

                    </option>


                    <option
                        value="preco_asc"
                        <?= $ordem === 'preco_asc'
                            ? 'selected'
                            : '' ?>
                    >

                        Preço (menor → maior)

                    </option>


                    <option
                        value="preco_desc"
                        <?= $ordem === 'preco_desc'
                            ? 'selected'
                            : '' ?>
                    >

                        Preço (maior → menor)

                    </option>

                </select>

            </div>



            <!-- AÇÕES -->

            <div class="filter-actions">

                <button
                    type="submit"
                    class="btn-filtrar"
                >

                    Filtrar

                </button>


                <a
                    href="Listar.php"
                    class="btn-limpar"
                >

                    Limpar

                </a>

            </div>


        </form>

    </div>



    <!-- =================================================
         INFORMAÇÃO DOS RESULTADOS
    ================================================== -->

    <p class="result-info">


        <?php if (
            $busca !== ''
            || $precoMin !== null
            || $precoMax !== null
        ): ?>


            Filtro ativo —

            <strong>
                <?= count($produtos) ?>
            </strong>


            <?= count($produtos) === 1
                ? 'produto encontrado'
                : 'produtos encontrados'
            ?>


        <?php else: ?>


            Total:

            <strong>
                <?= count($produtos) ?>
            </strong>


            <?= count($produtos) === 1
                ? 'produto cadastrado'
                : 'produtos cadastrados'
            ?>


        <?php endif; ?>


    </p>



    <!-- =================================================
         TABELA
    ================================================== -->

    <div class="table-wrap">


        <?php if (empty($produtos)): ?>


            <div class="empty-state">


                <div class="icon">
                    📦
                </div>


                <p>
                    Nenhum produto encontrado.
                </p>


            </div>


        <?php else: ?>


            <table>


                <thead>

                    <tr>

                        <th>
                            #
                        </th>

                        <th>
                            Produto
                        </th>

                        <th class="center">
                            Preço (R$)
                        </th>

                    </tr>

                </thead>


                <tbody>


                    <?php foreach (
                        $produtos as $i => $p
                    ): ?>


                    <tr>


                        <td
                            style="
                                color:#a07850;
                                font-size:12px;
                            "
                        >

                            <?= $i + 1 ?>

                        </td>


                        <td>

                            <?= htmlspecialchars(
                                $p['Produtos'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </td>


                        <td class="center">

                            <span class="badge-preco">

                                R$

                                <?= number_format(
                                    (float)$p['Valor'],
                                    2,
                                    ',',
                                    '.'
                                ) ?>

                            </span>

                        </td>


                    </tr>


                    <?php endforeach; ?>


                </tbody>


            </table>


        <?php endif; ?>


    </div>


</main>



<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script>


/* =======================================================
   BOTÃO SAIR
=======================================================

   IMPORTANTE:

   O replace() é usado SOMENTE no botão Sair.

   Assim:

   Listar.php
       ↓
   Sair
       ↓
   Login vs1.0.php

   O Listar.php é removido da posição atual
   do histórico.

   A navegação normal entre as outras páginas
   continua funcionando.

======================================================= */


const btnSair =
    document.getElementById("btnSair");


if (btnSair) {

    btnSair.addEventListener(
        "click",
        function (event) {

            event.preventDefault();


            /*
             * Vai para o mesmo Login usado
             * pelo restante do sistema.
             */

            window.location.replace(
                "Login vs1.0.php"
            );

        }
    );

}


</script>


</body>

</html>