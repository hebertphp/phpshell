# Ambiente de Desenvolvimento PHP + MySQL + SQLite (com driver PostgreSQL)

Ambiente leve para as aulas de **PHP com banco de dados**. Com um único comando sobem o servidor web com PHP 8.2, o banco MySQL e o phpMyAdmin. O PHP já tem os drivers de **MySQL**, **SQLite** e **PostgreSQL**. Não é preciso instalar nada no computador: tudo roda no [Google Cloud Shell](https://shell.google.com).

## O que sobe

| Serviço | O que é | Porta | Para que serve |
|---|---|---|---|
| `web` | PHP 8.2 (servidor embutido, imagem Alpine) | 8080 | Executa os arquivos da pasta `src/` |
| `mysql_db` | MySQL 8.0 | (interna) | Banco `meubanco_mysql` |
| `phpmyadmin` | phpMyAdmin | 8081 | Interface do MySQL pelo navegador |

- **SQLite** não é um servidor: é o arquivo `src/database.sqlite`, criado pelo PHP na primeira execução.
- **PostgreSQL**: o PHP tem o driver (`pdo_pgsql`), mas este ambiente **não inclui um servidor PostgreSQL**. Para praticar, conecte em um servidor externo (por exemplo, um banco gratuito no Supabase ou no Neon).

## 1. Iniciar no Google Cloud Shell

1. Abra [shell.google.com](https://shell.google.com) e entre com sua conta Google.
2. Clone o repositório e entre na pasta:

   ```bash
   git clone https://github.com/hebertphp/phpshell.git
   cd phpshell
   ```

3. Suba o ambiente:

   ```bash
   docker compose up -d
   ```

   Na primeira vez as imagens são baixadas (alguns minutos) e o comando só termina quando o MySQL estiver pronto (cerca de 30 segundos). Nas próximas vezes é rápido.

4. Confira se tudo está de pé (a coluna `STATUS` deve mostrar `Up`):

   ```bash
   docker compose ps
   ```

> ⚠️ **Não aperte Ctrl+C no terminal do ambiente.**
> Se o terminal estiver mostrando os logs (por exemplo, porque você rodou `docker compose up` **sem o `-d`**), o Ctrl+C **para todos os containers** e o sistema sai do ar.
>
> - **Para rodar outros comandos** (como `git pull`), abra uma **aba nova** do terminal no botão **+** da barra do Cloud Shell, em vez de interromper a primeira.
> - **Para ver os logs** sem risco: `docker compose logs -f web`. Aqui o Ctrl+C só sai da tela de logs.
> - **Já parou?** Volte para a pasta `phpshell` e rode `docker compose up -d`. Os dados do MySQL continuam lá.

## 2. Testar no navegador (Visualização na Web)

O Cloud Shell não tem endereço público: você abre as portas pelo botão **Visualização na Web** (ícone de tela com seta), na barra de ferramentas do Cloud Shell, no canto superior direito.

### 2.1 Testar o PHP e os bancos (porta 8080)

1. Clique em **Visualização na Web** e escolha **Visualizar na porta 8080**.
2. Uma nova aba abre com a página `src/index.php`. O resultado esperado é:

   - ✅ Conexão com **MySQL** realizada com sucesso!
   - ✅ Conexão com **SQLite** realizada com sucesso!
   - ✅ Driver **PostgreSQL** (pdo_pgsql) instalado.

3. Se algum aparecer com ❌, veja [Problemas comuns](#problemas-comuns).

### 2.2 Abrir o phpMyAdmin (porta 8081)

1. Em **Visualização na Web**, escolha **Alterar porta**, informe `8081` e confirme.
2. Entre com:

   | Usuário | Senha |
   |---|---|
   | `aluno` | `alunopassword` |

   (O usuário administrador é `root`, senha `rootpassword`.)
3. Escolha o banco `meubanco_mysql` na lista da esquerda.

## 3. Testar pelo terminal

Também dá para conversar com os bancos direto no terminal do Cloud Shell, sem navegador:

```bash
# MySQL
docker compose exec mysql_db mysql -ualuno -palunopassword meubanco_mysql

# SQLite
docker compose exec web sqlite3 /var/www/html/database.sqlite
```

Dentro do MySQL, experimente `SHOW TABLES;`. Dentro do SQLite, `.tables`. Para sair: `exit` (MySQL) ou `.quit` (SQLite).

Para executar um arquivo PHP direto no terminal, sem o navegador:

```bash
docker compose exec web php index.php
```

## 4. Escrever e testar seus próprios códigos

1. Clique em **Abrir editor** (botão no topo do Cloud Shell) e abra a pasta `phpshell/src`.
2. Crie um arquivo, por exemplo `src/teste.php`. Exemplo que cria uma tabela e insere uma linha no MySQL:

   ```php
   <?php
   $pdo = new PDO("mysql:host=mysql_db;dbname=meubanco_mysql", "aluno", "alunopassword");
   $pdo->exec("CREATE TABLE IF NOT EXISTS aluno (id INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(50))");
   $pdo->exec("INSERT INTO aluno (nome) VALUES ('Maria')");

   foreach ($pdo->query("SELECT id, nome FROM aluno") as $linha) {
       echo $linha['id'] . " - " . $linha['nome'] . "<br>";
   }
   ```

3. Salve e abra, na **Visualização na Web** da porta 8080, o endereço do arquivo: o endereço da aba termina com `/`; acrescente `teste.php`.

Não precisa reiniciar nada: a pasta `src/` é compartilhada com o container, então toda alteração aparece ao atualizar a página (F5).

### Como conectar no PHP (PDO)

Dentro do PHP, o servidor do MySQL é o **nome do serviço** (`mysql_db`), não `localhost`:

| Banco | DSN | Usuário | Senha |
|---|---|---|---|
| MySQL | `mysql:host=mysql_db;dbname=meubanco_mysql` | `aluno` | `alunopassword` |
| SQLite | `sqlite:/var/www/html/database.sqlite` | não usa | não usa |
| PostgreSQL (servidor externo) | `pgsql:host=SERVIDOR;port=5432;dbname=BANCO;sslmode=require` | o do seu servidor | o do seu servidor |

## CRUD pronto (exemplo da aula)

A pasta [`src/crud`](src/crud) tem o **CRUD de produtos** completo da aula [PBE, Tópico 08](https://profhebert.com.br/aulas/pbe/topico08-crud-em-php-com-pdo.html). Não precisa copiar nada: com o ambiente de pé, abra na Visualização na Web (porta 8080):

1. `/crud/criatabela.php` para criar a tabela `produtos` com 3 produtos de exemplo;
2. `/crud/relatorio.php` para listar, cadastrar, alterar e excluir.

| Arquivo | Função |
|---|---|
| `conecta.php` | Conexão com o MySQL (PDO) |
| `criatabela.php` | Cria a tabela e os dados de exemplo |
| `header.php` e `footer.php` | Layout: menu, estilo e rodapé |
| `relatorio.php` | **R**ead: lista os produtos e mostra os avisos |
| `insere.php` | **C**reate: formulário e `INSERT` |
| `altera.php` | **U**pdate: formulário preenchido e `UPDATE` |
| `exclui.php` | **D**elete: `DELETE` |

> Esta é a mesma pasta usada na aula (`src/crud`). Se você alterar os arquivos e o `git pull` reclamar de alterações locais, guarde-as antes de atualizar: `git stash`, depois `git pull`, e `git stash pop` para recuperá-las.

## 5. Parar, reiniciar e limpar

```bash
docker compose logs -f web    # ver mensagens de erro do PHP (Ctrl+C para sair)
docker compose restart        # reiniciar tudo
docker compose down           # parar e remover os containers (mantém os dados do MySQL)
docker compose down -v        # parar e APAGAR também os dados do MySQL
```

## Problemas comuns

| Sintoma | O que fazer |
|---|---|
| ❌ Erro no MySQL | O MySQL ainda estava iniciando ou parou. Rode `docker compose ps`, espere ~30 s e atualize a página. |
| `docker: command not found` ou erro de permissão | Feche e abra o Cloud Shell. O Docker já vem instalado nele. |
| A Visualização na Web mostra "não foi possível conectar" | Confira com `docker compose ps` se o serviço está `Up` e se a porta escolhida é a certa (8080 PHP, 8081 phpMyAdmin). |
| Tudo parou depois que apertei Ctrl+C | O Ctrl+C no terminal que mostra os logs (`docker compose up` sem `-d`) para os containers. Rode `docker compose up -d` e use uma **aba nova** do terminal (botão **+**) para outros comandos. |
| Voltei no dia seguinte e os containers sumiram | O Cloud Shell recicla a máquina após um tempo parado. Rode `docker compose up -d` de novo. Os arquivos da pasta `phpshell` permanecem; as **tabelas e dados do MySQL podem ser perdidos**, então guarde seus comandos `CREATE TABLE` e `INSERT` em um arquivo `.sql` ou `.php` dentro de `src/`. |
| Atualizei o repositório e o ambiente antigo não sobe | Rode `docker compose down --remove-orphans` e depois `docker compose up -d --build`. |

## Segurança

As senhas deste repositório (`alunopassword`, `rootpassword`) são **só para estudo**. A Visualização na Web é acessível apenas pela sua conta Google, mas nunca use esses valores em um servidor de verdade nem publique as portas na internet.

## Estrutura do repositório

```
phpshell/
├── Dockerfile          # PHP 8.2 (Alpine) com mysqli, pdo_mysql, pdo_pgsql e SQLite
├── docker-compose.yml  # serviços web, mysql_db e phpmyadmin
└── src/                # seus arquivos PHP (pasta do servidor web)
    ├── index.php       # teste de conexão (MySQL, SQLite e driver PostgreSQL)
    └── crud/           # exemplo pronto: CRUD de produtos (aula PBE Tópico 08)
```
