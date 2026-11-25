# Configuração local e efeitos de inicialização

Requer PHP 8+, PDO MySQL/mbstring, MySQL/MariaDB e rede para dependências frontend via CDN. Sirva `fullstack_mercado_twandi/public/` mantendo o backend adjacente: o proxy PHP inclui o roteador por caminho relativo.

Configure `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS` no processo PHP. [.env.example](../.env.example) é referência: **não há loader dotenv**. Crie banco local novo `mercado_tawandi` e revise `database/schema.sql` antes de importação manual.

## Efeitos ao abrir a aplicação

`conectarBanco()` chama `inicializarBanco()`, que lê/executa o esquema, verifica/adiciona `eh_admin`, cria conta administrativa fixa e produtos quando necessário e pode promover a conta interna se não houver administrador. Até uma consulta ao catálogo pode disparar escritas.

A auditoria não iniciou o servidor nem fez requests à API. O bootstrap é uma limitação atual, preservada nesta revisão. Não aponte o processo para produção e não exponha a conta padrão. Não copie valores fixos para READMEs ou exemplos de variáveis.

## Autorização e contrato

A sessão PHP identifica o usuário; `exigirLogin()` protege pedidos e `exigirAdmin()` protege produtos. `eh_admin` controla o privilégio. O formulário não substitui a verificação do endpoint.

Após preparação local, a leitura do catálogo é `GET /fullstack_mercado_twandi/public/api/index.php?rota=produtos`. Outras rotas estão no README e no roteador; POSTs usam JSON com `Content-Type: application/json`.

O projeto registra pedidos, sem processar pagamentos. Testes e endurecimento do bootstrap são trabalho futuro.
