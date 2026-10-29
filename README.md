# Doce Gestão

Aplicação didática de uma confeitaria em Laravel 13, com Blade, Docker Compose e MySQL.

## Executar com Docker

Requisitos: Docker Desktop em execução e `docker-compose` disponível no terminal.

```bash
./docker/install.sh
./docker/start.sh
```

Acesse http://localhost:8000. O MySQL usa o volume persistente `mysql_data`.

## Testes PHPUnit

Com os serviços Docker em execução:

```bash
docker-compose exec app php artisan test
```

O PHPUnit usa SQLite em memória; a imagem PHP inclui `pdo_sqlite` para isso.

## Testes Cypress

Instale as dependências JavaScript e mantenha a aplicação Docker em execução:

```bash
npm install
npm run cypress:open
```

Para executar sem a interface do Cypress:

```bash
npm run cypress:run
```

A configuração usa `http://localhost:8000`. Antes da primeira spec, a task Cypress executa `docker-compose exec app php artisan migrate:fresh --seed --force`. Esse comando apaga e recria as tabelas do banco MySQL configurado pela aplicação. Execute a suíte apenas quando esse banco contiver dados descartáveis.

## Módulos

- **Produtos e estoque:** categorias, preço, quantidade, estoque mínimo e alerta visual de estoque baixo.
- **Clientes:** contato e endereço.
- **Pedidos:** preço registrado no pedido, validação de estoque e baixa protegida por transação.
- **Fretes:** pedido relacionado, endereço, taxa, data prevista, rastreio e status.

Os cadastros têm listagem paginada, criação, edição e exclusão. As regras ficam nos controllers, e o esquema do banco fica nas migrations.
