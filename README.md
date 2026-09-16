# ByteBloom — Portfólio Pessoal

Portfólio pessoal responsivo para apresentar experiências em desenvolvimento front-end, criação de jogos, pixel art e automação. O projeto combina uma estética inspirada em jogos clássicos com uma identidade visual baseada em girassóis, amarelo vibrante, verde natural e tipografia pixelada.

A aplicação é uma interface estática executada no navegador. Não utiliza banco de dados, autenticação ou API própria. O projeto usa **React com Vite e TypeScript** na estrutura de desenvolvimento, além de CSS customizado e JavaScript para as interações visuais.

## Visão geral

O site contém as seguintes áreas:

- Hero com apresentação pessoal, chamada para download do currículo e links sociais.

- Seção “Sobre mim” com resumo profissional e indicadores.

- Projetos em destaque apresentados como fases de um jogo.

- Linha do tempo com experiências profissionais e formação.

- Cursos, certificações, hobbies e interesses.

- Seção de contato com link de e-mail.

- Menu hambúrguer para telas menores.

- Alternador entre tema escuro e tema claro.

- Animações suaves de entrada durante a rolagem.

O conteúdo atual possui textos, links e dados de exemplo. Substitua esses valores pelos seus dados antes de publicar o site.

## Requisitos

Para executar o projeto localmente, instale:

- [Node.js](https://nodejs.org/) versão 18 ou superior.

- [pnpm](https://pnpm.io/) versão 9 ou superior. Também é possível usar `npm` ou `yarn`, adaptando os comandos.

- Git, caso o projeto seja baixado de um repositório.

Confira as versões instaladas:

```bash
node --version
pnpm --version
```

## Executar em localhost

### 1. Clonar o repositório

Se o projeto estiver no GitHub, clone-o com:

```bash
git clone https://github.com/BellaFGS/Portifolio_BellaFGS.git
cd SEU_REPOSITORIO
```

Se você já recebeu a pasta do projeto, abra o terminal dentro dela:

```bash
cd portfolio_bellafgs
```

### 2. Instalar as dependências

```bash
pnpm install
```

### 3. Iniciar o servidor de desenvolvimento

```bash
pnpm dev
```

O Vite exibirá um endereço semelhante a:

```
http://localhost:3000/
```

Abra esse endereço no navegador. O servidor possui atualização automática: quando você salvar um arquivo, a página será atualizada sem precisar reiniciar o comando.

Para interromper o servidor, pressione `Ctrl + C` no terminal.

## Comandos disponíveis

| Comando | Função |
| --- | --- |
| `pnpm install` | Instala as dependências do projeto. |
| `pnpm dev` | Inicia o servidor local de desenvolvimento. |
| `pnpm build` | Gera a versão otimizada para produção em `dist/public`. |
| `pnpm preview` | Executa localmente a versão já compilada. |
| `pnpm check` | Verifica erros de TypeScript. |
| `pnpm format` | Formata os arquivos usando Prettier. |

Depois de gerar a build, você pode conferir a versão de produção com:

```bash
pnpm build
pnpm preview
```

## Estrutura principal

```
.
├── client/
│   ├── index.html          # Documento HTML principal
│   ├── css/
│   │   └── style.css      # Paleta, layout, responsividade e animações
│   ├── js/
│   │   └── main.js        # Menu, tema, rolagem e interações
│   ├── public/             # Arquivos públicos pequenos
│   └── src/                # Estrutura React original do template
├── dist/
│   └── public/             # Resultado gerado pela build
├── package.json            # Scripts e dependências
├── vite.config.ts          # Configuração do Vite
└── README.md               # Este manual
```

O site personalizado está concentrado principalmente nestes arquivos:

- `client/index.html`

- `client/css/style.css`

- `client/js/main.js`

O arquivo `server/index.ts` pertence ao ambiente de desenvolvimento e não é necessário para servir o conteúdo estático no GitHub Pages.

## Como personalizar o portfólio

### Nome, título e descrição

Abra `client/index.html` e altere:

- O título da página dentro da tag `<title>`.

- A descrição da meta tag `description`.

- O texto `SEU NOME` no hero.

- A apresentação profissional.

- Os textos das seções “Sobre mim”, “Projetos”, “Trajetória” e “Contato”.

### Links sociais

No arquivo `client/index.html`, procure por links como:

```html
https://github.com/seu-usuario
https://seu-usuario.itch.io
https://www.instagram.com
```

Substitua-os pelos seus endereços reais. Também atualize o link do rodapé para o repositório correto.

### Projetos

Cada projeto está dentro de um elemento `<article class="project-card">`. Para atualizar um cartão:

1. Altere o título do projeto.

1. Escreva uma descrição curta e objetiva.

1. Atualize as tags de tecnologia.

1. Substitua os links marcados com `data-placeholder`.

1. Remova o atributo `data-placeholder` depois de inserir um link real.

Enquanto o atributo `data-placeholder` existir, o JavaScript impede a navegação e exibe uma mensagem informativa.

### Currículo em PDF

O botão principal aponta para:

```
curriculo.pdf
```

Adicione seu arquivo PDF na pasta pública usada pelo site. No projeto atual, o caminho mais simples é:

```
client/curriculo.pdf
```

Depois, confirme que o link no `client/index.html` corresponde ao local do arquivo. O nome do arquivo diferencia letras maiúsculas e minúsculas em servidores Linux.

### E-mail de contato

Procure por:

```html
mailto:seuemail@exemplo.com
```

Substitua pelo seu endereço de e-mail profissional.

### Cores e tipografia

As principais cores estão no início de `client/css/style.css`, dentro de `:root`:

```css
:root {
  --ink: #1a1c23;
  --panel: #242833;
  --sun: #ffc83d;
  --leaf: #97bc62;
}
```

Altere essas variáveis para criar outra identidade visual sem precisar editar cada componente individualmente. O tema claro possui regras específicas no seletor `.light-mode`.

As fontes usadas atualmente são carregadas pelo Google Fonts em `client/index.html`:

- `Press Start 2P` para títulos e detalhes retro.

- `DM Sans` para textos corridos e leitura confortável.

### Tema claro e escuro

O alternador de tema é controlado por `client/js/main.js`. A preferência é salva no `localStorage` do navegador usando a chave `bytebloom-theme`.

As cores do tema escuro ficam nas variáveis principais. As correções de contraste do tema claro ficam nos seletores que começam com `.light-mode` em `client/css/style.css`.

## Publicar no GitHub Pages

O comando de build gera os arquivos estáticos em:

```
dist/public/
```

Para publicar no GitHub Pages, configure um workflow do GitHub Actions para instalar as dependências, executar `pnpm build` e publicar a pasta `dist/public`.

Se o site for publicado em um repositório de projeto, como:

```
https://seu-usuario.github.io/meu-portfolio/
```

configure o `base` no `vite.config.ts`:

```
export default defineConfig({
  base: "/meu-portfolio/",
  // restante da configuração
} );
```

Se o repositório for `seu-usuario.github.io`, normalmente o caminho-base pode ser `/`.

Antes de publicar, gere e valide a build:

```bash
pnpm build
pnpm preview
```

O GitHub Pages precisa receber os arquivos gerados dentro de `dist/public`, e não o código-fonte de desenvolvimento diretamente.

## Cuidados antes da publicação

Antes de disponibilizar o site publicamente:

- Substitua todos os textos entre colchetes e os dados de exemplo.

- Atualize todos os links sociais e de projetos.

- Adicione o arquivo real `curriculo.pdf`.

- Confira o endereço de e-mail.

- Teste o menu mobile em diferentes larguras.

- Teste o alternador de tema claro e escuro.

- Execute `pnpm build` para confirmar que a versão de produção é gerada sem erros.

- Verifique se as fontes, imagens e links carregam corretamente no domínio final.

## Tecnologias

- React

- Vite

- TypeScript

- HTML5 semântico

- CSS3 customizado

- JavaScript moderno

- Google Fonts

- GitHub Pages

## Licença

Este projeto pode ser adaptado para uso pessoal. Se você reutilizar partes significativas do código ou da identidade visual, verifique e defina a licença adequada para o seu caso.

## Referências

- manus_definition1a09861a0986https://vite.dev/guide/static-deploy.htmla0986Vite — Static Deploya09861manus_definition

- manus_definition2a09862a0986https://docs.github.com/en/pagesa0986GitHub Pages Documentationa09862manus_definition

- manus_definition3a09863a0986https://pnpm.io/cli/installa0986pnpm — Installa09863manus_definition