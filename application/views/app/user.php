<div class="modal-dialog">
  <div class="modal-content">
    <div class="card-user">
      <div class="image">
        <img src="<?= base_url(); ?>assets/img/background.jpg" alt="">
      </div>
      <div class="card-content">
        <div class="author">
          <div class="upload-container">
            <img class="avatar border-white" src="<?= (isset($user) && isset($user->user_avatar) && !in_array($user->user_avatar, ['assets/img/faces/face-1.jpg', ''])) ? 'files/app/' . $user->user_avatar : base_url() . 'assets/img/faces/face-1.jpg'; ?>" alt="No Profile Image">
          </div>
          <div class="row">
            <div class="col-xs-6">
              <h4 for="">Full name</h4>
              <h5><?= (isset($user)) ? $user->user_full_name : ''; ?></h5>
            </div>
            <div class="col-xs-6">
              <h4 for="">Mail</h4>
              <h5><a href="mailto:<?= (isset($user)) ? $user->user_mail : ''; ?>"><?= (isset($user)) ? $user->user_mail : ''; ?></a></h5>
            </div>
          </div>
          <br /><br />
          <div class="row">
            <div class="col-xs-6">
              <h4 for="">User name</h4>
              <h5><?= (isset($user)) ? $user->user_name : ''; ?></h5>
            </div>
            <div class="col-xs-6">
              <h4 for="">Role</h4>
              <h5><?= (isset($user)) ? $user->user_role : ''; ?></h5>
            </div>
          </div>
          <br /><br />
          <center><button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button></center>
        </div>
      </div>
    </div>
    <br /><br />
  </div>
</div>
