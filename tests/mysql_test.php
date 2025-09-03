<?php

require_once '../includes/funcoes.php';
require_once '../core/conexao_mysql.php';
require_once '../core/sql.php';
require_once '../core/mysql.php';

insert_teste('Joao', 'joao@ifsp.edu.br', 'abc123');
buscar_teste();
update_teste(38, 'victor', 'victor@gmail.com');
buscar_teste();
update_teste(39, 'abner', 'abner@gmail.com');
buscar_teste();
update_teste(40, 'bryan', 'bryan@gmail.com');
buscar_teste();
update_teste(41, 'lucas', 'lucas@gmail.com');
buscar_teste();

// Teste inserção banco de dados
function insert_teste($nome, $email, $senha): void
{
    $dados = ['nome' => $nome, 'email' => $email, 'senha' => $senha];
    insere('usuario', $dados);
}

// Teste select banco de dados
function buscar_teste(): void
{
    $usuarios = buscar('usuario', ['id', 'nome', 'email'], [], '');
    print_r($usuarios);
}

// Teste update banco de dados
function update_teste($id, $nome, $email): void
{
    $dados = ['nome' => $nome, 'email' => $email];
    $criterio = [['id', '=', $id]];
    atualiza('usuario', $dados, $criterio);
}

?>
