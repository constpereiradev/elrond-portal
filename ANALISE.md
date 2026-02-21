Aos Representantes dos Reinos Livres da Terra-média,

Caros representantes, recebemos a solicitação da criação de uma API REST oficial 
do Conselho e este pedido será atendido.

Ressalto que, para a realização desta demanda, preciso fornecer a análise inicial 
e o plano de resolução para a implementação da solução.

1. Classificação da Solicitação
A solicitação pode ser classificada como uma demanda por automação e modernização de
uum processo administrativo interno, com ênfase na garantia de segurança, rastreabilidade
e integridade das informações das expedições.

2. Impacto e Complexidade da implementação
A criação desta API terá impactos significativos nos seguintes pontos:
- Segurança: A solução garantirá a integridade dos dados;
- Rastreabilidade: Será possível checar todas as expedições através do seu número de protocolo;
- Redução de inconsistencias manuais: Erros humanos como de análise ou escrita não ocorrerão mais;
- Economia de tempo: O processo manual de análise, verificação de informações, 
registro de dados e envio de cartas será descartado;

Complexidade da implementação:
- Desenvolvimento de uma API robusta que suporte tráfego e interação entre múltiplos reinos;
- Processamento assíncrono de requisições, especialmente para missões urgentes que demandam tratamento imediato;
- Integração com sistemas já existentes nos reinos, que podem ter diferentes níveis de maturidade tecnológica;


3. Pontos críticos e riscos técnicos
- Escalabilidade: A arquitetura precisa ser escalável para suportar picos de demanda sem 
comprometer a performante da aplicação;
- Rastreamento de status: Garantir que o status das expedições seja atualizado em tempo real;
- Segurança: Manter dados sensíveis armazenados e mantidos de forma segura;
- Documentação: O processo deve ser bem documentado para que o fluxo seja entendível e
possa ser atualizado por futuros Conselhos;

4. Plano de resolução
Arquitetura geral da API REST:
A arquitetura será baseada em uma estrutura RESful, respeitando os princípios REST,
garantindo uma comunicação simples e eficiente entre os reinos e o Conselho.
A API será projetada para ser escalável, com capacidade para suportar um grande número
de requisições e de forma simultânea.

Será composta por múltiplos serviçõs, como:
- Serviço de autenticação de autorização;
- Serviço de registro de expedições;
- Serviço de acompanhamento do status das expedições;
- Serviço de notificação e respostas;


Principais endpoints:
- Registro de expedições: api/v1/expeditions - POST
- Consulta de expedição: api/v1/expeditions /{id} - GET
- Consulta de todas as expedições: api/v1/expeditions - GET
- Envio de resposta ao reino solicitante: api/v1/notify - POST

Fluxo de autenticação:
A autententicação será baseada em tokens JWT. Somente usuários autenticados
e autorizados poderão interagir com a API. cada reino será responsável por 
gerenciar suas credenciais e obter tokens para autenticação.

Fluxo:
- Cadastro de reinos;
- Cadastro de usuário por reino: Cada reino possui seus usuários;
- Cadastro de Conselhos;
- Cadastro de usuário por Conselho: Cada Conselho possui seus usuários;
- Autenticação: O usuário realiza a autenticação para obter um token JWT codificado;
- Validação do token: O token JWT é decodificado e validado para garantir o acesso do usuário;



Tratamento do processamento assíncrono:
O sistema adotará um modelo de processamento assíncrono, garantindo que, 
mesmo com múltiplas requisições de reinos distintos, não ocorrerá o bloqueio do envio
de novas expedições e que a performance do sistema não seja afetada.

Além disso, permitirá que os reinos acompanhem o processo das expedições em tempo real.

Para isso, serão uilizadas filas de mensagens, para que o processo de recebimento de
uma expedição seja desacoplado da sua análise. Isto é, permitindo que umprocesso
não dependa do outro, fazendo com que novos pedidos cheguem enquanto as expedições 
anteriores ainda estão sendo processadas.


Estratégia de tratamento de erros e rejeições:
A API possuirá um sistema de código de erro HTTP padronizado pra indicar falhas no processamento,
seja devido a dados inválidos ou problemas internos.

A API retornará mensagens detalhadas com a razão da falha, permitindo que os reinos
corrijam e reenviem a solicitação. 


Por fim, este projeto visa transformar a forma que os reinos interagem conosco, 
o Conselho de Elrond, hoje. Traremos uma solução moderna e de alto impacto positivo.
Com a implementação desta tecnologia, esperamos que o processo manual seja erradicado
e que a comunicação passe a ser mais ágil e transparente, e, claro, segura.
 

Assinado:
Conselho de Elrond