# Crawler

## Configuração:

Crie o Arquivo .env
```sh
cp .env.example .env
```

Atualize as variáveis de ambiente do arquivo .env
```dosini
APP_NAME="Crawler"
APP_URL=http://localhost:8989

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=crawler
DB_USERNAME=root
DB_PASSWORD=root

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

Suba os containers do projeto
```sh
docker-compose up -d
```


Acesse o container app com o bash
```sh
docker-compose exec app bash
```


Instale as dependências do projeto
```sh
composer install
```


Gere a key do projeto Laravel
```sh
php artisan key:generate
```

Acesse o projeto
[http://localhost:8989](http://localhost:8989)

### As URL's do projeto são:

[Crawler utilizando CURL ](http://localhost:8989/api/get-answer-curl)

[Crawler sem utilizar CURL ](http://localhost:8989/api/get-answer)

### Para realizar os testes:
```sh
php artisan test
```

### Considerações:
Apesar de não ter conseguido realizar que o Crawler fizesse obtivesse o resultado final que seria a resposta após o click no botão, identifiquei o seguinte processo para tentar extrair o resultado:

- Ao entrar o link, existe um campo ```hidden``` que tem um token;
- Este token deve passar por uma criptografia que inverte os valores, ex: a = z e 0 = 9;
- Também percebi que existia um cookie chamado ``` PHPSESSID ``` que era resetado após a requisição do form;
- Após isso, inputamos o valor do campo hidden descriptografado no mesmo;
- Realizamos uma requisição ``` POST ``` para a mesma URL.
- Infelizmente sempre que tentei desta forma a resposta era um HTTP 200 porem com o corpo da resposta Forbidden, falhei em conseguir o retorno correto da requisição como o comportamento em um browser. 


> **_OBSERVAÇÃO:_** Se possivel, ficaria muito feliz se alguém me retornasse como seria a forma correta para obter o resultado.

```
Aquele que pergunta é tolo por 5 minutos. Aquele que não pergunta será tolo para o resto da vida. (Provérbio Chinês)
```

