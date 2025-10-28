// Extracted checkout/cart page JS
(function(){
    function luhnCheck(card) {
        let sum = 0;
        let shouldDouble = false;
        card = (card || '').replace(/\s/g, '');
        for (let i = card.length - 1; i >= 0; i--) {
            let digit = parseInt(card.charAt(i), 10);
            if (shouldDouble) {
                digit *= 2;
                if (digit > 9) digit -= 9;
            }
            sum += digit;
            shouldDouble = !shouldDouble;
        }
        return sum % 10 === 0;
    }

    function validateCheckoutCardNumber() {
        const el = document.getElementById('card_number');
        const number = (el && el.value || '').replace(/\s/g, '');
        const msg = document.getElementById('checkout-card-number-validation');
        if (!/^\d{13,19}$/.test(number)) {
            if (msg) { msg.textContent = 'Card number must be 13–19 digits.'; msg.className = 'mt-2 text-sm text-red-600'; }
        } else if (!luhnCheck(number)) {
            if (msg) { msg.textContent = 'Card number is not valid.'; msg.className = 'mt-2 text-sm text-red-600'; }
        } else {
            if (msg) { msg.textContent = 'Valid card number.'; msg.className = 'mt-2 text-sm text-green-600'; }
        }
    }

    function validateCheckoutCardExpiry() {
        const expiry = (document.getElementById('card_expiry')||{}).value.trim();
        const msg = document.getElementById('checkout-card-expiry-validation');
        const re = /^(0[1-9]|1[0-2])\/(\d{2})$/;
        if (!re.test(expiry)) {
            if (msg) { msg.textContent = 'Expiry must be MM/YY.'; msg.className = 'mt-2 text-sm text-red-600'; }
            return;
        }
        const [mm, yy] = expiry.split('/');
        const now = new Date();
        const yyyy = parseInt(yy,10) + 2000;
        const expDate = new Date(yyyy, parseInt(mm,10) - 1, 1);
        if (msg) {
            if (expDate < new Date(now.getFullYear(), now.getMonth(), 1)) { msg.textContent = 'Card is expired.'; msg.className = 'mt-2 text-sm text-red-600'; }
            else { msg.textContent = 'Valid expiry date.'; msg.className = 'mt-2 text-sm text-green-600'; }
        }
    }

    function validateCheckoutCardCVV() {
        const ccv = (document.getElementById('card_cvv')||{}).value.trim();
        const msg = document.getElementById('checkout-card-cvv-validation');
        if (msg) {
            if (!/^\d{3,4}$/.test(ccv)) { msg.textContent = 'CVV must be 3 or 4 digits.'; msg.className = 'mt-2 text-sm text-red-600'; }
            else { msg.textContent = 'Valid CVV.'; msg.className = 'mt-2 text-sm text-green-600'; }
        }
    }

    function validateBankAccount(input) {
        if (!input) return;
        let value = input.value || '';
        const msg = document.getElementById('bank-account-validation');
        if (!/^[0-9]{16}$/.test(value)) {
            if (msg) { msg.textContent = 'Account number must be exactly 16 digits, no spaces.'; msg.className = 'mt-2 text-sm text-red-600'; }
            input.classList.remove('border-[#0d9488]','focus:ring-[#0d9488]'); input.classList.add('border-red-500','focus:ring-red-500');
        } else {
            if (msg) { msg.textContent = 'Valid account number.'; msg.className = 'mt-2 text-sm text-green-600'; }
            input.classList.remove('border-red-500','focus:ring-red-500'); input.classList.add('border-[#0d9488]','focus:ring-[#0d9488]');
        }
        input.value = value.replace(/[^0-9]/g, '');
    }

    function showPaymentSection() {
        const checked = document.querySelector('input[name="payment_method"]:checked');
        const method = checked ? checked.value : null;
        const cashEl = document.getElementById('cash-payment-info');
        const cardEl = document.getElementById('credit-card-form');
        const bankEl = document.getElementById('bank-transfer-fields');
        if (cashEl) cashEl.style.display = (method === 'cash') ? 'block' : 'none';
        if (cardEl) cardEl.style.display = (method === 'online') ? 'block' : 'none';
        if (bankEl) bankEl.style.display = (method === 'bank_transfer') ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        showPaymentSection();
        document.querySelectorAll('input[name="payment_method"]').forEach(function(radio){ radio.addEventListener('change', showPaymentSection); });
        const num = document.getElementById('card_number'); if (num) num.addEventListener('input', validateCheckoutCardNumber);
        const exp = document.getElementById('card_expiry'); if (exp) exp.addEventListener('input', validateCheckoutCardExpiry);
        const cvv = document.getElementById('card_cvv'); if (cvv) cvv.addEventListener('input', validateCheckoutCardCVV);
        const bank = document.getElementById('bank_account'); if (bank) bank.addEventListener('input', function(){ validateBankAccount(this); });
    });

})();
