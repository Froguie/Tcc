

CREATE DATABASE tastXbd;
USE tastXbd;


CREATE TABLE Admin (
    codAdmin INT PRIMARY KEY AUTO_INCREMENT,
    codMesa INT,
    codProduto INT,
    loginAdmin VARCHAR(50) NOT NULL,
    senhaAdmin VARCHAR(50) NOT NULL,
    FOREIGN KEY (codMesa) REFERENCES Mesa(codMesa),
    FOREIGN KEY (codProduto) REFERENCES Produto(codProduto)
);


CREATE TABLE Produto (
    codProduto INT PRIMARY KEY AUTO_INCREMENT,
    nomeProduto VARCHAR(100) NOT NULL,
    descricaoProduto TEXT,
    precoProduto DECIMAL(10, 2) NOT NULL
);


CREATE TABLE Pedido (
    codPedido INT PRIMARY KEY AUTO_INCREMENT,
    codProPe INT,
    codMesa INT,
    observacaoPedido TEXT,
    statusPedido VARCHAR(50),
    horarioPedido TIME,
    codAdicional INT,
    FOREIGN KEY (codProPe) REFERENCES Produto(codProduto),
    FOREIGN KEY (codMesa) REFERENCES Mesa(codMesa),
    FOREIGN KEY (codAdicional) REFERENCES Adicional(codAdicional)
);


CREATE TABLE Cozinha (
    codCozinha INT PRIMARY KEY AUTO_INCREMENT,
    codPedido INT,
    FOREIGN KEY (codPedido) REFERENCES Pedido(codPedido)
);


CREATE TABLE Funcionario (
    codFuncionario INT PRIMARY KEY AUTO_INCREMENT,
    nomeFuncionario VARCHAR(100) NOT NULL,
    cpfFuncionario VARCHAR(11) UNIQUE NOT NULL,
    loginFuncionario VARCHAR(50) NOT NULL,
    senhaFuncionario VARCHAR(50) NOT NULL
);


CREATE TABLE Caixa (
    codCaixa INT PRIMARY KEY AUTO_INCREMENT,
    codFuncionario INT,
    metodoPagamento VARCHAR(50),
    FOREIGN KEY (codFuncionario) REFERENCES Funcionario(codFuncionario)
);


CREATE TABLE Mesa (
    codMesa INT PRIMARY KEY AUTO_INCREMENT,
    codPedido INT,
    statusMesa VARCHAR(50),
    loginMesa VARCHAR(50),
    senhaMesa VARCHAR(50),
    comanda DECIMAL(10, 2),
    FOREIGN KEY (codPedido) REFERENCES Pedido(codPedido)
);


CREATE TABLE Adicional (
    codAdicional INT PRIMARY KEY AUTO_INCREMENT,
    nomeAdicional VARCHAR(100) NOT NULL,
    precoAdicional DECIMAL(10, 2) NOT NULL
);


CREATE TABLE ProdutoPedido (
    codProPe INT PRIMARY KEY AUTO_INCREMENT,
    codProduto INT,
    codPedido INT,  
    quantidade INT,
    FOREIGN KEY (codProduto) REFERENCES Produto(codProduto),
    FOREIGN KEY (codPedido) REFERENCES Pedido(codPedido)
);
