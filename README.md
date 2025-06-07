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

## 

## Como Rodar o Projeto 
Siga os passos abiaxo para configurar e executar o projeto:

1. Clone o reposiório do projeto do GitHub: 
```
git clone https://github.com/Sr-MARTINS/teste-pigz.git <br>
cd teste-pigz
```
2. Rodar as fixtures:
```
php bin/console doctrine:fixtures:load
```
3. Starte o servidor:
```
symfony serve:start
```

### Login de usuario
```
{
    "email": "admin@admin.com",
    "password": 123456
}
```
