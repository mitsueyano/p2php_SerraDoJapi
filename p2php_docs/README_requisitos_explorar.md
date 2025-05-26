# Requisitos da Página "Explorar" e Sub-seções

## 1. Requisitos Funcionais

| ID    | Tipo      | Descrição                                                                                                 |
|-------|-----------|-----------------------------------------------------------------------------------------------------------|
| RF01  | Usuário   | O sistema deve exibir um texto introdutório "O que você quer observar hoje?" no topo da página.           |
| RF02  | Usuário   | O sistema deve apresentar as opções de navegação para "Espécies" e "Ocorrências".                        |
| RF03  | Usuário   | O sistema deve disponibilizar um botão "Compartilhe sua observação" visível na página.                    |
| RF04  | Usuário   | O sistema deve exibir os registros mais recentes dos usuários, incluindo data/hora, imagem, nomes, descrição, local, data de avistamento, nome do usuário e barra de interações (comentários, likes, sugestões de alteração). |
| RF05  | Usuário   | O sistema deve permitir que usuários enviem sugestões de alteração para o dono do post.                    |
| RF06  | Sistema   | O sistema deve buscar e exibir em tempo real os registros mais recentes e suas interações.                 |

#### Sub-seção "Espécies"

| ID    | Tipo      | Descrição                                                                                                 |
|-------|-----------|-----------------------------------------------------------------------------------------------------------|
| RF07  | Usuário   | O sistema deve exibir todas as espécies registradas em ordem alfabética, com letras sem registros em cinza.|
| RF08  | Usuário   | O sistema deve permitir filtrar espécies por "todos", "fauna" e "flora".                                  |
| RF09  | Usuário   | O sistema deve disponibilizar uma barra de pesquisa para buscar espécies por nome ou classe.               |
| RF10  | Usuário   | Ao clicar em uma espécie, o sistema deve exibir todos os posts relacionados a ela, com imagem, usuário, local e data. |

#### Sub-seção "Ocorrências"

| ID    | Tipo      | Descrição                                                                                                 |
|-------|-----------|-----------------------------------------------------------------------------------------------------------|
| RF11  | Usuário   | O sistema deve exibir todas as ocorrências cadastradas pelos usuários.                                     |
| RF12  | Usuário   | O sistema deve permitir marcar ocorrências como "sensíveis", ocultando/exibindo a imagem conforme seleção.|
| RF13  | Usuário   | O sistema deve disponibilizar um botão "Registrar ocorrência".                                            |
| RF14  | Sistema   | O sistema deve garantir que ocorrências só sejam publicadas após validação de especialistas.               |

---

## 2. Requisitos Não-Funcionais

### 2.1 Produto

| ID    | Categoria    | Descrição                                                                                              |
|-------|--------------|--------------------------------------------------------------------------------------------------------|
| RNF01 | Eficiência   | A página "Explorar" e suas subseções devem carregar em até 3 segundos em conexões de banda larga.      |
| RNF02 | Usabilidade  | Os filtros e barra de pesquisa devem ser intuitivos e responsivos.                                     |
| RNF03 | Confiança    | Os dados exibidos (espécies, ocorrências, registros) devem ser consistentes com o banco de dados.      |
| RNF04 | Proteção     | Ocorrências sensíveis devem ser ocultadas por padrão, exibidas apenas mediante ação do usuário.         |

### 2.2 Organizacionais

| ID    | Categoria      | Descrição                                                                                             |
|-------|----------------|-------------------------------------------------------------------------------------------------------|
| RNF05 | Operacional    | O sistema deve estar disponível 24/7, exceto em períodos programados de manutenção.                   |
| RNF06 | Desenvolvimento| O código das páginas deve seguir o padrão de desenvolvimento do projeto (HTML, PHP, JS).              |

### 2.3 Externos

| ID    | Categoria   | Descrição                                                                                                |
|-------|-------------|----------------------------------------------------------------------------------------------------------|
| RNF07 | Reguladores | O sistema deve estar em conformidade com a LGPD quanto à exibição de dados de usuários e registros.      |
| RNF08 | Legais      | O uso de APIs externas (iNaturalist, GBIF, Cloudinary, OpenStreetMap) deve respeitar os termos de uso.   |

---

## 3. Requisitos de Usabilidade

| ID    | Descrição                                                                                                              |
|-------|------------------------------------------------------------------------------------------------------------------------|
| RU01  | O layout deve ser responsivo, adaptando-se a diferentes dispositivos.                                                  |
| RU02  | Os filtros e barra de pesquisa devem ser facilmente acessíveis e de fácil compreensão.                                 |
| RU03  | O botão "Compartilhe sua observação" e "Registrar ocorrência" devem ser destacados visualmente.                        |
| RU04  | As imagens de ocorrências sensíveis devem ser claramente identificadas e protegidas por um overlay ou aviso.           |
| RU05  | A navegação entre as subseções deve ser fluida e sem recarregamento completo da página, se possível.                   |

---

## 4. Requisitos de Domínio

| ID    | Descrição                                                                                                              |
|-------|------------------------------------------------------------------------------------------------------------------------|
| RD01  | "Espécies" refere-se a todos os organismos catalogados, classificados por fauna ou flora.                              |
| RD02  | "Ocorrências" são eventos registrados pelos usuários, podendo ser de natureza animal ou ambiental.                     |
| RD03  | "Ocorrências sensíveis" são aquelas que, por envolverem espécies ameaçadas ou locais restritos, devem ser protegidas.  |
| RD04  | Apenas especialistas podem validar e publicar ocorrências na plataforma.                                               |
| RD05  | Filtros e buscas devem considerar a classificação taxonômica e nomes populares/científicos das espécies.               |

---

Se precisar de ajustes ou quiser adicionar mais detalhes, é só avisar!