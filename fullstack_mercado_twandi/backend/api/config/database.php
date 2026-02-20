<?php

declare(strict_types=1);

function conectarBanco(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $porta = getenv('DB_PORT') ?: '3306';
    $banco = getenv('DB_NAME') ?: 'mercado_tawandi';
    $usuario = getenv('DB_USER') ?: 'root';
    $senha = getenv('DB_PASS') ?: '';

    $dsn = "mysql:host={$host};port={$porta};dbname={$banco};charset=utf8mb4";
    $pdo = new PDO($dsn, $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    inicializarBanco($pdo);

    return $pdo;
}

function inicializarBanco(PDO $pdo): void
{
    $schemaPath = __DIR__ . '/../../../database/schema.sql';
    $schemaSql = file_get_contents($schemaPath);

    if ($schemaSql !== false) {
        $pdo->exec($schemaSql);
    }

    garantirColunaUsuariosAdmin($pdo);

    $countUsuarios = (int) $pdo->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();
    if ($countUsuarios === 0) {
        $stmt = $pdo->prepare('INSERT INTO usuarios (nome, email, senha_hash, eh_admin) VALUES (:nome, :email, :senha_hash, :eh_admin)');
        $stmt->execute([
            ':nome' => 'Administrador',
            ':email' => 'admin@mercadotawandi.com',
            ':senha_hash' => password_hash('123456', PASSWORD_DEFAULT),
            ':eh_admin' => 1,
        ]);
    } else {
        // Garante ao menos um administrador para acessar o CRUD.
        $countAdmins = (int) $pdo->query('SELECT COUNT(*) FROM usuarios WHERE eh_admin = 1')->fetchColumn();
        if ($countAdmins === 0) {
            $stmtAdmin = $pdo->prepare('UPDATE usuarios SET eh_admin = 1 WHERE email = :email');
            $stmtAdmin->execute([':email' => 'admin@mercadotawandi.com']);
        }
    }

    $countProdutos = (int) $pdo->query('SELECT COUNT(*) FROM produtos')->fetchColumn();
    if ($countProdutos === 0) {
        $produtos = [
            ['Cesta Prime Mensal', 'Assinatura', 'Selecao mensal de produtos premium para sua casa.', 189.90, 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=900&q=80'],
            ['Kit Organico Essencial', 'Organicos', 'Frutas e vegetais sem agrotoxicos, colhidos na semana.', 119.50, 'https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&w=900&q=80'],
            ['Linha Gourmet Italiana', 'Importados', 'Massas, azeites e molhos selecionados para cozinha autoral.', 249.00, 'https://images.unsplash.com/photo-1473093226795-af9932fe5856?auto=format&fit=crop&w=900&q=80'],
            ['Selecao Cafes Especiais', 'Bebidas', 'Graos especiais torrados em micro-lotes.', 96.70, 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=900&q=80'],
            ['Combo Bem-Estar', 'Saudavel', 'Proteinas, snacks naturais e itens funcionais.', 139.90, 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=900&q=80'],
            ['Adega Smart Selection', 'Bebidas', 'Vinhos e harmonizacoes para jantares especiais.', 329.00, 'https://images.unsplash.com/photo-1510627498534-cf7e9002facc?auto=format&fit=crop&w=900&q=80']
        ];

        $stmt = $pdo->prepare(
            'INSERT INTO produtos (nome, categoria, descricao, preco, imagem, ativo) VALUES (:nome, :categoria, :descricao, :preco, :imagem, 1)'
        );

        foreach ($produtos as $produto) {
            $stmt->execute([
                ':nome' => $produto[0],
                ':categoria' => $produto[1],
                ':descricao' => $produto[2],
                ':preco' => $produto[3],
                ':imagem' => $produto[4],
            ]);
        }
    }
}

function garantirColunaUsuariosAdmin(PDO $pdo): void
{
    $stmt = $pdo->query("SHOW COLUMNS FROM usuarios LIKE 'eh_admin'");
    $coluna = $stmt->fetch();

    if (!$coluna) {
        $pdo->exec('ALTER TABLE usuarios ADD COLUMN eh_admin TINYINT(1) NOT NULL DEFAULT 0');
    }
}
