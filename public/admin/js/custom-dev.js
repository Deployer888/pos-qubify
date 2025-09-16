!(function($) {
    "use strict";

    // Delete form
    $(document).on("click", ".delete-list-data", function() {
        let from_id = $(this).data("from-id");
        var from_name = typeof $(this).data("from-name");
        var from_text = typeof $(this).data("from-text");

        if (from_name === 'undefined') {
            from_name = "this!"
        }else{
            from_name = $(this).data("from-name").slice(0, 10)+'...';
        }
        if (from_text === 'undefined') {
            from_text = "You won't be able to revert this!"
        }else{
            from_text = $(this).data("from-text");
        }
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger mr-2"
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons
            .fire({
                title: "Are you sure?",
                text: '',html: from_text,
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete " + from_name + "!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
            })
            .then(result => {
                if (result.value) {
                    let delete_form_id = $("#delete-form-" + from_id);
                    $(delete_form_id).submit();
                }
            });
    });

    // View invoice payment
    $(document).on("click", ".view-invoice-payment", function(e) {
        e.preventDefault();
        let invoice_id = $(this).data("invoice-id");
        $.ajax({
            url: `/admin/invoices/payments/${invoice_id}`,
            type: "GET",
            dataType: "json",
            success: function(res) {
                let table_data = "";
                res.forEach(function(item) {
                    table_data += `<tr><td>${item.date}</td><td>${
                        item.payment_type
                    }</td><td>${item.amount}</td><td>${
                        item.notes ? item.notes : ""
                    }</td><td class="text-center"><a class="text-danger anchor-redirect" href="#" rel="${
                        item.id
                    }" ><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>`;
                });

                $("#invoice-payment-view-table tbody").empty();
                $("#invoice-payment-view-table tbody").append(table_data);
            }
        });
        $("#invoicePaymentView").modal("toggle");
    });
$(document).on("click", ".view-customer-invoice-payment", function(e) {
        e.preventDefault();
        let invoice_id = $(this).data("invoice-id");
        $.ajax({
            url: `/customer/invoices/payments/${invoice_id}`,
            type: "GET",
            dataType: "json",
            success: function(res) {
                let table_data = "";
                res.forEach(function(item) {
                    table_data += `<tr><td>${item.date}</td><td>${
                        item.payment_type
                    }</td><td>${item.amount}</td><td>${
                        item.notes ? item.notes : ""
                    }</td></tr>`;
                });

                $("#invoice-payment-view-table tbody").empty();
                $("#invoice-payment-view-table tbody").append(table_data);
            }
        });
        $("#invoicePaymentView").modal("toggle");
    });
    $(document).on("click", ".update-stock", function(e) {
        e.preventDefault();
        let product_id = $(this).data("id");
        $.ajax({
            url: `app/api/products/stack-update/${product_id}`,
            type: "GET",
            dataType: "html",
            success: function(res) {
                $("#stack-update-pupup").empty();
                $("#stack-update-pupup").append(res);
            }
        });
        $("#stockUpdate").modal("toggle");
    });

    $(document).on("click", ".anchor-redirect", function() {
        let id = $(this).attr("rel");
        if (confirm("are you sure?")) {
            window.location.href = "/admin/invoices/payments/delete/" + id;
        }
    });

    // Send invoice payment
    $(document).on("click", ".send-invoice-payment", function(e) {
        e.preventDefault();
        let invoice_id = $(this).data("invoice-id");
        $.ajax({
            url: `/admin/invoices/customer-email/${invoice_id}`,
            type: "GET",
            dataType: "json",
            success: function(res) {
                $("#send-invoice-payment-email").val(res);
            }
        });

        $("#send-invoice-payment-invoice-id").val(invoice_id);
        $("#invoicePaymentSend").modal("toggle");
    });

    // Live invoice payment
    $(document).on("click", ".live-invoice-payment", function(e) {
        e.preventDefault();
        let token = $(this).data("invoice-token");

        $("#live-invoice-token").val(token);
        $("#liveInvoiceUrl").modal("toggle");
    });

    // Copy invoice url
    $(document).on("click", ".copy-url-btn", function(e) {
        e.preventDefault();
        let id = $(this).data("copy-id");

        let copyText = document.getElementById(id ? id : "live-invoice-token");
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */
        navigator.clipboard.writeText(copyText.value);

        // alert("Copied the text: " + copyText.value);

        $("#copied_msg").show();

        if (id) {
        } else {
            // $("#liveInvoiceUrl").modal("toggle");
        }
    });

    $("#liveInvoiceUrl").on("hidden.bs.modal", function () {
        // put your default event here
        $("#copied_msg").hide();
    });

    // Logout
    $(document).on("click", ".logout-btn", function(e) {
        e.preventDefault();
        let logout_from = $("#logout-form");
        $(logout_from).submit();
    });

    // Print
    $(document).on("click", ".section-print-btn", function(e) {
        e.preventDefault();
        let divName = $(this).data("div-name");
        let printContents = document.getElementById(divName).innerHTML;
        let originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        window.location.reload();
    });

    $(document).on("click", ".prevent-default", function(e) {
        e.preventDefault();
    });

    $(document).on("change", "#lang-change", function (){
        let set_lang_url = $("#set_lang_url").val();
        let lang_val = $(this).val();
        $.get(set_lang_url, {'lang': lang_val}, function (){
            location.reload();
        });
    })

    $(document).on("click", "#printWareHouseDetails", function (){
        window.print()
    })

    $(document).on("change", "#reportPurchaseWarehouses", function (){
        let wareHouse = $(this).val();
        $("#allTimeWarehouse").val(wareHouse)
    })

    $(document).on("change", "#invoiceCreateWarehouse", function (){
        let wareHouse = $(this).val();
        $("#invoiceCreate").submit()
    })

    $(document).on("click", "#submit_with_stock", function (){
        $("#is_submit_with_stock").val(1)
    })
    $(document).on("click", "#submit_set_product", function (){
        $("#is_submit_set_product").val(1)
    })

    $(document).ready(function($) {
        $(document).on('click', '#generatePDF', function(event) {
            let invoice_number = $("#invoice_number").text()
            event.preventDefault();
            var element = document.getElementById('print-invoice');
            var opt =
                {
                    margin:       [0, 0, 30, 0], //top, left, buttom, right
                    filename:    'Invoice_'+ invoice_number +'.pdf',
                    image:        { type: 'jpeg', quality: 0.98 },
                    html2canvas:  { dpi: 192, scale: 5, letterRendering: true},
                    jsPDF:        { unit: 'pt', format: 'a4', orientation: 'portrait'},
                    pageBreak: { mode: 'css', after:'.break-page'}
                };
            html2pdf().set(opt).from(element).save();
        });
    });

})(jQuery);
