<!DOCTYPE html>

<html lang="en">

<head>

  <?php $this->load->view('admin/partials/_head'); ?>

  <!-- DataTable -->

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/jquery.dataTables.min.css" />

  <style type="text/css">
    .title-text {

      font-size: 14px !important;

      font-weight: 700;

      font-family: Gill Sans, sans-serif;

    }
  </style>

</head>

<body>

  <div class="container-scroller">

    <?php $this->load->view('admin/partials/_sidebar'); ?>

    <div class="container-fluid page-body-wrapper">

      <?php $this->load->view('admin/partials/_settings-panel'); ?>

      <?php $this->load->view('admin/partials/_navbar'); ?>

      <div class="main-panel">

        <div class="content-wrapper">

          <div class="page-header">

            <h3 class="page-title"><i class="mdi mdi-dumbbell"></i> Add / Update Physio Program Name</h3>

            <nav aria-label="breadcrumb">

              <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="<?= site_url(); ?>admin/physio">Physio</a></li>

                <li class="breadcrumb-item active" aria-current="page"> Add / Update Physio Program</li>

              </ol>

            </nav>

          </div>

          <div class="row">

            <div class="col-12 grid-margin stretch-card">
              <form id="programFrm" name="programFrm" action="#" method="post">
                <input id="id" name="id" type="hidden" value="<?php echo (isset($resultLog->id) && $resultLog->id > 0) ? $resultLog->id : 0; ?>">

                <div class="card">

                  <div class="card-body">

                    <div class="row">

                      <div class="col-md-8">

                        <!-- <p class="title-text">Add Meal</p><hr> -->

                        <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                          <p class="title-text">Program Info</p>
                          <hr>

                          <div class="card-body">

                            <div class="form-group">

                              <label>Title </label> <i class="text-danger">*</i>

                              <input type="text" class="form-control" name="title" id="title" placeholder="Title" value="<?php echo (isset($resultLog->title) && $resultLog->title != "") ? $resultLog->title : ''; ?>" required>

                            </div>

                            <div class="form-group">

                              <label>Description</label>

                              <textarea type="text" class="form-control ckeditor" id="description" name="description" placeholder="Enter Description" row="5" autocomplete="off"><?php echo (isset($resultLog->description) && $resultLog->description != "") ? $resultLog->description : ''; ?></textarea>

                            </div>

                            <div class="form-group">

                              <label>Status</label> <i class="text-danger">*</i>

                              <select type="text" name="status" id="status" class="form-control" required>

                                <option value="Y" <?php echo (isset($resultLog->status) && $resultLog->status == "Y") ? 'selected' : ''; ?>>Active</option>

                                <option value="N" <?php echo (isset($resultLog->status) && $resultLog->status == "N") ? 'selected' : ''; ?>>Deactivate</option>

                              </select>

                            </div>

                          </div>

                        </div>

                      </div>

                      <!-- <div class="col-md-1"></div> -->

                      <div class="col-md-4">
                       <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                          <p class="title-text">Select No of Weeks</p>
                          <hr>

                          <div class="card-body">

                            <div class="form-group">
                            
                            <input type="number" class="form-control" name="no_of_week" id="no_of_week" placeholder="No of Weeks" value="<?php echo (isset($resultLog->no_of_week) && $resultLog->no_of_week != "") ? $resultLog->no_of_week : ''; ?>" required min="1">
                            </div>

                          </div>

                        </div> 

                        <br>
                        

                        <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                          <p class="title-text">Upload an Image</p>
                          <hr>

                          <div class="card-body">

                            <div class="custom-file">

                              <input type="file" class="custom-file-input" id="image" name="image" <?php echo (isset($resultLog->id) && $resultLog->id > 0) ? '' : 'required'; ?>>

                              <label class="custom-file-label" for="inputGroupFile01">Choose..</label>

                            </div>
                            <?php if (isset($resultLog->image) && $resultLog->image != '') { ?>
                              <img src="<?php echo site_url($resultLog->image) ?>" width="200">
                            <?php } ?>

                          </div>

                        </div>

                      </div>

                    </div>

                    <br>

                    <div class="text-right">
                      <span id="msg" style="margin-right: 20px;"></span>
                      <button type="submit" class="btn btn-primary btn-sm">Save changes</button>

                    </div>

                  </div>

                </div>
              </form>
            </div>



          </div>

        </div>

        <?php $this->load->view('admin/partials/_footer'); ?>

      </div>

      <!-- main-panel ends -->

    </div>

    <!-- page-body-wrapper ends -->

  </div>

  <!-- container-scroller -->

  <?php $this->load->view('admin/partials/_scripts'); ?>


  <script type="text/javascript">
    $(document).ready(function() {

      $('#example').DataTable({

        "scrollX": true

      });

    });





    /* Set Status */

    $(function() {

      $('button#setStatus').on('click', function() { // onclick button ID

        //$('button').on('click', function () {   // onclick button



        $.alert('Record status has successfully changed', {

          // Message title

          title: false,

          // Enable auto close

          autoClose: true,

          // Auto close delay time in ms (>1000)

          closeTime: 1000,

          // Display a countdown timer

          withTime: false,

          // danger, success, warning or info

          type: 'success',

          // position+offeset

          // top-left,top-right,bottom-left,bottom-right,center

          //position: ['center', [-0.42, 0]], // 

          position: [$(this).data('position')],

          // For close button

          //close: '',

          // <a href="https://www.jqueryscript.net/animation/">Animation</a> speed

          speed: 'normal',

          // Set to false to display multiple messages at a time

          isOnly: true,

          // Minimal space in PX from top

          minTop: 0,

          // onShow callback

          onShow: function() {

          },

          // onClose callback

          onClose: function() {

          }

        });

      });

    })
  </script>

  <script>
    $(document).ready(function() {

      $('#programFrm').submit(function() {
        /* $('#msg').removeClass('text-danger');
        $('#msg').html(''); */
        tinyMCE.triggerSave();
        var form = $("#programFrm");
        var formData = new FormData(form[0]);
        $('#msg').removeClass('text-danger');
        $('#msg').html('');
        $('#loader').show();
        $.ajax({
          url: "<?php echo base_url(); ?>/admincontrl/physio/addUpdateProgramData",
          data: formData, //$('#profileFrm').serialize(),
          type: 'post',
          cache: false,
          processData: false,
          contentType: false,
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert('Oops! Internal Server Error. Please try again!');
          },
          success: function(value) {
            var data = JSON.parse(value);
            if (data.status == 1) {
              $('#loginmsg').html(data.message);
              setTimeout(function() {
                window.location.href = '<?php echo base_url() ?>/admin/physio/program';
              }, 3000);
            } else if (data.status == 0) {
              $('#msg').addClass('text-danger');
              $('#msg').html(data.message);
              $('#loader').hide();
            }
          }
        });
        return false;
      })
    })
  </script>

  <script src="https://cdn.tiny.cloud/1/riphsr2yy3x79vz03gwbh9f0snaeo7cof5p66w1yvxp6i8na/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
    tinymce.init({
      selector: '#description',
      setup: function(editor) {
        editor.on('change', function() {
          editor.save();
        });
      }
    });
  </script>
</body>

</html>