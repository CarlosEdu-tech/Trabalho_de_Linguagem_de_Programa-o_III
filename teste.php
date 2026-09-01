<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {

    /*
    ===========================================================
        CONFIGURAÇÃO DO SERVIDOR SMTP
    ===========================================================
    */

    $mail->isSMTP();

    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;

    $mail->Username   = 'beneditogsjunior@gmail.com';

    $mail->Password   = 'cajdwdtfmimbaoit';

    $mail->SMTPSecure = 'ssl';

    $mail->Port       = 465;


    /*
    ===========================================================
        REMETENTE E DESTINATÁRIO
    ===========================================================
    */

    $mail->setFrom(
        'beneditogsjunior@gmail.com',
        'Sistema'
    );

    $mail->addAddress(
        'beneditogsjunior@gmail.com'
    );


    /*
    ===========================================================
        CONTEÚDO DO E-MAIL
    ===========================================================
    */

    $mail->isHTML(true);

    $mail->Subject = 'Teste de Email';

    $mail->Body = '
        <h1>Email enviado com sucesso</h1>

        <p>
            PHPMailer funcionando corretamente.
            Pode trocar a senha.
        </p>
    ';


    /*
    ===========================================================
        ENVIA O E-MAIL
    ===========================================================
    */

    $mail->send();


    /*
    ===========================================================
        REGISTRA O ENVIO
    ===========================================================
    */

    $arquivoRegistro = __DIR__ . "/registro_emails.json";


    /*
        Se o arquivo não existir, cria.
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
        Lê os registros existentes.
    */

    $conteudo = file_get_contents(
        $arquivoRegistro
    );

    $registros = json_decode(
        $conteudo,
        true
    );


    /*
        Se o arquivo estiver vazio ou inválido,
        começa uma nova lista.
    */

    if (!is_array($registros)) {

        $registros = [];

    }


    /*
    ===========================================================
        ADICIONA NOVO REGISTRO
    ===========================================================
    */

    $registros[] = [

        'email' => 'beneditogsjunior@gmail.com',

        'data_hora' => date(
            'Y-m-d H:i:s'
        )

    ];


    /*
        Salva novamente o arquivo.
    */

    file_put_contents(

        $arquivoRegistro,

        json_encode(
            $registros,
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_UNICODE
        )

    );


    /*
    ===========================================================
        FINALIZA
    ===========================================================
    */

    echo "Email enviado com sucesso!";

    header(
        "Location: Recuperaçao vs1.php"
    );

    exit;


} catch (Exception $e) {

    echo "Erro ao enviar email: "
        . $mail->ErrorInfo;

}

?>