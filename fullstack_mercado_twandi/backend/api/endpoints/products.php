<?php

declare(strict_types=1);

function endpointProdutos(PDO $pdo): void
{
    $stmt = $pdo->query('SELECT id, nome, categoria, descricao, preco, imagem FROM produtos WHERE ativo = 1 ORDER BY id DESC');
    $produtos = $stmt->fetchAll();

    responderJson(200, [
        'ok' => true,
        'dados' => $produtos,
    ]);
}
