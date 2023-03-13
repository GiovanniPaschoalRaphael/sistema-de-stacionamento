CREATE DATABASE crud_estacionamento DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE crud_estacionamento;

CREATE TABLE veiculo (
	placa VARCHAR(8) NOT NULL PRIMARY KEY,
    modelo VARCHAR(30) NOT NULL,
    ano YEAR NOT NULL,
    cor VARCHAR (20) NOT NULL,
    estacionado BOOLEAN DEFAULT 0
) ENGINE = INNODB;

CREATE VIEW veiculos_estacionados AS SELECT * FROM veiculo WHERE veiculo.estacionado = 1;

DELIMITER $$
CREATE PROCEDURE cadastro_veiculo (IN placa VARCHAR(8), IN modelo VARCHAR(30), ano YEAR, cor VARCHAR(20))
BEGIN
	INSERT INTO veiculo VALUES (placa, modelo, ano, cor, 0);
END $$

CREATE PROCEDURE atualizar_veiculo (IN p_antiga VARCHAR(8), IN p_placa VARCHAR(8), IN p_modelo VARCHAR(30), p_ano YEAR, p_cor VARCHAR(20))
BEGIN
	-- atualizando a placa nos registros de movimentação
	UPDATE saida SET placa_veiculo = p_placa WHERE placa_veiculo = p_antiga;
	UPDATE entrada SET placa_veiculo = p_placa WHERE placa_veiculo = p_antiga;

	UPDATE veiculo SET placa = p_placa, modelo = p_modelo, ano = p_ano, cor = p_cor WHERE placa = p_antiga;
END $$

CREATE PROCEDURE remover_veiculo (IN p_placa VARCHAR(8))
BEGIN
	# Removendo as movimentações antes de apagar o veículo
    DELETE FROM entrada WHERE placa_veiculo = p_placa;
	DELETE FROM saida WHERE placa_veiculo = p_placa;
    
	DELETE FROM veiculo WHERE placa = p_placa;
END $$

CREATE TRIGGER before_update_veiculo BEFORE UPDATE ON veiculo
FOR EACH ROW
BEGIN
	SET NEW.placa = UPPER(NEW.placa);
	SET NEW.cor = UPPER(NEW.cor);
	SET NEW.modelo = UPPER(NEW.modelo);
    
    -- atualizando a placa nos registros de movimentação
	UPDATE saida SET placa_veiculo = NEW.placa WHERE placa_veiculo = OLD.placa;
	UPDATE entrada SET placa_veiculo = NEW.placa WHERE placa_veiculo = OLD.placa;
    
END$$
DELIMITER ;

CREATE TABLE entrada (
	identrada INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    placa_veiculo VARCHAR(8) NOT NULL,
	fracao DECIMAL(3,2) NOT NULL, 
    preco DECIMAL (6,2) NOT NULL, # preço a ser cobrado pela fração de tempo
    dt_hr TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(placa_veiculo) REFERENCES veiculo(placa)
) ENGINE = INNODB;

DELIMITER $$
CREATE PROCEDURE entrada_veiculo (IN placa_veiculo VARCHAR(8), IN fracao DECIMAL(3,2), IN preco DECIMAL(6,2))
BEGIN
	INSERT INTO entrada VALUES (NULL, placa_veiculo, fracao, preco, NULL);
    UPDATE veiculo SET estacionado = 1 WHERE placa = placa_veiculo;
END $$
DELIMITER ;

CREATE TABLE saida (
	idsaida INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    dt_hr TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	tempo DECIMAL(4, 2) NOT NULL, # tempo de estadia
    total DECIMAL (5, 2), # total a ser cobrado pela estadia
    placa_veiculo VARCHAR(8) NOT NULL,
    FOREIGN KEY(placa_veiculo) REFERENCES veiculo(placa)
) ENGINE = INNODB;

DELIMITER $$
CREATE PROCEDURE saida_veiculo (IN dt_hr TIMESTAMP, IN tempo DECIMAL(4,2), IN total DECIMAL(6,2), IN placa_veiculo VARCHAR(8))
BEGIN
	INSERT INTO saida VALUES (NULL, dt_hr, tempo, total, placa_veiculo);
    UPDATE veiculo SET estacionado = 0 WHERE placa = placa_veiculo;
END $$

DELIMITER ;