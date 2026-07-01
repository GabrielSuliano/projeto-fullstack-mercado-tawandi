# Mercado Tawandi

Projeto full stack de mercado online com vitrine de produtos, carrinho, autenticacao, pedidos e painel administrativo. O sistema combina frontend em HTML/CSS/JavaScript com API em PHP e banco MySQL.

O codigo principal esta em:

```text
fullstack_mercado_twandi/
```

## Recursos

- Home de mercado com catalogo de produtos.
- Carrinho e criacao de pedidos.
- Login, registro e logout.
- Sessao PHP para autenticacao.
- Painel administrativo.
- CRUD de produtos para administradores.
- Seed inicial de admin e produtos.
- API PHP organizada por rotas.

## Stack

- PHP 8+
- MySQL
- PDO
- HTML5
- CSS3
- JavaScript
- React no catalogo/carrinho
- XAMPP/phpMyAdmin para ambiente local

## Estrutura

```text
fullstack_mercado_twandi/
├── backend/api/
│   ├── config/
│   ├── endpoints/
│   └── index.php
├── database/
│   └── schema.sql
└── public/
    ├── assets/
    ├── index.php
    ├── login.php
    ├── registro.php
    └── admin.php
```

## Banco de dados

Crie o banco:

```text
mercado_tawandi
```

Depois importe:

```text
fullstack_mercado_twandi/database/schema.sql
```

Configuracao do banco:

```text
fullstack_mercado_twandi/backend/api/config/database.php
```

Variaveis aceitas:

- `DB_HOST`
- `DB_PORT`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`

## Como rodar no XAMPP

1. Coloque `fullstack_mercado_twandi` dentro de `htdocs`.
2. Inicie Apache e MySQL.
3. Crie o banco `mercado_tawandi`.
4. Importe `database/schema.sql`.
5. Acesse:

```text
http://localhost/fullstack_mercado_twandi/public/
```

Painel admin:

```text
http://localhost/fullstack_mercado_twandi/public/admin.php
```

## Rotas principais da API

```text
GET  api/index.php?rota=produtos
POST api/index.php?rota=auth.login
POST api/index.php?rota=auth.registrar
POST api/index.php?rota=auth.logout
POST api/index.php?rota=pedidos.criar
GET  api/index.php?rota=admin.produtos.listar
POST api/index.php?rota=admin.produtos.criar
POST api/index.php?rota=admin.produtos.atualizar
POST api/index.php?rota=admin.produtos.excluir
```

## Credencial inicial

Quando as tabelas estao vazias, o sistema cria um usuario admin inicial:

```text
admin@mercadotawandi.com
123456
```

## Status

Projeto full stack funcional para portfolio, com loja, API PHP, autenticacao, pedidos e painel administrativo.
