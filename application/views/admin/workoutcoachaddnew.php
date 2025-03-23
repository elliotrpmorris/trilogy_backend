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

            <h3 class="page-title"><i class="mdi mdi-dumbbell"></i> Add / Update Coach</h3>

            <nav aria-label="breadcrumb">

              <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="<?= site_url(); ?>admin/workout">Workout</a></li>

                <li class="breadcrumb-item active" aria-current="page"> Add / Update Coach</li>

              </ol>

            </nav>

          </div>

          <div class="row">

            <div class="col-12 grid-margin stretch-card">
              <form id="routineFrm" name="routineFrm" action="#" method="post">
                <input id="id" name="id" type="hidden" value="<?php echo (isset($resultLog->id) && $resultLog->id > 0) ? $resultLog->id : 0; ?>">

                <div class="card">

                  <div class="card-body">

                    <div class="row">

                      <div class="col-md-8">

                        <!-- <p class="title-text">Add Meal</p><hr> -->

                        <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                          <p class="title-text">Routine Info</p>
                          <hr>

                          <div class="card-body">

                            <div class="form-group">

                              <label>Title </label> <i class="text-danger">*</i>

                              <input type="text" class="form-control" name="workout_coach_name" id="workout_coach_name" placeholder="Coach Name" value="<?php echo (isset($resultLog->workout_coach_name) && $resultLog->workout_coach_name != "") ? $resultLog->workout_coach_name : ''; ?>" required>

                            </div>

                            <div class="form-group">

                              <label>Description</label>

                              <textarea type="text" class="form-control" id="workout_coach_desc" name="workout_coach_desc" placeholder="Enter description" row="5" autocomplete="off"><?php echo (isset($resultLog->workout_coach_desc) && $resultLog->workout_coach_desc != "") ? $resultLog->workout_coach_desc : ''; ?></textarea>

                            </div>

                           <!--  <div class="row">

                              <div class="col-sm-6">

                                <div class="form-group">

                                  <label>Min. Time (Mins)</label> <i class="text-danger">*</i>

                                  <input type="number" class="form-control" id="min_time" name="min_time" placeholder="Min. Time" autocomplete="off" value="<?php echo (isset($resultLog->min_time) && $resultLog->min_time != "") ? $resultLog->min_time : ''; ?>">

                                </div>

                              </div>

                              <div class="col-sm-6">

                                <div class="form-group">

                                  <label>Max. Time (Mins)</label> <i class="text-danger">*</i>

                                  <input type="number" class="form-control" id="max_time" name="max_time" placeholder="Max. Time" autocomplete="off" value="<?php echo (isset($resultLog->max_time) && $resultLog->max_time != "") ? $resultLog->max_time : ''; ?>">

                                </div>

                              </div>

                            </div>
                             -->

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
                       <!--  <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                          <p class="title-text">Select Workout Level</p>
                          <hr>

                          <div class="card-body">

                            <div class="form-group">
                              <select type="text" name="workout_level_id" id="workout_level_id" class="form-control" required autocomplete="off">
                                <option value="">Select Workout Level</option>
                                <?php
                                if (isset($workGroupLog) && !empty($workGroupLog)) {
                                  foreach ($workGroupLog as $key => $val) {
                                ?>
                                    <option value="<?php echo $val->id; ?>" <?php echo (isset($resultLog->workout_level_id) && $resultLog->workout_level_id == $val->id) ? 'selected' : ''; ?>><?php echo $val->workout_level_name; ?></option>
                                <?php
                                  }
                                }
                                ?>
                              </select>

                            </div>

                          </div>

                        </div> 

                        <br>-->
                        

                        <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                          <p class="title-text">Upload an Image</p>
                          <hr>

                          <div class="card-body">

                            <div class="custom-file">

                              <input type="file" class="custom-file-input" id="coach_image" name="coach_image" <?php echo (isset($resultLog->id) && $resultLog->id > 0) ? '' : 'required'; ?>>

                              <label class="custom-file-label" for="inputGroupFile01">Choose..</label>

                            </div>
                            <?php if (isset($resultLog->coach_image) && $resultLog->coach_image != '') { ?>
                              <img src="<?php echo site_url($resultLog->coach_image) ?>" width="200">
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

      $('#routineFrm').submit(function() {
        /* $('#msg').removeClass('text-danger');
        $('#msg').html(''); */
        tinyMCE.triggerSave();
        var form = $("#routineFrm");
        var formData = new FormData(form[0]);
        $('#msg').removeClass('text-danger');
        $('#msg').html('');
        $('#loader').show();
        $.ajax({
          url: "<?php echo base_url(); ?>/admincontrl/workoutmaster/addUpdateworkoutcoach",
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
                window.location.href = '<?php echo base_url() ?>/admin/workout/workoutcoach';
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