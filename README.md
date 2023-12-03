# Роутинг для RabbitMQ в приложении

В данном приложении реализован роутинг для взаимодействия с RabbitMQ. Два основных эндпоинта предоставляют различные
реализации, позволяя вам взаимодействовать с системой очередей.

## /rabbitmq

Этот адрес предоставляет первую реализацию взаимодействия с RabbitMQ. Здесь используется стандартный механизм обмена
сообщениями, позволяя отправлять и обрабатывать сообщения через очереди.


## /rabbitmq2

Этот адрес предоставляет вторую реализацию взаимодействия с RabbitMQ. Здесь была попытка использовать Consistent-Hashing Exchange
для более правильног ораспределения по очередям.

### Пример использования
Развернуть -- make up

### Метод 1
```bash
curl -X POST  http://localhost:81/api/rabbitmq
```
### Метод 2
```bash
curl -X POST  http://localhost:81/api/rabbitmq2
```

В обоих случаях будет сгенерирован массив из 10к элементов которые в свою очередь будут пушить по разным очередям.
Вопрос воркера кастомного, к сожалению, остается открктым




