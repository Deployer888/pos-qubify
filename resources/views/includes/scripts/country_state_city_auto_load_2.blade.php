<script type="text/javascript">
    !(function ($) {
        "use strict";
            // Load country wise state
            $('#country2').on('change',function(e){
                let country = e.target.value;
                $.ajax({
                    url: "{{url('/locations/api/country-wise-state?id=')}}"+country,
                    type: "GET",
                    dataType: "JSON",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data){
                        let state =  $('#state2').empty();
                        state.append('<option class="form-control" value="0" selected disabled>Select State</option>');
                        $.each(data.data,function(key,val){
                            state.append('<option value ="'+val.id+'">'+val.name+'</option>');
                        });

                        // Empty city
                        $('#city2').empty();
                    }
            })
        });

        // Load state wise city
        $('#state2').on('change',function(e){
            let state = e.target.value;
            $.ajax({
                url: "{{url('/locations/api/state-wise-city?id=')}}"+state,
                type: "GET",
                dataType: "JSON",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data){
                    let city =  $('#city2').empty();
                    city.append('<option class="form-control" value="0" selected disabled>Select City</option>');
                    $.each(data.data,function(key,val){
                        city.append('<option value ="'+val.id+'">'+val.name+'</option>');
                    });
                }
            })
        });


        // Load previous data
        $(document).ready(function() {
            let country = "{{ isset($address_data->b_country) ? $address_data->b_country : 0 }}";

            $.ajax({
                url: "{{url('/locations/api/country-wise-state?id=')}}"+country,
                type: "GET",
                dataType: "JSON",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data){
                    let state =  $('#state2').empty();
                    let $option_front = "";
                    let $option_select ="";
                    let $option_back = "";
                    let $option ="";
                    state.append('<option class="form-control" value="0" selected disabled>Select State</option>');
                    $.each(data.data,function(key,val){
                        $option_front = '<option value ="' + val.id + '" ';
                        $option_select = val.id == {{isset($address_data->b_state) ? $address_data->b_state : 0}} ? 'selected' : '';
                        $option_back = '>' + val.name+'</option>';
                        $option = $option_front +$option_select+ $option_back;

                        state.append($option);
                    });
                }
            })
        });

        $(document).ready(function() {
            let state = "{{ isset($address_data->b_state) ? $address_data->b_state : 0 }}";

            $.ajax({
                url: "{{url('/locations/api/state-wise-city?id=')}}"+state,
                type: "GET",
                dataType: "JSON",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data){
                    let city =  $('#city2').empty();
                    let $option_front = "";
                    let $option_select = "";
                    let $option_back ="";
                    let $option ="";
                    city.append('<option class="form-control" value="0" selected disabled>Select State</option>');
                    $.each(data.data,function(key,val){
                       
                        $option_front = '<option value ="' + val.id + '" ';
                        $option_select = val.id == {{isset($address_data->b_city) ? $address_data->b_city : 0}} ? 'selected' : '';
                        $option_back = '>' + val.name+'</option>';

                        $option = $option_front +$option_select+ $option_back;

                        city.append($option);
                    });
                }
            })
        });
    })(jQuery);

</script>