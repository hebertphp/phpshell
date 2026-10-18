# Exemplos das aulas

Códigos prontos das aulas de **PHP com banco de dados**. Servem para conferir o seu trabalho ou para testar o resultado final antes de escrever o seu.

## crud/ — CRUD de produtos com PDO e MySQL

Aula: [PBE, Tópico 08: CRUD em PHP com PDO](https://profhebert.com.br/aulas/pbe/topico08-crud-em-php-com-pdo.html)

| Arquivo | Função |
|---|---|
| `conecta.php` | Conexão com o MySQL (PDO) |
| `criatabela.php` | Cria a tabela `produtos` e 3 produtos de exemplo |
| `header.php` e `footer.php` | Layout: menu, estilo e rodapé |
| `relatorio.php` | **R**ead: lista os produtos e mostra os avisos |
| `insere.php` | **C**reate: formulário e `INSERT` |
| `altera.php` | **U**pdate: formulário preenchido e `UPDATE` |
| `exclui.php` | **D**elete: `DELETE` |

A pasta `crud/etapas/` tem as versões **sem layout** de `relatorio`, `insere` e `altera`, como aparecem no meio da aula (antes de `header.php` e `footer.php`).

### Como testar o exemplo pronto

Na pasta `phpshell`, com o ambiente de pé (`docker compose up -d`):

```bash
cp -r exemplos/crud src/crud
```

Depois abra, na Visualização na Web (porta 8080), o endereço da aba acrescentado de `/crud/criatabela.php` e, em seguida, `/crud/relatorio.php`.

> Se você for escrever o CRUD durante a aula, **não copie** a pasta antes: crie seus próprios arquivos em `src/crud` e use `exemplos/crud` só para comparar.
