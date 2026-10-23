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

- **`/crud/`**: página com os links dos arquivos do CRUD (também há um link para ela na página inicial, `/`);
- **`/crud/criatabela.php`**: cria a tabela `produtos` com 3 produtos de exemplo (só na primeira vez);
- **`/crud/relatorio.php`**: lista, cadastra, altera e exclui.

> **Atenção ao endereço:** a pasta `src` é a raiz do servidor e **não aparece na URL**. O arquivo `src/crud/relatorio.php` abre em `/crud/relatorio.php` (e **não** em `/src/crud/relatorio.php`). Uma pasta sozinha, como `/crud`, só abre se tiver um `index.php`; nas suas próprias pastas, abra um arquivo específico.

| Arquivo | Função |
|---|---|
| `index.php` | Página inicial com os links do exemplo |
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

## 6. Rodar na máquina local (sem o Cloud Shell)

O mesmo repositório funciona no seu computador. A diferença é que você abre os endereços `localhost` direto no navegador, sem a Visualização na Web.

### 6.1 Instalar o Docker

- **Windows e Mac:** instale o **Docker Desktop** e abra o programa.
- **Linux (Ubuntu, Linux Mint e derivados):**

  ```bash
  sudo apt update
  sudo apt install docker.io docker-compose-v2
  ```

  Para usar o `docker` sem `sudo`, adicione seu usuário ao grupo `docker` e **saia e entre de novo** na sessão. Quem está nesse grupo tem poder equivalente ao de administrador; se preferir, use `sudo docker compose ...`.

  ```bash
  sudo usermod -aG docker $USER
  ```

Confira se funcionou:

```bash
docker --version
docker compose version
```

### 6.2 Subir o ambiente

```bash
git clone https://github.com/hebertphp/phpshell.git
cd phpshell
docker compose up -d
```

Depois abra no navegador:

| O quê | Endereço |
|---|---|
| Páginas PHP | `http://localhost:8080` |
| CRUD pronto | `http://localhost:8080/crud/` (links) ou direto `http://localhost:8080/crud/criatabela.php` e depois `http://localhost:8080/crud/relatorio.php` |
| phpMyAdmin | `http://localhost:8081` (usuário `aluno`, senha `alunopassword`) |

Edite os arquivos de `src/` com qualquer editor (por exemplo, o VS Code). A pasta é compartilhada com o container: salve e atualize a página (F5).

O `conecta.php` continua igual, com `host=mysql_db`: esse é o nome do serviço dentro do Docker e vale em qualquer máquina. As portas `8080` e `8081` precisam estar livres (feche, por exemplo, um XAMPP que esteja rodando).

### 6.3 Ligar o Docker só quando precisar (Linux, pouca memória)

Logo depois de instalar, o Docker no Linux passa a iniciar junto com o sistema. Para ele **só rodar quando você quiser**, desligue a inicialização automática uma vez:

```bash
sudo systemctl disable --now docker.service docker.socket containerd.service
```

Para usar o ambiente:

```bash
sudo systemctl start docker          # liga o Docker
cd ~/phpshell
docker compose up -d                 # sobe o ambiente
```

Ao terminar, pare os containers **antes** de parar o Docker (assim eles não voltam sozinhos na próxima vez) e libere a memória:

```bash
docker compose down
sudo systemctl stop docker docker.socket containerd
```

Com o Docker parado ele não consome memória. O ambiente completo (PHP, MySQL e phpMyAdmin) costuma gastar algo como 0,5 a 1 GB. Para gastar menos, suba só o essencial: `docker compose up -d web mysql_db`.

> A regra do Ctrl+C vale aqui também: não o aperte no terminal que mostra os logs, e use uma aba nova para outros comandos.

## Problemas comuns

| Sintoma | O que fazer |
|---|---|
| ❌ Erro no MySQL | O MySQL ainda estava iniciando ou parou. Rode `docker compose ps`, espere ~30 s e atualize a página. |
| `docker: command not found` ou erro de permissão | Feche e abra o Cloud Shell. O Docker já vem instalado nele. |
| A Visualização na Web mostra "não foi possível conectar" | Confira com `docker compose ps` se o serviço está `Up` e se a porta escolhida é a certa (8080 PHP, 8081 phpMyAdmin). |
| Tudo parou depois que apertei Ctrl+C | O Ctrl+C no terminal que mostra os logs (`docker compose up` sem `-d`) para os containers. Rode `docker compose up -d` e use uma **aba nova** do terminal (botão **+**) para outros comandos. |
| Voltei no dia seguinte e os containers sumiram | O Cloud Shell recicla a máquina após um tempo parado. Rode `docker compose up -d` de novo. Os arquivos da pasta `phpshell` permanecem; as **tabelas e dados do MySQL podem ser perdidos**, então guarde seus comandos `CREATE TABLE` e `INSERT` em um arquivo `.sql` ou `.php` dentro de `src/`. |
| Atualizei o repositório e o ambiente antigo não sobe | Rode `docker compose down --remove-orphans` e depois `docker compose up -d --build`. |
| Quero garantir o PHP atualizado (segurança) | Rode `docker compose up -d --build`: ele baixa de novo a imagem base do PHP e recria o container. Depois abra a página inicial (porta 8080): a linha `PHP x.y.z` deve mostrar **8.2.35 ou maior** (correção das falhas críticas de 21/10/2026; [saiba mais](https://profhebert.com.br/dicas/php/atualize-seu-php)). Só `docker compose up -d` **não** atualiza uma imagem já baixada. |

## Segurança

**Mantenha o PHP atualizado.** Em 21/10/2026 o PHP corrigiu duas falhas críticas, que permitem executar código no servidor sem login. As versões corrigidas são 8.5.12, 8.4.27, 8.3.36 e 8.2.35 (o PHP 8.1 e anteriores não recebem correção). O ambiente usa o PHP 8.2, que já recebeu a correção, mas quem montou o ambiente antes dessa data continua com a imagem antiga até reconstruí-la. Para atualizar:

```bash
docker compose up -d --build
```

Depois abra a página inicial (porta 8080) e confira a linha `PHP x.y.z`: ela deve mostrar **8.2.35 ou maior**. Só `docker compose up -d` **não** atualiza uma imagem já baixada. Para entender o que aconteceu e como conferir a versão em outros ambientes (por exemplo, `php -v`), veja a dica: [Seu PHP está atualizado?](https://profhebert.com.br/dicas/php/atualize-seu-php)

As senhas deste repositório (`alunopassword`, `rootpassword`) são **só para estudo**. A Visualização na Web é acessível apenas pela sua conta Google, mas nunca use esses valores em um servidor de verdade nem publique as portas na internet.

## Estrutura do repositório

```
phpshell/
├── Dockerfile          # PHP 8.2 (Alpine) com mysqli, pdo_mysql, pdo_pgsql e SQLite
├── docker-compose.yml  # serviços web, mysql_db e phpmyadmin
└── src/                # seus arquivos PHP (pasta do servidor web)
    ├── index.php       # teste de conexão (MySQL, SQLite e driver PostgreSQL) e link do CRUD
    └── crud/           # exemplo pronto: CRUD de produtos (aula PBE Tópico 08); /crud/ tem os links
```
