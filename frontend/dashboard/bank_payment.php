<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="/project_wad/styles/registeredUser/bank_payment.css">
</head>

<body>
    <div class="payment-container">
        <h2>Payment</h2>
        <div class="payment-tabs">
            <button class="tab active" id="card-tab">Card</button>
            <button class="tab" id="online-banking-tab">Online Banking</button>
        </div>

        <!-- Card Payment Section -->
        <div id="card-section" class="payment-section" style="display: block;">
            <form action="/project_wad/backend/process_payment.php" method="POST" class="payment-form">
                <!-- Pass the payment ID -->
                <input type="hidden" name="payment_id" value="<?php echo htmlspecialchars($_GET['payment_id']); ?>">
                <div class="details-group">
                    <label for="card-number">Credit card details</label>
                    <input type="text" id="card-number" name="card_number" placeholder="0000 0000 0000 0000" required>
                </div>
                <div class="details-group-inline">
                    <input type="text" id="expiry-date" name="expiry_date" placeholder="MM/YY" required>
                    <input type="text" id="cvv" name="cvv" placeholder="CVV" required>
                </div>
                <div class="details-group">
                    <input type="text" id="card-holder" name="card_holder" placeholder="Name on card" required>
                </div>
                <div class="address-group">
                    <label for="billing-address">Billing address</label>
                    <select id="billing-country" name="billing_country" required>
                        <option value="Malaysia" selected>Malaysia</option>
                        <option value="Singapore">Singapore</option>
                        <option value="Indonesia">Indonesia</option>
                        <option value="Brunei">Brunei</option>
                    </select>
                </div>
                <div class="postal-group">
                    <input type="text" id="postal_code" name="postal_code" placeholder="Postal code" required>
                </div>
                <div class="consent-text">
                    By providing your card information, you allow us to charge your card for future payments in
                    accordance with their terms.
                </div>
                <button type="submit" class="pay_button">Pay</button>
            </form>
        </div>

        <!-- Online Banking Section -->
        <div id="online-banking-section" class="payment-section" style="display: none;">
            <form action="/project_wad/backend/process_payment.php" method="POST">
                <!-- Pass the payment ID -->
                <input type="hidden" name="payment_id" value="<?php echo htmlspecialchars($_GET['payment_id']); ?>">
                <div class="bank-option">
                    <img src="/project_wad/images/bank/maybank.png" alt="Maybank Logo">
                    <label for="maybank">Maybank</label>
                    <input type="radio" id="maybank" name="bank" value="Maybank">
                </div>
                <div class="bank-option">
                    <img src="/project_wad/images/bank/cimb.png" alt="CIMB Logo">
                    <label for="cimb">CIMB</label>
                    <input type="radio" id="cimb" name="bank" value="CIMB">
                </div>
                <div class="bank-option">
                    <img src="/project_wad/images/bank/publicbank.png" alt="Public Bank Logo">
                    <label for="publicbank">Public Bank</label>
                    <input type="radio" id="publicbank" name="bank" value="Public Bank">
                </div>
                <div class="bank-option">
                    <img src="/project_wad/images/bank/rhb.png" alt="RHB Logo">
                    <label for="rhb">RHB</label>
                    <input type="radio" id="rhb" name="bank" value="RHB">
                </div>
                <div class="bank-option">
                    <img src="/project_wad/images/bank/hongleong.png" alt="Hong Leong Logo">
                    <label for="hongleong">Hong Leong</label>
                    <input type="radio" id="hongleong" name="bank" value="Hong Leong">
                </div>
                <button class="pay-button">Pay</button>
            </form>
        </div>
    </div>


    <script src="/project_wad/javascript/bank_payment.js"></script>
</body>

</html>