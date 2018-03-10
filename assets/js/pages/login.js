$(document).keyup(function(event) {
    if (event.keyCode == '13') {
        submit_form('#loginForm', '#form_results');
    }
});
