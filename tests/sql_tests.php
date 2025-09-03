<?php
require_once '../core/sql.php';

$id = 1;
$nome = 'victor';
$email = 'victor@gmail.com';
$senha = 'abc123';
$dados = [
    'nome' => $nome,
    'email' => $email,
    'senha' => $senha
];

$entidade = 'usuario';
$criterio = [['id', '=', $id]];
$campos = ['id', 'nome', 'email'];

print_r($dados);
echo '  
';
print_r($campos);
echo '  
';
print_r($criterio);
echo '  
';

// Teste geração INSERT
$instrucao = insert($entidade, $dados);
echo $instrucao . '  
';

// Teste geração UPDATE
$instrucao = update($entidade, $dados, $criterio);
echo $instrucao . '  
';

// Teste geração SELECT
$instrucao = select($entidade, $campos, $criterio);
echo $instrucao . '  
';

// Teste geração DELETE
$instrucao = delete($entidade, $criterio);
echo $instrucao . '  
';
?>
