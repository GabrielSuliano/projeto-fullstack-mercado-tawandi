# Mercado Tawandi

Sistema de loja virtual com frontend refinado, API em PHP, login/sessao e CRUD admin de produtos.

## Stack
- Frontend: HTML5, CSS, JavaScript e React (catalogo/carrinho)
- Backend: PHP 8+, PDO, sessoes
- Banco: MySQL (phpMyAdmin)

## Banco no phpMyAdmin (exato)
Crie exatamente este banco:

- Nome do banco: `mercado_tawandi`
- Collation: `utf8mb4_unicode_ci`

Depois importe este arquivo:
- `database/schema.sql`

## Credenciais do banco no projeto
Arquivo: `backend/api/config/database.php`

Padrao atual:
- Host: `127.0.0.1`
- Porta: `3306`
- Banco: `mercado_tawandi`
- Usuario: `root`
- Senha: vazia (`''`)

Se seu MySQL for diferente, ajuste no `database.php` ou configure variaveis de ambiente:
- `DB_HOST`
- `DB_PORT`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`

## Como rodar no XAMPP
1. Coloque a pasta do projeto em `htdocs`.
2. Inicie `Apache` e `MySQL` no XAMPP.
3. No phpMyAdmin, crie o banco `mercado_tawandi`.
4. Importe `database/schema.sql` nesse banco.
5. Abra:
   - `http://localhost/Nova%20pasta%20(3)/public/`

## Seed inicial
Na primeira requisicao, se as tabelas estiverem vazias, o sistema cria:
- Usuario admin: `admin@mercadotawandi.com` / `123456`
- Produtos iniciais de exemplo

Painel admin:
- `http://localhost/Nova%20pasta%20(3)/public/admin.php`

## Rotas da API
- `GET api/index.php?rota=produtos`
- `POST api/index.php?rota=auth.login`
- `POST api/index.php?rota=auth.registrar`
- `POST api/index.php?rota=auth.logout`
- `POST api/index.php?rota=pedidos.criar`
- `GET api/index.php?rota=admin.produtos.listar`
- `POST api/index.php?rota=admin.produtos.criar`
- `POST api/index.php?rota=admin.produtos.atualizar`
- `POST api/index.php?rota=admin.produtos.excluir`
