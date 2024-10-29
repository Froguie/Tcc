CREATE DATABASE tastXbd;
USE tastXbd;

CREATE TABLE usuario (
    codUsuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipoConta ENUM('mesa', 'administrador', 'funcionario', 'cozinha') NOT NULL,
    criadoEm TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE mesa (
    codMesa INT AUTO_INCREMENT PRIMARY KEY,
    numero INT UNIQUE NOT NULL,
    status ENUM('livre', 'ocupada') NOT NULL DEFAULT 'livre',
    codUsuario INT,
    FOREIGN KEY (codUsuario) REFERENCES usuario(codUsuario) ON DELETE SET NULL
);

CREATE TABLE pedido (
    codPedido INT AUTO_INCREMENT PRIMARY KEY,
    codMesa INT NOT NULL,
    status ENUM('pendente', 'preparando', 'pronto', 'entregue') NOT NULL DEFAULT 'pendente',
    total DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    criadoEm TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (codMesa) REFERENCES mesa(codMesa) ON DELETE CASCADE
);

CREATE TABLE produto (
    codProduto INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    descricao TEXT,
    categoria VARCHAR(50)
);

CREATE TABLE adicional (
    codAdicional INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    preco DECIMAL(10, 2) NOT NULL
);

CREATE TABLE produtoPedido (
    codProdutoPedido INT AUTO_INCREMENT PRIMARY KEY,
    codPedido INT NOT NULL,
    codProduto INT NOT NULL,
    quantidade INT NOT NULL DEFAULT 1,
    codAdicional INT,
    FOREIGN KEY (codPedido) REFERENCES pedido(codPedido) ON DELETE CASCADE,
    FOREIGN KEY (codProduto) REFERENCES produto(codProduto) ON DELETE CASCADE,
    FOREIGN KEY (codAdicional) REFERENCES adicional(codAdicional) ON DELETE SET NULL
);

CREATE TABLE cozinha (
    codCozinha INT AUTO_INCREMENT PRIMARY KEY,
    codPedido INT NOT NULL,
    status ENUM('pendente', 'preparando', 'pronto') NOT NULL DEFAULT 'pendente',
    atualizadoEm TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (codPedido) REFERENCES pedido(codPedido) ON DELETE CASCADE
);

CREATE TABLE caixa (
    codCaixa INT AUTO_INCREMENT PRIMARY KEY,
    codPedido INT NOT NULL,
    valorPago DECIMAL(10, 2) NOT NULL,
    metodoPagamento ENUM('dinheiro', 'cartao', 'pix') NOT NULL,
    criadoEm TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (codPedido) REFERENCES pedido(codPedido) ON DELETE CASCADE
);
