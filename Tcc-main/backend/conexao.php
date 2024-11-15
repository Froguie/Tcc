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

$conexao->query("CREATE TABLE IF NOT EXISTS Produto (
    codProduto INT PRIMARY KEY AUTO_INCREMENT,
    nomeProduto VARCHAR(100) NOT NULL,
    descricaoProduto TEXT,
    precoProduto DECIMAL(10, 2) NOT NULL,
    quantidaade INT
)");

$conexao->query("CREATE TABLE IF NOT EXISTS Adicional (
    codAdicional INT PRIMARY KEY AUTO_INCREMENT,
    nomeAdicional VARCHAR(100) NOT NULL,
    precoAdicional DECIMAL(10, 2) NOT NULL
)");

$conexao->query("CREATE TABLE IF NOT EXISTS Mesa (
    codMesa INT PRIMARY KEY AUTO_INCREMENT,
    statusMesa VARCHAR(50),
    loginMesa VARCHAR(50),
    senhaMesa VARCHAR(50),
    comanda DECIMAL(10, 2)
)");

$conexao->query("CREATE TABLE IF NOT EXISTS Funcionario (
    codFuncionario INT PRIMARY KEY AUTO_INCREMENT,
    nomeFuncionario VARCHAR(100) NOT NULL,
    cpfFuncionario VARCHAR(11) UNIQUE NOT NULL,
    loginFuncionario VARCHAR(50) NOT NULL,
    senhaFuncionario VARCHAR(255) NOT NULL
)");

$conexao->query("CREATE TABLE IF NOT EXISTS Pedido (
    codPedido INT PRIMARY KEY AUTO_INCREMENT,
    codProPe INT,
    codMesa INT,
    observacaoPedido TEXT,
    quantidaade INT,
    statusPedido VARCHAR(50),
    horarioPedido TIME,
    codAdicional INT,
    FOREIGN KEY (codProPe) REFERENCES Produto(codProduto),
    FOREIGN KEY (codMesa) REFERENCES Mesa(codMesa),
    FOREIGN KEY (codAdicional) REFERENCES Adicional(codAdicional)
)");

$conexao->query("CREATE TABLE IF NOT EXISTS Cozinha (
    codCozinha INT PRIMARY KEY AUTO_INCREMENT,
    codPedido INT,
    FOREIGN KEY (codPedido) REFERENCES Pedido(codPedido)
)");

$conexao->query("CREATE TABLE IF NOT EXISTS Caixa (
    codCaixa INT PRIMARY KEY AUTO_INCREMENT,
    codFuncionario INT,
    metodoPagamento VARCHAR(50),
    FOREIGN KEY (codFuncionario) REFERENCES Funcionario(codFuncionario)
)");

$conexao->query("CREATE TABLE IF NOT EXISTS Administrador (
    codAdmin INT PRIMARY KEY AUTO_INCREMENT,
    codMesa INT,
    codProduto INT,
    email VARCHAR(50) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    FOREIGN KEY (codMesa) REFERENCES Mesa(codMesa),
    FOREIGN KEY (codProduto) REFERENCES Produto(codProduto)
)");

$conexao->query("CREATE TABLE IF NOT EXISTS ProdutoPedido (
    codProPe INT PRIMARY KEY AUTO_INCREMENT,
    codProduto INT,
    codPedido INT,
    quantidade INT,
    FOREIGN KEY (codProduto) REFERENCES Produto(codProduto),
    FOREIGN KEY (codPedido) REFERENCES Pedido(codPedido)
)");

$conexao->query("CREATE TABLE IF NOT EXISTS usuario (
    codusuario INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(200) NOT NULL,
    email VARCHAR(200) NOT NULL,
    senha VARCHAR(200) NOT NULL
)");

?>
