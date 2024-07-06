<?php

// Redirecionar ou para o processamento quando o usuário não acessa o arquivo index.php
if (!defined('C7E3L8K9E5')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!-- Aceitar caracteres especiais -->
    <meta charset="UTF-8">
    <!-- identificar o tamanho da tela do dispositivo do usuario -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- incluir o icone -->
    <link rel="shortcut icon" href="<?php echo URL; ?>app/sts/assets/images/icon/favicon.ico">
    <!-- incluir o CSS -->
    <link rel="stylesheet" href="<?php echo URL; ?>app/sts/assets/css/custom.css">
    <!-- incluir a biblioteca de icone -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Colocar o titulo na aba do navegador -->
    <title>Celke</title>
</head>

<body>