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

              <button class="btn btn-outline-default btn-rounded btn-icon" data-position="bottom-right"><i class="mdi mdi mdi-silverware-variant"></i></button> Health Problems

            </h4>

            <nav aria-label="breadcrumb">

              <a type="button" href="JavaScript:void(0)" data-id="0" class="btn btn-outline-primary btn-icon-text btn-xl addUpdateHealthProblem"><i class="mdi mdi mdi-silverware-variant btn-icon-prepend"></i> Add Health Problem</a>

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
                          <th>Updated On</th>
                          <th>Health Problem name</th>
                          <th>Status</th>
                          <th>Edit</th>
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
                              <td><?php echo $val->health_problem_name; ?></td>
                              <td>
                                <?php if (isset($val->status) && $val->status == 'Y') { ?>
                                  <span class="btn btn-outline-secondary btn-rounded btn-icon" data-position="bottom-right"><i class="mdi mdi-check text-success"></i></span>
                                <?php }elseif (isset($val->status) && $val->status == 'N') { ?>
                                  <span class="btn btn-outline-secondary btn-rounded btn-icon" data-position="bottom-right"><i class="mdi mdi-close text-danger"></i></span>
                                <?php } ?>
                              </td>
                              <td>
                                <a href="JavaScript:void(0)" data-id="<?php echo $edit_id; ?>" class="btn btn-xs btn-inverse-primary btn-icon addUpdateHealthProblem">
                                  <i class="mdi mdi-border-color"></i>
                                </a>
                              </td>
                              <td><a href="<?php echo site_url('admincontrl/healthproblem/deleteHealthProblem/' . $val->id); ?>" class="btn btn-sm btn-rounded btn-inverse-danger btn-icon" onclick="return confirm('Are you sure want to delete this record?');">
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
      $('.addUpdateHealthProblem').click(function() {
        $('#errorMsg').removeClass('text-danger');
        $('#errorMsg').html('');
        var id = $(this).data('id');
        if (id == 0) {
          $('#healthProblemFrm')[0].reset();
          $('#addHealthProblem').modal('show');
        } else {
          $.ajax({
            url: '<?php echo  base_url(); ?>/admincontrl/healthproblem/selectHealthProblemById',
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
                $('#healthProblemId').val(data.returnData.id);
                $('#health_problem_name').val(data.returnData.health_problem_name);
                $('#status').val(data.returnData.status);
                $('#addHealthProblem').modal('show');
              }
            }
          });
        }
      });

      $('#healthProblemFrm').submit(function() {
        $.ajax({
          url: '<?php echo base_url(); ?>/admincontrl/healthproblem/addUpdateHealthProblem',
          data: $('#healthProblemFrm').serialize(),
          type: 'post',
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert('Oops! Internal Server Error. Please try again!');
          },
          success: function(value) {
            var data = JSON.parse(value);
            if (data.status == '1') {
              /* $('#msg').addClass('text-success');
              $('#msg').html(data.message);
              $('#addHealthProblem').modal('hide'); */
              $('#healthProblemFrm')[0].reset();
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

<!-- add ADD UPDATE MEAL TYPE -->

<div class="modal" id="addHealthProblem" tabindex="-1">

  <div class="modal-dialog modal-dialog-centered" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h6 class="modal-title" id="exampleModalLongTitle">Add / Update Health Problem</h6>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <form action="#" method="post" id="healthProblemFrm" name="healthProblemFrm" enctype="multipart/form-data">
        <input type="hidden" name="id" id="healthProblemId" value="0">
        <div class="modal-body">
          <span id="errorMsg"></span>
          <div class="form-group">

            <label for="exampleInputUsername1">Health Problem Name</label> <i class="text-danger">*</i>

            <input type="text" class="form-control" placeholder="Enter Health Problem Name" id="health_problem_name" name="health_problem_name" autocomplete="off" required>

          </div>
          <div class="form-group">

            <label for="exampleInputEmail1">Status</label> <i class="text-danger">*</i>

            <select name="status" id="status" class="form-control">

              <option value="Y">Activate</option>

              <option value="N">Diactivate</option>

            </select>

          </div>

        </div>

        <div class="modal-footer">

          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>

          <button type="submit" class="btn btn-primary btn-sm">Save changes</button>

        </div>

      </form>

    </div>

  </div>

</div>