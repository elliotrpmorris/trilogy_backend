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

              <button class="btn btn-outline-default btn-rounded btn-icon" data-position="bottom-right"><i class="mdi mdi-account-multiple"></i></button> User Management <?php echo $title; ?>

            </h4>

            <nav aria-label="breadcrumb">

              <!-- <a type="button" href="<?= site_url(); ?>admin/exportuser" class="btn btn-outline-primary btn-icon-text btn-xl" target="_blank"><i class="fa fa-file-excel"></i> Export All User</a> -->

            </nav>

          </div>

          <div class="row">

            <div class="col-12 grid-margin stretch-card">

              <div class="card">

                <div class="card-body">

                  <div class="template-demo">

                    <table id="example" class="display nowrap" style="width:100%">

                      <thead>

                        <tr>
                          <th>Sl No.</th>
                          <th>Registration Date</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Status</th>
                        </tr>

                      </thead>

                      <tbody>
                        <?php
                        $i = 1;
                        if (isset($userLog) && !empty($userLog)) {
                          foreach ($userLog as $key => $val) {
                            $id = $val->id;
                        ?>
                            <tr>
                              <td><?php echo $i; ?>.</td>
                              <td><?php echo $val->reg_date; ?></td>
                              <td><?php echo $val->name; ?></td>
                              <td><?php echo $val->email_id; ?></td>
                              <td><?php echo ($val->status == 'Y') ? 'Active' : 'Inactive'; ?></td>
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

</body>

</html>





<!-- Add Modal -->

<div class="modal fade" id="addMealTag" tabindex="-1" role="dialog" aria-hidden="true">

  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h6 class="modal-title" id="exampleModalLongTitle"> <i class="mdi mdi-silverware-variant"></i> Create Meal</h6>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <form action="" method="post">

        <div class="modal-body">

          <div class="row">

            <div class="col-md-8">

              <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                <p class="title-text">Nutritional Info</p>
                <hr>

                <div class="card-body">

                  <div class="form-group">

                    <label>Meal Title </label> <i class="text-danger">*</i>

                    <input type="text" class="form-control" placeholder="Enter meal title" autocomplete="off" required oninvalid="this.setCustomValidity('Enter meal tag name')" oninput="this.setCustomValidity('')" onKeyPress="if(this.value.length==50) return false;" pattern="[A-Za-z]">

                  </div>

                  <div class="form-group">

                    <label>Description</label> <i class="text-danger">*</i>

                    <textarea type="text" class="form-control" placeholder="Enter meal description" row="5" autocomplete="off" required></textarea>

                  </div>

                  <div class="form-group">

                    <label>Tips</label>

                    <textarea type="text" class="form-control" placeholder="Enter meal tips" row="5" autocomplete="off" required></textarea>

                  </div>

                  <div class="row">

                    <div class="col-sm-6">

                      <div class="form-group">

                        <label>Prep Time (Mins)</label> <i class="text-danger">*</i>

                        <input type="number" class="form-control" placeholder="Display Unit" autocomplete="off">

                      </div>

                    </div>

                    <div class="col-sm-6">

                      <div class="form-group">

                        <label>Cooking Time (Mins)</label> <i class="text-danger">*</i>

                        <input type="number" class="form-control" placeholder="Display Unit" autocomplete="off">

                      </div>

                    </div>

                  </div>

                </div>

              </div>

              <br>

              <!-- Add Ingredient -->

              <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                <p class="title-text">Main Ingradient</p>
                <hr>

                <div class="card-body">

                  <div class="table-responsive">

                    <table class="table table-bordered">

                      <thead>

                        <tr>

                          <th>Order</th>

                          <th>Ingredient</th>

                          <th>Unit of Measure</th>

                          <th>Min</th>

                          <th>max</th>

                          <th>Action</th>

                        </tr>

                      </thead>

                      <tbody>

                        <tr class="table-info">

                          <td>1</td>

                          <td>adasd</td>

                          <td>gm</td>

                          <td></td>

                          <td></td>

                          <td>

                            <button type="button" class="badge badge-primary"><i class="mdi mdi-border-color"></i></button>

                            <button type="button" class="badge badge-danger"><i class="mdi mdi-delete-variant"></i></button>

                          </td>

                        </tr>

                      </tbody>

                    </table>

                    <br>

                    <div style="float: right;">

                      <button type="button" data-toggle="modal" data-target="#addMainIngredient" class="btn btn-info"><i class="mdi mdi-plus btn-icon-prepend"></i> Add Ingredient</button>

                    </div>

                  </div>

                </div>

              </div>

              <!-- Add Extra Ingredient -->

              <br>

              <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                <p class="title-text">Main Ingradient</p>
                <hr>

                <div class="card-body">

                  <div class="table-responsive">

                    <table class="table table-bordered">

                      <thead>

                        <tr>

                          <th>Order</th>

                          <th>Ingredient</th>

                          <th>Amount</th>

                          <th>Unit of Measure</th>

                          <th>Action</th>

                        </tr>

                      </thead>

                      <tbody>

                        <tr class="table-info">

                          <td>1</td>

                          <td>adasd</td>

                          <td>gm</td>

                          <td></td>

                          <td>

                            <button type="button" class="badge badge-primary"><i class="mdi mdi-border-color"></i></button>

                            <button type="button" class="badge badge-danger"><i class="mdi mdi-delete-variant"></i></button>

                          </td>

                        </tr>

                      </tbody>

                    </table>

                    <br>

                    <div style="float: right;">

                      <button type="button" data-toggle="modal" data-target="#addMainIngredient" class="btn btn-info"><i class="mdi mdi-plus btn-icon-prepend"></i> Add Ingredient</button>

                    </div>

                  </div>

                </div>

              </div>

            </div>

            <!-- <div class="col-md-1"></div> -->

            <div class="col-md-4">

              <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                <p class="title-text">Status</p>
                <hr>

                <div class="card-body">

                  <div class="form-group">

                    <select type="text" name="" class="form-control">

                      <option>Choose a status..</option>

                      <option>Save</option>

                      <option>Draft</option>

                      <option>Publish</option>

                    </select>

                  </div>

                  <button type="submit" class="btn btn-sm btn-success">Update</button>

                </div>

              </div>

              <br>

              <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                <p class="title-text">Add a plan</p>
                <hr>

                <div class="card-body">

                  <div class="form-group">

                    <select type="text" name="" class="form-control">

                      <option>Choose status..</option>

                      <option>Primary</option>

                      <option>Vagitarian</option>

                    </select>

                  </div>

                </div>

              </div>

              <br>

              <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                <p class="title-text">Dietary Requirment</p>
                <hr>

                <div class="card-body">

                  <div class="form-group">

                    <select type="text" name="" class="form-control">

                      <option>Choose Dietary Requirment..</option>

                      <option>Primary</option>

                      <option>Vagitarian</option>

                    </select>

                  </div>

                </div>

              </div>

              <br>

              <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                <p class="title-text">Meal Type</p>
                <hr>

                <div class="card-body">

                  <div class="form-group">

                    <select type="text" name="" class="form-control">

                      <option>Choose Meal Type..</option>

                      <option>Primary</option>

                      <option>Vagitarian</option>

                    </select>

                  </div>

                </div>

              </div>

              <br>

              <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                <p class="title-text">Meal Tag</p>
                <hr>

                <div class="card-body">

                  <div class="form-group">

                    <select type="text" name="" class="form-control">

                      <option>Choose Meal Tag..</option>

                      <option>Meal Tag 1</option>

                      <option>Meal Tag 2</option>

                    </select>

                  </div>

                  <div class="text-right" style="font-size: 10px; font-style: italic;">

                    <a href="">Add New Meal Tag </a>

                  </div>

                </div>

              </div>

              <br>

              <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                <p class="title-text">Allergens</p>
                <hr>

                <div class="card-body">

                  <div class="form-group">

                    <select type="text" name="" class="form-control">

                      <option>Choose Allergen..</option>

                      <option>Egg</option>

                      <option>Vagitarian</option>

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

                    <input type="file" class="custom-file-input">

                    <label class="custom-file-label" for="inputGroupFile01">Choose..</label>

                  </div>

                </div>

              </div>

            </div>

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





<!-- edit Modal -->

<div class="modal fade" id="editMealTag" tabindex="-1" role="dialog" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h6 class="modal-title" id="exampleModalLongTitle">Edit Meal Tag</h6>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <form action="" method="post">

        <div class="modal-body">

          <div class="form-group">

            <label for="exampleInputUsername1">Meal Tag Name</label> <i class="text-danger">*</i>

            <input type="text" class="form-control" placeholder="Enter meal tag name" autocomplete="off" required oninvalid="this.setCustomValidity('Enter meal tag name')" oninput="this.setCustomValidity('')" onKeyPress="if(this.value.length==50) return false;" pattern="[A-Za-z]">

          </div>

          <div class="form-group">

            <label for="exampleInputEmail1">Status</label> <i class="text-danger">*</i>

            <select name="status" class="form-control">

              <option>Choose...</option>

              <option value="1">Activate</option>

              <option value="0">Diactivate</option>

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





<!-- add Main Ingredient Modal -->

<div class="modal fade" id="addMainIngredient" tabindex="-1" role="dialog" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h6 class="modal-title" id="exampleModalLongTitle">Add Main Ingredient</h6>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <form action="" method="post">

        <div class="modal-body">

          <div class="form-group">

            <label for="exampleInputUsername1">Meal Tag Name</label> <i class="text-danger">*</i>

            <input type="text" class="form-control" placeholder="Enter meal tag name" autocomplete="off" required>

          </div>

          <div class="form-group">

            <label for="exampleInputEmail1">Status</label> <i class="text-danger">*</i>

            <select name="status" class="form-control">

              <option>Choose...</option>

              <option value="1">Activate</option>

              <option value="0">Diactivate</option>

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

<div class="modal" tabindex="-1" role="dialog" id="userModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">User Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="userModalContent">

      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="passwordModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Change Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form name="changePassFrm" id="changePassFrm" action="#" method="post">
        <div class="modal-body">
          <div class="row">

            <input type="hidden" name="passid" id="passid" value="0">
            <div class="col-sm-12 from-group">
              <label>New Password</label>
              <input class="form-control" name="newpass" id="newpass" type="password" autocomplete="off" required>
            </div>
            <div class="col-sm-12 from-group">
              <label>Confirm Password</label>
              <input class="form-control" name="conpass" id="conpass" type="password" autocomplete="off" required>
            </div>
            <p id="passMsg"></p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Confirm</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('.viewUser').click(function() {
      var id = $(this).data('id');
      var packageid = $(this).data('packageid');
      $.ajax({
        url: "<?php echo base_url(); ?>/admincontrl/users/viewuserdetails",
        data: {
          'id': id,
          'packageid': packageid
        },
        type: 'post',
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          alert('Oops! Internal Server Error. Please try again!');
        },
        success: function(data) {
          $('#userModal').modal();
          $('#userModalContent').html(data);
        }
      });
    })

    $('#example').on('click', '.resetPass', function() {
      var id = $(this).data('id');
      $('#changePassFrm')[0].reset();
      $('#passid').val(id);
      $('#passwordModal').modal();
    });

    $('#passwordModal').on('submit', '#changePassFrm', function() {
      $('#passMsg').removeClass('text-danger');
      $('#passMsg').removeClass('text-success');
      $('#passMsg').html('');
      if ($('#newpass').val() != $('#conpass').val()) {
        $('#passMsg').addClass('text-danger');
        $('#passMsg').html('Confirm password does not matched.');
        return false;
      } else {
        $.ajax({
          url: "<?php echo base_url(); ?>/admincontrl/users/changepassword",
          data: $('#changePassFrm').serialize(),
          type: 'post',
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert('Oops! Internal Server Error. Please try again!');
          },
          success: function(data) {
            $('#passMsg').addClass('text-success');
            $('#passMsg').html('Password has been successfully changed.');
          }
        });

        return false;
      }
    });
  })
</script>
<style>
  .modal-dialog {
    max-width: 80% !important;
  }
</style>