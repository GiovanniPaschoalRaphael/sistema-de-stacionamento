<?php
class Veiculo
{

    private $placa;
    private $modelo;
    private $ano;
    private $cor;
    private $estacionado;
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    function remover()
    {
        $sql = "CALL remover_veiculo(:placa)";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':placa', $this->placa, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo "Erro no banco de dados: {$e->getMessage()}";
        }
    }

    function cadastrar()
    {
        $sql = "CALL cadastro_veiculo(:placa, :modelo, :ano, :cor);";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':placa', $this->placa, PDO::PARAM_STR);
        $stmt->bindParam(':modelo', $this->modelo, PDO::PARAM_STR);
        $stmt->bindParam(':ano', $this->ano, PDO::PARAM_STR);
        $stmt->bindParam(':cor', $this->cor, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo "Erro no banco de dados: {$e->getMessage()}";
        }
    }

    function getVeiculos($estacionados = false)
    {
        $sql = $estacionados ? "SELECT * FROM veiculos_estacionados;" : "SELECT * FROM veiculo;";
        return $this->db->query($sql)->fetchAll();
    }

    function getVeiculo($placa)
    {
        $sql = "SELECT * FROM veiculo WHERE placa = :placa";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':placa', $placa, PDO::PARAM_STR);

        try {
            $stmt->execute();

            $data = $stmt->fetch();

            $this->placa = $data['placa'];
            $this->modelo = $data['modelo'];
            $this->ano = $data['ano'];
            $this->cor = $data['cor'];
            $this->estacionado = $data['estacionado'];
        } catch (Exception $e) {
            echo "Erro no banco de dados: {$e->getMessage()}";
        }
    }

    function estacionar($fracao, $preco)
    {
        $sql = "CALL entrada_veiculo(:placa, :fracao, :preco);";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':placa', $this->placa, PDO::PARAM_STR);
        $stmt->bindParam(':fracao', $fracao, PDO::PARAM_STR);
        $stmt->bindParam(':preco', $preco, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo "Erro no banco de dados: {$e->getMessage()}";
        }
    }

    function saida()
    {
        # A subtração de 1h é para corrigir o fuso horário
        $dt_hr_saida = date('Y-m-d H:i:s', CORRECAO_HORARIO ? time() - 3600 : time());

        # Pegando o horário de entrada e o preço cobrado por hora
        $sql = "SELECT dt_hr, preco, fracao FROM entrada WHERE placa_veiculo = :placa ORDER BY dt_hr DESC LIMIT 1;";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':placa', $this->placa, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $data = $stmt->fetch();
            $dt_hr_entrada = $data['dt_hr'];
            $preco = $data['preco'];
            $fracao = $data['fracao'];
        } catch (Exception $e) {
            echo "Erro no banco de dados: {$e->getMessage()}";
        }

        $horas = $this->calcularEstadia($dt_hr_entrada, $dt_hr_saida);

        $total = $this->calcularPreco($horas, $fracao, $preco);

        $sql = "CALL saida_veiculo(:dt_hr, :tempo, :total, :placa_veiculo)";

        $stmt = $this->db->prepare($sql);

        try {
            $stmt->execute([
                'dt_hr' => $dt_hr_saida,
                'tempo' => $horas,
                'total' => $total,
                'placa_veiculo' => $this->placa
            ]);
        } catch (Exception $e) {
            echo "Erro no banco de dados: {$e->getMessage()}";
        }

        return ['tempo' => $horas, 'total' => $total, 'placa' => $this->placa];
    }

    # Recebe dois horários e retuorna em horas quantas horas o veículo ficou estacionado
    function calcularEstadia($dt_hr_entrada, $dt_hr_saida)
    {
        $entrada = new DateTime($dt_hr_entrada);
        $saida = new DateTime($dt_hr_saida);
        $diferenca = $saida->diff($entrada);

        return $diferenca->format('%i') / 60 + $diferenca->format('%h') + $diferenca->format('%d') * 24;
    }

    # Recebe os valores de cobrança e o tempo em horas, retorna o total a ser pago
    function calcularPreco($horas, $fracao, $preco)
    {
        return $horas / $fracao * $preco;
    }

    function atualizar($placa_antiga)
    {
        $sql = "CALL atualizar_veiculo(:placa_antiga, :placa, :modelo, :ano, :cor);";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':placa_antiga', $placa_antiga, PDO::PARAM_STR);
        $stmt->bindParam(':placa', $this->placa, PDO::PARAM_STR);
        $stmt->bindParam(':modelo', $this->modelo, PDO::PARAM_STR);
        $stmt->bindParam(':ano', $this->ano, PDO::PARAM_STR);
        $stmt->bindParam(':cor', $this->cor, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo "Erro no banco de dados: {$e->getMessage()}";
        }
    }

    function getPlaca()
    {
        return $this->placa;
    }

    function getModelo()
    {
        return $this->modelo;
    }

    function getAno()
    {
        return $this->ano;
    }

    function getCor()
    {
        return $this->cor;
    }

    function getEstacionado()
    {
        return $this->estacionado;
    }

    function setPlaca($placa)
    {
        $this->placa = $placa;
    }

    function setModelo($modelo)
    {
        $this->modelo = $modelo;
    }

    function setAno($ano)
    {
        $this->ano = $ano;
    }

    function setCor($cor)
    {
        $this->cor = $cor;
    }

    function setEstacionado($estacionado)
    {
        $this->estacionado = $estacionado;
    }
}
