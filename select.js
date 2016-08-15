$(document).ready(function() {
    var list=['area','office','member_name','topic_cat','adm_cat','gov']
    for (var key in list) {
        set_select2(list[key]);
    }

    function set_select2(kind) {
        $('#'+kind).select2({
            placeholder: '',
            allowClear: true,
            ajax: {
                url: "option.php",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term, // search term
                        type: kind
                    };
                },
                processResults: function(data) {
                    // parse the results into the format expected by Select2.
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data
                    return {
                        results: data.data
                    };
                },
                cache: true
            }
        });
    }
});