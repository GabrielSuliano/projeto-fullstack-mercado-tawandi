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

## Inicialização administrativa

A conexão contém uma rotina de inicialização com conta administrativa fixa e produtos demonstrativos. Não use a conta em ambiente público; consulte [configuração e limites](docs/SETUP.md). Credenciais não são reproduzidas aqui.

## Status

Implementação demonstrativa full stack para portfólio, com loja, API PHP, autenticacao, pedidos e painel administrativo.

## Arquitetura e contratos

Páginas PHP e React carregado pelo navegador fornecem catálogo/carrinho. `public/api/index.php` encaminha ao roteador `backend/api/index.php`; endpoints usam `lib/auth.php` e PDO. Não há instalação npm ou bundle React neste projeto.

O banco relaciona `usuarios` 1:N `pedidos`, `pedidos` 1:N `pedido_itens` e `produtos` 1:N `pedido_itens`. Pedidos usam transação e valores do catálogo no servidor; não há gateway de pagamento. Confira [configuração e limites](docs/SETUP.md).

## Testes e evolução

Não há suíte automatizada ou deploy confirmado. Esta revisão leu rotas, autenticação, pedidos e esquema sem executar a aplicação: a conexão inicializa dados automaticamente. Prioridades: retirar conta fixa do bootstrap em futura alteração de código, separar migrations do HTTP e testar sessão/pedidos/CSRF. Não há licença versionada.
