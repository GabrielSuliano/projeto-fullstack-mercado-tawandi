<?php

declare(strict_types=1);

function responderJson(int $status, array $dados): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function lerJsonCorpo(): array
{
    $conteudo = file_get_contents('php://input');

    if (!$conteudo) {
        return [];
    }

    $dados = json_decode($conteudo, true);

    return is_array($dados) ? $dados : [];
}
