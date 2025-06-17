# Ecoframe - Plataforma de Catalogação da Fauna e Flora Local (Em desenvolvimento)

**Ecoframe** é uma plataforma desenvolvida com o objetivo de apoiar a preservação ambiental e incentivar a participação cidadã por meio da catalogação da fauna e flora local.

Com uma proposta intuitiva e acessível, permite que usuários cadastrem, consultem e interajam com observações da biodiversidade. Esses registros incluem imagens, descrições, localização geográfica e informações taxonômicas. Contando com o apoio de APIs externas, como **iNaturalist** e **GBIF**, que auxiliam na identificação e validação automatizada das espécies.

O sistema também oferece recursos voltados ao engajamento da comunidade, como comentários, curtidas e sugestões de alteração nos registros. Casos sensíveis, como atropelamentos de animais silvestres, passam por um fluxo de **validação exclusivo**, revisado apenas por especialistas.

Desenvolvido com **HTML**, **PHP**, **JavaScript**, **Composer**, banco de dados relacional e integração com serviços como **Cloudinary** (armazenamento de imagens), **Geocoder** e **Nominatim** (geolocalização), o Ecoframe promove uma navegação fluida por meio de uma barra de menus com seções principais: Início, Explorar e Perfil/Entre.

---

## Funcionalidades principais

- Registro e catalogação de espécies da fauna e flora locais  
- Geolocalização e descrição taxonômica detalhada  
- Upload de imagens via Cloudinary  
- Integração com APIs de biodiversidade (iNaturalist, GBIF)  
- Fórum e comentários em registros  
- Curtidas e sugestões de alteração por usuários  
- Validação de ocorrências por especialistas  
- Filtros por categoria, nome e popularidade  
- Perfis de usuário com histórico de contribuições  

---

## ⚙️ Configuração para upload de imagens e uso da API

Para que o upload de imagens funcione corretamente, é necessário configurar uma conta no [Cloudinary](https://cloudinary.com/) e fornecer suas credenciais através de um arquivo `.env`.

### Passos para configuração:

1. **Criar uma conta no Cloudinary**  
   Acesse https://cloudinary.com/ e crie uma conta gratuita.

2. **Obter as credenciais da API**  
   No painel do Cloudinary, anote os seguintes dados:
   - `CLOUDINARY_CLOUD_NAME`
   - `CLOUDINARY_API_KEY`
   - `CLOUDINARY_API_SECRET`

3. **Criar o arquivo `.env`**  
   Dentro da pasta `p2php_site`, crie um arquivo chamado `.env` com o seguinte conteúdo (substitua pelos seus próprios dados):

   ```env
   DB_HOST=localhost
   DB_USER=root
   DB_PASSWORD=sua_senha
   DB_NAME=ecoframe
   CLOUDINARY_CLOUD_NAME=seu_cloud_name
   CLOUDINARY_API_KEY=sua_api_key
   CLOUDINARY_API_SECRET=sua_api_secret
   CLOUDINARY_FOLDER_RECORDS=nome_da_pasta_para_registros
   CLOUDINARY_FOLDER_INCIDENTS=nome_da_pasta_para_ocorrencias
   CLOUDINARY_FOLDER_PROFILES=nome_da_pasta_para_perfis
   ```

4. **Instalar dependências com Composer**  
   No terminal, navegue até a pasta `p2php_site`.  
   Antes de instalar as dependências, **apague a pasta `vendor/` caso ela já exista**:

   ```bash
   rm -rf vendor
   composer install
   ```



