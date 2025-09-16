<script>
    !(function($){
        "use strict"
        var isSubmitting = false
        $(document).ready(function () {
            $('form').on('submit', function(){
                isSubmitting = true
            })
            $('form').data('initial-state', $('form').serialize());
            $(window).on('beforeunload', function() {
                if (!isSubmitting && $('form').serialize() !== $('form').data('initial-state')){
                    return 'It looks like you have been editing something. If you leave before saving, your changes will be lost.'
                }
            });
        });
    })(jQuery)
</script>