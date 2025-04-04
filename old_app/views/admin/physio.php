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

            <h4>

              <button class="btn btn-outline-default btn-rounded btn-icon" data-position="bottom-right"><i class="mdi mdi mdi-dumbbell"></i></button> Physio Exercise

            </h4>

            <nav aria-label="breadcrumb">

              <a type="button" href="<?php echo site_url('admin/physio/addnewexcercise'); ?>" data-id="0" class="btn btn-outline-primary btn-icon-text btn-xl"><i class="mdi mdi-dumbbell btn-icon-prepend"></i> Add New Physio Exercise</a>

            </nav>

          </div>

          <div class="row">

            <div class="col-12 grid-margin stretch-card">

              <div class="card">

                <div class="card-body">

                  <div class="template-demo">
                    <span id="msg" class="text-success"><?php echo (isset($msg) && $msg != "") ? $msg : ''; ?></span>
                    <table id="example" class="display nowrap" style="width:100%">

                      <thead>

                        <tr>
                          <th>Sl No.</th>
                          <th>Update On</th>
                          <th>Image</th>
                          <th>Program Name</th>
                          <th>Week</th>
                          <!-- <th>Day</th> -->
                          <th>Title</th>
                          <th>Repetition</th>
                          <th>Sets</th>
                          <th>Duration</th>
                          <th>Equipments</th>
                          <th>Muscle Group</th>
                          <th>Status</th>
                          <th>Edit</th>
                          <th>Duplicate</th>
                          <th>Delete</th>
                        </tr>

                      </thead>

                      <tbody>
                        <?php
                        $i = 1;
                        if (isset($resultLog) && !empty($resultLog)) {
                          foreach ($resultLog as $key => $val) {
                            $edit_id = $val->id;
                        ?>
                            <tr>
                              <td><?php echo $i; ?>.</td>
                              <td><?php echo $val->update_on; ?></td>
                              <td><img src="<?php echo site_url().$val->image; ?>" width="100"></td>
                              <td><?php echo $val->program_name; ?></td>
                              <td><?php echo $val->week_no; ?></td>
                              <?php /* <td><?php echo $val->day_no; ?></td> */ ?>
                              <td><?php echo $val->title; ?></td>
                              <td><?php echo $val->rep; ?> time</td>
                              <td><?php echo $val->sets; ?> sets</td>
                              <td><?php echo $val->worktime; ?> min</td>
                              <td><?php echo $val->equipments; ?></td>
                              <td><?php echo $val->musclegroup; ?></td>
                              <td>
                                <?php if (isset($val->status) && $val->status == 'Y') { ?>
                                  <span class="btn btn-outline-secondary btn-rounded btn-icon" data-position="bottom-right"><i class="mdi mdi-check text-success"></i></span>
                                <?php }elseif (isset($val->status) && $val->status == 'N') { ?>
                                  <span class="btn btn-outline-secondary btn-rounded btn-icon" data-position="bottom-right"><i class="mdi mdi-close text-danger"></i></span>
                                <?php } ?>
                              </td>
                              <td>
                              <a href="<?php echo site_url('admin/physio/updateexcercise/' . $val->id); ?>" class="btn btn-xs btn-inverse-primary btn-icon">
                                  <i class="mdi mdi-border-color"></i>
                                </a>
                              </td>
                              <td>
                                <a href="<?php echo base_url(); ?>admin/physio/duplicateexcercise/<?php echo $edit_id; ?>" class="btn btn-xs btn-inverse-success btn-icon">
                                  <i class="mdi mdi-content-duplicate"></i>
                                </a>
                              </td>
                              <td><a href="<?php echo site_url('admincontrl/physio/deleteexcercise/' . $val->id); ?>" class="btn btn-sm btn-rounded btn-inverse-danger btn-icon" onclick="return confirm('Are you sure want to delete this record?');">
                                  <i class="mdi mdi-bitbucket"></i>
                                </a>
                              </td>
                            </tr>
                        <?php
                            $i++;
                          }
                        }
                        ?>

                      </tbody>

                    </table>

                  </div>

                </div>





              </div>

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
      $('.addUpdateWorkoutTypes').click(function() {
        $('#errorMsg').removeClass('text-danger');
        $('#errorMsg').html('');
        var id = $(this).data('id');
        if (id == 0) {
          $('#physioTypeFrm')[0].reset();
          $('#addWorkoutTypes').modal('show');
          
        } else {
          $.ajax({
            url: '<?php echo  base_url(); ?>/admincontrl/physiomaster/selectWorkoutTypesById',
            data: {
              'id': id,
            },
            type: 'post',
            error: function(XMLHttpRequest, textStatus, errorThrown) {
              alert('Oops! Internal Server Error. Please try again!');
            },
            success: function(value) {
              var data = JSON.parse(value);
              if (data.status == 1) {
                $('#typeid').val(data.returnData.id);
                $('#physio_type').val(data.returnData.physio_type);
                $('#status').val(data.returnData.status);
                $('#addWorkoutTypes').modal('show');
                
              }
            }
          });
        }
      });

      $('#physioTypeFrm').submit(function() {
        $.ajax({
          url: '<?php echo base_url(); ?>/admincontrl/physiomaster/addUpdateWorkoutTypes',
          data: $('#physioTypeFrm').serialize(),
          type: 'post',
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert('Oops! Internal Server Error. Please try again!');
          },
          success: function(value) {
            var data = JSON.parse(value);
            if (data.status == '1') {
              /* $('#msg').addClass('text-success');
              $('#msg').html(data.message);
              $('#addWorkoutTypes').modal('hide'); */
              $('#physioTypeFrm')[0].reset();
              window.location.reload();
            } else {
              $('#errorMsg').addClass('text-danger');
              $('#errorMsg').html(data.message);
            }
          }
        });

        return false;
      });
    })
  </script>

</body>

</html>
