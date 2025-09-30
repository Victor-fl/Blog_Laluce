<?php

function insere(string $entidade, array $dados): bool //$entidade = tabela e $dados = campos??; retorna T ou F
{
    $retorno = false; // retorna falso ate confirmar sucesso

    foreach ($dados as $campo => $dado) { //"passa por" cada campo que foi inserido
        $coringa[$campo] = '?'; // para cada campo cria um placeholder "?" = espaço reservado no SQL que sera preenchido pelo bind_param. Deixa mais seguro? sla
        $tipo[] = gettype($dado)[0]; // pega a primeira letra do tipo do dado, usados depois em: mysqli_stmt_bind_param
        $$campo = $dado; // se $campo = 'titulo', logo, $titulo = $dado
    }

    $instrucao = insert($entidade, $coringa);

    $conexao = conecta();

    $stmt = mysqli_prepare($conexao, $instrucao);

    eval('mysqli_stmt_bind_param($stmt, \'' . implode('', $tipo) . '\', $' . implode(', $', array_keys($dados)) . ');');

    mysqli_stmt_execute($stmt);

    $retorno = (bool) mysqli_stmt_affected_rows($stmt);

    $_SESSION['errors'] = mysqli_stmt_error_list($stmt);

    mysqli_stmt_close($stmt);

    desconecta($conexao); 

    return $retorno;
}

function atualiza(string $entidade, array $dados, array $criterio = []): bool //$criterio seria tipo o WHERE id = x, onde cada where sera uma array dentro de outra array
{
    $retorno = false;

    foreach ($dados as $campo => $dado) { // $campo = 'nome' e $dado = 'Victor'
        $coringa_dados[$campo] = '?'; // prepara o placeholders para os campos atualizados
        $tipo[] = gettype($dado)[0];
        $$campo = $dado;
    }

    foreach ($criterio as $expressao) { 
        $dado = $expressao[count($expressao) - 1]; // pega o ultimo item da expressão (numero do id)
        $tipo[] = gettype($dado)[0];
        $expressao[count($expressao) - 1] = '?';// coloca um placeholder no lugar do numero do id, para que serve isso?
        $coringa_criterio[] = $expressao;

        $nome_campo = (count($expressao) < 4) ? $expressao[0] : $expressao[1];

        if (isset($nome_campo)) { // verifica se o campo existe?
            $nome_campo = $nome_campo . '_' . rand();
        }

        $campos_criterio[] = $nome_campo;

        $$nome_campo = $dado;
    }

    $instrucao = update($entidade, $coringa_dados, $coringa_criterio); // uptade $entidade set $coringa_dados where $coringa_criterios

    $conexao = conecta();

    $stmt = mysqli_prepare($conexao, $instrucao);

    if (isset($tipo)) {
        $comando = 'mysqli_stmt_bind_param($stmt, "';
        $comando .= implode('', $tipo) . '", ';
        $comando .= '$' . implode(', $', array_keys($dados));
        $comando .= ', $' . implode(', $', $campos_criterio);
        $comando .= ');';

        eval($comando);
    }

    mysqli_stmt_execute($stmt);

    $retorno = (bool) mysqli_stmt_affected_rows($stmt);

    $_SESSION['errors'] = mysqli_stmt_error_list($stmt);

    mysqli_stmt_close($stmt);

    desconecta($conexao);

    return $retorno;
}


function deleta(string $entidade, array $criterio = []): bool
{
    $retorno = false;

    $coringa_criterio = [];

    foreach ($criterio as $expressao) {
        $dado = $expressao[count($expressao) - 1];

        $tipo[] = gettype($dado)[0];
        $expressao[count($expressao) - 1] = '?';
        $coringa_criterio[] = $expressao;

        $nome_campo = (count($expressao) < 4) ? $expressao[0] : $expressao[1];

        $campos_criterio[] = $nome_campo;

        $$nome_campo = $dado;
    }

    $instrucao = delete($entidade, $coringa_criterio); // delete from  $entidade where $coringa_criterio

    $conexao = conecta();

    $stmt = mysqli_prepare($conexao, $instrucao);

    if (isset($tipo)) {
        $comando = 'mysqli_stmt_bind_param($stmt, "';
        $comando .= implode('', $tipo) . '", ';
        $comando .= '$' . implode(', $', $campos_criterio);
        $comando .= ');';

        eval($comando);
    }

    mysqli_stmt_execute($stmt);

    $retorno = (bool) mysqli_stmt_affected_rows($stmt);

    $_SESSION['errors'] = mysqli_stmt_error_list($stmt);

    mysqli_stmt_close($stmt);

    desconecta($conexao);

    return $retorno;
}

function buscar(string $entidade, array $campos = ['*'], array $criterio = [], string $ordem = null): array
{
    $retorno = false;
    $coringa_criterio = [];

    foreach ($criterio as $expressao) {
        $dado = $expressao[count($expressao) - 1];

        $tipo[] = gettype($dado)[0];
        $expressao[count($expressao) - 1] = '?';
        $coringa_criterio[] = $expressao;

        $nome_campo = (count($expressao) < 4) ? $expressao[0] : $expressao[1];

        if (isset($$nome_campo)) {
            $nome_campo = $nome_campo . '_' . rand();
        }

        $campos_criterio[] = $nome_campo;

        $$nome_campo = $dado;
    }

    $instrucao = select($entidade, $campos, $coringa_criterio, $ordem); // select $campos from $entidade where $coringa_criterio order by $ordem

    $conexao = conecta();

    $stmt = mysqli_prepare($conexao, $instrucao);

    if (isset($tipo)) {
        $comando = 'mysqli_stmt_bind_param($stmt, "';
        $comando .= implode('', $tipo) . '", ';
        $comando .= '$' . implode(', $', $campos_criterio);
        $comando .= ');';

        eval($comando);
    }

    mysqli_stmt_execute($stmt);

    if ($result = mysqli_stmt_get_result($stmt)) {
        $retorno = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
    }

    $_SESSION['errors'] = mysqli_stmt_error_list($stmt);

    mysqli_stmt_close($stmt);

    desconecta($conexao);

    $retorno = $retorno;

    return $retorno;
}

?>