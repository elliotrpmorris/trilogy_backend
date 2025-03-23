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

              <button class="btn btn-outline-default btn-rounded btn-icon" data-position="bottom-right"><i class="mdi mdi mdi-silverware-variant"></i></button> Manage Meals

            </h4>

            <nav aria-label="breadcrumb">

              <a type="button" href="<?php echo base_url(); ?>admin/meals/addNewItem" class="btn btn-outline-primary btn-icon-text btn-xl"><i class="mdi mdi mdi-silverware-variant btn-icon-prepend"></i> Add Meals</a>

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
                          <th>Meal Type</th>
                          <th>Image</th>
                          <th>Title</th>
                          <!-- <th>Min. Calorie</th>
                          <th>Max. Calorie</th> -->
                          <th>Calorie</th>
                          <th>Food Type</th>
                          <th>Dietary</th>
                          <th>Preparation Time</th>
                          <th>Cooking Time</th>
                          <th>Status</th>
                          <th>View</th>
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
                              <td><?php echo $val->meal_type_name; ?></td>
                              <td><img src="<?php echo site_url($val->meal_image) ?>" width="100"></td>
                              <td><?php echo $val->meal_title; ?></td>
                              <td><?php echo $val->min_calorie; ?></td>
                              <!-- <td><?php echo $val->max_calorie; ?></td> -->
                              <td><?php echo $val->food_type; ?></td>
                              <td><?php echo $val->diet_type; ?></td>
                              <td><?php echo $val->prep_time; ?> mins.</td>
                              <td><?php echo $val->cooking_time; ?> mins.</td>
                              <td>
                                <?php if (isset($val->status) && $val->status == 'Y') { ?>
                                  <span class="btn btn-outline-secondary btn-rounded btn-icon" data-position="bottom-right"><i class="mdi mdi-check text-success"></i></span>
                                <?php } elseif (isset($val->status) && $val->status == 'N') { ?>
                                  <span class="btn btn-outline-secondary btn-rounded btn-icon" data-position="bottom-right"><i class="mdi mdi-close text-danger"></i></span>
                                <?php } ?>
                              </td>
                              <td>
                                <a href="JavaScript:void(0)" data-id="<?php echo $edit_id; ?>" class="btn btn-xs btn-inverse-primary btn-icon viewMealsDetails">
                                  <i class="mdi mdi-information"></i>
                                </a>
                              </td>
                              <td>
                                <a href="<?php echo base_url(); ?>admin/meals/updateMealItem/<?php echo $edit_id; ?>" class="btn btn-xs btn-inverse-primary btn-icon">
                                  <i class="mdi mdi-border-color"></i>
                                </a>
                              </td>
                              
                              <td>
                                <a href="<?php echo base_url(); ?>admin/meals/duplicateMeal/<?php echo $edit_id; ?>" class="btn btn-xs btn-inverse-success btn-icon">
                                  <i class="mdi mdi-content-duplicate"></i>
                                </a>
                              </td>
								<td>
                                <a href="<?php echo base_url(); ?>admin/meals/deletemeal/<?php echo $edit_id; ?>" class="btn btn-xs btn-inverse-danger btn-icon">
                                  <i class="mdi mdi-trash-can-outline"></i>
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
      $('#example').on('click','.viewMealsDetails',function() {
        $('#loader').show();
        var id = $(this).data('id');
        $.ajax({
          url: '<?php echo  base_url(); ?>/admincontrl/mealmaster/fetchMealsDetails',
          data: {
            'id': id,
          },
          type: 'post',
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert('Oops! Internal Server Error. Please try again!');
            $('#loader').hide();
          },
          success: function(data) {
            $('#mealDetailsContent').html(data);
            $('#mealDetailsModal').modal('show');
            $('#loader').hide();
          }
        });
      });

    })
  </script>

</body>

</html>

<!-- add ADD UPDATE MEAL TYPE -->

<div class="modal" id="mealDetailsModal" tabindex="-1">

  <div class="modal-dialog modal-dialog-centered" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h6 class="modal-title" id="exampleModalLongTitle">Meal Details</h6>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>


      <div class="modal-body" id="mealDetailsContent">


      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>

  </div>

</div>