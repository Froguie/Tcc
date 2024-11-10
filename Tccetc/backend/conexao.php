<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";

$conexao = new mysqli($servidor, $usuario, $senha);

if ($conexao->connect_error) {
    die("Conexão falhou: " . $conexao->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS dbtasty";
$conexao->query($sql);

if (!$conexao->select_db("dbtasty")) {
    die("Erro ao selecionar o banco de dados: " . $conexao->error);
}

$conexao->query("CREATE TABLE IF NOT EXISTS produto (
    codProduto INT PRIMARY KEY AUTO_INCREMENT,
    nomeProduto VARCHAR(100) NOT NULL,
    descricaoProduto TEXT,
    categoriaProduto VARCHAR(200) NOT NULL,
    precoProduto DECIMAL(10, 2) NOT NULL,
    imagemProduto MEDIUMBLOB
)");

$conexao->query("CREATE TABLE IF NOT EXISTS adicional (
    codAdicional INT PRIMARY KEY AUTO_INCREMENT,
    nomeAdicional VARCHAR(100) NOT NULL,
    precoAdicional DECIMAL(10, 2) NOT NULL
)");

$conexao->query("CREATE TABLE IF NOT EXISTS mesa (
    codMesa INT PRIMARY KEY AUTO_INCREMENT,
    statusMesa VARCHAR(50),
    comanda DECIMAL(10, 2),
    nomeMesa VARCHAR(100) NOT NULL,
    numero INT NOT NULL
)");

$conexao->query("CREATE TABLE IF NOT EXISTS funcionario (
    codFuncionario INT PRIMARY KEY AUTO_INCREMENT,
    nomeFuncionario VARCHAR(100) NOT NULL
)");

$conexao->query("CREATE TABLE IF NOT EXISTS pedido (
    codPedido INT PRIMARY KEY AUTO_INCREMENT,
    codProPe INT,
    codMesa INT,
    observacaoPedido TEXT,
    statusPedido VARCHAR(50),
    horarioPedido TIME,
    codAdicional INT,
    FOREIGN KEY (codProPe) REFERENCES produto(codProduto),
    FOREIGN KEY (codMesa) REFERENCES mesa(codMesa),
    FOREIGN KEY (codAdicional) REFERENCES adicional(codAdicional)
)");

$conexao->query("CREATE TABLE IF NOT EXISTS cozinha (
    codCozinha INT PRIMARY KEY AUTO_INCREMENT,
    nomeCozinha VARCHAR(100) NOT NULL,
    codPedido INT,
    FOREIGN KEY (codPedido) REFERENCES pedido(codPedido)
)");

$conexao->query("CREATE TABLE IF NOT EXISTS caixa (
    codCaixa INT PRIMARY KEY AUTO_INCREMENT,
    codFuncionario INT,
    metodoPagamento VARCHAR(50),
    FOREIGN KEY (codFuncionario) REFERENCES funcionario(codFuncionario)
)");

$conexao->query("CREATE TABLE IF NOT EXISTS administrador (
    codAdmin INT PRIMARY KEY AUTO_INCREMENT,
    codMesa INT,
    codProduto INT,
    nomeAdministrador VARCHAR(100) NOT NULL,
    FOREIGN KEY (codMesa) REFERENCES mesa(codMesa),
    FOREIGN KEY (codProduto) REFERENCES produto(codProduto)
)");

$conexao->query("CREATE TABLE IF NOT EXISTS produtoPedido (
    codProPe INT PRIMARY KEY AUTO_INCREMENT,
    codProduto INT,
    codPedido INT,
    quantidade INT,
    FOREIGN KEY (codProduto) REFERENCES produto(codProduto),
    FOREIGN KEY (codPedido) REFERENCES pedido(codPedido)
)");

$conexao->query("CREATE TABLE IF NOT EXISTS usuario (
    codUsuario INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(200) NOT NULL,
    email VARCHAR(200) NOT NULL,
    senha VARCHAR(200) NOT NULL,
    tipoConta ENUM('mesa', 'administrador', 'funcionario', 'cozinha') NOT NULL
)");

?>