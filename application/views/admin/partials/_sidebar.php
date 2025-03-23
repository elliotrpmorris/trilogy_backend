<div id="loader" style="background: rgba(0, 0, 0, 0.4); display: none; position: fixed; width: 100%; height: 100%; overflow: hidden; z-index: 99999; left: 0; top: 0;">
  <div style="position: absolute; width: 100%; height: 100%">
    <div style="background: none repeat scroll 0 0 #ffffff; border: 2px solid #666666; border-radius: 5px; left: 50%; position: absolute; top: 50%; width: 280px; text-align: center; transform: translateX(-50%); transform: -webkit-translateX(-50%); -moz-transform: translateX(-50%); padding-top: 10px;"><img src="<?php echo base_url(); ?>/uploads/ajax-loader.gif">
      <div style="color: #000000; font-size: 12px; font-weight: bold; padding: 5px; text-align: center;" id="loginmsg"></div>
    </div>
  </div>
</div>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <div class="text-center sidebar-brand-wrapper d-flex align-items-center">
    <a class="sidebar-brand brand-logo" href="<?= site_url(); ?>admin/dashboard"><img src="<?= site_url(); ?>assets/images/logo.png" alt="logo" style="width: 50px; height: auto;" /></a>
    <a class="sidebar-brand brand-logo-mini pl-4 pt-3" href="<?= site_url(); ?>admin/dashboard"><img src="<?= site_url(); ?>assets/images/logo.png" alt="logo" style="width: 30px; height: auto;" /></a>
  </div>
  <ul class="nav">
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
          <img src="<?= site_url(); ?>assets/images/admin.png" alt="profile" />
          <span class="login-status online"></span>
          <!--change to offline or busy as needed-->
        </div>
        <div class="nav-profile-text d-flex flex-column pr-3">
          <span class="font-weight-medium mb-2">Trilogy Admin</span>
          <!-- <span class="font-weight-normal">$8,753.00</span> -->
        </div>
        <!-- <span class="badge badge-danger text-white ml-3 rounded">3</span> -->
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="<?= site_url(); ?>admin/dashboard">
        <i class="mdi mdi-gauge menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
        <i class="mdi mdi-apps menu-icon"></i>
        <span class="menu-title">Master Data</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-basic">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/allergies">Allergies</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/healthproblem">Health Problems</a>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/packages">Packages Management</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/location">Location Management</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/weekdrop">6 weeks % drop</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/coupon">Coupon Code</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/batch">6 Week Batch</a>
          </li> -->
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#ui-meals" aria-expanded="false" aria-controls="ui-meals">
        <i class="mdi mdi-silverware-variant menu-icon"></i>
        <span class="menu-title">Meals</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-meals">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/mealtype">Meal Types Master</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/foodtype">Food Type</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/diettype">Diet Type</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/nutrition">Nutrition Master</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/meals">Meals</a>
          </li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#ui-workout" aria-expanded="false" aria-controls="ui-workout">
        <i class="mdi mdi-dumbbell menu-icon"></i>
        <span class="menu-title">Workouts / Physio</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-workout">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/workout/workoutlevel">Workout Level</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/workout/musclegroup">Muscle Groups</a>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/workout/workouttypes">Workout Type</a>
          </li> -->
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/workout/equipments">Workout Equipments</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/workout/routine">Workout Program Name</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/workout">Exercise</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/physio/program">Physio Program Name</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/physio">Physio Exercise</a>
          </li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#ui-user" aria-expanded="false" aria-controls="ui-user">
        <i class="mdi mdi-account-convert menu-icon"></i>
        <span class="menu-title">User Data</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-user">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="<?php echo site_url(); ?>admin/users">All Users</a>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" href="#">Payment History</a>
          </li> -->
        </ul>
      </div>
    </li>
    <!-- <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#ui-trainer" aria-expanded="false" aria-controls="ui-trainer">
        <i class="mdi mdi-dumbbell menu-icon"></i>
        <span class="menu-title">Trainer</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-trainer">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/trainers">Trainers</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/trainers/routine">Routine Group</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/trainers/workout">Exercise</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/sixweek/trainers">Six Weeks Trainers</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url(); ?>admin/sixweek/trainers/workout">Six Weeks Trainers Exercise</a>
          </li>
        </ul>
      </div>
    </li> -->
    <li>
      <hr>
    </li>

    <!-- <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#msi-basic" aria-expanded="false" aria-controls="msi-basic">
              <i class="mdi mdi-crosshairs-gps menu-icon"></i>
              <span class="menu-title">MIS</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="msi-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="<?= site_url(); ?>">Reports</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?= site_url(); ?>">Ingreadiant Report</a>
                </li>
              </ul>
            </div>
          </li> -->
  </ul>
</nav>