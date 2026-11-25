/*
@ASSESSME.USERID: YOUR_USERID
@ASSESSME.AUTHOR: YOUR_FULL_NAME
@ASSESSME.DESCRIPTION: Calculates live database-driven pizza order totals.
@ASSESSME.ANALYZE: YES
*/

document.addEventListener("DOMContentLoaded", function () {
    const pizza = document.getElementById("pizza");
    const size = document.getElementById("size");
    const crust = document.getElementById("crust");
    const quantity = document.getElementById("quantity");
    const orderType = document.getElementById("orderType");

    const itemSubtotal = document.getElementById("itemSubtotal");
    const deliveryFee = document.getElementById("deliveryFee");
    const tax = document.getElementById("tax");
    const grandTotal = document.getElementById("grandTotal");

    function money(amount) {
        return "€" + amount.toFixed(2);
    }

    function selectedPrice(selectElement) {
        return parseFloat(selectElement.options[selectElement.selectedIndex].dataset.price);
    }

    function calculateTotal() {
        let base = selectedPrice(pizza);
        let sizeMultiplier = selectedPrice(size);
        let crustCost = selectedPrice(crust);
        let qty = parseInt(quantity.value) || 1;

        let toppingsTotal = 0;
        document.querySelectorAll(".topping:checked").forEach(function (item) {
            toppingsTotal += parseFloat(item.dataset.price);
        });

        let extrasTotal = 0;
        document.querySelectorAll(".extra:checked").forEach(function (item) {
            extrasTotal += parseFloat(item.dataset.price);
        });

        let itemTotal = ((base * sizeMultiplier) + crustCost + toppingsTotal + extrasTotal) * qty;
        let delivery = orderType.value === "delivery" ? 2.50 : 0;
        let taxAmount = itemTotal * 0.08;
        let total = itemTotal + delivery + taxAmount;

        itemSubtotal.textContent = money(itemTotal);
        deliveryFee.textContent = money(delivery);
        tax.textContent = money(taxAmount);
        grandTotal.textContent = money(total);
    }

    document.querySelectorAll("select, input").forEach(function (input) {
        input.addEventListener("change", calculateTotal);
        input.addEventListener("input", calculateTotal);
    });

    calculateTotal();
});