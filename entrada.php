<?php
require 'includes/conexao.php';
require 'classes/Mensagem.php';
require 'classes/Veiculo.php';

$db = conectar();

# Verificando se o formulário já foi preenchido.
if (isset($_GET['placa'])) {
    $placa = filter_input(INPUT_GET, 'placa', FILTER_SANITIZE_STRING);
    $fracao = filter_input(INPUT_GET, 'fracao', FILTER_SANITIZE_STRING);
    $preco = filter_input(INPUT_GET, 'preco', FILTER_SANITIZE_STRING);

    $v = new Veiculo($db);
    $v->getVeiculo($placa);

    # Verificando se o veículo existe, se existe é feito o registro da entrada.
    if ($v->getPlaca()) {

        # verificando se o veículo já não está estacionado
        if ($v->getEstacionado() == 1) {
            Mensagem::setMensagem('msg', 'O veículo já está estacionado.', 'danger');
        } else {
            $v->estacionar($fracao, $preco);
            Mensagem::setMensagem('msg', 'Veículo estacionado com sucesso.', 'success');
        }

        header("Location: veiculos.php?estacionados");
        exit;
    }

    # Se o veículo não existe deve ser feito o cadastro.
    header("Location: cadastrar_veiculo.php?entrada&placa={$placa}&preco={$preco}&fracao={$fracao}");
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
    <title>Estacionamento | Entrada</title>
</head>

<body>
    <header>
        <?php include 'includes/menu.php' ?>
    </header>
    <main class="container p-3">
        <div class="row">
            <header>
                <h1>Entrada de veículo</h1>
            </header>
            <hr class="w-100">
            <form id="form" action="entrada.php" method="GET">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="placa">Placa: </label>
                        <input name="placa" id="placa" type="text" class="form-control" required="required" autofocus="autofocus">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="preco">Preço: </label>
                        <input name="preco" id="preco" type="text" class="form-control" required="required">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="fracao">A cada: </label>
                        <select name="fracao" id="fracao" class="form-control">
                            <option value="1" selected="selected">1 hora</option>
                            <option value="0.5">30 minutos</option>
                            <option value="0.25">15 minutos</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-success btn-lg"> <i class="fa fa-sign-out"></i>
                    Confirmar entrada</button>
            </form>

        </div>
    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="assets/js/jquery.inputmask.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>