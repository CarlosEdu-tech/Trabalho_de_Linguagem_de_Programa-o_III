<?php

session_start();

include "conexao.php";

require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;


/*
===========================================================
    REGISTRO DOS E-MAILS DE REDEFINIÇÃO
===========================================================
*/

$arquivoRegistro = __DIR__ . "/registro_emails.json";


/*
    Se o arquivo ainda não existir, cria vazio.
*/

if (!file_exists($arquivoRegistro)) {

    file_put_contents(
        $arquivoRegistro,
        json_encode(
            [],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        )
    );

}


/*
    Lê os registros
*/

$conteudoRegistro = file_get_contents(
    $arquivoRegistro
);

$emails = json_decode(
    $conteudoRegistro,
    true
);


/*
    Se houver algum problema no arquivo,
    começa novamente com uma lista vazia.
*/

if (!is_array($emails)) {

    $emails = [];

}


/*
===========================================================
    BUSCAR PRODUTOS
===========================================================
*/

$sqlProdutos = "
    SELECT
        id,
        Produtos,
        Valor
    FROM loja
    ORDER BY id DESC
";


$resultProdutos = $conexao->query(
    $sqlProdutos
);

$produtos = [];


if ($resultProdutos) {

    while ($row = $resultProdutos->fetch_assoc()) {

        $produtos[] = $row;

    }

}


/*
===========================================================
    ESTATÍSTICAS DOS PRODUTOS
===========================================================
*/

$totalProdutos = count(
    $produtos
);

$valorTotal = 0;


foreach ($produtos as $produto) {

    $valorTotal += (float)$produto['Valor'];

}


$mediaProdutos = $totalProdutos > 0
    ? $valorTotal / $totalProdutos
    : 0;


/*
===========================================================
    ESTATÍSTICAS DOS E-MAILS
===========================================================
*/

$totalEmails = count(
    $emails
);

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
    Relatório – NexusDigital
</title>


<style>


*,
*::before,
*::after {

    margin:0;

    padding:0;

    box-sizing:border-box;

}


body {

    background:#f5ecd9;

    font-family:
        "Times New Roman",
        serif;

    min-height:100vh;

    padding:30px;

    color:#3b2a1a;

}


.container {

    max-width:1100px;

    margin:auto;

    background:#fffaf0;

    padding:30px;

    border:
        3px solid #5a3b1c;

    box-shadow:
        6px 6px 15px
        rgba(0,0,0,.25);

}


.header {

    display:flex;

    justify-content:space-between;

    align-items:center;

    gap:15px;

    margin-bottom:25px;

    flex-wrap:wrap;

}


.header h1 {

    color:#3b2a1a;

    font-size:30px;

}


.header p {

    color:#777;

    margin-top:5px;

}


.buttons {

    display:flex;

    gap:10px;

    flex-wrap:wrap;

}


.btn {

    display:inline-block;

    padding:11px 18px;

    text-decoration:none;

    color:white;

    background:#4d7ea8;

    border:
        2px solid #345a78;

    font-weight:bold;

    transition:.2s;

}


.btn:hover {

    background:#345a78;

    transform:translateY(-2px);

}


.btn-pdf {

    background:#8b3a3a;

    border-color:#672727;

}


.btn-pdf:hover {

    background:#672727;

}


.stats {

    display:grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap:15px;

    margin-bottom:30px;

}


.stat {

    background:#f5ecd9;

    border:
        2px solid #5a3b1c;

    padding:18px;

    text-align:center;

}


.stat small {

    display:block;

    font-size:13px;

    color:#777;

    margin-bottom:8px;

}


.stat strong {

    display:block;

    font-size:25px;

    color:#5a3b1c;

}


.section {

    margin-top:25px;

}


.section h2 {

    color:#3b2a1a;

    margin-bottom:12px;

    font-size:22px;

}


.table-wrap {

    overflow-x:auto;

    border:
        2px solid #5a3b1c;

}


table {

    width:100%;

    border-collapse:collapse;

}


thead {

    background:#5a3b1c;

    color:white;

}


th {

    padding:13px;

    text-align:left;

}


td {

    padding:12px;

    border-bottom:
        1px solid #ddd;

}


tbody tr:nth-child(even) {

    background:#faf4e8;

}


tbody tr:hover {

    background:#f0e3cd;

}


.price {

    display:inline-block;

    padding:4px 10px;

    background:#5a3b1c;

    color:#f5ecd9;

    font-weight:bold;

}


.email-count {

    background:#eee0c8;

    border:
        2px solid #5a3b1c;

    padding:15px;

    margin-bottom:15px;

}


.empty {

    padding:35px;

    text-align:center;

    color:#777;

    font-style:italic;

}


.footer {

    margin-top:30px;

    padding-top:15px;

    border-top:
        1px solid #ddd;

    text-align:center;

    color:#777;

    font-size:12px;

}


@media(max-width:800px) {

    body {

        padding:10px;

    }


    .container {

        padding:18px;

    }


    .stats {

        grid-template-columns:
            repeat(2,1fr);

    }

}


@media(max-width:500px) {

    .stats {

        grid-template-columns:
            1fr;

    }


    .header h1 {

        font-size:24px;

    }

}


</style>

</head>


<body>


<div class="container">


<!-- =====================================================
     CABEÇALHO
===================================================== -->

<div class="header">


<div>

<h1>
    📊 Relatório NexusDigital
</h1>

<p>
    Informações gerais do sistema
</p>

</div>


<div class="buttons">


<a
    href="Site_principal.php"
    class="btn"
>
    🏠 Voltar ao site
</a>


<a
    href="relatorio_pdf.php"
    class="btn btn-pdf"
>
    📄 Criar PDF
</a>


</div>

</div>


<!-- =====================================================
     ESTATÍSTICAS
===================================================== -->

<div class="stats">


<div class="stat">

<small>
    📦 Produtos cadastrados
</small>

<strong>

<?= $totalProdutos ?>

</strong>

</div>


<div class="stat">

<small>
    💰 Valor total dos produtos
</small>

<strong>

R$

<?= number_format(
    $valorTotal,
    2,
    ',',
    '.'
) ?>

</strong>

</div>


<div class="stat">

<small>
    📈 Média dos produtos
</small>

<strong>

R$

<?= number_format(
    $mediaProdutos,
    2,
    ',',
    '.'
) ?>

</strong>

</div>


<div class="stat">

<small>
    📧 E-mails de redefinição
</small>

<strong>

<?= $totalEmails ?>

</strong>

</div>


</div>


<!-- =====================================================
     PRODUTOS
===================================================== -->

<div class="section">


<h2>
    📦 Produtos cadastrados
</h2>


<div class="table-wrap">


<?php if (empty($produtos)): ?>


<div class="empty">

    📭 Nenhum produto cadastrado.

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

<th>
    Valor
</th>

</tr>

</thead>


<tbody>


<?php foreach (
    $produtos as $i => $produto
): ?>


<tr>


<td>

<?= $i + 1 ?>

</td>


<td>

<?= htmlspecialchars(
    $produto['Produtos'],
    ENT_QUOTES,
    'UTF-8'
) ?>

</td>


<td>

<span class="price">

R$

<?= number_format(
    (float)$produto['Valor'],
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

</div>


<!-- =====================================================
     HISTÓRICO DE E-MAILS
===================================================== -->

<div class="section">


<h2>
    📧 Histórico de e-mails de redefinição
</h2>


<div class="email-count">

<strong>
    Total de e-mails enviados:
</strong>

<?= $totalEmails ?>

</div>


<div class="table-wrap">


<?php if (empty($emails)): ?>


<div class="empty">

    📭 Nenhum e-mail de redefinição foi enviado ainda.

</div>


<?php else: ?>


<table>


<thead>

<tr>

<th>
    #
</th>

<th>
    E-mail
</th>

<th>
    Data/Hora
</th>

</tr>

</thead>


<tbody>


<?php foreach (
    $emails as $i => $emailRegistro
): ?>


<tr>


<td>

<?= $i + 1 ?>

</td>


<td>

📧

<?= htmlspecialchars(
    $emailRegistro['email'],
    ENT_QUOTES,
    'UTF-8'
) ?>

</td>


<td>

<?= htmlspecialchars(
    $emailRegistro['data_hora'],
    ENT_QUOTES,
    'UTF-8'
) ?>

</td>


</tr>


<?php endforeach; ?>


</tbody>

</table>


<?php endif; ?>


</div>

</div>


<!-- =====================================================
     RODAPÉ
===================================================== -->

<div class="footer">

    NexusDigital — Relatório do sistema

</div>


</div>

</body>

</html>