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

            <h3 class="page-title"><i class="mdi mdi-silverware-variant"></i> Add / Update Meal</h3>

            <nav aria-label="breadcrumb">

              <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="<?= site_url(); ?>admin/meals">Meals</a></li>

                <li class="breadcrumb-item active" aria-current="page"> Add / Update Meal</li>

              </ol>

            </nav>

          </div>

          <div class="row">
            <form id="ingredientFrm" name="ingredientFrm" action="#" method=" post">
              <input id="id" name="id" type="hidden" value="<?php echo (isset($resultLog->id) && $resultLog->id > 0) ? $resultLog->id : 0; ?>">
              <div class="col-12 grid-margin stretch-card">

                <div class="card">

                  <div class="card-body">

                    <div class="row">

                      <div class="col-md-8">

                        <!-- <p class="title-text">Add Meal</p><hr> -->

                        <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                          <p class="title-text">Meal Info</p>
                          <hr>

                          <div class="card-body">

                            <div class="form-group">

                              <label>Meal Title </label> <i class="text-danger">*</i>

                              <input type="text" class="form-control" name="meal_title" id="meal_title" placeholder="Meal title" value="<?php echo (isset($resultLog->meal_title) && $resultLog->meal_title != "") ? $resultLog->meal_title : ''; ?>" required>

                            </div>

                            <div class="form-group">

                              <label>Description</label> <i class="text-danger">*</i>

                              <textarea type="text" class="form-control" id="meal_desc" name="meal_desc" placeholder="Enter meal description" row="5" autocomplete="off" required><?php echo (isset($resultLog->meal_desc) && $resultLog->meal_desc != "") ? $resultLog->meal_desc : ''; ?></textarea>

                            </div>

                            <div class="form-group">

                              <label>Tips</label>

                              <textarea type="text" class="form-control" id="meal_tips" name="meal_tips" placeholder="Enter meal tips" row="5" autocomplete="off" required><?php echo (isset($resultLog->meal_tips) && $resultLog->meal_tips != "") ? $resultLog->meal_tips : ''; ?></textarea>

                            </div>

                            <div class="form-group">

                              <label>Cooking Method</label>

                              <textarea type="text" class="form-control ckeditor" id="cooking_method" name="cooking_method" placeholder="Enter cooking Method" row="5" autocomplete="off"><?php echo (isset($resultLog->cooking_method) && $resultLog->cooking_method != "") ? $resultLog->cooking_method : ''; ?></textarea>

                            </div>

                            <div class="row">

                              <div class="col-sm-6">

                                <div class="form-group">

                                  <label>Prep Time (Mins)</label> <i class="text-danger">*</i>

                                  <input type="number" class="form-control" id="prep_time" name="prep_time" placeholder="Prep Time" autocomplete="off" value="<?php echo (isset($resultLog->prep_time) && $resultLog->prep_time != "") ? $resultLog->prep_time : ''; ?>">

                                </div>

                              </div>

                              <div class="col-sm-6">

                                <div class="form-group">

                                  <label>Cooking Time (Mins)</label> <i class="text-danger">*</i>

                                  <input type="number" class="form-control" id="cooking_time" name="cooking_time" placeholder="Cooking Time" autocomplete="off" value="<?php echo (isset($resultLog->cooking_time) && $resultLog->cooking_time != "") ? $resultLog->cooking_time : ''; ?>">

                                </div>

                              </div>

                            </div>
                            <div class="row">

                              <div class="col-sm-3">

                                <div class="form-group">

                                  <label>Calorie</label> <i class="text-danger">*</i>

                                  <input type="number" class="form-control" id="min_calorie" name="min_calorie" placeholder="Total Min Calorie" autocomplete="off" value="<?php echo (isset($resultLog->min_calorie) && $resultLog->min_calorie != "") ? $resultLog->min_calorie : ''; ?>">

                                </div>

                              </div>
<!--                               <div class="col-sm-3">

                                <div class="form-group">

                                  <label>Max Calorie</label> <i class="text-danger">*</i>

                                  <input type="number" class="form-control" id="max_calorie" name="max_calorie" placeholder="Total Max Calorie" autocomplete="off" value="<?php echo (isset($resultLog->max_calorie) && $resultLog->max_calorie != "") ? $resultLog->max_calorie : ''; ?>" readonly>

                                </div>

                              </div> -->

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

                        <br>

                        <!-- Add Ingredient -->

                        <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                          <p class="title-text">Main Ingradient</p>
                          <hr>

                          <div class="card-body" style="padding: 5px; margin: 0px;">

                            <div class="table-responsive">

                              <table class="table table-bordered">

                                <thead>

                                  <tr class="table-info">

                                    <th>Order</th>

                                    <th>Ingredient</th>

                                    <th>Unit of<br>Measurement</th>

                                    <th>Calorie per<br>Measurement</th>

                                    <th>Amount</th>

                                    <!-- <th>max Amount</th>

                                    <th>% of meal</th> -->

                                    <!-- <th>Min Calorie</th>

                                    <th>max Calorie</th> -->

                                    <th>Action</th>

                                  </tr>

                                </thead>

                                <tbody id="ingredientContent">
                                  <?php
                                  $count = 0;
                                  if (isset($ingredientLog) && !empty($ingredientLog)) {
                                    foreach ($ingredientLog as $key => $val) {
                                  ?>
                                      <tr id="ingredientTr<?php echo $count; ?>">

                                        <td><?php echo ($count + 1); ?>
                                          <input type="hidden" step="0.01" class="form-control" id="ing_min_calorie<?php echo $count; ?>" name="ing_min_calorie[]" placeholder="" autocomplete="off" value="<?php echo $val->ing_min_calorie; ?>" readonly>
                                          <input type="hidden" step="0.01" class="form-control" id="ing_max_calorie<?php echo $count; ?>" name="ing_max_calorie[]" placeholder="" autocomplete="off" value="<?php echo $val->ing_max_calorie; ?>" readonly>
                                        </td>

                                        <td><input type="text" class="form-control" id="ingredient_name<?php echo $count; ?>" name="ingredient_name[]" placeholder="" autocomplete="off" required value="<?php echo $val->ingredient_name; ?>"></td>

                                        <td><input type="text" class="form-control" data-count="<?php echo $count; ?>" id="unit_of_measure<?php echo $count; ?>" name="unit_of_measure[]" placeholder="" autocomplete="off" required value="<?php echo $val->unit_of_measure; ?>"></td>

                                        <td><input type="number" step="0.01" class="form-control calClorie" data-count="<?php echo $count; ?>" id="calorie_per_gm<?php echo $count; ?>" name="calorie_per_gm[]" placeholder="" autocomplete="off" required value="<?php echo $val->calorie_per_gm; ?>"></td>

                                        <td><input type="number" step="0.01" class="form-control calClorie" data-count="<?php echo $count; ?>" id="min_amt<?php echo $count; ?>" name="min_amt[]" placeholder="" autocomplete="off" required value="<?php echo $val->min_amt; ?>"></td>

                                        <!-- <td><input type="number" step="0.01" class="form-control calClorie" data-count="<?php echo $count; ?>" id="max_amt<?php echo $count; ?>" name="max_amt[]" placeholder="" autocomplete="off" required value="<?php echo $val->max_amt; ?>"></td>

                                        <td><input type="number" step="0.01" class="form-control" data-count="<?php echo $count; ?>" id="per_of_meal<?php echo $count; ?>" name="per_of_meal[]" placeholder="" autocomplete="off" required value="<?php echo $val->per_of_meal; ?>"></td> -->

                                        <td>
                                          <button type="button" class="badge badge-danger deleteIngri" data-count="<?php echo $count; ?>"><i class="mdi mdi-delete-variant"></i></button>
                                        </td>

                                      </tr>
                                    <?php
                                      $count++;
                                    }
                                  } else {
                                    ?>

                                    <tr id="ingredientTr<?php echo $count; ?>">

                                      <td><?php echo ($count + 1); ?>
                                        <input type="hidden" step="0.01" class="form-control" id="ing_min_calorie<?php echo $count; ?>" name="ing_min_calorie[]" placeholder="" autocomplete="off" required readonly>
                                        <input type="hidden" step="0.01" class="form-control" id="ing_max_calorie<?php echo $count; ?>" name="ing_max_calorie[]" placeholder="" autocomplete="off" required readonly>
                                      </td>

                                      <td><input type="text" class="form-control" id="ingredient_name<?php echo $count; ?>" name="ingredient_name[]" placeholder="" autocomplete="off" required></td>

                                      <td><input type="text" class="form-control" id="unit_of_measure<?php echo $count; ?>" name="unit_of_measure[]" placeholder="" autocomplete="off" required></td>

                                      <td><input type="number" step="0.01" class="form-control calClorie" data-count="<?php echo $count; ?>" id="calorie_per_gm<?php echo $count; ?>" name="calorie_per_gm[]" placeholder="" autocomplete="off" required></td>

                                      <td><input type="number" step="0.01" class="form-control calClorie" data-count="<?php echo $count; ?>" id="min_amt<?php echo $count; ?>" name="min_amt[]" placeholder="" autocomplete="off" required></td>

                                      <!-- <td><input type="number" step="0.01" class="form-control calClorie" data-count="<?php echo $count; ?>" id="max_amt<?php echo $count; ?>" name="max_amt[]" placeholder="" autocomplete="off" required></td>

                                      <td><input type="number" step="0.01" class="form-control" data-count="<?php echo $count; ?>" id="per_of_meal<?php echo $count; ?>" name="per_of_meal[]" placeholder="" autocomplete="off" required></td> -->

                                      <td>
                                        <button type="button" class="badge badge-danger deleteIngri" data-count="<?php echo $count; ?>"><i class="mdi mdi-delete-variant"></i></button>
                                      </td>

                                    </tr>
                                  <?php
                                  }
                                  ?>

                                </tbody>

                              </table>

                              <br>

                              <div style="float: right;">
                                <span id="msgIng" style="margin-right: 20px;"></span>
                                <button type="button" class="btn btn-info addMore"><i class="mdi mdi-plus btn-icon-prepend"></i> Add More Ingredient</button>

                              </div>

                            </div>

                          </div>

                        </div>
                        <br>
                        <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                          <p class="title-text">Extra Ingradient</p>
                          <hr>

                          <div class="card-body" style="padding: 5px; margin: 0px;">

                            <div class="table-responsive">

                              <table class="table table-bordered">

                                <thead>

                                  <tr class="table-info">

                                    <th>Order</th>

                                    <th>Ingredient Name</th>

                                    <th>Unit of<br>Measurement</th>

                                    <th>Amount</th>

                                    <th>Action</th>

                                  </tr>

                                </thead>

                                <tbody id="extraingredientContent">
                                  <?php
                                  $count = 0;
                                  if (isset($mealExtraLog) && !empty($mealExtraLog)) {
                                    foreach ($mealExtraLog as $key => $val) {
                                  ?>
                                      <tr id="extraingredientTr<?php echo $count; ?>">

                                        <td><?php echo ($count + 1); ?>
                                        </td>

                                        <td><input type="text" class="form-control" id="extra_ingredient_name<?php echo $count; ?>" name="extra_ingredient_name[]" placeholder="" autocomplete="off" value="<?php echo $val->extra_ingredient_name; ?>"></td>

                                        <td><input type="text" class="form-control" data-count="<?php echo $count; ?>" id="extra_measure<?php echo $count; ?>" name="extra_measure[]" placeholder="" autocomplete="off" value="<?php echo $val->extra_measure; ?>"></td>

                                        <td><input type="number" step="0.01" class="form-control" data-count="<?php echo $count; ?>" id="extra_amt<?php echo $count; ?>" name="extra_amt[]" placeholder="" autocomplete="off" value="<?php echo $val->extra_amt; ?>"></td>
                                        <td>
                                          <button type="button" class="badge badge-danger deleteExtraIngri" data-count="<?php echo $count; ?>"><i class="mdi mdi-delete-variant"></i></button>
                                        </td>

                                      </tr>
                                    <?php
                                      $count++;
                                    }
                                  } else {
                                    ?>

                                    <tr id="extraingredientTr<?php echo $count; ?>">

                                      <td><?php echo ($count + 1); ?>
                                      </td>

                                      <td><input type="text" class="form-control" id="extra_ingredient_name<?php echo $count; ?>" name="extra_ingredient_name[]" placeholder="" autocomplete="off"></td>

                                      <td><input type="text" class="form-control" id="extra_measure<?php echo $count; ?>" name="extra_measure[]" placeholder="" autocomplete="off"></td>

                                      <td><input type="number" step="0.01" class="form-control" data-count="<?php echo $count; ?>" id="extra_amt<?php echo $count; ?>" name="extra_amt[]" placeholder="" autocomplete="off"></td>

                                      <td>
                                        <button type="button" class="badge badge-danger deleteExtraIngri" data-count="<?php echo $count; ?>"><i class="mdi mdi-delete-variant"></i></button>
                                      </td>

                                    </tr>
                                  <?php
                                  }
                                  ?>

                                </tbody>

                              </table>

                              <br>

                              <div style="float: right;">
                                <span id="msgIng" style="margin-right: 20px;"></span>
                                <button type="button" class="btn btn-info addMoreExtra"><i class="mdi mdi-plus btn-icon-prepend"></i> Add More Extra Ingredient</button>

                              </div>

                            </div>

                          </div>

                        </div>
                        <br>
                        <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                          <p class="title-text">Nutritions</p>
                          <hr>

                          <div class="card-body">

                            <div class="table-responsive">

                              <table class="table table-bordered">

                                <thead>

                                  <tr class="table-info">

                                    <th>Order</th>

                                    <th>Nutritions</th>

                                    <th>Amount (%)</th>
                                  </tr>

                                </thead>

                                <tbody>
                                  <?php
                                  function myArrayContainsWord(array $myArray, $word)
                                  {
                                    foreach ($myArray as $element) {
                                      if ($element->nutrition_name == $word) {
                                        return $element->nutrition_amount;
                                      }
                                    }
                                    return '';
                                  }
                                  $i = 1;
                                  if (isset($nutritionLog) && !empty($nutritionLog)) {
                                    foreach ($nutritionLog as $key => $val) {
                                      if (isset($mealNutriLog) && !empty($mealNutriLog)) {
                                        $nutrition_amount = myArrayContainsWord($mealNutriLog, $val->nutrition_name);
                                      } else {
                                        $nutrition_amount = "";
                                      }
                                  ?>
                                      <tr>

                                        <td><?php echo $i; ?>
                                          <input type="hidden" class="form-control" id="nutrition_name<?php echo $val->id; ?>" name="nutrition_name[]" value="<?php echo $val->nutrition_name; ?>" autocomplete="off">
                                        </td>

                                        <td><?php echo $val->nutrition_name; ?></td>
                                        <td><input type="text" class="form-control" id="nutrition_amount<?php echo $val->id; ?>" name="nutrition_amount[]" placeholder="Values" autocomplete="off" value="<?php echo $nutrition_amount; ?>"></td>

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

                      <!-- <div class="col-md-1"></div> -->

                      <div class="col-md-4">

                        <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">

                          <p class="title-text">Meal Type</p>
                          <hr>

                          <div class="card-body">

                            <div class="form-group">

                              <select type="text" name="meal_type_id" id="meal_type_id" class="form-control" required>
                                <option value="">Choose one...</option>
                                <?php
                                if (isset($mealTypeLog) && !empty($mealTypeLog)) {
                                  foreach ($mealTypeLog as $key => $val) {
                                ?>
                                    <option value="<?php echo $val->id; ?>" <?php echo (isset($resultLog->meal_type_id) && $resultLog->meal_type_id == $val->id) ? 'selected' : ''; ?>><?php echo $val->meal_type_name; ?></option>
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

                          <p class="title-text">Food Type Requirment</p>
                          <hr>
                          <div class="card-body">
                            <div class="form-group">
                              <?php
                              if (isset($resultLog->food_type) && $resultLog->food_type) {
                                $foodType = explode(', ', $resultLog->food_type);
                              }
                              ?>
                              <select type="text" name="food_type[]" id="food_type" class="form-control" required multiple>
                                <?php
                                if (isset($foodTypeLog) && !empty($foodTypeLog)) {
                                  foreach ($foodTypeLog as $key => $val) {
                                ?>
                                    <option value="<?php echo $val->food_type_name; ?>" <?php echo (isset($foodType) && !empty($foodType) && in_array($val->food_type_name, $foodType)) ? 'selected' : ''; ?>><?php echo $val->food_type_name; ?></option>
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

                          <p class="title-text">Dietary Requirment</p>
                          <hr>
                          <div class="card-body">
                            <div class="form-group">
                              <select type="text" name="diet_type" id="diet_type" class="form-control" required>
                                <option value="">Select One</option>
                                <?php
                                if (isset($dietTypeLog) && !empty($dietTypeLog)) {
                                  foreach ($dietTypeLog as $key => $val) {
                                ?>
                                    <option value="<?php echo $val->diet_type_name; ?>" <?php echo (isset($resultLog->diet_type) && $resultLog->diet_type == $val->diet_type_name) ? 'selected' : ''; ?>><?php echo $val->diet_type_name; ?></option>
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

                              <input type="file" class="custom-file-input" id="meal_image" name="meal_image" <?php echo (isset($resultLog->id) && $resultLog->id > 0) ? '' : 'required'; ?>>

                              <label class="custom-file-label" for="inputGroupFile01">Choose..</label>

                            </div>
                            <?php if (isset($resultLog->meal_image_thumb) && $resultLog->meal_image_thumb != '') { ?>
                              <img src="<?php echo site_url($resultLog->meal_image_thumb) ?>">
                            <?php } ?>

                          </div>

                        </div>

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

              </div>
            </form>


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
  <style>
    .card-body {
      overflow-x: scroll !important;
    }

    .table th,
    .table td {
      text-align: left !important;
      padding: 10px 5px !important;
      margin: 0px !important;
      font-size: 13px !important;
      min-width: 100px !important;
    }
  </style>
  <script>
    $(document).ready(function() {
      $('#video_type').change(function() {
        $('.videoType').toggle();
      });


      $('.addMoreExtra').click(function() {
        var len = $('input[name="extra_ingredient_name[]"').length;
        $('#extraingredientContent').append('<tr id="extraingredientTr' + len + '">' +
          '<td>' + (len + 1) + '</td>' +
          '<td><input type="text" class="form-control" id="extra_ingredient_name' + len + '" name="extra_ingredient_name[]" placeholder="" autocomplete="off"></td>' +
          '<td><input type="text" class="form-control" id="extra_measure' + len + '" name="extra_measure[]" placeholder="" autocomplete="off"></td>' +
          '<td><input type="number" step="0.01" class="form-control" data-count="' + len + '" id="extra_amt' + len + '" name="extra_amt[]" placeholder="" autocomplete="off"></td>' +
          '<td>' +
          '<button type="button" class="badge badge-danger deleteExtraIngri" data-count="' + len + '"><i class="mdi mdi-delete-variant"></i></button>' +
          '</td>' +
          '</tr>');
      });

      $('#extraingredientContent').on('click', '.deleteExtraIngri', function() {
        var id = $(this).data('count');
        $('#extraingredientTr' + id).remove();
      });

      $('.addMore').click(function() {
        $('#msgIng').removeClass('text-danger');
        $('#msgIng').html('');
        //var addRow = false;
        var addRow = true;
        var len = $('input[name="ingredient_name[]"').length;
        /* if (len > 0) {
          var totalPerMeal = 0;
          $('input[name="per_of_meal[]"]').each(function() {
            totalPerMeal = parseFloat(totalPerMeal) + parseFloat($(this).val());
          });

          if (parseFloat(totalPerMeal) > 0 && parseFloat(totalPerMeal) < 100) {
            addRow = true;
          } else {
            $('#msgIng').addClass('text-danger');
            $('#msgIng').html('Please check % of meal');
          }
        } */

        if (addRow) {
          $('#ingredientContent').append('<tr id="ingredientTr' + len + '">' +
            '<td>' + (len + 1) +
            '<input type="hidden" step="0.01" class="form-control" id="ing_min_calorie' + len + '" name="ing_min_calorie[]" placeholder="" autocomplete="off" readonly>' +
            '<input type="hidden" step="0.01" class="form-control" id="ing_max_calorie' + len + '" name="ing_max_calorie[]" placeholder="" autocomplete="off" readonly>' +
            '</td>' +
            '<td><input type="text" class="form-control" id="ingredient_name' + len + '" name="ingredient_name[]" placeholder="" autocomplete="off" required></td>' +
            '<td><input type="text" class="form-control" id="unit_of_measure' + len + '" name="unit_of_measure[]" placeholder="" autocomplete="off" required></td>' +
            '<td><input type="number" step="0.01" class="form-control calClorie" data-count="' + len + '" id="calorie_per_gm' + len + '" name="calorie_per_gm[]" placeholder="" autocomplete="off" required></td>' +
            '<td><input type="number" step="0.01" class="form-control calClorie" data-count="' + len + '" id="min_amt' + len + '" name="min_amt[]" placeholder="" autocomplete="off" required></td>' +
            /* '<td><input type="number" step="0.01" class="form-control calClorie" data-count="' + len + '" id="max_amt' + len + '" name="max_amt[]" placeholder="" autocomplete="off" required></td>' +
            '<td><input type="number" step="0.01" class="form-control" data-count="' + len + '" id="per_of_meal' + len + '" name="per_of_meal[]" placeholder="" autocomplete="off" required></td>' + */
            '<td>' +
            '<button type="button" class="badge badge-danger deleteIngri" data-count="' + len + '"><i class="mdi mdi-delete-variant"></i></button>' +
            '</td>' +
            '</tr>');
        }
      });

      $('#ingredientContent').on('click', '.deleteIngri', function() {
        var id = $(this).data('count');
        $('#ingredientTr' + id).remove();
      });

      $('#ingredientContent').on('keyup', '.calClorie', function() {
        var id = $(this).data('count');
        var calPerGm = parseFloat($('#calorie_per_gm' + id).val());
        var minAmt = parseFloat($('#min_amt' + id).val());
        var maxAmt = parseFloat($('#max_amt' + id).val());

        var minIngCalorie = parseFloat(minAmt) * parseFloat(calPerGm);
        var maxIngCalorie = parseFloat(maxAmt) * parseFloat(calPerGm);

        $('#ing_min_calorie' + id).val(parseFloat(minIngCalorie).toFixed(2));
        $('#ing_max_calorie' + id).val(parseFloat(maxIngCalorie).toFixed(2));

        var min_calorie = 0;
        var max_calorie = 0;
        $('input[name="ing_min_calorie[]"]').each(function() {
          if (parseFloat($(this).val()) > 0) {
            min_calorie = parseFloat(min_calorie) + parseFloat($(this).val());
          }
        });

        //$('#min_calorie').val(parseFloat(min_calorie).toFixed(2));

        $('input[name="ing_max_calorie[]"]').each(function() {
          if (parseFloat($(this).val()) > 0) {
            max_calorie = parseFloat(max_calorie) + parseFloat($(this).val());
          }
        });

        $('#max_calorie').val(parseFloat(max_calorie).toFixed(2));
      });


      $('#ingredientFrm').submit(function() {
        /* $('#msg').removeClass('text-danger');
        $('#msg').html(''); */
        var isSubmit = false;
        /* var totalPerMeal = 0;
        $('input[name="per_of_meal[]"]').each(function() {
          totalPerMeal = parseFloat(totalPerMeal) + parseFloat($(this).val());
        });
        if (parseFloat(totalPerMeal) == 100) {
          if ($('#package_id').val().contains(2) && $('#week_id').val() == "0") { */
          if ((jQuery.inArray("2", $('#package_id').val()) !== -1) && $('#week_id').val() == "0") {
            $('#msg').addClass('text-danger');
            $('#msg').html('Please select week');
            return false;
          } else {
            tinyMCE.triggerSave();
            var form = $("#ingredientFrm");
            var formData = new FormData(form[0]);
            $('#msg').removeClass('text-danger');
            $('#msg').html('');
            $('#loader').show();
            $.ajax({
              url: "<?php echo base_url(); ?>/admincontrl/mealmaster/addUpdateMealsData",
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
                    window.location.href = '<?php echo base_url() ?>/admin/meals';
                  }, 3000);
                } else if (data.status == 0) {
                  $('#msg').addClass('text-danger');
                  $('#msg').html(data.message);
                  $('#loader').hide();
                }
              }
            });
            return false;
          }
       /*  } else {
          $('#msg').addClass('text-danger');
          $('#msg').html('Please check % of meal');
          return false;
        } */
      })
    })
  </script>

  <script src="https://cdn.tiny.cloud/1/riphsr2yy3x79vz03gwbh9f0snaeo7cof5p66w1yvxp6i8na/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
    tinymce.init({
      selector: '#cooking_method',
      setup: function(editor) {
        editor.on('change', function() {
          editor.save();
        });
      }
    });
  </script>
</body>

</html>