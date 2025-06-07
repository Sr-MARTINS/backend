# Desafio Pigz
Este projeto é uma solução para o desafio proposto pela Pigz. Ele foi desenvolvido com o objetivo de atender aos requisitos especificados e demonstrar habilidades técnicas.

## Funcionalidades
+ Criar uma lista
+ Compartilhar uma lista
+ Deletar uma lista
+ Adicionar tarefas na lista
+ Concluir tarefas na lista
+ Remover uma tarefa
+ Somente pessoas autorizadas poderão cadastrar outros usuarios

## Requisitos

- PHP >= 8.1
- Composer
- Symfony CLI 
- MySQL 

## Como Rodar o Projeto 
Siga os passos abiaxo para configurar e executar o projeto:

1. Clone o reposiório do projeto do GitHub: 
```
git clone https://github.com/Sr-MARTINS/teste-pigz.git <br>
cd teste-pigz
```
2.Instale as dependências
```
composer install
```
3. Configure o .env
```
DATABASE_URL="mysql://root:@127.0.0.1:3306/test_pigz?serverVersion=8.0.32&charset=utf8mb4"
```
4. Execute as fixures do banco de dados com o seguinte comando:
```
php bin/console doctrine:fixtures:load
```
5. Inicie o servidor local
```
symfony server:start
```
### Login de usuario
```
{
    "email": "admin@admin.com",
    "password": 123456
}
```
