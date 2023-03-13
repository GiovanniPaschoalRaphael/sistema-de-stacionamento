<?php
require 'includes/conexao.php';
require 'classes/Mensagem.php';
require 'classes/Veiculo.php';

$db = conectar();

if (!isset($_GET['p'])) {
    header('Location: index.php');
    exit;
}

$placa_antiga = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_STRING);

$placa = filter_input(INPUT_GET, 'placa', FILTER_SANITIZE_STRING);

$v = new Veiculo($db);
$v->getVeiculo($placa_antiga);

# Verificando se o veículo existe.
if (!$v->getPlaca()) {
    Mensagem::setMensagem('msg', 'O veículo não existe.', 'danger');
    header('Location: veiculos.php');
    exit;
}

# Verificando se o veículo está estacionado
if ($v->getEstacionado() == 1) {
    Mensagem::setMensagem('msg', 'Você não pode editar veículos estacionados.', 'danger');
    header('Location: veiculos.php');
    exit;
}

$modelo = $v->getModelo();
$cor = $v->getCor();
$ano = $v->getAno();


# Verificando se foi enviado o formulário
if (isset($_GET['enviar'])) {

    # Verificando se a placa foi alterada

    if ($placa_antiga != $placa) {
        # Verificando se já não existe um veículo com a nova placa
        $v = new Veiculo($db);
        $v->getVeiculo($placa);
        if ($v->getPlaca()) {
            Mensagem::setMensagem("msg_editar", "Já existe um veículo com essa placa!", 'danger');
            header("Location: editar_veiculo.php?p={$placa_antiga}");
            exit;
        }
    }

    $modelo = filter_input(INPUT_GET, 'modelo', FILTER_SANITIZE_STRING);
    $ano = filter_input(INPUT_GET, 'ano', FILTER_SANITIZE_NUMBER_INT);
    $cor = filter_input(INPUT_GET, 'cor', FILTER_SANITIZE_STRING);

    $v = new Veiculo($db);

    $v->getVeiculo($placa_antiga);

    $v->setModelo($modelo);
    $v->setAno($ano);
    $v->setCor($cor);
    $v->setPlaca($placa);

    $v->atualizar($placa_antiga);
    Mensagem::setMensagem('msg', 'Veículo atualizado com sucesso.');
    header('Location: veiculos.php');
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
    <title>Estacionamento | Atualizar veículo</title>
</head>

<body>
    <header>
        <?php include 'includes/menu.php' ?>
    </header>
    <main class="container p-3">
        <?= Mensagem::getMensagem("msg_editar") ?>
        <div class="row">
            <header>
                <h1>Atualizar veículo</h1>
            </header>
            <hr class="w-100">
            <form action="editar_veiculo.php" method="GET">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="placa">Placa: </label>
                        <input value="<?= $placa_antiga ?>" autofocus='autofocus' name="placa" id="placa" type="text" class="form-control" required="required">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="modelo">Modelo: </label>
                        <input value="<?= $modelo ?>" name="modelo" id="placa" type="text" class="form-control" required="required">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="cor">Cor: </label>
                        <input value="<?= $cor ?>" name="cor" id="cor" type="text" class="form-control" required="required" autofocus="autofocus">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="ano">Ano: </label>
                        <input value="<?= $ano ?>" name="ano" id="ano" type="text" class="form-control" required="required" autofocus="autofocus">
                    </div>
                </div>
                <input value="<?= $placa_antiga ?>" name="p" type="hidden">
                <button name="enviar" type="submit" class="btn btn-success btn-lg"> <i class="fa fa-sign-out"></i>
                    Salvar</button>
            </form>

        </div>
    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="assets/js/jquery.inputmask.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>