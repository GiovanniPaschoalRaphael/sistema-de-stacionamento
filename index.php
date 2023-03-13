<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Estacionamento | Home</title>
</head>

<body>
    <header>
        <?php include 'includes/menu.php' ?>
    </header>
    <main class="container p-3">
        <div class="row">
            <header>
                <h1>Sistema estacionamento</h1>
            </header>
            <hr class="w-100">
            <div class="text-center">
                <a href="entrada.php" class="btn btn-sq-lg btn-success mb-1">
                    <i class="fa fa-sign-in fa-5x"></i><br />
                    Entrada
                </a>
                <a href="saida.php" class="btn btn-sq-lg btn-danger mb-1">
                    <i class="fa fa-sign-out fa-5x"></i><br />
                    Saída
                </a>
                <a href="veiculos.php?estacionados" class="btn btn-sq-lg btn-info mb-1">
                    <i class="fa fa-clock-o fa-5x"></i><br />
                    Estacionados
                </a>
                <a href="veiculos.php" class="btn btn-sq-lg btn-info mb-1">
                    <i class="fa fa-list fa-5x"></i><br />
                    Veículos
                </a>
            </div>
    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>