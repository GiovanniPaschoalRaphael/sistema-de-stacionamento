<?php
session_start();

date_default_timezone_set('Brazil/East');

define("DB_HOST", "127.0.0.1");
define("DB_NAME", "crud_estacionamento");
define("DB_USER", "root");
define("DB_PASSWORD", "");

// Devido ao cancelamento do horário de verão, foi necessário adiantar 1h
// Se desabilitado, não adiantará 1h
define("CORRECAO_HORARIO", true);
