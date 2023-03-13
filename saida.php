<?php
require 'includes/conexao.php';
require 'classes/Mensagem.php';
require 'classes/Veiculo.php';

$db = conectar();

if (isset($_GET['placa'])) {
    $placa = filter_input(INPUT_GET, 'placa', FILTER_SANITIZE_STRING);

    $v = new Veiculo($db);
    $v->getVeiculo($placa);

    # Verificando se o veículo existe e está estacionado.
    if ($v->getPlaca() && $v->getEstacionado() == 1) {
        try {
            $informacoes = $v->saida();

            $placa = $informacoes['placa'];

            # valor a ser pago

            $total = number_format($informacoes['total'], 2, ',', '.');

            # convertendo horas em decimal para horas e minutos para apresentar
            $tempo = $informacoes['tempo'];
            $horas = $tempo % 60;
            $tempo -= $horas;
            $minutos = $tempo * 60;

            Mensagem::setMensagem('msg', "O veículo de placa <strong>{$placa}</strong> ficou estacionado por {$horas} hora(s) e {$minutos} minuto(s) e o valor a ser pago é de R$ {$total}");
        } catch (Exception $e) {
            Mensagem::setMensagem('msg', "Erro no banco de dados: {$e->getMessage()}", 'danger');
        }

        header('Location: veiculos.php?estacionados');
        exit;
    }

    # Se o veículo não existir ou não estiver estacionado

    Mensagem::setMensagem('msg', 'O veículo não está cadastrado ou não está estacionado.', 'danger');
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="wplacath=device-wplacath, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Estacionamento | Saída</title>
</head>

<body>
    <header>
        <?php include 'includes/menu.php' ?>
    </header>
    <main class="container p-3">
        <?= Mensagem::getMensagem('msg') ?>
        <div class="row">
            <header>
                <h1>Saída de veículo</h1>
            </header>
            <hr class="w-100">
            <form id="form" action="saida.php" method="GET">
                <div class="form-group">
                    <label for="placa">Placa: </label>
                    <input name="placa" id="placa" type="text" class="form-control" required="required" autofocus="autofocus">
                </div>
                <button type="submit" class="btn btn-danger btn-lg"> <i class="fa fa-sign-out"></i>
                    Confirmar saída</button>
            </form>

        </div>
    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="assets/js/jquery.inputmask.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>