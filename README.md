### POST `/api/order/create`
Json body:
```js
{
    // unique worker id
    "worker": String,
    // is it master signaling?
    "is_master": Boolean,
    "pair": String,
    // decimal string
    "volume": String,
    // 'sell' or 'buy'
    "operation": String,
}
```
Json response:
```js
{
    // true -> saved, false -> some errors (details are in `data` key)
    "ok": Boolean,
    // String w/ problem description OR an array w/ pairs 'field name' -> 'promlem description'
    "data"?: String|Array<String, String>
}
```

### GET `/api/orders/{since?}`
`since` is an optional Integer parameter. Used as a filter for Order.id > `since`
Json response:
```js
{
    // always true currently
    "ok": True,
    "data": Array<Array{
        id: Int,
        // Y-m-d H:i:s
        createdAt: String,
        // is it master signaling?
        isMaster: Boolean,
        // unique worker id
        worker: String,
        pair: String,
        // decimal string
        volume: String
        // 'sell' or 'buy'
        operation: String
    }>
}
```
