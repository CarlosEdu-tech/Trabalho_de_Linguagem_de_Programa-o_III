<?php

session_start();

include "conexao.php";


/*
===========================================================
    NEXUSDIGITAL
    CADASTRO DE PRODUTOS
===========================================================
*/

$pagina = $_GET['pagina'] ?? 'cadastro';

$mensagem = '';
$erro = false;


/*
===========================================================
    CADASTRO DO PRODUTO
===========================================================
*/

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

        $stmt->bind_param(
            "sd",
            $produto,
            $valor
        );

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

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Cadastrar Produto – NexusDigital</title>


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

    font-family: "Times New Roman", serif;

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

    transition: 0.2s;

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
   CONTEÚDO PRINCIPAL
================================================== */

.main {

    margin-left: 250px;

    padding: 35px;

    flex: 1;

    position: relative;

    z-index: 1;

    display: flex;

    align-items: center;

    justify-content: center;

    min-height: 100vh;

}


/* ==================================================
   CONTAINER DO CADASTRO
================================================== */

.container {

    width: 100%;

    max-width: 550px;

    background: #fffaf0;

    border:
        2px solid #5a3b1c;

    padding: 35px;

    box-shadow:
        6px 6px 15px rgba(0,0,0,.20);

    animation:
        fadeIn 0.6s ease;

}


/* ==================================================
   ANIMAÇÃO
================================================== */

@keyframes fadeIn {

    from {

        opacity: 0;

        transform:
            translateY(25px);

    }

    to {

        opacity: 1;

        transform:
            translateY(0);

    }

}


/* ==================================================
   CABEÇALHO
================================================== */

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


/* ==================================================
   MENSAGEM
================================================== */

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


/* ==================================================
   FORMULÁRIO
================================================== */

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

    border:
        1px solid #5a3b1c;

    font-size: 15px;

    font-family:
        "Times New Roman", serif;

    background: #fffdf7;

    color: #3b2a1a;

    transition:
        border-color .2s,
        box-shadow .2s;

}


.form-group input:focus {

    outline: none;

    border-color:
        #3b2a1a;

    box-shadow:
        0 0 0 3px
        rgba(90,59,28,0.12);

}


.hint {

    font-size: 11px;

    color: #a07850;

    margin-top: 4px;

    font-style: italic;

}


/* ==================================================
   BOTÕES DO FORMULÁRIO
================================================== */

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

    font-family:
        "Times New Roman", serif;

    box-shadow:
        0 4px 0 #3b2a1a;

    transition: .2s;

}


.btn-salvar:hover {

    background:
        #3b2a1a;

    transform:
        translateY(-2px);

}


.btn-limpar {

    padding: 13px 20px;

    background: transparent;

    color: #5a3b1c;

    border:
        2px solid #5a3b1c;

    font-family:
        "Times New Roman", serif;

    font-size: 15px;

    cursor: pointer;

    transition: .2s;

}


.btn-limpar:hover {

    background:
        #5a3b1c;

    color: white;

}


/* ==================================================
   ÁREA DO JOGO
================================================== */

.game-container {

    width: 100%;

    height:
        calc(100vh - 70px);

    min-height: 650px;

    background: #101820;

    border:
        3px solid #5a3b1c;

    border-radius: 15px;

    overflow: hidden;

    box-shadow:
        6px 6px 20px rgba(0,0,0,0.3);

    animation:
        fadeIn 0.6s ease;

    position: relative;

}


/* ==================================================
   IFRAME DO JOGO
================================================== */

.game-frame {

    width: 100%;

    height: 100%;

    min-height: 650px;

    border: none;

    display: block;

    background: #101820;

}


/* ==================================================
   BOTÃO TELA CHEIA
================================================== */

.fullscreen-btn {

    position: absolute;

    top: 15px;

    right: 15px;

    z-index: 20;

    padding: 10px 16px;

    background: #5a3b1c;

    color: #fffaf0;

    border:
        2px solid #f5ecd9;

    border-radius: 8px;

    font-family:
        "Times New Roman", serif;

    font-size: 14px;

    font-weight: bold;

    cursor: pointer;

    transition: 0.2s;

    box-shadow:
        3px 3px 8px rgba(0,0,0,0.35);

}


.fullscreen-btn:hover {

    background:
        #3b2a1a;

    transform:
        scale(1.05);

}


/* ==================================================
   TELA CHEIA
================================================== */

.game-container:fullscreen {

    width: 100vw;

    height: 100vh;

    min-height: 100vh;

    border: none;

    border-radius: 0;

}


.game-container:fullscreen .game-frame {

    width: 100%;

    height: 100%;

    min-height: 100vh;

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

        min-height: auto;

    }


    .container {

        width: 100%;

    }


    .game-container {

        width: 100%;

        min-height: 750px;

        height:
            calc(100vh - 100px);

    }


    .game-frame {

        min-height: 750px;

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
            alt="Logo"
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
             CADASTRAR PRODUTOS
        ================================================== -->

        <div
            class="nav-item
            <?= ($pagina === 'cadastro') ? 'active' : '' ?>"
        >

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
             LISTAR PRODUTOS
        ================================================== -->

        <div class="nav-item">

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
             EDITAR PRODUTO
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
             JOGO DA COBRINHA
        ================================================== -->

        <div
            class="nav-item
            <?= ($pagina === 'jogo') ? 'active' : '' ?>"
        >

            <a href="Cad.php?pagina=jogo">

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
                onclick="sairSistema(event);"
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
     CONTEÚDO PRINCIPAL
===================================================== -->

<main class="main">


<?php if ($pagina === 'jogo'): ?>


    <!-- =================================================
         JOGO DA COBRINHA
    ================================================== -->

    <div
        class="game-container"
        id="gameContainer"
    >


        <!-- BOTÃO TELA CHEIA -->

        <button
            type="button"
            class="fullscreen-btn"
            id="fullscreenBtn"
            onclick="toggleFullscreen()"
        >

            ⛶ Tela cheia

        </button>


        <!-- JOGO -->

        <iframe
            src="jogo_cobrinha_php/index.php"
            class="game-frame"
            id="gameFrame"
            title="Jogo da Cobrinha"
            allowfullscreen
        ></iframe>


    </div>


<?php else: ?>


    <!-- =================================================
         CADASTRO DE PRODUTO
    ================================================== -->

    <div class="container">


        <div class="page-header">

            <h2>
                Cadastrar Produto
            </h2>

            <p>
                Preencha os campos abaixo para adicionar
                um novo produto.
            </p>

        </div>


        <!-- MENSAGEM -->

        <?php if ($mensagem !== ''): ?>

            <div
                class="mensagem
                <?= $erro ? 'erro' : 'sucesso' ?>"
            >

                <?= htmlspecialchars($mensagem) ?>

            </div>

        <?php endif; ?>


        <!-- FORMULÁRIO -->

        <form
            method="POST"
            action="Cad.php"
        >


            <!-- PRODUTO -->

            <div class="form-group">

                <label>
                    Nome do Produto
                </label>

                <input
                    type="text"
                    name="produto"
                    placeholder="Ex: Acer Nitro V15"
                    value="<?=
                        isset($_POST['produto']) && $erro
                        ? htmlspecialchars($_POST['produto'])
                        : ''
                    ?>"
                    required
                >

            </div>


            <!-- VALOR -->

            <div class="form-group">

                <label>
                    Preço (R$)
                </label>

                <input
                    type="number"
                    name="valor"
                    step="0.01"
                    min="0"
                    placeholder="Ex: 6800.00"
                    value="<?=
                        isset($_POST['valor']) && $erro
                        ? htmlspecialchars($_POST['valor'])
                        : ''
                    ?>"
                    required
                >

                <span class="hint">

                    Use ponto como separador decimal.
                    Ex: 49.90

                </span>

            </div>


            <!-- AÇÕES -->

            <div class="actions">

                <button
                    type="submit"
                    class="btn-salvar"
                >

                    Cadastrar Produto

                </button>


                <button
                    type="reset"
                    class="btn-limpar"
                >

                    Limpar

                </button>

            </div>


        </form>


    </div>


<?php endif; ?>


</main>



<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script>


/* =======================================================
   SAIR DO SISTEMA
======================================================= */

function sairSistema(event) {

    event.preventDefault();

    /*
        IMPORTANTE:

        O mesmo destino usado no Site_principal.php.

        replace() substitui a página atual no histórico.
    */

    window.location.replace(
        "Login vs1.0.php"
    );

}


/* =======================================================
   TELA CHEIA
======================================================= */

function toggleFullscreen() {

    const gameContainer =
        document.getElementById(
            "gameContainer"
        );

    const button =
        document.getElementById(
            "fullscreenBtn"
        );


    if (!gameContainer || !button) {

        return;

    }


    if (!document.fullscreenElement) {

        gameContainer
            .requestFullscreen()

            .then(function () {

                button.innerHTML =
                    "⛶ Sair da tela cheia";

            })

            .catch(function (error) {

                console.log(
                    "Erro ao entrar em tela cheia:",
                    error
                );

            });

    }

    else {

        document.exitFullscreen();

    }

}


/* =======================================================
   DETECTAR MUDANÇA DE TELA CHEIA
======================================================= */

document.addEventListener(
    "fullscreenchange",
    function () {

        const button =
            document.getElementById(
                "fullscreenBtn"
            );


        if (!button) {

            return;

        }


        if (document.fullscreenElement) {

            button.innerHTML =
                "⛶ Sair da tela cheia";

        }

        else {

            button.innerHTML =
                "⛶ Tela cheia";

        }

    }
);


</script>


</body>

</html>