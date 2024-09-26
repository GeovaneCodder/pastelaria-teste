# Pastelaria

### Obs:
É nescessário ter o docker instalado, para instalar siga os passos no link abaixo.
https://docs.docker.com/engine/install/ubuntu/

Para subir o projeto usaremos o pacote sail(padrão do Laravel), segue a doc:
https://laravel.com/docs/11.x/sail

## Iniciando o projeto
Para subir os container's execute:
`./vendor/bin/sail up -d`

Para instalar as depedências execute:
`./vendor/bin/sail composer install`

Para criar as migrações no banco de dados execute:
`./vendor/bin/sail artisan migrate`

Para criar os dados fakes para testes execute:
`./vendor/bin/sail artisan db:seed`

Para executar os testes:
`./vendor/bin/sail test`

As collections do postman se encontra na raiz do projeto no arquivo
```Comerc.postman_collection.json```

## Fluxo
O fluxo consiste da seguinte maneira:
- Um cliente é cadastrado
- Um produto é cadastrado
- Uma pedido é gerado
- Um email é disparado para o cliente

° Um cliente poderá ter vários pedidos
° Um pedido poderá ter vário produtos

O email desparado para o cliente poderá ser vericado em ambiente de teste:
`comerc/storage/logs/laravel.log`