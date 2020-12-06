<?php
require_once './config.php';
// require_once './db.php';
// $sql   = 'select * from stripe;';
// $query = mysqli_query($con, $sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charge with Stripe</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-3"></div>
            <div class="col-6">
                <form method="POST" action="./stripe_process.php" id="form">
                    <?php
                        if (isset($_GET['msg'])) {
                            echo '<div class="alert alert-success mt-5" role="alert">' . $_GET['msg'] . ' Transaction ID: ' . $_GET['id'] . '</div>';
                        }
                    ?>

                    <div class="row mt-5">
                        <div class="col mt-2">
                            <input type="text" name="price" class="form-control" placeholder="Donation Amount">
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <div id="card-element" style="border:1px solid #c7c8cd;padding:9px;background: white;
">
                            <div id="card-result">
                            </div>
                        </div>
                        <span class="error-msg card_number-error"></span>
                    </div>
                    <div class="form-group mt-2">
                        <input type="button" value="Pay" class="btn btn-primary btn-block" id="pay">
                    </div>
                </form>
            </div>
            <div class="col-3"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');
        var elements = stripe.elements();
        var cardElement = elements.create('card');
        cardElement.mount('#card-element');

        var resultContainer = document.getElementById('card-result');
        $(function() {

            $("#pay").on('click', function(e) {
                e.preventDefault();
                stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement
                }).then(function(result) {
                    if (result.error) {
                        // Display error.message in your UI
                        resultContainer.textContent = result
                            .error.message;
                    } else {
                        // alert(result.paymentMethod.id);
                        stripeTokenHandler(result
                            .paymentMethod.id);
                    }
                });
            })
        });


        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token);
            form.appendChild(hiddenInput);
            form.submit();
        }
    </script>
</body>

</html>