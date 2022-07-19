<div id="sobre"/>

<h1 align="center">API CRYPTO DACXI</h1>

<p align="justify">🚀 Essa API tem como objetivo retornar o valor de uma determinada criptomoeda informada pelo usuário no momento exato da requisição, ou então, por uma data em específico. Além disso, a aplicação também consegue salvar, a cada 10 minutos, o valor atual de 5 criptomoedas sendo elas: Bitcoin, Cosmos, Ethereum, Dacxi e Moon.</p>

## Índice 


   * [Sobre](#sobre)
   * [Índice](#índice)
   * [Instalação](#instalação)
       * [Etapas](#passo-a-passo)
       * [Configurando o banco de dados](#configurando-banco-de-dados)
   * [Funcionalidades e Demonstração da Aplicação](#funcionalidades-e-demonstração-da-aplicação)
       * [Obter o valor atual de uma criptomoeda](#obter-valor-atual-de-uma-criptomoeda)
       * [Obter o valor de uma criptomoeda a partir de uma data](#obter-valor-de-uma-criptomoeda-a-partir-de-uma-data)
       * [Salvando os dados das criptomoedas](#salvando-os-dados-das-criptomoedas)
   * [Autor](#autor)


<div id="instalação"/>

## Instalação

A aplicação está disponível para ser acessada pela internet, mas, caso você deseje baixar-lá para fins de testes, é necessário seguir algumas etapas que serão descritas logo abaixo.

<div id="passo-a-passo"/>

### Etapas

<p>Primeiro clone o repositório do projeto API-Crypto.</p>

```bash
git clone https://github.com/matheusdesouzab/API-DACXI.git
```

<p>Em seguida, abra no seu terminal a pasta onde o projeto foi instalado e execute o comando abaixo para instalar as dependências necessárias para o projeto rodar.</p>

```
npm install
```

Execute o comando abaixo para que os arquivos de `vendor` sejam gerados de forma automática.

```
composer install
```

<p>Execute a aplicação em modo de desenvolvimento.</p>

```
npm run dev
```

Para verificar se a aplicação está rodando, execute o comando `php artisan serve` em seu terminal e acesse em seguida a rota raiz da sua aplicação, através do caminho `localhost:8000`. Caso a página de home padrão do Laravel seja carregada, é sinal que deu tudo certo.

<div id="configurando-banco-de-dados"/>

#### Configurando o banco de dados

Com a aplicação já funcionando em seu ambiente de desenvolvimento, você ainda precisa criar e configurar um banco de dados. Para isso, primeiro crie uma base de dados, depois crie um arquivo com o nome `.env`, copie o conteudo do arquivo `.env.example` para esse novo arquivo. Agora configure o arquivo com os dados relacionados ao banco que você acabou de criar, em seguida, execute o comando abaixo para a criação das tabelas de forma automática:

```
php artisan migrate
```

Por fim, execute o comando abaixo para que os dados adicionais da tabela **coin_type** sejam criados de forma automática. Logo mais abaixo você irá entender como estão organizadas as tabelas que você acabou de criar.

```
php artisan db:seed
```

<div id="funcionalidades-e-demonstração-da-aplicação"/>

## Funcionalidades e demonstração da aplicação

A API já está disponível para ser acessada pela internet, como também, pode ser instalada em sua máquina para fins de testes. Você pode aprender a instalá-la na sessão de **Instalação**.

A aplicação possui somente dois endpoints, são eles:

```
api/coins - Endpoint para obter o valor atual de uma criptomoeda
```

```
api/coins/history - Endpoint para obter o valor atual de uma criptomoeda, a partir da data informada
```

Para entender melhor cada uma delas, vamos analisar o arquivo `routes/api.php` no projeto. Nesse arquivo, temos as duas rotas:

```
Route::get('coins/{id}/{conversion_currency?}', 'App\Http\Controllers\CoinController@getPrice');
Route::get('coins/history/{id}/{date}/{countryCurrency?}', 'App\Http\Controllers\CoinController@getHistory');
```
<div id="obter-valor-atual-de-uma-criptomoeda"/>

### Obter o valor atual de uma criptomoeda

Para obter o valor atual de uma criptomoeda, o sistema trabalha com a rota `api/coins`. Como podemos ver acima, ela possui dois parâmetros, são eles: o **id** e o **conversion_currency**. O id diz respeito a moeda na qual desejamos obter o valor atual, nesse caso, o id da criptomoeda será seu nome, por exemplo, **bitcoin**. O segundo parâmetro, que não é obrigatório, é a moeda que será usada como base para a conversão da criptomoeda. Um pouco mais adiante, vamos observar que por padrão esse valor é igual a **usd**.

Já sabendo como essa rota é formada, vamos entender agora como funciona o método que retorna esse valor. Para isso vamos acessar `getPrice`, dentro do controler `CoinController`. Nesse método temos o seguinte código:

```
    /**
     * This method returns the current value of a currency
     *
     * @param string $id
     * @param string $conversion_currency
     * 
     * @return \Illuminate\Http\Response
     */
    public function getPrice($id, $conversion_currency = 'usd')
    {

        $client = new CoinGeckoClient();

        $data = $client->simple()->getPrice($id, $conversion_currency);

        if (!$data) {
            return response()->json([
                'error' => 'Não existe nenhuma criptomoeda cadastrada com o nome '.$id
            ], 404);
        }

        return response()->json($data, 200);
    }
```

Então, como foi dito anteriormente, esse método vai receber dois argumentos, são eles o **id** e o **conversion_currency**. Em seguida, a class **CoinGeckoClient** será instanciada – e a partir dela os dados referentes as criptomoedas serão obtidos. Você pode compreendê-la melhor lendo a documentação que refere-se a ela em   <https://github.com/codenix-sv/coingecko-api>. Posteriomente, o método `getPrice` será executado, e como é exemplificado acima, ele recebe dois argumentos, são eles: a **criptomoeda** e a **moeda de conversão**. Como retorno, caso os dados sejam informados de forma correta, teremos um objeto contendo a criptomoeda e o seu respectivo valor. Caso alguns desses dados sejam informados de forma incorreta, o retorno será uma mensagem de erro. Para simular um teste, vamos tentar obter o valor atual do bitcoin nesse exato momento, você pode obter esse valor  através  da sua aplicação que está no ambiente de desenvolvimento, ou então,  através  da aplicação que já está disponivel na internet, abaixo temos esses dois caminhos.

```
https://api-crypto-dacxi.herokuapp.com/api/coins/bitcoin
```
```
localhost:8000/api/coins/bitcoin
```
<div id="obter-valor-de-uma-criptomoeda-a-partir-de-uma-data"/>

### Obter o valor de uma criptomoeda a partir de uma data

Para obter o valor de uma criptomoeda, baseado em uma data, o sistema trabalha com a rota `api/coins/history`. Como podemos ver acima, ela tem três parâmetros, são eles: o **id** , **date** e **conversion_currency**. O id diz respeito a criptomoeda, nesse caso, o id da criptomoeda será seu nome, por exemplo, **moon**. O segundo parâmetro é a data na qual desejamos ver o valor dessa moeda e o último é a moeda base para a conversão da criptomoeda.
Já sabendo como essa rota é formada, vamos entender agora como funciona o método que retorna esse valor. Para isso vamos acessar `getHistory`, dentro do controler `CoinController`. Nesse método temos o seguinte código:

```
    /**
     * This method returns the value of a currency on a given date
     *
     * @param  string $id
     * @param  string $date
     * @param  string $conversion_currency
     * 
     * @return \Illuminate\Http\Response
     */
    public function getHistory($id, $date, $conversion_currency = 'usd')
    {

        $client = new CoinGeckoClient();

        $data = $client->coins()->getHistory($id, $date);

        if (!isset($data['market_data'])) {
            return response()->json(['error' => 'Não existe nenhum registro da criptomoeda ' .$id. ' em ' .$date], 404);
        }

        $current_price = $data['market_data']['current_price'][$conversion_currency];

        return response()->json([
            'success' => 'O valor do '.$id.' em ' .$conversion_currency. ' na data de ' .$date. ', estava em ' .$current_price
        ], 200);   
    }

```

Inicialmente, a class `CoinGeckoClient` será instanciada, e logo em seguida, o método `getHistory` será executado. Esse método recebe a **criptomoeda** e a **data**, e então, retorna uma lista de dados, dentre eles, temos o **maket_data** / Dados do Mercado. Nesse vetor vamos ter acesso a uma série de dados, e assim, caso nenhum dado seja retornado, é sinal que algum dado informado está incorreto, desse modo, o código será encerrado nesse ponto e o usuário receberá uma mensagem informando que os dados recebidos não estão corretos. Mas caso os dados informados estejam corretos, vamos dar continuidade encontrando o valor respectivo da moeda, para isso, dentro de [market_data], vamos acessar [current_price] / Preço atual, e nesse vetor vamos ter acesso ao valor da criptomoeda informada sendo convertido para diversos tipos de moedas mundiais. Contundo, como desejamos obter esse valor por uma moeda específica, vamos selecionar o índice correspondente a moeda informada através de [$conversion_currency], assim, no final, a variável `$current_price` receberá o valor da criptomoeda na data informada. Para simular um teste, vamos tentar obter o valor atual do **moon** em **brl** na **data** de 16-07-2022. Você pode obter esse valor  através  da sua aplicação que está no ambiente de desenvolvimento, ou então, através da aplicação que já está disponível na internet, abaixo podemos observar esses dois caminhos.

```
https://api-crypto-dacxi.herokuapp.com/api/coins/history/moon/16-07-2022/brl
```
```
localhost:8000/api/coins/history/moon/16-07-2022/brl
```
<div id="salvando-os-valores-das-criptomoedas"/>

### Salvando os dados das criptomoedas

Como foi mencionado no início, a aplicação também consegue salvar os dados de valores atuais de 5 criptomoedas que são definidas por padrão no sistema. Ademais, você verá como adicionar mais criptomoedas nessa lista.

O método que faz esse processo é o `schedule`, que está na pasta `app/Console/Kernel.php`. Dentro dessa rotina nativa do Laravel é possível criar métodos, que serão executados em um determinado momento. Nesse caso, temos duas funções, uma que a cada 10 minutos salva o valor atual das criptomoedas definidas, e outra, que no final do dia, faz uma limpeza no banco de dados da aplicação para que ele não fique tão cheio. Antes de entender como isso está organizado, vamos dar uma olhadinha no banco de dados que foi criado anteriormente.

<p align="center">
    <img src="https://user-images.githubusercontent.com/60266964/179431380-4d4d2fbf-efdd-4c50-ade7-e6e3c8103cfa.png"/>
</p>

Basicamente, temos duas tabelas, são elas `coins` e `coin_type`. Em coin_type, vamos criar todas as moedas que queremos que o sistema salve a cada 10 minutos. Quando o comando `php artisan db:seed` foi executado no início, cinco moedas foram criadas de forma automática na tabela: Bitcoin, Cosmos, Ethereum, Dacxi e Moon. Com as moedas que desejamos salvar definidas, podemos analisar agora a tabela **coins**. Nela será armazenado o valor da criptomoeda naquele momento e criptomoeda terá uma chave estrangeira relacionando-a com a tabela **coin_type**.

Indo agora para o método `schedule`, vamos analisar essa função:

```
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->call(fn() => DB::table('coins')->delete())->daily()->timezone('America/Sao_Paulo');

        $schedule->call(function() {

            $client = new CoinGeckoClient();
            $available_coins_types = DB::table('coin_type')->select(['id','type'])->get();
            $response = $client->simple()->getPrice('bitcoin,cosmos,ethereum,dacxi,moon', 'usd');

            foreach($available_coins_types as $key => $coin_type){
                if(array_key_exists($coin_type->type, $response)){
                    Coin::create([
                        'coin_type_id' => $coin_type->id,
                        'value' => $response[$coin_type->type]['usd']
                    ]);
                }
            }
        })->everyTenMinutes();

    }
```
Dentro dele temos à nossa disposição o `$schedule->call()`. Essa função possibilita que diversos trechos de códigos sejam executados a partir de um determinado tempo. 

Na primeira chamada dessa função, está o trecho de códigos que faz a limpeza do banco de dados no final do dia atráves da função **daily**. Abaixo, na outra chamada dessa função, temos o código para salvar os dados das moedas. 

Inicialmente, a class CoinGeckoClient será instanciada, em seguida, na variável $available_coins_types, serão armazenados os tipos de criptomoedas disponíveis da tabela **coin_type**. Em $response, estamos recebendo o valor atual das nossas cincos moedas, no trecho `getPrice('bitcoin,cosmos,ethereum,dacxi,moon', 'usd')`. Assim, caso você queira salvar os dados de uma outra criptomoeda, adicione o nome da mesma no primeiro argumento da função, e também, crie na tabela **coin_type** um novo tipo de moeda, correspondente ao nome da moeda que você irá adicionar. Por fim, no **foreach**, vamos verificar se nos índices de $response existe algum índice que corresponde aos tipos de criptomoeda disponíveis em **coin_type**, caso exista, vamos salvar esse valor em **coins**. Essa função será executada a cada 10 minutos através de `everyTenMinutes()`. 

Para testar isso no seu ambiente de desenvolvimento, use o comando abaixo e observe os dados sendo inseridos na sua base de dados.

```
php artisan schedule:work
```

Para entender melhor sobre o schedule, você pode acessar a documentação do Laravel em https://laravel.com/docs/8.x/scheduling.

## Autor

<img style="border-radius: 50%;" src="https://user-images.githubusercontent.com/60266964/179579723-aa8b9c19-0418-4a1c-a391-fd1644dc8839.png" width="100px;" alt=""/>
<br/>

Feito por Matheus de Souza Barbosa 👋🏽 Entre em contato!

[![Linkedin Badge](https://img.shields.io/badge/-matheussouzab-blue?style=flat-square&logo=Linkedin&logoColor=white&link=https://www.linkedin.com/in/matheussouzab/)](https://www.linkedin.com/in/matheussouzab/) 
[![Gmail Badge](https://img.shields.io/badge/-matheusdesouza187@gmail.com-c14438?style=flat-square&logo=Gmail&logoColor=white&link=mailto:matheusdesouza187@gmail.com)](mailto:matheusdesouza187@gmail.com)



