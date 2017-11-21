/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
window.onload = function () {
    document.getElementById("StartBooking").style.display = 'none';
    document.getElementById("SeatSelection").style.display = 'none';

    function disableBack() {
        window.history.forward()
    }

    window.onload = disableBack();
    window.onpageshow = function (evt) {
        if (evt.persisted)
            disableBack()
    }

}

function updateAmount(list) {
    var count;
    var countValue;
    if (list.checked)
    {
        countValue = document.getElementById("demo").innerHTML;
        if (countValue === '')
        {
            count = 0;
        }
        else
        {
            count = document.getElementById("demo").innerHTML;
        }
        count++
        count = document.getElementById("demo").innerHTML = count;
    }
    else {
        count = document.getElementById("demo").innerHTML;

        count--
        document.getElementById("demo").innerHTML = count;
    }
    if (count != 0) {
        document.getElementById("SeatSelection").style.display = 'block';
    }
    else
    {
        document.getElementById("SeatSelection").style.display = 'none';
    }
}

function TicketType() {
    var PaymentMode = {"Standard Price - $12.50": 12.50, "Visa Checkout - $12.00": 12, "DBS/POSB Credit & Debit - $7.50": 7.50};


    var x = document.getElementById("BuyTicket").value;
    var count = document.getElementById("demo").innerHTML.valueOf();
    var TicketPrice = PaymentMode[x] * count;
    //alert("Total Price: " + TicketPrice);
    document.getElementById("TicketType").innerHTML = x;
    document.getElementById("TicketType").innerHTML.style = 'color:White';

    document.getElementById("TicketPrice").innerHTML = "$" + PaymentMode[x];
    document.getElementById("TicketPrice").innerHTML.style = 'White';

    document.getElementById("Qty").innerHTML = count;
    document.getElementById("Qty").innerHTML.style = 'White';

    document.getElementById("TotalAmount").innerHTML = "$" + TicketPrice;
    document.getElementById("TotalAmount").innerHTML.style = 'White';

    if (x != "" && count != 0) {
        document.getElementById("StartBooking").style.display = 'block';

    }
}

