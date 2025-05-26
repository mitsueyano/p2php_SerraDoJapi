# Requisitos das Páginas "Cadastre-se/Entre" e "Perfil"

## 1. Requisitos Funcionais

### Página Cadastre-se/Entre
- O sistema deve permitir que o usuário realize login informando CPF e senha.
- O sistema deve disponibilizar a opção "Esqueci minha senha".
- O sistema deve permitir o cadastro de novos usuários, solicitando nome, sobrenome, nickname, e-mail, senha, confirmação de senha e foto de perfil.
- O sistema deve validar se o CPF e e-mail já estão cadastrados.
- O sistema deve validar a força da senha e a confirmação da senha.
- O sistema deve exibir mensagens de erro claras em caso de falha no login ou cadastro.

### Página Perfil
- O sistema deve exibir a foto do usuário, nickname, e-mail, data de criação da conta, número de seguidores e seguindo.
- O sistema deve permitir a edição de nickname, e-mail, senha e foto de perfil.
- O sistema deve exibir três abas: "Meus registros", "Minhas ocorrências" e "Minhas curtidas".
- O sistema deve listar os registros, ocorrências e curtidas do usuário nessas abas.
- O sistema deve permitir que o usuário visualize detalhes de cada registro ou ocorrência.

---

## 2. Requisitos Não-Funcionais

### Produto
- As páginas devem ser responsivas e funcionar em diferentes dispositivos.
- O tempo de resposta para login, cadastro e edição de perfil deve ser inferior a 2 segundos.
- Mensagens de erro e sucesso devem ser claras e objetivas.
- Dados sensíveis (senha, CPF) devem ser protegidos e não exibidos em tela.

### Organizacionais
- O sistema deve estar disponível 24/7, exceto em períodos programados de manutenção.
- O código das páginas deve seguir o padrão de desenvolvimento do projeto (HTML, PHP, JS).

### Externos
- O sistema deve estar em conformidade com a LGPD quanto ao tratamento de dados pessoais.
- O uso de APIs externas para upload de imagem (ex: Cloudinary) deve respeitar os termos de uso.

---

## 3. Requisitos de Usabilidade
- O formulário de cadastro deve ser simples, com campos bem identificados e instruções claras.
- O botão de login/cadastro deve ser destacado visualmente.
- O usuário deve receber feedback imediato sobre erros de preenchimento.
- A navegação entre as abas do perfil deve ser fluida e intuitiva.
- O usuário deve conseguir editar seus dados com poucos cliques.

---

## 4. Requisitos de Domínio
- O "nickname" é o identificador público do usuário na plataforma.
- O CPF é utilizado apenas para autenticação e não é exibido publicamente.
- "Meus registros" são observações de fauna/flora feitas pelo usuário.
- "Minhas ocorrências" são eventos reportados pelo usuário.
- "Minhas curtidas" são registros e ocorrências que o usuário marcou como favorito.

---

Se precisar de ajustes ou quiser adicionar mais detalhes, é só avisar!
