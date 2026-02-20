<?php

declare(strict_types=1);

function endpointAuthLogin(PDO $pdo): void
{
    $dados = lerJsonCorpo();

    $email = strtolower(trim((string)($dados['email'] ?? '')));
    $senha = (string)($dados['senha'] ?? '');

    if ($email === '' || $senha === '') {
        responderJson(422, [
            'ok' => false,
            'mensagem' => 'Informe email e senha.',
        ]);
    }

    $stmt = $pdo->prepare('SELECT id, nome, email, senha_hash, eh_admin FROM usuarios WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch();

    if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
        responderJson(401, [
            'ok' => false,
            'mensagem' => 'Credenciais invalidas.',
        ]);
    }

    iniciarSessaoSegura();
    $_SESSION['usuario'] = [
        'id' => (int) $usuario['id'],
        'nome' => $usuario['nome'],
        'email' => $usuario['email'],
        'eh_admin' => (int) $usuario['eh_admin'],
    ];

    responderJson(200, [
        'ok' => true,
        'mensagem' => 'Login realizado com sucesso.',
        'usuario' => $_SESSION['usuario'],
    ]);
}

function endpointAuthRegistrar(PDO $pdo): void
{
    $dados = lerJsonCorpo();

    $nome = trim((string)($dados['nome'] ?? ''));
    $email = strtolower(trim((string)($dados['email'] ?? '')));
    $senha = (string)($dados['senha'] ?? '');

    if ($nome === '' || $email === '' || $senha === '') {
        responderJson(422, [
            'ok' => false,
            'mensagem' => 'Nome, email e senha sao obrigatorios.',
        ]);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        responderJson(422, [
            'ok' => false,
            'mensagem' => 'Email invalido.',
        ]);
    }

    if (mb_strlen($senha) < 6) {
        responderJson(422, [
            'ok' => false,
            'mensagem' => 'A senha precisa ter ao menos 6 caracteres.',
        ]);
    }

    $stmtExiste = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email LIMIT 1');
    $stmtExiste->execute([':email' => $email]);

    if ($stmtExiste->fetch()) {
        responderJson(409, [
            'ok' => false,
            'mensagem' => 'Ja existe uma conta com este email.',
        ]);
    }

    $stmt = $pdo->prepare('INSERT INTO usuarios (nome, email, senha_hash, eh_admin) VALUES (:nome, :email, :senha_hash, :eh_admin)');
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha_hash' => password_hash($senha, PASSWORD_DEFAULT),
        ':eh_admin' => 0,
    ]);

    $id = (int) $pdo->lastInsertId();

    iniciarSessaoSegura();
    $_SESSION['usuario'] = [
        'id' => $id,
        'nome' => $nome,
        'email' => $email,
        'eh_admin' => 0,
    ];

    responderJson(201, [
        'ok' => true,
        'mensagem' => 'Conta criada com sucesso.',
        'usuario' => $_SESSION['usuario'],
    ]);
}

function endpointAuthLogout(): void
{
    iniciarSessaoSegura();
    $_SESSION = [];
    session_destroy();

    responderJson(200, [
        'ok' => true,
        'mensagem' => 'Sessao encerrada.',
    ]);
}
