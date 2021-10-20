# Narutogame

## Pré-requisitos
- PHP 8+
- Extensão Curl
- Extensão OpenSSL
- Extensão GD

## Permissões
AS seguintes pastas precisam ter suas permissões(776)/grupo de usuário configurados(de acordo com o usuário/grupo do servidor web) apropriadamente:

- images
- cache
- suporte_upload

## Instalação da crontab
A crontab pode ser inserida diretamente no arquivo **crontab.txt**

## Serviços em NodeJS
Em caso de novo servidor, ter instalado o NodeJS 12 ou superior e o yarn(preferencialmente)/npm.

Entrar em node/chat e node/highlights e executar o:

```shell
yarn install
```

> ***Nunca se deve copiar a pasta "node_modules" dos serviços em node. Ela sempre deve ser instalada pois dependencias nativas podem quebrar.**

> ***Nunca comitar a pasta node_modules dos serviços de forma forçada. Não é boa prática e não faz sentido armazenar dados de depenências.***
