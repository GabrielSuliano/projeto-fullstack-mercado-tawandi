<?php

declare(strict_types=1);

function iniciarSessaoSegura(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function usuarioAutenticado(): ?array
{
    iniciarSessaoSegura();

    if (!isset($_SESSION['usuario'])) {
        return null;
    }

    return $_SESSION['usuario'];
}

function exigirLogin(): array
{
    $usuario = usuarioAutenticado();

    if (!$usuario) {
        responderJson(401, [
            'ok' => false,
            'mensagem' => 'Voce precisa estar logado para executar esta acao.',
        ]);
    }

    return $usuario;
}

function exigirAdmin(): array
{
    $usuario = exigirLogin();

    if ((int)($usuario['eh_admin'] ?? 0) !== 1) {
        responderJson(403, [
            'ok' => false,
            'mensagem' => 'Acesso restrito ao administrador.',
        ]);
    }

    return $usuario;
}
