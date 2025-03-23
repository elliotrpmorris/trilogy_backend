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

            <h3 class="page-title"><i class="mdi mdi-dumbbell"></i> Add / Update Physio Exercise</h3>

            <nav aria-label="breadcrumb">

              <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="<?= site_url(); ?>admin/physio">Physio</a></li>

                <li class="breadcrumb-item active" aria-current="page"> Add / Update Physio Exercise</li>

              </ol>

            </nav>

          </div>

          <div class="row">

            <div class="col-12 grid-margin stretch-card">
              <form id="physioFrm" name="physioFrm" action="#" method="post">
                <input id="id" name="id" type="hidden" value="<?php echo (isset($resultLog->id) && $resultLog->id > 0) ? $resultLog->id : 0; ?>">

                <div class="card">

                  <div class="card-body">

                    <div class="row">

                      <div class="col-md-8">

                        <!-- <p class="title-text">Add Meal</p><hr> -->

                        <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                          <p class="title-text">Exercise Info</p>
                          <hr>

                          <div class="card-body">

                            <div class="form-group">

                              <label>Title </label> <i class="text-danger">*</i>

                              <input type="text" class="form-control" name="title" id="title" placeholder="Title" value="<?php echo (isset($resultLog->title) && $resultLog->title != "") ? $resultLog->title : ''; ?>" required>

                            </div>

                            <div class="form-group">

                              <label>Tips</label>

                              <textarea type="text" class="form-control" id="tipes" name="tipes" placeholder="Enter Description" row="5" autocomplete="off"><?php echo (isset($resultLog->tipes) && $resultLog->tipes != "") ? $resultLog->tipes : ''; ?></textarea>

                            </div>

                            <div class="form-group">

                              <label>Description</label>

                              <textarea type="text" class="form-control ckeditor" id="description" name="description" placeholder="Enter Description" row="5" autocomplete="off"><?php echo (isset($resultLog->description) && $resultLog->description != "") ? $resultLog->description : ''; ?></textarea>

                            </div>

                            <div class="row">

                              <div class="col-sm-6">

                                <div class="form-group">

                                  <label>Repetition</label> <i class="text-danger">*</i>

                                  <input type="number" class="form-control" id="rep" name="rep" placeholder="Repetition" autocomplete="off" value="<?php echo (isset($resultLog->rep) && $resultLog->rep != "") ? $resultLog->rep : ''; ?>">

                                </div>

                              </div>

                              <div class="col-sm-6">

                                <div class="form-group">

                                  <label>Sets</label> <i class="text-danger">*</i>

                                  <input type="number" class="form-control" id="sets" name="sets" placeholder="No of sets" autocomplete="off" value="<?php echo (isset($resultLog->sets) && $resultLog->sets != "") ? $resultLog->sets : ''; ?>">

                                </div>

                              </div>

                            </div>
                            <div class="row">

                              <div class="col-sm-6">

                                <div class="form-group">

                                  <label>Duration</label> <i class="text-danger">*</i>

                                  <input type="number" class="form-control" id="worktime" name="worktime" placeholder="Durations" autocomplete="off" value="<?php echo (isset($resultLog->worktime) && $resultLog->worktime != "") ? $resultLog->worktime : ''; ?>" step="0.01">

                                </div>

                              </div>
                              <div class="col-sm-6">

                                <div class="form-group">

                                  <label>Status</label> <i class="text-danger">*</i>

                                  <select type="text" name="status" id="status" class="form-control" required>

                                    <option value="Y" <?php echo (isset($resultLog->status) && $resultLog->status == "Y") ? 'checked' : ''; ?>>Active</option>

                                    <option value="N" <?php echo (isset($resultLog->status) && $resultLog->status == "N") ? 'checked' : ''; ?>>Deactivate</option>

                                  </select>

                                </div>

                              </div>

                            </div>

                          </div>

                        </div>

                      </div>

                      <!-- <div class="col-md-1"></div> -->

                      <div class="col-md-4">
                        <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                          <p class="title-text">Select Program Name</p>
                          <hr>

                          <div class="card-body">

                            <div class="form-group">
                              <select type="text" name="program_id" id="program_id" class="form-control" required autocomplete="off">
                                <option value="">Select one</option>
                                <?php
                                if (isset($programLog) && !empty($programLog)) {
                                  foreach ($programLog as $key => $val) {
                                ?>
                                    <option value="<?php echo $val->id; ?>" <?php echo (isset($resultLog->program_id) && $resultLog->program_id == $val->id) ? 'selected' : ''; ?>><?php echo $val->title; ?></option>
                                <?php
                                  }
                                }
                                ?>
                              </select>

                            </div>

                          </div>

                        </div>
                        <br>
                        <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                          <p class="title-text">Select Week</p>
                          <hr>

                          <div class="card-body">

                            <div class="form-group">
                            <input type="number" class="form-control" name="week_no" id="week_no" placeholder="select week" value="<?php echo (isset($resultLog->week_no) && $resultLog->week_no != "") ? $resultLog->week_no : ''; ?>" required min="1">

                            </div>

                          </div>

                        </div>
                        <br>
                        <?php /* <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                          <p class="title-text">Select Day</p>
                          <hr>

                          <div class="card-body">

                            <div class="form-group">
                            <input type="number" class="form-control" name="day_no" id="day_no" placeholder="select day" value="<?php echo (isset($resultLog->day_no) && $resultLog->day_no != "") ? $resultLog->day_no : ''; ?>" required min="1" max="7">

                            </div>

                          </div>

                        </div> */ ?>
                        
                        <br>
                        <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                          <p class="title-text">Select Target Muscle</p>
                          <hr>

                          <div class="card-body">

                            <div class="form-group">
                              <?php
                              if (isset($resultLog->musclegroup) && $resultLog->musclegroup) {
                                $foodType = explode(', ', $resultLog->musclegroup);
                              }
                              ?>
                              <select type="text" name="musclegroup[]" id="musclegroup" class="form-control" required multiple>
                                <?php
                                if (isset($muscleLog) && !empty($muscleLog)) {
                                  foreach ($muscleLog as $key => $val) {
                                ?>
                                    <option value="<?php echo $val->muscle_type; ?>" <?php echo (isset($foodType) && !empty($foodType) && in_array($val->muscle_type, $foodType)) ? 'selected' : ''; ?>><?php echo $val->muscle_type; ?></option>
                                <?php
                                  }
                                }
                                ?>
                              </select>

                            </div>

                          </div>

                        </div>

                        <br>
                        <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                          <p class="title-text">Select Equipments</p>
                          <hr>

                          <div class="card-body">

                            <div class="form-group">
                              <?php
                              if (isset($resultLog->equipments) && $resultLog->equipments) {
                                $equipments = explode(', ', $resultLog->equipments);
                              }
                              ?>
                              <select type="text" name="equipments[]" id="equipments" class="form-control" required multiple autocomplete="off">
                                <?php
                                if (isset($equipmentLog) && !empty($equipmentLog)) {
                                  foreach ($equipmentLog as $key => $val) {
                                ?>
                                    <option value="<?php echo $val->equipment_name; ?>" <?php echo (isset($equipments) && !empty($equipments) && in_array($val->equipment_name, $equipments)) ? 'selected' : ''; ?>><?php echo $val->equipment_name; ?></option>
                                <?php
                                  }
                                }
                                ?>
                              </select>

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
                            <?php if (isset($resultLog->image_thumb) && $resultLog->image_thumb != '') { ?>
                              <img src="<?php echo site_url($resultLog->image_thumb) ?>">
                            <?php } ?>

                          </div>

                        </div>
                        <br>
                        <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                          <p class="title-text">Upload Video</p>
                          <hr>

                          <div class="card-body">

                            <div class="form-group">
                              <label>Video Type </label> <i class="text-danger">*</i>
                              <select name="video_type" id="video_type" class="form-control" autocomplete="off">
                                <option value="URL" <?php echo (isset($resultLog->video_type) && $resultLog->video_type == "URL") ? 'selected' : ''; ?>> URL</option>
                                <option value="file" <?php echo (isset($resultLog->video_type) && $resultLog->video_type == "file") ? 'selected' : ''; ?>> File</option>
                              </select>
                            </div>
                            <div class="form-group videoType" style="display: <?php echo (isset($resultLog->video_type) && $resultLog->video_type == "URL") ? 'block' : 'block'; ?>;">
                              <label>URL Type </label>
                              <select name="url_type" id="url_type" class="form-control" autocomplete="off" <?php echo (isset($resultLog->video_type) && $resultLog->video_type == "file") ? '' : 'required'; ?>>
                                <option value="">Select one</option>
                                <option value="vimeo" <?php echo (isset($resultLog->url_type) && $resultLog->url_type == "vimeo") ? 'selected' : ''; ?>> Vimeo</option>
                                <option value="server" <?php echo (isset($resultLog->url_type) && $resultLog->url_type == "server") ? 'selected' : ''; ?>> Server</option>
                              </select>
                            </div>
                            <div class="form-group videoType" style="display: <?php echo (isset($resultLog->video_type) && $resultLog->video_type == "URL") ? 'block' : 'block'; ?>;">
                              <label>Video Link </label>
                              <input type="text" name="video_path" id="video_path" class="form-control" value="<?php echo (isset($resultLog->video_path) && $resultLog->video_path != "") ? $resultLog->video_path : ''; ?>" placeholder="Video Link">
                            </div>
                            <div class="custom-file videoType" style="display: <?php echo (isset($resultLog->video_type) && $resultLog->video_type == "file") ? 'block' : 'none'; ?>;">

                              <input type="file" class="custom-file-input" id="inputGroupFile02" name="video_path_file">

                              <label class="custom-file-label" for="inputGroupFile02">Choose..</label>

                            </div>
                            <?php /* if (isset($resultLog->meal_image_thumb) && $resultLog->meal_image_thumb != '') { ?>
                              <img src="<?php echo site_url($resultLog->meal_image_thumb) ?>">
                            <?php } */ ?>

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
      $('#video_type').change(function() {
        $('.videoType').toggle();
        if($(this).val() == 'URL'){
          $('#url_type').attr('required','required');
        }else{
          $('#url_type').removeAttr('required','');
        }
      });

      $('#physioFrm').submit(function() {
        /* $('#msg').removeClass('text-danger');
        $('#msg').html(''); */
        tinyMCE.triggerSave();
        var form = $("#physioFrm");
        var formData = new FormData(form[0]);
        $('#msg').removeClass('text-danger');
        $('#msg').html('');
        $('#loader').show();
        $.ajax({
          url: "<?php echo base_url(); ?>/admincontrl/physio/addUpdatePhysioData",
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
                window.location.href = '<?php echo base_url() ?>/admin/physio';
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