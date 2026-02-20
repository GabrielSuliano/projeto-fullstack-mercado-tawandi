<?php

declare(strict_types=1);

function endpointAdminProdutosListar(PDO $pdo): void
{
    exigirAdmin();

    $stmt = $pdo->query('SELECT id, nome, categoria, descricao, preco, imagem, ativo, criado_em FROM produtos ORDER BY id DESC');
    $produtos = $stmt->fetchAll();

    responderJson(200, [
        'ok' => true,
        'dados' => $produtos,
    ]);
}

function endpointAdminProdutosCriar(PDO $pdo): void
{
    exigirAdmin();
    $dados = lerJsonCorpo();
    $produto = validarPayloadProduto($dados);

    $stmt = $pdo->prepare(
        'INSERT INTO produtos (nome, categoria, descricao, preco, imagem, ativo) VALUES (:nome, :categoria, :descricao, :preco, :imagem, :ativo)'
    );
    $stmt->execute([
        ':nome' => $produto['nome'],
        ':categoria' => $produto['categoria'],
        ':descricao' => $produto['descricao'],
        ':preco' => $produto['preco'],
        ':imagem' => $produto['imagem'],
        ':ativo' => $produto['ativo'],
    ]);

    responderJson(201, [
        'ok' => true,
        'mensagem' => 'Produto criado com sucesso.',
        'id' => (int) $pdo->lastInsertId(),
    ]);
}

function endpointAdminProdutosAtualizar(PDO $pdo): void
{
    exigirAdmin();
    $dados = lerJsonCorpo();
    $id = (int)($dados['id'] ?? 0);

    if ($id <= 0) {
        responderJson(422, [
            'ok' => false,
            'mensagem' => 'ID do produto invalido.',
        ]);
    }

    $produto = validarPayloadProduto($dados);

    $stmt = $pdo->prepare(
        'UPDATE produtos
         SET nome = :nome, categoria = :categoria, descricao = :descricao, preco = :preco, imagem = :imagem, ativo = :ativo
         WHERE id = :id'
    );
    $stmt->execute([
        ':id' => $id,
        ':nome' => $produto['nome'],
        ':categoria' => $produto['categoria'],
        ':descricao' => $produto['descricao'],
        ':preco' => $produto['preco'],
        ':imagem' => $produto['imagem'],
        ':ativo' => $produto['ativo'],
    ]);

    if ($stmt->rowCount() === 0) {
        responderJson(404, [
            'ok' => false,
            'mensagem' => 'Produto nao encontrado.',
        ]);
    }

    responderJson(200, [
        'ok' => true,
        'mensagem' => 'Produto atualizado com sucesso.',
    ]);
}

function endpointAdminProdutosExcluir(PDO $pdo): void
{
    exigirAdmin();
    $dados = lerJsonCorpo();
    $id = (int)($dados['id'] ?? 0);

    if ($id <= 0) {
        responderJson(422, [
            'ok' => false,
            'mensagem' => 'ID do produto invalido.',
        ]);
    }

    $stmt = $pdo->prepare('DELETE FROM produtos WHERE id = :id');
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() === 0) {
        responderJson(404, [
            'ok' => false,
            'mensagem' => 'Produto nao encontrado.',
        ]);
    }

    responderJson(200, [
        'ok' => true,
        'mensagem' => 'Produto excluido com sucesso.',
    ]);
}

function validarPayloadProduto(array $dados): array
{
    $nome = trim((string)($dados['nome'] ?? ''));
    $categoria = trim((string)($dados['categoria'] ?? ''));
    $descricao = trim((string)($dados['descricao'] ?? ''));
    $preco = (float)($dados['preco'] ?? 0);
    $imagem = trim((string)($dados['imagem'] ?? ''));
    $ativo = (int)($dados['ativo'] ?? 1) === 1 ? 1 : 0;

    if ($nome === '' || $categoria === '' || $descricao === '') {
        responderJson(422, [
            'ok' => false,
            'mensagem' => 'Nome, categoria e descricao sao obrigatorios.',
        ]);
    }

    if ($preco <= 0) {
        responderJson(422, [
            'ok' => false,
            'mensagem' => 'Informe um preco valido.',
        ]);
    }

    if ($imagem === '') {
        responderJson(422, [
            'ok' => false,
            'mensagem' => 'Informe a URL da imagem.',
        ]);
    }

    return [
        'nome' => $nome,
        'categoria' => $categoria,
        'descricao' => $descricao,
        'preco' => round($preco, 2),
        'imagem' => $imagem,
        'ativo' => $ativo,
    ];
}
