/* JS Settings */
$(document).ready(function() {

  showLoader($('#saveGeneralSettingsLoader'));
  showLoader($('#saveOssecConfLoader'));

  $(window).on('load', function() {

    hideLoader($('#saveGeneralSettingsLoader'));
    hideLoader($('#saveOssecConfLoader'));

  }).trigger('load');

  $('.url-auto-switch').bootstrapSwitch({size: 'large', handleWidth: 30, labelWidth: 5});

  $('.url-auto-switch').on('switchChange.bootstrapSwitch', function(event, state) {

    if(state == true) {
      $('.url-settings').addClass('disabled');
    }else{
      $('.url-settings').removeClass('disabled');
    }

  });

  var src_logo = $('.img-logo').attr('src');

  var url_logo_split = src_logo.split('/');

  if (url_logo_split[url_logo_split.length-1] == 'logo.png') {
    $('.saveGeneral .btn-danger').addClass('remove-logo');
  }

  $('.remove-logo-button').click(function () {

    $('.img-logo').attr('src', '/assets/img/logo.png');
    $('.logo-removed').val('Removed Logo');
    $('.saveGeneral .btn-danger').addClass('remove-logo');

  });

  function readFile(input) {

    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (ev) {
        $('.img-logo').attr('src', ev.target.result);
        $('.saveGeneral .btn-danger').removeClass('remove-logo');
      }
      reader.readAsDataURL(input.files[0]);
    }

  }

  $('#upload-logo').on('change', function () { readFile(this); });

});

function settingPasswordVerification(form_selector, modal) {

  formCallback = function saveSetting() {
    $('*[data-dismiss="modal"]').trigger('click');
    $('.formToken').val(Cookies.get('token'));
    submit_form('#saveAsteriskSettingsAction', '#saveAsteriskSettingsOutput');
  }

  js_modal(
    modal,
    'app/passwordVerification'
  );

}
