$(document).ready(function (){
    $(".select_all").on('click', function(){
        if($(".select_all").is(':checked')){
            $('input:checkbox').prop('checked',true);
            getValueFromCheckbox()
        } else {
            $('input:checkbox').prop('checked', false);
            getValueFromCheckbox()
        }
    });

    $('#download_barcode').on('click', function (){
        $("#download_form").submit()
    })
})
$(document).ready(function () {
    $(document).on('click', function () {
        if ($(".product_checkbox").is(':checked')) {
            getValueFromCheckbox()
        }else {
            getValueFromCheckbox()
        }
    })

    $(".select_all_checkbox").removeAttr("title");
})

function getValueFromCheckbox(){
    var val = [];
    $(':checkbox:checked').each(function(i){
        if( $(this).val()!='on'){
            val.push($(this).val());
        }
    });
    $("#product_ids").val(val)
}
