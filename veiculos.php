<?php
require 'includes/conexao.php';
require 'classes/Mensagem.php';
require 'classes/Veiculo.php';

$db = conectar();

$v = new Veiculo($db);

$veiculos = $v->getVeiculos(isset($_GET['estacionados']));
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
    <title>Estacionamento | Veículos</title>
</head>

<body>
    <header>
        <?php include 'includes/menu.php' ?>
    </header>
    <main class="container p-3">
        <?= Mensagem::getMensagem('msg') ?>

        <div class="row">
            <header>
                <h1>Veículos <?= isset($_GET['estacionados']) ? "estacionados" : "" ?></h1>
            </header>
            <hr class="w-100">
            <table class="table table-bordered text-center">
                <tr>
                    <th>Placa</th>
                    <th>Modelo</th>
                    <th>Cor</th>
                    <th>Ano</th>
                    <th>Ações</th>
                </tr>
                <?php foreach ($veiculos as $veiculo) : ?>
                    <tr>
                        <td><?= $veiculo['placa'] ?></td>
                        <td><?= $veiculo['modelo'] ?></td>
                        <td><?= $veiculo['cor'] ?></td>
                        <td><?= $veiculo['ano'] ?></td>
                        <td>
                            <?php if ($veiculo['estacionado'] == 0) : ?>
                                <a href="editar_veiculo.php?p=<?= $veiculo['placa'] ?>" class="btn btn-info"><i class="fa fa-pencil"></i></a>
                                <a href="remover_veiculo.php?p=<?= $veiculo['placa'] ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                            <?php else : ?>
                                <a href="saida.php?placa=<?= $veiculo['placa'] ?>" class="btn btn-danger"><i class="fa fa-sign-out"></i> Saída</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <a href="cadastrar_veiculo.php" class="btn btn-primary">Cadastrar veículo</a>
        </div>
    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>