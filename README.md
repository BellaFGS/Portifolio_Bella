# 🎮 Portfólio Web Interativo

Um portfólio web completo e interativo com design inspirado em jogos indie, sistema de autenticação 2FA e área administrativa para gerenciamento de conteúdo.

## ✨ Características Principais

### 🎨 Design e Estética
- **Tema**: Inspirado em jogos indie (Stardew Valley, galáxia, girassóis, gatos pretos)
- **Paleta de Cores**:
  - Roxo: `rgb(37, 30, 82)`
  - Amarelo: `rgb(199, 161, 98)`
  - Azul claro: `rgb(73, 119, 157)`
  - Azul escuro: `rgb(23, 34, 101)`
  - Vermelho: `rgb(195, 70, 75)`
- **Efeitos Visuais**: Sombras suaves, gradientes, animações, efeito de estrelas
- **Responsividade**: Compatível com desktop, notebook e celular

### 🚀 Funcionalidades Interativas
- **Animação de digitação** na mensagem de boas-vindas
- **Efeito de borboletas** animadas ao clicar na tela
- **Música de fundo** com controles de volume
- **Navegação suave** entre seções
- **Filtros dinâmicos** para projetos e blog
- **Gráfico de skills** animado com HTML/CSS/JS

### 🔐 Sistema de Segurança
- **Autenticação 2FA** para administradores
- **Criptografia SHA256** para senhas
- **Proteção CSRF** em todas as operações
- **Headers de segurança** configurados
- **Sistema de logs** para auditoria
- **Validação de entrada** em todos os formulários

### 📝 Área Administrativa
- **Dashboard completo** com estatísticas
- **CRUD para blog** (criar, editar, deletar posts)
- **Gerenciamento de projetos**
- **Filtros avançados** por categoria, nome e data
- **Interface intuitiva** com modais e notificações

## 🛠️ Tecnologias Utilizadas

### Frontend
- **HTML5** - Estrutura semântica
- **CSS3** - Estilos avançados com gradientes e animações
- **JavaScript ES6+** - Interatividade e comunicação com API

### Backend
- **PHP 8.1+** - Lógica do servidor
- **SQLite** - Banco de dados leve e prático
- **API RESTful** - Comunicação frontend-backend

### Segurança
- **Autenticação 2FA** - Verificação em duas etapas
- **CSRF Protection** - Proteção contra ataques CSRF
- **Input Validation** - Validação rigorosa de dados
- **Security Headers** - Headers de segurança HTTP

## 📁 Estrutura do Projeto

```
portfolio_web/
├── index.php                 # Página principal
├── admin_login.php          # Login administrativo
├── admin_dashboard.php      # Dashboard administrativo
├── README.md               # Documentação
├── test_results.md         # Resultados dos testes
├── todo.md                 # Lista de tarefas
├── frontend/
│   ├── css/
│   │   └── style.css       # Estilos principais
│   ├── js/
│   │   ├── script.js       # Scripts principais
│   │   └── admin.js        # Scripts administrativos
│   └── img/                # Imagens (vazio)
├── backend/
│   ├── db_config.php       # Configuração do banco
│   ├── api.php             # API básica
│   ├── secure_api.php      # API segura
│   ├── auth.php            # Sistema de autenticação
│   └── init_data.php       # Inicialização de dados
├── database/
│   └── portfolio.sqlite    # Banco de dados SQLite
└── logs/
    └── security.log        # Logs de segurança
```

## 🚀 Instalação e Configuração

### Pré-requisitos
- PHP 8.1 ou superior
- Extensões PHP: `sqlite3`, `pdo`
- Servidor web (Apache/Nginx) ou PHP built-in server

### Passo a Passo

1. **Clone ou baixe o projeto**
   ```bash
   # Se usando Git
   git clone <url-do-repositorio>
   cd portfolio_web
   
   # Ou extraia o arquivo ZIP
   unzip portfolio_web.zip
   cd portfolio_web
   ```

2. **Verifique as permissões**
   ```bash
   chmod 755 database/
   chmod 666 database/portfolio.sqlite
   chmod 755 logs/
   ```

3. **Inicie o servidor**
   ```bash
   # Usando servidor built-in do PHP
   php -S localhost:8080
   
   # Ou configure no Apache/Nginx apontando para a pasta do projeto
   ```

4. **Acesse o portfólio**
   - Site principal: `http://localhost:8080`
   - Área administrativa: `http://localhost:8080/admin_login.php`

### Credenciais Padrão
- **Usuário**: `admin`
- **Senha**: `admin123`
- **Código 2FA**: Será exibido na tela após o login

## 📊 Banco de Dados

O projeto utiliza SQLite com as seguintes tabelas:

### `users`
- `id` - ID único do usuário
- `username` - Nome de usuário
- `password` - Senha criptografada (SHA256)
- `two_factor_secret` - Segredo para 2FA

### `projects`
- `id` - ID único do projeto
- `title` - Título do projeto
- `description` - Descrição detalhada
- `category` - Categoria (individual, collaboration, curricular)
- `github_link` - Link do GitHub
- `live_link` - Link do projeto online

### `blog_posts`
- `id` - ID único do post
- `title` - Título do post
- `content` - Conteúdo completo
- `image_url` - URL da imagem
- `category` - Categoria do post
- `created_at` - Data de criação

## 🎯 Funcionalidades Implementadas

### Para Visitantes
- ✅ Visualização do portfólio completo
- ✅ Navegação suave entre seções
- ✅ Filtros para projetos e blog
- ✅ Design responsivo
- ✅ Animações e efeitos visuais
- ✅ Controles de música de fundo

### Para Administradores
- ✅ Login seguro com 2FA
- ✅ Dashboard com estatísticas
- ✅ CRUD completo para blog
- ✅ Gerenciamento de projetos
- ✅ Filtros avançados
- ✅ Sistema de notificações
- ✅ Logs de segurança

## 🔧 Personalização

### Alterando Cores
Edite as variáveis CSS em `frontend/css/style.css`:
```css
:root {
    --roxo: rgb(37, 30, 82);
    --amarelo: rgb(199, 161, 98);
    --azul-claro: rgb(73, 119, 157);
    --azul-escuro: rgb(23, 34, 101);
    --vermelho: rgb(195, 70, 75);
}
```

### Adicionando Música de Fundo
1. Adicione seu arquivo de música na pasta `frontend/`
2. Edite `frontend/js/script.js` linha 97:
```javascript
backgroundMusic.src = 'frontend/sua-musica.mp3';
```

### Modificando Conteúdo
- **Informações pessoais**: Edite `index.php`
- **Projetos**: Use a área administrativa ou edite diretamente no banco
- **Posts do blog**: Use a área administrativa

## 🛡️ Segurança

### Medidas Implementadas
- Autenticação 2FA obrigatória
- Criptografia SHA256 para senhas
- Proteção CSRF em todas as operações
- Validação rigorosa de entrada
- Headers de segurança HTTP
- Sistema de logs de auditoria
- Limitação de tentativas de login

### Recomendações para Produção
1. **Altere as credenciais padrão**
2. **Configure HTTPS**
3. **Use um servidor web robusto** (Apache/Nginx)
4. **Configure backups regulares**
5. **Monitore os logs de segurança**
6. **Mantenha o PHP atualizado**

## 📱 Responsividade

O portfólio é totalmente responsivo com breakpoints:
- **Desktop**: > 1200px
- **Tablet**: 768px - 1200px
- **Mobile**: < 768px

## 🎨 Customização Visual

### Adicionando Novos Efeitos
O projeto está preparado para extensões:
- Novos efeitos de partículas
- Animações personalizadas
- Temas alternativos
- Modo escuro/claro

### Modificando Layout
- Grid responsivo configurável
- Componentes modulares
- CSS bem organizado
- Fácil manutenção

## 🐛 Solução de Problemas

### Problemas Comuns

1. **Erro de permissão no banco de dados**
   ```bash
   chmod 666 database/portfolio.sqlite
   ```

2. **Página em branco**
   - Verifique se o PHP está instalado
   - Verifique os logs de erro do PHP

3. **Estilos não carregam**
   - Verifique se o servidor está servindo arquivos CSS
   - Limpe o cache do navegador

4. **API não responde**
   - Verifique se as extensões PHP estão instaladas
   - Verifique os logs de erro

## 📞 Suporte

Para dúvidas ou problemas:
1. Verifique a documentação
2. Consulte os logs em `logs/security.log`
3. Verifique os resultados dos testes em `test_results.md`

## 📄 Licença

Este projeto foi desenvolvido como um portfólio pessoal. Sinta-se livre para usar como base para seus próprios projetos.

## 🎉 Créditos

Desenvolvido com inspiração em:
- **Stardew Valley** - Estética e paleta de cores
- **Jogos indie** - Design e atmosfera
- **Comunidade web** - Melhores práticas de desenvolvimento

---

**Versão**: 1.0.0  
**Data**: Setembro 2025  
**Status**: ✅ Completo e Funcional

