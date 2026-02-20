<?php

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/lib/response.php';
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/endpoints/products.php';
require_once __DIR__ . '/endpoints/auth.php';
require_once __DIR__ . '/endpoints/orders.php';
require_once __DIR__ . '/endpoints/admin_products.php';

$pdo = conectarBanco();

$rota = $_GET['rota'] ?? '';
$metodo = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($rota === 'produtos' && $metodo === 'GET') {
    endpointProdutos($pdo);
}

if ($rota === 'auth.login' && $metodo === 'POST') {
    endpointAuthLogin($pdo);
}

if ($rota === 'auth.registrar' && $metodo === 'POST') {
    endpointAuthRegistrar($pdo);
}

if ($rota === 'auth.logout' && $metodo === 'POST') {
    endpointAuthLogout();
}

if ($rota === 'pedidos.criar' && $metodo === 'POST') {
    endpointPedidosCriar($pdo);
}

if ($rota === 'admin.produtos.listar' && $metodo === 'GET') {
    endpointAdminProdutosListar($pdo);
}

if ($rota === 'admin.produtos.criar' && $metodo === 'POST') {
    endpointAdminProdutosCriar($pdo);
}

if ($rota === 'admin.produtos.atualizar' && $metodo === 'POST') {
    endpointAdminProdutosAtualizar($pdo);
}

if ($rota === 'admin.produtos.excluir' && $metodo === 'POST') {
    endpointAdminProdutosExcluir($pdo);
}

responderJson(404, [
    'ok' => false,
    'mensagem' => 'Rota nao encontrada.',
]);
