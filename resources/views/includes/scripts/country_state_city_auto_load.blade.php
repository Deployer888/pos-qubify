<script type="text/javascript">
    !(function($){
        "use strict";
            // Load country wise state
            $('#country').on('change',function(e){
                let country = e.target.value;
                $.ajax({
                    url: "{{url('/locations/api/country-wise-state?id=')}}"+country,
                    type: "GET",
                    dataType: "JSON",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data){
                        let state =  $('#state').empty();
                        state.append('<option class="form-control" value="0" selected disabled>Select State</option>');
                        $.each(data.data,function(key,val){
                            state.append('<option value ="'+val.id+'">'+val.name+'</option>');
                        });
                        // Empty city
                        $('#city').empty();
                    }
                })
            });

            // Load state wise city
            $('#state').on('change',function(e){
                let state = e.target.value;
                $.ajax({
                    url: "{{url('/locations/api/state-wise-city?id=')}}"+state,
                    type: "GET",
                    dataType: "JSON",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data){
                        let city =  $('#city').empty();
                        city.append('<option class="form-control" value="0" selected disabled>Select City</option>');
                        $.each(data.data,function(key,val){
                            city.append('<option value ="'+val.id+'">'+val.name+'</option>');
                        });
                    }
                })
            });


            // Load previous data
            $(document).ready(function() {
                let country = "{{ isset($address_data->country) ? $address_data->country : 0 }}";

                $.ajax({
                    url: "{{url('/locations/api/country-wise-state?id=')}}"+country,
                    type: "GET",
                    dataType: "JSON",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data){
                        let state =  $('#state').empty();
                        let $option_front = "";
                        let $option_select ="";
                        let $option_back = "";
                        let $option ="";
                        state.append('<option class="form-control" value="0" selected disabled>Select State</option>');
                        $.each(data.data,function(key,val){
                            
                            $option_front = '<option value ="' + val.id + '" ';
                            $option_select = val.id == {{isset($address_data->state) ? $address_data->state : 0}} ? 'selected' : '';
                            $option_back = '>' + val.name+'</option>';

                            $option = $option_front +$option_select+ $option_back;

                            state.append($option);
                        });
                    }
                })
            });

            $(document).ready(function() {
                let state = "{{ isset($address_data->state) ? $address_data->state : 0 }}";

                $.ajax({
                    url: "{{url('/locations/api/state-wise-city?id=')}}"+state,
                    type: "GET",
                    dataType: "JSON",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data){
                        let city =  $('#city').empty();
                        let $option_front;
                        let $option_select;
                        let $option_back;
                        let $option;
                        city.append('<option class="form-control" value="0" selected disabled>Select State</option>');
                        $.each(data.data,function(key,val){
                           
                            $option_front = '<option value ="' + val.id + '" ';
                            $option_select = val.id == {{isset($address_data->city) ? $address_data->city : 0}} ? 'selected' : '';
                            $option_back = '>' + val.name+'</option>';

                            $option = $option_front +$option_select+ $option_back;

                            city.append($option);
                        });
                    }
                })
            });
    })(jQuery)
</script>
