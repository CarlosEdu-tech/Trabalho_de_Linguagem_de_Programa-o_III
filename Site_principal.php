<?php

/*
===========================================================
    NEXUSDIGITAL
    SITE PRINCIPAL
===========================================================
*/

$pagina = $_GET['pagina'] ?? 'inicio';

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>NexusDigital - Iniciando</title>


<style>

/* ==================================================
   RESET
================================================== */

*,
*::before,
*::after {

    margin:0;

    padding:0;

    box-sizing:border-box;

}


/* ==================================================
   BODY
================================================== */

body {

    background:#f5ecd9;

    font-family:"Times New Roman", serif;

    min-height:100vh;

    display:flex;

    overflow-x:hidden;

    position:relative;

}


/* ==================================================
   FUNDO ANIMADO
================================================== */

body::before {

    content:"";

    position:absolute;

    width:200%;

    height:200%;

    background:

        radial-gradient(
            circle,
            rgba(90,59,28,0.06) 10%,
            transparent 60%
        );

    animation:
        moveBg 25s linear infinite;

    pointer-events:none;

    z-index:0;

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

    width:250px;

    min-height:100vh;

    background:#5a3b1c;

    border-right:
        3px solid #3b2a1a;

    display:flex;

    flex-direction:column;

    position:fixed;

    top:0;

    left:0;

    bottom:0;

    z-index:100;

    box-shadow:
        6px 0 15px rgba(0,0,0,0.25);

}


/* ==================================================
   LOGO
================================================== */

.logo-area {

    padding:16px 12px;

    border-bottom:
        1px solid rgba(255,255,255,0.1);

    display:flex;

    align-items:center;

    justify-content:center;

    background:#3b2a1a;

}


.logo-img {

    width:220px;

    height:120px;

    object-fit:cover;

    border-radius:8px;

}


/* ==================================================
   WELCOME
================================================== */

.welcome {

    padding:16px;

    font-size:12px;

    color:#f5ecd9;

}


.welcome strong {

    display:block;

    font-size:22px;

    color:white;

    margin-top:4px;

}


/* ==================================================
   MENU
================================================== */

nav {

    padding:10px 0;

    flex:1;

}


.nav-item {

    margin:4px 8px;

    border-radius:10px;

    overflow:hidden;

    transition:0.2s;

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

    text-decoration:none;

    display:block;

    padding:12px 14px;

}


.label {

    color:white;

    font-size:14px;

    font-weight:bold;

}


.sublabel {

    color:#f5ecd9;

    font-size:11px;

    margin-top:2px;

}


/* ==================================================
   CONTEÚDO PRINCIPAL
================================================== */

.main {

    margin-left:250px;

    padding:35px;

    flex:1;

    position:relative;

    z-index:1;

    display:flex;

    align-items:center;

    justify-content:center;

    min-height:100vh;

}


/* ==================================================
   BOX INICIAL
================================================== */

.box {

    background:#fffaf0;

    padding:40px;

    border:
        3px solid #5a3b1c;

    box-shadow:
        6px 6px 15px rgba(0,0,0,0.3);

    width:450px;

    text-align:center;

    position:relative;

    overflow:hidden;

    animation:
        fadeIn 1.5s ease;

}


@keyframes fadeIn {

    from {

        opacity:0;

        transform:
            translateY(25px);

    }

    to {

        opacity:1;

        transform:
            translateY(0);

    }

}


/* ==================================================
   TÍTULO
================================================== */

h1 {

    color:#3b2a1a;

    margin-bottom:10px;

    animation:
        pulse 2.5s infinite;

}


@keyframes pulse {

    0%,
    100% {

        transform:
            scale(1);

    }

    50% {

        transform:
            scale(1.03);

    }

}


p {

    color:#5a3b1c;

    margin-bottom:20px;

}


/* ==================================================
   LOADER
================================================== */

.loader {

    width:100%;

    height:10px;

    border:
        2px solid #5a3b1c;

    margin-bottom:25px;

    background:#f5ecd9;

    overflow:hidden;

}


.bar {

    height:100%;

    width:0%;

    background:#5a3b1c;

    animation:
        load 3s ease forwards;

}


@keyframes load {

    from {

        width:0%;

    }

    to {

        width:100%;

    }

}


/* ==================================================
   INFORMAÇÕES
================================================== */

.info {

    text-align:left;

    font-size:14px;

    color:#3b2a1a;

    margin-top:10px;

    line-height:1.5;

    background:
        rgba(245,236,217,0.5);

    padding:10px;

    border:
        1px solid #5a3b1c;

}


.info strong {

    color:#2a1d12;

}


/* ==================================================
   BOTÃO DO RELATÓRIO
================================================== */

.report-button {

    display:block;

    margin-top:20px;

    padding:13px 18px;

    background:#5a3b1c;

    color:#fffaf0;

    text-decoration:none;

    border:
        2px solid #3b2a1a;

    font-size:14px;

    font-weight:bold;

    text-align:center;

    transition:0.2s;

    box-shadow:
        3px 3px 0 #3b2a1a;

}


.report-button:hover {

    background:#3b2a1a;

    transform:
        translateY(-2px);

    box-shadow:
        3px 5px 0 #24180e;

}


/* ==================================================
   EASTER EGG
================================================== */

.easter-inline {

    font-size:12px;

    margin-left:8px;

    text-decoration:none;

    color:transparent;

    opacity:0.25;

    transition:0.3s;

}


.easter-inline:hover {

    color:#3b2a1a;

    opacity:1;

}


/* ==================================================
   EFEITO BRILHO
================================================== */

.box::after {

    content:"";

    position:absolute;

    top:-100%;

    left:-50%;

    width:200%;

    height:300%;

    background:

        linear-gradient(
            120deg,
            transparent,
            rgba(255,255,255,0.2),
            transparent
        );

    transform:
        rotate(25deg);

    animation:
        shine 6s infinite;

    pointer-events:none;

}


@keyframes shine {

    0% {

        top:-100%;

    }

    100% {

        top:100%;

    }

}


/* ==================================================
   ÁREA DO JOGO
================================================== */

.game-container {

    width:100%;

    height:
        calc(100vh - 70px);

    min-height:650px;

    background:#101820;

    border:
        3px solid #5a3b1c;

    border-radius:15px;

    overflow:hidden;

    box-shadow:
        6px 6px 20px rgba(0,0,0,0.3);

    animation:
        fadeIn 0.6s ease;

    position:relative;

}


/* ==================================================
   IFRAME DO JOGO
================================================== */

.game-frame {

    width:100%;

    height:100%;

    min-height:650px;

    border:none;

    display:block;

    background:#101820;

}


/* ==================================================
   BOTÃO TELA CHEIA
================================================== */

.fullscreen-btn {

    position:absolute;

    top:15px;

    right:15px;

    z-index:20;

    padding:10px 16px;

    background:#5a3b1c;

    color:#fffaf0;

    border:
        2px solid #f5ecd9;

    border-radius:8px;

    font-family:
        "Times New Roman",
        serif;

    font-size:14px;

    font-weight:bold;

    cursor:pointer;

    transition:0.2s;

    box-shadow:
        3px 3px 8px rgba(0,0,0,0.35);

}


.fullscreen-btn:hover {

    background:#3b2a1a;

    transform:
        scale(1.05);

}


/* ==================================================
   TELA CHEIA
================================================== */

.game-container:fullscreen {

    width:100vw;

    height:100vh;

    min-height:100vh;

    border:none;

    border-radius:0;

}


.game-container:fullscreen .game-frame {

    width:100%;

    height:100%;

    min-height:100vh;

}


/* ==================================================
   RESPONSIVO
================================================== */

@media(max-width:768px) {

    body {

        flex-direction:column;

    }


    .sidebar {

        width:100%;

        position:relative;

        min-height:auto;

    }


    .main {

        margin-left:0;

        padding:20px;

        min-height:auto;

    }


    .box {

        width:100%;

    }


    .game-container {

        min-height:750px;

        height:
            calc(100vh - 100px);

    }


    .game-frame {

        min-height:750px;

    }

}

</style>

</head>


<body>


<!-- =====================================================
     MENU LATERAL
===================================================== -->

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

    <strong>
        NexusDigital
    </strong>

</div>


<nav>


<!-- =====================================================
     INÍCIO
===================================================== -->

<div
    class="nav-item
    <?php

        echo ($pagina === 'inicio')
            ? 'active'
            : '';

    ?>"
>

<a href="Site_principal.php">

<div class="label">
    Início
</div>

<div class="sublabel">
    Página principal
</div>

</a>

</div>


<!-- =====================================================
     CADASTRAR PRODUTOS
===================================================== -->

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


<!-- =====================================================
     LISTAR PRODUTOS
===================================================== -->

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


<!-- =====================================================
     EDITAR PRODUTO
===================================================== -->

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


<!-- =====================================================
     JOGO
===================================================== -->

<div
    class="nav-item
    <?php

        echo ($pagina === 'jogo')
            ? 'active'
            : '';

    ?>"
>

<a href="?pagina=jogo">

<div class="label">
    🎮 Jogar
</div>

<div class="sublabel">
    Jogo da Cobrinha
</div>

</a>

</div>


<!-- =====================================================
     SAIR
===================================================== -->

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
     CONTEÚDO
===================================================== -->

<main class="main">


<?php

/* =====================================================
   JOGO
===================================================== */

if ($pagina === 'jogo') {

?>


<div
    class="game-container"
    id="gameContainer"
>


<button
    class="fullscreen-btn"
    id="fullscreenBtn"
    onclick="toggleFullscreen()"
>

    ⛶ Tela cheia

</button>


<iframe
    src="jogo_cobrinha_php/index.php"
    class="game-frame"
    id="gameFrame"
    title="Jogo da Cobrinha"
    allowfullscreen
>
</iframe>


</div>


<?php

}


/* =====================================================
   PÁGINA INICIAL
===================================================== */

else {

?>


<div class="box">


<h1>

    NexusDigital

    <a
        href="https://www.friv.com/"
        class="easter-inline"
        title="..."
    >
        .
    </a>

</h1>


<p>

    Sistema inicializando
    ambiente seguro...

</p>


<div class="loader">

    <div class="bar"></div>

</div>


<div class="info">


<strong>

    Fatos do sistema:

</strong>


<br>

• Interface inspirada em sistemas clássicos dos anos 70

<br>

• Estrutura leve e otimizada para navegação rápida

<br>

• Design baseado em papel e estética vintage

<br>

• Segurança básica de sessão integrada


<br>
<br>


<strong>

    Características:

</strong>


<br>

• Estilo visual retrô (bege e marrom)

<br>

• Animações suaves e não intrusivas

<br>

• Experiência focada em simplicidade

<br>

• Navegação direta e funcional


</div>


<!-- =====================================================
     BOTÃO DO RELATÓRIO
===================================================== -->

<a
    href="Relatorio.php"
    class="report-button"
>

    📊 Acessar Relatório do Sistema

</a>


</div>


<?php

}

?>


</main>



<script>


/*
===========================================================
    SAIR DO SISTEMA
===========================================================
*/

function sairSistema(event) {

    event.preventDefault();

    /*
        replace() substitui a página atual no histórico.

        Assim, depois de clicar em Sair,
        o botão Voltar não volta para esta página.
    */

    window.location.replace("Login vs1.0.php");

}



/*
===========================================================
    TELA CHEIA
===========================================================
*/

function toggleFullscreen() {


    const gameContainer =
        document.getElementById(
            "gameContainer"
        );


    const button =
        document.getElementById(
            "fullscreenBtn"
        );


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


        document
            .exitFullscreen();

    }

}



/*
===========================================================
    DETECTAR TELA CHEIA
===========================================================
*/

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
