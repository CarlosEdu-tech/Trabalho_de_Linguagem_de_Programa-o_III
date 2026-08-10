<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexusDigital - Iniciando</title>

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
            to { transform: translate(-60px,-60px); }
        }

        /* ── SIDEBAR ── */
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

        /* ── CONTEÚDO ── */
        .main {
            margin-left: 250px;
            padding: 35px;
            flex: 1;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ── BOX ── */
        .box {
            background: #fffaf0;
            padding: 40px;
            border: 3px solid #5a3b1c;
            box-shadow: 6px 6px 15px rgba(0,0,0,0.3);
            width: 450px;
            text-align: center;
            position: relative;
            overflow: hidden;
            animation: fadeIn 1.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(25px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        h1 {
            color: #3b2a1a;
            margin-bottom: 10px;
            animation: pulse 2.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50%       { transform: scale(1.03); }
        }

        p {
            color: #5a3b1c;
            margin-bottom: 20px;
        }

        .loader {
            width: 100%;
            height: 10px;
            border: 2px solid #5a3b1c;
            margin-bottom: 25px;
            background: #f5ecd9;
            overflow: hidden;
        }

        .bar {
            height: 100%;
            width: 0%;
            background: #5a3b1c;
            animation: load 3s ease forwards;
        }

        @keyframes load {
            from { width: 0%; }
            to   { width: 100%; }
        }

        .info {
            text-align: left;
            font-size: 14px;
            color: #3b2a1a;
            margin-top: 10px;
            line-height: 1.5;
            background: rgba(245,236,217,0.5);
            padding: 10px;
            border: 1px solid #5a3b1c;
        }

        .info strong {
            color: #2a1d12;
        }

        .easter-inline {
            font-size: 12px;
            margin-left: 8px;
            text-decoration: none;
            color: transparent;
            opacity: 0.25;
            transition: 0.3s;
        }

        .easter-inline:hover {
            color: #3b2a1a;
            opacity: 1;
        }

        .box::after {
            content: "";
            position: absolute;
            top: -100%;
            left: -50%;
            width: 200%;
            height: 300%;
            background: linear-gradient(
                120deg,
                transparent,
                rgba(255,255,255,0.2),
                transparent
            );
            transform: rotate(25deg);
            animation: shine 6s infinite;
            pointer-events: none;
        }

        @keyframes shine {
            0%   { top: -100%; }
            100% { top: 100%; }
        }

        /* ── RESPONSIVO ── */
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; position: relative; min-height: auto; }
            .main { margin-left: 0; padding: 20px; }
        }

    </style>
</head>
<body>

<!-- ── SIDEBAR ── -->
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

        <div class="nav-item active">
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

<!-- ── CONTEÚDO ── -->
<main class="main">

    <div class="box">

        <h1>
            NexusDigital
            <a href="https://www.friv.com/" class="easter-inline" title="...">.</a>
        </h1>

        <p>Sistema inicializando ambiente seguro...</p>

        <div class="loader">
            <div class="bar"></div>
        </div>

        <div class="info">
            <strong>Fatos do sistema:</strong><br>
            • Interface inspirada em sistemas clássicos dos anos 70<br>
            • Estrutura leve e otimizada para navegação rápida<br>
            • Design baseado em papel e estética vintage<br>
            • Segurança básica de sessão integrada<br><br>

            <strong>Características:</strong><br>
            • Estilo visual retrô (bege e marrom)<br>
            • Animações suaves e não intrusivas<br>
            • Experiência focada em simplicidade<br>
            • Navegação direta e funcional
        </div>

    </div>

</main>

</body>
</html>