# Demonstração de WebSockets com PHP

[![Backend](https://github.com/vcampitelli/demo-websockets-php/actions/workflows/backend.yaml/badge.svg)](https://github.com/vcampitelli/demo-websockets-php/actions/workflows/backend.yaml) [![Frontend](https://github.com/vcampitelli/demo-websockets-php/actions/workflows/frontend.yaml/badge.svg)](https://github.com/vcampitelli/demo-websockets-php/actions/workflows/frontend.yaml)

## Instalação

- Instale o [Docker Compose](https://docs.docker.com/compose/install/)
- Clone este repositório
    ```shell-session
    git clone git@github.com:vcampitelli/demo-websockets-php.git
    ```
- Abra um terminal e execute o projeto
    ```shell-session
    docker compose up -d
    ```
- Acompanhe os logs do _backend_:
    ```shell-session
    docker compose logs -f backend
    ```
- Em seu navegador, acesse o _frontend_ em [localhost:5173](http://localhost:5173)
- Abra o _console_ do navegador para acompanhar os _logs_

## Explicação

> @TODO: explicar sobre como funcionam as classes do backend. Aceito PRs :)

## Stack

- Backend
  - PHP 8.4 
  - [Ratchet](http://socketo.me/) para a criação do WebSocket
  - Ferramentas de Desenvolvimento
    - [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer) para _linting_
    - [PHPMD](https://phpmd.org/) para identificar potenciais problemas
    - [PHPStan](https://phpstan.org/) para análise estática
- Frontend
  - [Preact](https://preactjs.com/), uma alternativa mais leve ao React, para a construção da página
  - [Pico CSS](https://picocss.com/) para a UI
  - Ferramentas de Desenvolvimento
    - [Vite](https://vite.dev/) para o _build_
    - [Biome](https://biomejs.dev/) para _linting_ e formatação
