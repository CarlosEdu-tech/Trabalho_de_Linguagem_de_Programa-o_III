<?php

session_start();

include "conexao.php";

require_once __DIR__ . "/vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;


/*
===========================================================
    REGISTRO DOS E-MAILS
===========================================================
*/

$arquivoRegistro = __DIR__ . "/registro_emails.json";

if (!file_exists($arquivoRegistro)) {

    file_put_contents(
        $arquivoRegistro,
        json_encode(
            [],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        )
    );

}

$conteudoRegistro = file_get_contents(
    $arquivoRegistro
);

$emails = json_decode(
    $conteudoRegistro,
    true
);

if (!is_array($emails)) {

    $emails = [];

}


/*
===========================================================
    PRODUTOS
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
    ESTATÍSTICAS
===========================================================
*/

$totalProdutos = count($produtos);

$valorTotal = 0;

foreach ($produtos as $produto) {

    $valorTotal += (float) $produto["Valor"];

}

$mediaProdutos = 0;

if ($totalProdutos > 0) {

    $mediaProdutos =
        $valorTotal / $totalProdutos;

}

$totalEmails = count($emails);


/*
===========================================================
    FORMATAÇÃO
===========================================================
*/

$valorTotalFormatado = number_format(
    $valorTotal,
    2,
    ",",
    "."
);

$mediaProdutosFormatada = number_format(
    $mediaProdutos,
    2,
    ",",
    "."
);


/*
===========================================================
    HTML DO PDF
===========================================================
*/

$html = '';

$html .= '
<!DOCTYPE html>

<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<style>

@page {

    margin: 35px;

}

body {

    font-family: DejaVu Sans, sans-serif;

    font-size: 11px;

    color: #3b2a1a;

}

h1 {

    text-align: center;

    font-size: 24px;

    margin-bottom: 5px;

}

.subtitulo {

    text-align: center;

    color: #777;

    margin-bottom: 25px;

}

h2 {

    font-size: 17px;

    color: #3b2a1a;

    border-bottom: 2px solid #5a3b1c;

    padding-bottom: 5px;

    margin-top: 25px;

}

table {

    width: 100%;

    border-collapse: collapse;

    margin-top: 10px;

}

th {

    background: #5a3b1c;

    color: white;

    padding: 8px;

    text-align: left;

}

td {

    padding: 8px;

    border: 1px solid #cccccc;

}

.estatisticas td {

    width: 25%;

    text-align: center;

    border: 1px solid #5a3b1c;

}

.titulo {

    font-size: 9px;

    color: #777;

}

.valor {

    display: block;

    margin-top: 5px;

    font-size: 15px;

    font-weight: bold;

}

.vazio {

    text-align: center;

    padding: 15px;

    color: #777;

}

.rodape {

    margin-top: 30px;

    padding-top: 10px;

    border-top: 1px solid #ccc;

    text-align: center;

    color: #777;

    font-size: 9px;

}

</style>

</head>

<body>

<h1>
    NexusDigital
</h1>

<div class="subtitulo">
    Relatório do sistema
</div>

<h2>
    Estatísticas gerais
</h2>

<table class="estatisticas">

<tr>

<td>

<span class="titulo">
    Produtos cadastrados
</span>

<span class="valor">
    ' . $totalProdutos . '
</span>

</td>

<td>

<span class="titulo">
    Valor total
</span>

<span class="valor">
    R$ ' . $valorTotalFormatado . '
</span>

</td>

<td>

<span class="titulo">
    Média dos produtos
</span>

<span class="valor">
    R$ ' . $mediaProdutosFormatada . '
</span>

</td>

<td>

<span class="titulo">
    E-mails enviados
</span>

<span class="valor">
    ' . $totalEmails . '
</span>

</td>

</tr>

</table>

<h2>
    Produtos cadastrados
</h2>
';


/*
===========================================================
    PRODUTOS
===========================================================
*/

if (empty($produtos)) {

    $html .= '

    <div class="vazio">

        Nenhum produto cadastrado.

    </div>

    ';

} else {

    $html .= '

    <table>

    <thead>

    <tr>

        <th>#</th>

        <th>Produto</th>

        <th>Valor</th>

    </tr>

    </thead>

    <tbody>

    ';


    foreach ($produtos as $i => $produto) {

        $numero = $i + 1;

        $nomeProduto = htmlspecialchars(
            $produto["Produtos"],
            ENT_QUOTES,
            "UTF-8"
        );

        $valorProduto = number_format(
            (float) $produto["Valor"],
            2,
            ",",
            "."
        );


        $html .= '

        <tr>

            <td>
                ' . $numero . '
            </td>

            <td>
                ' . $nomeProduto . '
            </td>

            <td>
                R$ ' . $valorProduto . '
            </td>

        </tr>

        ';

    }


    $html .= '

    </tbody>

    </table>

    ';

}


/*
===========================================================
    HISTÓRICO DE E-MAILS
===========================================================
*/

$html .= '

<h2>
    Histórico de e-mails de redefinição
</h2>

';


if (empty($emails)) {

    $html .= '

    <div class="vazio">

        Nenhum e-mail de redefinição foi enviado ainda.

    </div>

    ';

} else {

    $html .= '

    <table>

    <thead>

    <tr>

        <th>#</th>

        <th>E-mail</th>

        <th>Data/Hora</th>

    </tr>

    </thead>

    <tbody>

    ';


    foreach ($emails as $i => $emailRegistro) {

        $numero = $i + 1;

        $email = htmlspecialchars(
            $emailRegistro["email"] ?? "",
            ENT_QUOTES,
            "UTF-8"
        );

        $dataHora = htmlspecialchars(
            $emailRegistro["data_hora"] ?? "",
            ENT_QUOTES,
            "UTF-8"
        );


        $html .= '

        <tr>

            <td>
                ' . $numero . '
            </td>

            <td>
                ' . $email . '
            </td>

            <td>
                ' . $dataHora . '
            </td>

        </tr>

        ';

    }


    $html .= '

    </tbody>

    </table>

    ';

}


/*
===========================================================
    RODAPÉ
===========================================================
*/

$html .= '

<div class="rodape">

    NexusDigital — Relatório do sistema

</div>

</body>

</html>

';


/*
===========================================================
    GERAR PDF
===========================================================
*/

$options = new Options();

$options->set(
    "isHtml5ParserEnabled",
    true
);

$options->set(
    "isRemoteEnabled",
    true
);


$dompdf = new Dompdf(
    $options
);


$dompdf->loadHtml(
    $html
);


$dompdf->setPaper(
    "A4",
    "portrait"
);


$dompdf->render();


$dompdf->stream(
    "relatorio_nexusdigital.pdf",
    [
        "Attachment" => true
    ]
);


exit;

?>