<?php
require 'includes/conexao.php';
require 'classes/Mensagem.php';
require 'classes/Veiculo.php';

$db = conectar();

$entrada = false;

# Verificando se após o cadastro deve ser registrada a entrada
if (isset($_GET['entrada'])) {
    $placa = filter_input(INPUT_GET, 'placa', FILTER_SANITIZE_STRING);
    $fracao = filter_input(INPUT_GET, 'fracao', FILTER_SANITIZE_STRING);
    $preco = filter_input(INPUT_GET, 'preco', FILTER_SANITIZE_STRING);
    $entrada = true;
}

# Verificando se foi enviado o formulário
if (isset($_GET['enviar'])) {

    $placa = filter_input(INPUT_GET, 'placa', FILTER_SANITIZE_STRING);
    $modelo = filter_input(INPUT_GET, 'modelo', FILTER_SANITIZE_STRING);
    $ano = filter_input(INPUT_GET, 'ano', FILTER_SANITIZE_NUMBER_INT);
    $cor = filter_input(INPUT_GET, 'cor', FILTER_SANITIZE_STRING);

    $v = new Veiculo($db);

    # Verificando se a placa já não está cadastrada

    $v->getVeiculo($placa);
    if ($v->getPlaca()) {
        Mensagem::setMensagem("msg_cadastrar", "Já existe um veículo com essa placa!", 'danger');
        header("Location: cadastrar_veiculo.php");
        exit;
    }

    $v->setModelo($modelo);
    $v->setAno($ano);
    $v->setCor($cor);
    $v->setPlaca($placa);

    $v->cadastrar();

    Mensagem::setMensagem('msg', 'Veículo cadastrado com sucesso.', 'success');

    # Verificando se deve ser feito o registro de entrada
    if ($entrada) {
        $v->estacionar($fracao, $preco);
        Mensagem::setMensagem('msg', 'Veículo estacionado com sucesso.', 'success');
        header('Location: veiculos.php?estacionados');
        exit;
    }

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
    <title>Estacionamento | Cadastro de veículo</title>
</head>

<body>
    <header>
        <?php include 'includes/menu.php' ?>
    </header>
    <main class="container p-3">
        <?= Mensagem::getMensagem("msg_cadastrar") ?>
        <div class="row">
            <header>
                <h1>Cadastrar veículo</h1>
            </header>
            <hr class="w-100">
            <form id="form" action="cadastrar_veiculo.php" method="GET">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="placa">Placa: </label>
                        <input <?= isset($placa) ? "value='{$placa}' readOnly='readOnly'" : " autofocus='autofocus'" ?> name="placa" id="placa" type="text" class="form-control" required="required">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="modelo">Modelo: </label>
                        <input name="modelo" id="placa" type="text" class="form-control" required="required" autofocus="autofocus">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="cor">Cor: </label>
                        <input name="cor" id="cor" type="text" class="form-control" required="required" autofocus="autofocus">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="ano">Ano: </label>
                        <input name="ano" id="ano" type="number" class="form-control" required="required" autofocus="autofocus" min="1884" max="<?= date("Y")+2 ?>">
                    </div>
                </div>
                <?php if ($entrada) : ?>
                    <input name="entrada" type="hidden">
                    <input name="preco" type="hidden" value="<?= $preco ?>">
                    <input name="fracao" type="hidden" value="<?= $fracao ?>">
                <?php endif; ?>
                <button name="enviar" type="submit" class="btn btn-success btn-lg"> <i class="fa fa-sign-out"></i>
                    Salvar <?= $entrada ? " e confirmar entrada" : "" ?></button>
            </form>

        </div>
    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="assets/js/jquery.inputmask.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>