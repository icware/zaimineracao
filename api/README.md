# Comandos Laravel

## Artisan

O Artisan é a interface de linha de comando incluída no Laravel que fornece diversas ferramentas para automatizar tarefas comuns durante o desenvolvimento.

### Comandos Básicos

- `php artisan list`: Lista todos os comandos disponíveis do Artisan.
- `php artisan help <comando>`: Exibe ajuda para um comando específico.
- `php artisan make:model <NomeModelo>`: Cria um novo modelo.
- `php artisan make:controller <NomeControlador>`: Cria um novo controlador.
- `php artisan make:migration <NomeMigracao>`: Cria uma nova migração de banco de dados.
- `php artisan migrate`: Executa as migrações pendentes do banco de dados.
- `php artisan serve`: Inicia o servidor de desenvolvimento.

### Gerenciamento de Pacotes

- `composer require <Pacote>`: Instala um novo pacote via Composer.
- `composer update`: Atualiza todos os pacotes instalados.
- `composer dump-autoload`: Recarrega automaticamente as classes do projeto.

### Testes

- `php artisan make:test <NomeTeste>`: Cria um novo teste.
- `php artisan test`: Executa todos os testes.

### Outros Comandos Úteis

- `php artisan route:list`: Lista todas as rotas registradas na aplicação.
- `php artisan cache:clear`: Limpa o cache da aplicação.
- `php artisan config:cache`: Recompila as configurações da aplicação.
- `php artisan key:generate`: Gera uma nova chave de aplicativo.
- `php artisan optimize`: Otimiza a aplicação para melhor desempenho.