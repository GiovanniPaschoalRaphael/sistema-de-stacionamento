<?php

require 'includes/conexao.php';
require 'classes/Mensagem.php';
require 'classes/Veiculo.php';

if (isset($_GET['p'])) {
    $placa = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_STRING);
} else {
    header('Location: veiculos.php');
    exit;
}

$db = conectar();

$v = new Veiculo($db);
$v->getVeiculo($placa);


# Verificando se o veículo existe.
if (!$v->getPlaca()) {
    Mensagem::setMensagem('msg', 'O veículo não existe.', 'danger');
    header('Location: veiculos.php');
    exit;
}

# Verificando se o veículo está estacionado
if ($v->getEstacionado() == 1) {
    Mensagem::setMensagem('msg', 'Você não pode remover veículos estacionados.', 'danger');
    header('Location: veiculos.php');
    exit;
}

try {
    $v->remover();
} catch (Exception $e) {
    echo "Erro no banco de dados: {$e->getMessage()}";
}

Mensagem::setMensagem('msg', 'Veículo removido com sucesso.', 'success');
header('Location: veiculos.php');
