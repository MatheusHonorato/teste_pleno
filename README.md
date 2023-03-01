# Desafio | Fullstack

O teste consiste em implementar uma lista de contatos e empresas. O projeto, obrigatoriamente, deve ser separado em backend e frontend.

## Backend

O backend **deve** ser desenvolvido em `php` e **deve** conter uma API Rest.

O sistema deve conter as seguintes entidades e seus respectivos campos:

- Usuário
    - Nome: obrigatório para preenchimento
    - E-mail: obrigatório para preenchimento
    - Telefone: não obrigatório
    - Data de nascimento: não obrigatório
    - Cidade onde nasceu: não obrigatório
    - Empresas: obrigatório para preenchimento

- Empresa
    - Nome: obrigatório para preenchimento
    - CNPJ: obrigatório para preenchimento
    - Endereço: obrigatório para preenchimento
    - Usuários: obrigatório para preenchimento

A regra de relacionamento para `Usuário` e `Empresa` deve ser de __n para n__

### Banco
Você **deve** utilizar um banco de dados para o sistema. Pode-se escolher qualquer opção que desejar, mas o seguite cenário deve ser levado em consideração:
- O sistema lida com informações sensíveis e preza pela integridade dos dados
- O sistema lida com diferentes entidades relacionadas

Pedimos para que nos sinalize o motivo da escolha do banco no final do documento

O banco de dados escolhido foi o MySQL. O banco de dados MySQL já tem se consolidado no mercado como um banco de dados robusto e seguro, sendo ideal para utilização em sistemas que lidam com dados sensiveis e integridade de dados. Além disso
o MySQL possui o recurso de transactions que é essencial para atomicidade das operações realizadas no banco possibilitando maior garantia de consistência dos dados. O banco mysql possui quando habilitado na criação de suas tabelas ENGINE=InnoDB,
o uso de chaves estrangeiras, essencial para trabalhar com bancos que possuem diferentes entidades relacionadas.

## Frontend
O frontend **deve** ser desenvolvido utilizando `react` e **deve** usar os dados fornecidos pela API.

Você **pode** e, de preferência, **deve** utilizar bibliotecas de terceiros.

Deve-se desenvolver uma página de formulário para cada uma das entidades (`Usuario` e `Empresa`). Também deve ser desenvolvida uma página listando todos os usuários e seus respectivos campos, inclusive todas as empresas de que ele faz parte.

Deve-se ter a possibilidade de filtrar os dados conforme cada um dos campos.

Obs: para facilitar, segue uma proposta de layout, você tem liberdade para desenvolver o layout da forma que achar mais adequado.

## Testes
Testes unitários **devem** ser implementados no backend para validação das operações do CRUD.

Testes unitários **devem** ser implementados no frontend para a tela de exibição dos usuários.

Você pode utilizar o framework de sua preferência tanto para o backend quanto para o frontend.

## Ambiente
Aqui na Contato Seguro, utilizamos __Docker__ nos nossos ambientes, então será muito bem visto caso decida utilizar. Principalmente para que tenhamos o mesmo resultado (mesma configuração de ambiente). Caso desenvolva com docker, nos envie junto com o projeto o `docker-compose.yml` e/ou os `Dockerfile´`s.

## Requisitos mínimos
- As 4 operações CRUD, tanto para entidade `Usuário`, quanto para `Empresa`. Todas as operações devem ter rotas específicas no backend.
- O filtro de registros
- Código legível, limpo e seguindo boas práticas de Orientação a Objetos
- Um dump ou DDL do banco de dados
- Testes unitários

## Requisitos bônus
- Utilizar Docker
- Outras entidades e relacionamento entre entidades. Por exemplo: uma entidade `Relatos` ou `Atividades` que tenha `Usuários` e/ou `Empresas` vinculadas.
- Separação em commits, especialmente com boas mensagens de identificação.

# Resposta do participante

## Tutorial de como rodar o back-end da aplicação(API):

### Softwares necessários

- Docker
- Docker-Compose

### Paso a passo

- Instalando

Se possui o git instalado:

Clone o repositório em: https://github.com/MatheusHonorato/teste_pleno

Se não possui o git instalado:

Acesse:  https://github.com/MatheusHonorato/teste_pleno

Clique em: CODE > Download ZIP

- Rodando a api

Após efetuar o download do projeto é necessário executar os seguintes passos:

- Habilite a instalação do seu docker;
- Acesse a raiz do projeto e rode: 'docker run build' para fazer o build do arquivo Dockerfile;
- Copie o arquivo '.env-example' e renomeie para '.env';
- Após o build rode o comando: 'docker-compose up -d' para subir os containers, rodar a aplicação e o script build para criar as tabelas no banco e inserir dados default;
- Acesse o bash do container php com o comando 'docker exec -ti app bash' e rode o comando 'composer install' para instalar as dependencias do projeto;
- Aguarde alguns segundos e acesse o servidor da aplicação que estará disponível em: 'http://localhost:8000';
- Se ocorrer algum erro rode 'docker-compose ps' e verifique a coluna 'State' de cada container, se alguma não estiver como 'Up' provavelmente alguma porta já está sendo utilizada no sistema,
para resolver de forma rapida e conseguir testar a aplicação altere as portas utilizadas pelos containers no arquivo docker-compose.yml, rode 'docker-compose down' e inicie o processo novamente;
- A API pode ser testada de maneira isolada em softwares como o insomnia ou postman.

- Rodando testes

Par rodar os testes é necessário acessar o container docker onde o php está sendo interpretado utilizando o seguinte comando: 'docker exec -ti app bash'. Em seguinda execute o comando 'vendor/bin/phpunit tests/' para rodar os testes.

# Rotas API:


## Companies

CompanyFind

    Método: GET

    Endereço: http://localhost:8000/company/1

CompanyList

    Método: GET

    Endereço: http://localhost:8000/company

CompanySearch

    Método: GET

    Endereço: http://localhost:8000/company?name=empresa

CompanyCreate

    Método: POST

    Endereço: http://localhost:8000/company

    JSON:

    {
        "name": "Empresa teste",
        "cnpj": "12345600001",
        "address": "Rua exemplo",
        "user_ids": [1]
    }

CompanyUpdate

    Método: PUT

    Endereço: http://localhost:8000/company/1

    JSON:

    {
        "name": "Empresa teste update",
        "cnpj": "12345600001",
        "address": "Rua exemplo",
        "user_ids": [1]
    }

CompanyDelete

    Método: DELETE

    Endeeço: http://localhost:8000/company/1

## Users

UserFind

    Método: GET

    Endereço: http://localhost:8000/user/1

UserList

    Método: GET

    Endereço: http://localhost:8000/user

UserSearch

    Método: GET

    Endereço: http://localhost:8000/user?name=empresa

UserCreate

    Método: POST

    Endereço: http://localhost:8000/user

    JSON:

    {
        "name": "teste",
        "email": "teste@teste.com",
        "date": "2020-05-05",
        "city": "moc",
        "phone": "3222222",
        "company_ids": [1]
    }

UserUpdate

    Método: PUT

    Endereço: http://localhost:8000/user/1

    JSON:

    {
        "name": "teste update",
        "email": "teste@teste.com",
        "date": "2020-05-05",
        "city": "moc",
        "phone": "3222222",
        "company_ids": [1]
    }

UserDelete

    Método: DELETE

    Endeeço: http://localhost:8000/user/1


## Um pouco sobre a aplicação (API)

Stack utilizada:

- Git
- Docker
- PHP 8.1.0
- Composer 2
- Mysql 5.7
- phpmyadmin
- nginx

Pacotes:

- vlucas/phpdotenv 5.5
- guzzlehttp/guzzle 7.5
- phpunit/phpunit 10.0

Descrição

A aplicação foi desenvolvida utilizando php orientado a objetos com tipagem forte e arquitetura model, controller. Além dos models e controllers, para um maior desacoplamento da aplicação foi aplicado o padrão singleton na conexão com o banco de dados, garantindo que não sejam abertas varias conexões desnecessarias com o mysql.

Uma versão simplificada do padrão querybuilder foi utilizada para abstrair as querys do banco de dados. Services foram criados para que os controllers não ficassem inchados com regras de negócio. O sistema de roteamento da api é feito carregando os controladores a partir dos endereços das rotas com o respectivo método http 
ex: rota 'users' utilizando o método http 'get' carrega o  método de nome 'get' no controlador 'UserController'.

## Pricipais dificuldades e dúvidas

A principal dificuldade durante o processo de desenvolvimento foi trabalhar com uma abstração para o banco de dados. No desenvolvimento da aplicação tive a ideia de utilizar o query builder para abstrair as consultas do banco e deixar o software mais desacoplado, o que acabou levando um bom tempo de desenvolvimento
e na minha opinião um certo overengineering.
ALém disso o escopo bem aberto das possibilidades no back-end geraram dúvidas em relação ao que a empresa espera do teste.

## Melhorias propostas

- Melhora da implementação do query builder possibilitando trabalhar também com joins e assim não ter a necessidade de fazer várias consultas no service;
- Implementação de paginação;
- Implementação de interfaces principalmente nas classes relacionadas ao banco de dados para garantir o principio inversão de dependências do SOLID onde aprendemos que é melhor depender de interfaces do que de implementações;
- Fiquei na dúvida sobre a utilização de pacotes no backend. Uma boa melhoria seria a utilização de pacotes consolidados para algumas tarefas como um sistema de rotas robusto.
