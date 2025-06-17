# Ecoframe - Catalogação da Fauna e Flora Local - Em desenvolvimento

Ecoframe é um site colaborativo para catalogar a fauna e flora local, permitindo o registro de espécies e ocorrências ambientais. Oferece um fórum para discussões relevantes sobre conservação, biodiversidade e temas ambientais, promovendo a participação da comunidade.

## Funcionalidades principais

- Registro e catalogação de espécies da fauna e flora locais
- Upload de imagens associadas aos registros
- Fórum para debates e troca de informações entre usuários
- Visualização e filtragem de espécies por categoria e nome
- Perfil de usuários com histórico de contribuições

---

## Configuração para upload de imagens e acesso à API

Para que o upload de imagens funcione corretamente, é necessário configurar uma conta no [Cloudinary](https://cloudinary.com/), que é usada para hospedar as imagens do site.

### Passos para configuração:

1. **Criar uma conta no Cloudinary**  
   Acesse https://cloudinary.com/ e crie uma conta gratuita.

2. **Obter credenciais da API**  
   No painel do Cloudinary, anote:
   - `CLOUDINARY_CLOUD_NAME`  
   - `CLOUDINARY_API_KEY`  
   - `CLOUDINARY_API_SECRET`

3. **Criar o arquivo `.env`**  
   Na pasta raiz do projeto, dentro de `p2php_site`, crie um arquivo `.env` com o seguinte conteúdo (substitua pelos seus dados do Cloudinary e do banco de dados):
   
     DB_HOST=nome_host
     DB_USER=nome_usuario
     DB_PASSWORD=senha_bancodedados
     DB_NAME=nome_bancodedados
     CLOUDINARY_CLOUD_NAME=nome_bancodedados_cloudinary
     CLOUDINARY_API_KEY=api_key_cloudinary
     CLOUDINARY_API_SECRET=api_secret_cloudinary
     CLOUDINARY_FOLDER_RECORDS=pasta_observacoes
     CLOUDINARY_FOLDER_INCIDENTS=pasta_ocorrencias
     CLOUDINARY_FOLDER_PROFILES=pasta_perfis

4. **Instalar dependências com Composer**  
No terminal, navegue até a pasta `p2php_site` e execute:

```bash
composer install





