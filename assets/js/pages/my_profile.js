function settingPasswordVerification(form_selector, modal) {
  formCallback = function saveSetting() {

    $('*[data-dismiss="modal"]').trigger('click');
    $('.formToken').val(Cookies.get('token'));
    submit_form('#updateProfile', '#updateMyProfile');

  }

  js_modal(
    modal,
    'app/passwordVerification'
  );

}

$(document).ready(function() {

  var src_avatar = $('.avatar').attr('src');
  var url_avatar_split = src_avatar.split('/');

  if (url_avatar_split[url_avatar_split.length-1] == 'face-1.jpg') {
    $('.changed-avatar').hide();
  } else if (src_avatar) {
    $('.default-avatar').hide();
  }

  $('.remove-avatar').click(function () {
    $('.avatar').attr('src', 'assets/img/faces/face-1.jpg');
    $('.changed-avatar').css('display','none');
    $('.default-avatar').css('display','block');
    $('*[name="user_avatar_default"]').val('1');
  });

  function readFile(input) {

    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (ev) {
        $('.avatar').attr('src', ev.target.result);
        $('.default-avatar').css('display','none');
        $('.changed-avatar').css('display','block');
        $('*[name="user_avatar_default"]').val('0');
      }
      reader.readAsDataURL(input.files[0]);
    }

  }

  $('#upload-default-logo').click(function () { this.value = ''; });
  $('#upload-default-logo').on('change', function () { readFile(this); });
  $('#upload-default-change-logo').on('change', function () { readFile(this); });

});
