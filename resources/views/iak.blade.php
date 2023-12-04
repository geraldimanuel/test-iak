<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test IAK</title>
</head>

<body>
    <div style="">
        <form action="/payment/process" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Name" value="Gerald Imanuel Wijaya">
            <input type="number" name="amount" placeholder="Amount" value=100000>
            <input type="text" name="payment_code" placeholder="Payment Code" value="BTX001VIP">
            <button type="submit">Validate</button>
        </form>
    </div>
</body>

</html>
