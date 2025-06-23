Il bundle mette a disposizione alcune funzionalità utili per progetti che espongono API Rest, tanto per la serializzazione
che per la deserializzazione delle richieste. Il bundle si basa fortemente sul bundle JMS Serializer (https://jmsyst.com/libs/serializer).

I service messi a dispozione sono:
- \App\Darce\RestBundle\Service\RequestDeserializer, espone metodi che consentono la deserializzazione del 
contenuto delle richieste in  nel tipo di model passato in input. Ogni elemento del json che deve essere deserializzato
deve presentare l'apposita annotation di \JMS\Serializer\Annotation\Type, ed eventualmente l'annoation \JMS\Serializer\Annotation\SerializedName
se il nome dell'attributo del model è diverso da quello del json. Il servizio oltre a deserializzare il Model si occupa
anche di validarlo se questo presenta le apposite regole di Symfony Validator. Sia per la deserializzazione che per la 
validazione il servizio sfrutta il concetto dei gruppi (https://jmsyst.com/libs/serializer/master/reference/annotations#groups)
per avere massima flessibilità.

- \App\Darce\RestBundle\Service\ResponseSerializer, esponde metodi che consentono la serializzazione di un oggetto, o
di un'array di oggetti, di tipo \App\Darce\RestBundle\View\SerializableViewInterface (nota, per le array non si tratta 
di un vincolo stretto).

- \App\Darce\RestBundle\Model\TranslatableString e il relativo listener \App\Darce\RestBundle\EventListener\TranslatableStringListener
consentono di tradurre in maniera semplice una stringa. E' sufficiente infatti che un metodo restituisca un oggetto di tipo
TranslatableString per far si che la stringa di tale metodo venga tradotta in fase di serializzazione da JMS:

`return new TranslatableString('key_string_to_translate', ['foo' => 'baz'])`

- \App\Darce\RestBundle\EventListener\OnExceptionListener si occupa della serializzazione in un formato standard di
alcune tipologie di Exception:

```
{
    "error": {
        "code": 0,
        "message": "Foo Bar",
        "violations": [
            {
                "message" : "Foo",
                "property" : "bar"
            } 
        ]
    }
}
```