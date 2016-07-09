$(document).ready(function() {
    $("#area").select2({
        placeholder: '',
        allowClear: true,
        ajax: {
            url: "option.php",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term, // search term
                    type: "area"
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
    $("#office").select2({
        placeholder: '',
        allowClear: true,
        ajax: {
            url: "option.php",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term,
                    type: "office" // search term
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
    $("#member_name").select2({
        placeholder: '',
        allowClear: true,
        ajax: {
            url: "option.php",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term,
                    type: "member_name" // search term
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
    $("#topic_cat").select2({
        placeholder: '',
        allowClear: true,
        ajax: {
            url: "option.php",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term,
                    type: "topic_cat" // search term
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
    $("#adm_cat").select2({
        placeholder: '',
        allowClear: true,
        ajax: {
            url: "option.php",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term,
                    type: "adm_cat" // search term
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
    $("#gov").select2({
        placeholder: '',
        allowClear: true,
        ajax: {
            url: "option.php",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term,
                    type: "gov" // search term
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
});