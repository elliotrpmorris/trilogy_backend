<!DOCTYPE html>

<html lang="en">

<head>

  <?php $this->load->view('admin/partials/_head'); ?>

</head>

<body>

  <div class="container-scroller">

    <?php $this->load->view('admin/partials/_sidebar'); ?>

    <div class="container-fluid page-body-wrapper">

      <?php $this->load->view('admin/partials/_settings-panel'); ?>

      <?php $this->load->view('admin/partials/_navbar'); ?>

      <div class="main-panel">

        <div class="content-wrapper pb-0">

          <div class="page-header flex-wrap">

            <h3 class="mb-0"> Hi, welcome back! <span class="pl-0 h6 pl-sm-2 text-muted d-inline-block">Your are now in admin panel...</span>

            </h3>

          </div>



          <div class="row">

            <div class="col-xl-3 col-md-3">

              <div class="card bg-warning">

                <div class="card-body px-3 py-4">

                  <div class="d-flex justify-content-between align-items-start">

                    <div class="color-card">

                      <p class="mb-0 color-card-head">Total Users</p>

                      <h2 class="text-white"> <?php echo (isset($totalCust->num) && $totalCust->num > 0) ? $totalCust->num : 0; ?> </h2>

                    </div>

                    <i class="card-icon-indicator mdi mdi-account-multiple-outline bg-inverse-icon-warning"></i>

                  </div>

                </div>

              </div>

            </div>


            <div class="col-xl-3 col-md-3">

              <div class="card bg-danger">

                <div class="card-body px-3 py-4">

                  <div class="d-flex justify-content-between align-items-start">

                    <div class="color-card">

                      <p class="mb-0 color-card-head">Total Meal</p>

                      <h2 class="text-white"> <?php echo (isset($totalMeal->num) && $totalMeal->num > 0) ? $totalMeal->num : 0; ?> </h2>

                    </div>

                    <i class="card-icon-indicator mdi mdi-food bg-inverse-icon-warning"></i>

                  </div>

                </div>

              </div>

            </div>

            <!-- <div class="col-xl-3 col-md-3">

              <div class="card bg-danger">

                <div class="card-body px-3 py-4">

                  <div class="d-flex justify-content-between align-items-start">

                    <div class="color-card">

                      <p class="mb-0 color-card-head">Total Workouts</p>

                      <h2 class="text-white"> <?php echo (isset($totalWork->num) && $totalWork->num > 0) ? $totalWork->num : 0; ?> </h2>

                    </div>

                    <i class="card-icon-indicator mdi mdi-dumbbell bg-inverse-icon-danger"></i>

                  </div>

                </div>

              </div>

            </div> -->

            <div class="col-xl-3 col-md-3">

              <div class="card bg-primary">

                <div class="card-body px-3 py-4">

                  <div class="d-flex justify-content-between align-items-start">

                    <div class="color-card">

                      <p class="mb-0 color-card-head">Total Exercises</p>

                      <h2 class="text-white"> <?php echo (isset($totalExer->num) && $totalExer->num > 0) ? $totalExer->num : 0; ?> </h2>

                    </div>

                    <i class="card-icon-indicator mdi mdi-bike bg-inverse-icon-primary"></i>

                  </div>

                </div>

              </div>

            </div>

            <div class="col-xl-3 col-md-3">

              <div class="card bg-success">

                <div class="card-body px-3 py-4">

                  <div class="d-flex justify-content-between align-items-start">

                    <div class="color-card">

                      <p class="mb-0 color-card-head">Total Subscription Amount</p>

                      <h2 class="text-white">£ <?php echo (isset($totalPayment->payment_amt) && $totalPayment->payment_amt > 0) ? $totalPayment->payment_amt : 0; ?></h2>

                    </div>

                    <i class="card-icon-indicator mdi mdi-comment-multiple-outline bg-inverse-icon-success"></i>

                  </div>

                </div>

              </div>

            </div>

          </div>

          <br>

          <div class="row">

            <div class="col-xl-8 col-sm-12 grid-margin stretch-card">

              <div class="card">

                <div class="card-body px-0 overflow-auto">

                  <h4 class="card-title pl-4">Trasaction History</h4>

                  <div class="table-responsive">

                    <table class="table">

                      <thead class="bg-light">

                        <tr>

                          <th>Customer</th>

                          <th>Plan</th>

                          <th>Payment date</th>

                          <th>Amount</th>

                        </tr>

                      </thead>

                      <tbody>
                        <?php
                        /* if (isset($recentTran) && !empty($recentTran)) {
                          foreach ($recentTran as $key => $val) {
                        ?>
                            <tr>

                              <td>

                                <div class="d-flex align-items-center">

                                  <i class="mdi mdi-account-convert btn-rounded btn-icon"></i>

                                  <div class="table-user-name ml-3">

                                    <p class="mb-0 font-weight-medium"> <?php echo $val->name; ?> </p>

                                  </div>

                                </div>

                              </td>

                              <td><?php echo $val->package_title; ?></td>

                              <td>

                                <div> <?php echo $val->payment_date; ?> </div>

                              </td>

                              <td>£ <?php echo $val->payment_amt; ?></td>

                            </tr>
                        <?php

                          }
                        } */
                        ?>




                      </tbody>

                    </table>

                  </div>


                </div>

              </div>

            </div>

            <div class="col-xl-4 col-md-6 grid-margin stretch-card">

              <div class="card">

                <div class="card-body">

                  <h4 class="card-title text-black">Recent Customers</h4>
                  <?php
                  /* if (isset($recentCust) && !empty($recentCust)) {
                    foreach ($recentCust as $key => $val) {
                  ?>
                      <div class="row pt-2 pb-1">

                        <div class="col-12">

                          <div class="row">

                            <div class="col-4 col-md-4">

                              <i class="mdi mdi-account-convert btn-rounded btn-icon"></i>

                            </div>

                            <div class="col-8 col-md-8 p-sm-0">

                              <h6 class="mb-0"><?php echo $val->name ?></h6>

                              <p class="text-muted font-12"><?php echo $val->email_id ?></p>
                              <p class="text-muted font-12"><?php echo $val->reg_date ?></p>

                            </div>

                          </div>

                        </div>
                      </div>
                  <?php
                    }
                  } */
                  ?>

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

  <!-- Custom js for this page -->

  <script src="<?= site_url(); ?>assets/js/dashboard.js"></script>

  <!-- End custom js for this page -->

</body>

</html>