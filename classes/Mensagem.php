<?php

    abstract class Mensagem{

        public static function setMensagem($id, $msg, $estilo = 'info'){
            $_SESSION[$id] = "<div class='alert alert-{$estilo}' role='alert'>{$msg} <button type='button' class='close' data-dismiss='alert' aria-label='Close'> <span aria-hidden='true'>&times;</span> </button></div>";
        }

        public static function getMensagem($id){
            $msg = "";
            if(isset($_SESSION[$id])){
                $msg = $_SESSION[$id];
                unset($_SESSION[$id]);
            }
            return $msg;
        }
    }