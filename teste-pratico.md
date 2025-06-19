# Desafio Neoestech

## Descrição
O desafio consiste em implementar uma aplicação API Rest utilizando o framework PHP Laravel, um banco de dados relacional (Mysql), que terá como finalidade a inscrição de candidatos a uma oportunidade de emprego.

## Tecnologias utilizadas
**Linguagem:** PHP 8.3.20<br>
**Framework:** Laravel 12<br>
**Banco de dados:** MySQL 8.0<br>
**Containerização:** Docker<br>
**Cache**: Redis

## Variáveis de ambiente (.env):
Após clonar o projeto para a máquina local, é necessário configurar as variáveis de ambiente abaixo:

Renomeie arquivo .env.example para .env 

- **Banco de dados**
    - Deixei as informações do banco da configuradas para uso com o docker.
    - Ps: Caso seja alterado o usuário ou senha, é necessário mudar tambem no docker compose.

## Instalação e configuração Docker
Para utilizar o Docker, o env example já foi configurado com o nome dos serviços (containers) do banco de dados e redis no docker compose, para conexão entre os containers, feito isso acesse a raiz do projeto e apenas execute o comando abaixo:

```bash
docker compose up -d
```
Feito isso, toda estrutura ja será preparada automaticamente, já subindo uma instância container do backend, banco de dados, redis e nginx (para proxy reverso), ele também iniciará o supervisor dentro do container para importação dos dados do CSV, assim que o container subir ele executará os comandos.

Na arquitetura do docker, eu costumo utilizar o script **entrypoint.sh**, pois ele executa diversos comandos ao iniciar o container, assim separo as informações do Dockerfile das dependencias do Laravel (dentro do entrypoint), também utilizo um script muito útil que é o **wait-for-it**, pois as vezes o container do backend inicia antes do banco de dados, e ao rodar as migrations ele falha, então esse script espera o banco estar 100% disponível, para ai sim executar todos comandos do Laravel.

Link para acessar aplicação no browser: http://localhost<br>
Para teste de API (via postman): http://localhost/api

## Instalação e configuração local
### Instalação tecnologias
- PHP 8.3 - <a href="https://www.php.net/downloads.php">Link</a>
- Composer - <a href="https://getcomposer.org/">Link</a>
- Laravel 12 - <a href="https://laravel.com/docs/12.x/installation">Link</a>
- MySQL - <a href="https://www.digitalocean.com/community/tutorials/how-to-install-mysql-on-ubuntu-20-04">Link</a>

### Comandos PHP

#### 1. Instalar as depêndencias do Laravel
```bash
composer install
```

#### 2. Criar no dotenv a chave do projeto
    
```bash
php artisan key:generate
```

#### 3. Executar as migrations, criando as tabelas no banco de dados

```bash
php artisan migrate
```

#### 4. Executar os Seeders, inserindo os dados de usuário e alerta no banco de dados

```bash
php artisan db:seed
```

#### 5. Iniciar o servidor e sobe o projeto
    
```bash
php artisan serve
```

Ps: Lembre-se de alterar as variáveis do .env com as informações locais.

Link para acessar aplicação no browser: http://127.0.0.1:8000
### Usuário para teste (autenticação)

**E-mail:** maria@neoestech.com.br<br>
**Password:** #Recruiter10

### Linha de raciocínio no desenvolvimento

#### Configuração ambiente
Utilizei MySQL e o framework Laravel conforme solicitado. Realizei a configuração do driver e as informações no arquivo .env para acesso ao banco.

#### Design Pattern
Para o Design Pattern da aplicação utilizarei Repository Pattern e Service Layer Pattern, separando em controller, service e repository, deixando a aplicação mais modular e abstraindo a comunicação com o banco de dados das demais lógicas, onde o controller recebe a requisição, realiza as validações dos campos, em seguida é enviado ao service onde serão implementadas validações e regras de negócio (**como por exemplo quando a vaga esta pausada, ninguém pode se candidatar mais**), por fim chegando ao repository para persistência dos dados, usando também injeção de depêndencia, trabalho com essa arquitetura há alguns anos e acho muito eficiente, por isso a minha escolha.

#### CRUD's
Todos CRUD's desenvolvidos com as seguintes regras:

- Podem ser filtrados por qualquer campo especifico pela query **filter**, também ordenado por qualquer campo especifico pela query **order_by** e **order_dir** para direção da ordenação (por exemplo em ordem alfabética ou por IDs crescentes/decrescentes).
- É possível decidir quantidade de itens na paginação, passando a query **paginate**.
- Utilizam softdelete (remoção lógica).
- Validam campos obrigatórios no controller.
- Validam suas regras de negócio no service.
- Utilizam caches em consultas GET.
- Contém testes automatizados.
- Contém endpoint para deleção em massa.

#### Usuários
Para a feature users foi desenvolvido o CRUD completo, implementando também a feature role/função, onde é possível determinar a função do usuário dentro da plataforma, conforme solicitado Recrutador e Candidato, ID's 1 e 2 respectivamente.

Para os testes, execute na raiz do projeto o comando abaixo:

```bash
./vendor/bin/phpunit tests/Unit/UserTest.php
```

#### Vagas
Para a features jobs foi desenvolvido o CRUD completo, adicionando também a regra de negócio para os tipos de vagas e status das vagas, foi criado um enum personalizado com as opções fornecidas: CLT, Pessoa Jurídica ou Freelancer.

Para os testes, execute na raiz do projeto o comando abaixo:

```bash
./vendor/bin/phpunit tests/Unit/JobTest.php
```

#### Candidatos 
Nesta feature achei interessante alterar o nome para applications, achei mais semântico para aplicações a uma vaga de um determinado usuário, então foi desenvolvido o CRUD completo, onde criar é onde o usuário se candidata para uma vaga. Foi desenvolvido a regra de negócio onde é possível se candidatar a uma ou mais vagas, não é possível se candidatar em uma vaga com status **"paused"**, e também desenvolvi o endpoint para pausar e posteriormente finalizar a vaga, onde só é permitido ser feito por um **Recrutador**.

Para os testes, execute na raiz do projeto o comando abaixo:

```bash
./vendor/bin/phpunit tests/Unit/ApplicationTest.php
```

#### Autenticação
Foi desenvolvida uma autenticação simples com Laravel Sanctum, para proteção dos endpoints com chaves.

Ps: Ao testar no postman, se atentar a adiconar **Accept - application/json** no headers, é uma necessidade do laravel para autenticação funcionar corretamente.

#### ImportDataCSV
No ImportDataCSV, criei um comando no routes/console.php que dispara um **Job assíncrono**, onde ele é executado assim que a aplicação sobe, pegando o arquivo CSV que se encontra no resources, percorrendo por ele, extrai as informações percorrendo cada data, e se caso fosse a mesma data, ele adicionaria as temperaturas com essa data em um novo array, assim quando mudar para uma nova data, é adicionado um novo array, e as informações ficam separadas pela data. Posteriormente percorri cada resultado, pegando as temperaturas e realizando os calculos, com funções criadas especificamente para a necessidade, e armazenando ao banco de dados.

Também criei o endpoint /temperatures/information (sem autenticação)

#### Rotas desenvolvidas

**POST** - /api/login -> Realiza o login do usuário  
**GET** - /api/temperatures/information -> Retorna informações com os cálculos da temperatura

#### Rotas privadas (autenticação via Sanctum)

**GET** - /api/users -> Retorna todos os usuários  
**GET** - /api/users/{id} -> Retorna um usuário específico  
**POST** - /api/users -> Cria um novo usuário  
**PUT** - /api/users/{id} -> Atualiza um usuário específico  
**DELETE** - /api/users/{id} -> Remove um usuário específico  
**DELETE** - /api/users/delete -> Remove usuários em massa

**GET** - /api/jobs -> Retorna todas as vagas  
**GET** - /api/jobs/{id} -> Retorna uma vaga específica  
**POST** - /api/jobs -> Cria uma nova vaga  
**PUT** - /api/jobs/{id} -> Atualiza uma vaga específica  
**DELETE** - /api/jobs/{id} -> Remove uma vaga específica  
**POST** - /api/jobs/{id}/finish -> Finaliza uma vaga  
**POST** - /api/jobs/{id}/pause -> Pausa uma vaga  
**DELETE** - /api/jobs/delete -> Remove vagas em massa

**GET** - /api/applications/job/{id} -> Retorna candidaturas de uma vaga específica  
**GET** - /api/applications/user/{id} -> Retorna candidaturas de um usuário específico  
**POST** - /api/applications -> Cria uma nova candidatura  
**PUT** - /api/applications/{id} -> Atualiza o status de uma candidatura  
**DELETE** - /api/applications/{id} -> Remove uma candidatura 
**DELETE** - /api/applications/delete -> Remove candidaturas em massa

**POST** - /api/logout -> Realiza o logout do usuário