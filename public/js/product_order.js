function addRow() {
    $("#addRowBtn").button("loading");

    var tableLength = $("#productTable tbody tr").length;

    var tableRow;
    var arrayNumber;
    var count;

    if (tableLength > 0) {
        tableRow = $("#productTable tbody tr:last").attr('id');
        arrayNumber = $("#productTable tbody tr:last").attr('class');
        count = tableRow.substring(3);
        count = Number(count) + 1;
        arrayNumber = Number(arrayNumber) + 1;
    } else {
        // no table row
        count = 1;
        arrayNumber = 0;
    }

    $.ajax({
        url: '/api/products/all',
        type: 'get',
        dataType: 'json',
        success: function (response) {
            $("#addRowBtn").button("reset");

            var tr = '<tr id="row' + count + '" class="' + arrayNumber + '">' +
                '<td>' +
                '<div class="form-group">' +
                '<select class="form-control select2" name="product[]" id="product' + count + '" onchange="getProductData(' + count + ')" >' +
                '<option value="">--product--</option>';
            // console.log(response);
            $.each(response, function (index, value) {
                tr += '<option value="' + value.id + '">' + value.name + '</option>';
            });

            tr += '</select>' +
                '</div>' +
                '</td>' +
                '<td>' +
                '<input type="text" placeholder="Price" name="rate[]" id="rate' + count + '" autocomplete="off" readonly class="form-control" />' +
                '</td >' +
                '<td>' +
                '<div class="form-group">' +
                '<input type="text" placeholder="Qty" name="quantity[]" id="quantity' + count + '" onkeyup="getTotal(' + count + ')" autocomplete="off" class="form-control" />' +
                '</div>' +
                '</td>' +
                '<td>' +
                '<input type="text" placeholder="Total" name="total[]" readonly id="total' + count + '" autocomplete="off" class="form-control"  />' +
                '</td>' +
                '<td>' +
                '<button class="btn btn-default removeProductRowBtn" type="button" onclick="removeProductRow(' + count + ')">' +
                '<i class="glyphicon glyphicon-trash"></i></button>' +
                '</td>' +
                '</tr>';
            if (tableLength > 0) {
                $("#productTable tbody tr:last").after(tr);
            } else {
                $("#productTable tbody").append(tr);
            }

        } // /success
    });	// get the product data

} // /add row

function removeProductRow(row = null) {
    if (row) {
        $("#row" + row).remove();
        subAmount();
    } else {
        alert('error! Refresh the page again');
    }
}

// table total
function getTotal(row = null) {
    if (row) {
        var total = Number($("#rate" + row).val()) * Number($("#quantity" + row).val());
        total = total.toFixed(2);
        $("#total" + row).val(total);
        subAmount();
    } else {
        alert('no row !! please refresh the page');
    }
}

function paidAmount() {
    var grandTotal = $("#amount_to_pay").val();

    if (grandTotal) {
        var dueAmount = Number($("#amount_to_pay").val()) - Number($("#amount_paid").val());
        dueAmount = dueAmount.toFixed(2);
        $("#due").val(dueAmount);
    } // /if
} // /paid amoutn function

function subAmount() {
    var tableProductLength = $("#productTable tbody tr").length;
    var totalSubAmount = 0;
    for (x = 0; x < tableProductLength; x++) {
        var tr = $("#productTable tbody tr")[x];
        var count = $(tr).attr('id');
        count = count.substring(3);

        totalSubAmount = Number(totalSubAmount) + Number($("#total" + count).val());
    } // /for

    totalSubAmount = totalSubAmount.toFixed(2);
    var amountToPay=$("#amount_to_pay");
    // vat
    var vat = (Number(amountToPay.val())/100) * 18;
    vat = vat.toFixed(2);
    $("#tax").val(vat);

    // sub total
    amountToPay.val(totalSubAmount);
    var paidAmount = $("#amount_paid").val();
    if (paidAmount) {
        paidAmount = Number($("#amount_to_pay").val()) - Number(paidAmount);
        paidAmount = paidAmount.toFixed(2);
        $("#due").val(paidAmount);
    } else {
        $("#due").val($("#amount_to_pay").val());
    } // else

} // /sub total amount

// select on product data
function getProductData(row = null) {
    if (row) {
        var menuId = $("#product" + row).val();
        if (menuId === "") {
            $("#rate" + row).val("");
            $("#quantity" + row).val("");
            $("#total" + row).val("");
        } else {
            $.ajax({
                url: '/admin/products/' + menuId,
                type: 'get',
                dataType: 'json',
                success: function (response) {
                    // setting the rate value into the rate input field
                    $("#rate" + row).val(response.price);

                    $("#quantity" + row).val(1);

                    var total = Number(response.price);
                    total = total.toFixed(2);
                    $("#total" + row).val(total);

                    subAmount();
                } // /success
            }); // /ajax function to fetch the product data
        }

    } else {
        alert('no row! please refresh the page');
    }
} // /select on product data





