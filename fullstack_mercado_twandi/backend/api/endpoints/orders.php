<?php

declare(strict_types=1);

function endpointPedidosCriar(PDO $pdo): void
{
    $usuario = exigirLogin();
    $dados = lerJsonCorpo();

    $itens = $dados['itens'] ?? [];

    if (!is_array($itens) || count($itens) === 0) {
        responderJson(422, [
            'ok' => false,
            'mensagem' => 'Envie ao menos um item para o pedido.',
        ]);
    }

    $idsProdutos = [];
    $quantidades = [];

    foreach ($itens as $item) {
        $produtoId = (int)($item['produto_id'] ?? 0);
        $quantidade = (int)($item['quantidade'] ?? 0);

        if ($produtoId <= 0 || $quantidade <= 0) {
            responderJson(422, [
                'ok' => false,
                'mensagem' => 'Item de pedido invalido.',
            ]);
        }

        $idsProdutos[] = $produtoId;
        $quantidades[$produtoId] = ($quantidades[$produtoId] ?? 0) + $quantidade;
    }

    $idsUnicos = array_values(array_unique($idsProdutos));
    $placeholders = implode(',', array_fill(0, count($idsUnicos), '?'));
    $stmtProdutos = $pdo->prepare("SELECT id, nome, preco FROM produtos WHERE ativo = 1 AND id IN ($placeholders)");
    $stmtProdutos->execute($idsUnicos);
    $produtos = $stmtProdutos->fetchAll();

    if (count($produtos) === 0) {
        responderJson(422, [
            'ok' => false,
            'mensagem' => 'Nenhum produto valido foi encontrado.',
        ]);
    }

    $mapaProdutos = [];
    foreach ($produtos as $produto) {
        $mapaProdutos[(int)$produto['id']] = $produto;
    }

    $total = 0.0;
    $itensPersistir = [];

    foreach ($quantidades as $produtoId => $quantidade) {
        if (!isset($mapaProdutos[$produtoId])) {
            responderJson(422, [
                'ok' => false,
                'mensagem' => 'Carrinho contem produto inexistente.',
            ]);
        }

        $preco = (float)$mapaProdutos[$produtoId]['preco'];
        $subtotal = $preco * $quantidade;
        $total += $subtotal;

        $itensPersistir[] = [
            'produto_id' => $produtoId,
            'quantidade' => $quantidade,
            'preco_unit' => $preco,
            'subtotal' => $subtotal,
        ];
    }

    $pdo->beginTransaction();

    try {
        $stmtPedido = $pdo->prepare('INSERT INTO pedidos (usuario_id, total, status) VALUES (:usuario_id, :total, :status)');
        $stmtPedido->execute([
            ':usuario_id' => (int)$usuario['id'],
            ':total' => $total,
            ':status' => 'recebido',
        ]);

        $pedidoId = (int)$pdo->lastInsertId();

        $stmtItem = $pdo->prepare(
            'INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, preco_unit, subtotal) VALUES (:pedido_id, :produto_id, :quantidade, :preco_unit, :subtotal)'
        );

        foreach ($itensPersistir as $item) {
            $stmtItem->execute([
                ':pedido_id' => $pedidoId,
                ':produto_id' => $item['produto_id'],
                ':quantidade' => $item['quantidade'],
                ':preco_unit' => $item['preco_unit'],
                ':subtotal' => $item['subtotal'],
            ]);
        }

        $pdo->commit();
    } catch (Throwable $erro) {
        $pdo->rollBack();
        responderJson(500, [
            'ok' => false,
            'mensagem' => 'Nao foi possivel finalizar o pedido.',
        ]);
    }

    responderJson(201, [
        'ok' => true,
        'mensagem' => 'Pedido criado com sucesso.',
        'pedido_id' => $pedidoId,
        'total' => round($total, 2),
    ]);
}
