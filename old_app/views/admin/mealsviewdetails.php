<div class="row">
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
                                    <label>Meal Title </label> <br>
                                    <?php echo (isset($resultLog->meal_title) && $resultLog->meal_title != "") ? $resultLog->meal_title : ''; ?>
                                </div>
                                <div class="form-group">
                                    <label>Description</label> <br>
                                    <?php echo (isset($resultLog->meal_desc) && $resultLog->meal_desc != "") ? $resultLog->meal_desc : ''; ?>
                                </div>
                                <div class="form-group">
                                    <label>Tips</label> <br>
                                    <?php echo (isset($resultLog->meal_tips) && $resultLog->meal_tips != "") ? $resultLog->meal_tips : ''; ?>
                                </div>
                                <div class="form-group">
                                    <label>Cooking Method</label> <br>
                                    <?php echo (isset($resultLog->cooking_method) && $resultLog->cooking_method != "") ? $resultLog->cooking_method : ''; ?>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Prep Time (Mins)</label> <br>
                                            <?php echo (isset($resultLog->prep_time) && $resultLog->prep_time != "") ? $resultLog->prep_time : ''; ?> mins.
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Cooking Time (Mins)</label> <br>
                                            <?php echo (isset($resultLog->cooking_time) && $resultLog->cooking_time != "") ? $resultLog->cooking_time : ''; ?> mins.
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Min. Calorie</label> <br>
                                            <?php echo (isset($resultLog->min_calorie) && $resultLog->min_calorie != "") ? $resultLog->min_calorie : ''; ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Max. Calorie</label> <br>
                                            <?php echo (isset($resultLog->max_calorie) && $resultLog->max_calorie != "") ? $resultLog->max_calorie : ''; ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Status</label> <br>
                                            <?php echo (isset($resultLog->status) && $resultLog->status == "Y") ? 'Active' : 'Deactivate'; ?>
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
                                            <tr class="table-info">
                                                <th>Order</th>
                                                <th>Ingredient</th>
                                                <th>Unit of<br>Measurement</th>
                                                <th>Calorie per<br>Measurement</th>
                                                <th>Min</th>
                                                <th>max</th>
                                                <th>% of meal</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ingredientContent">
                                            <?php
                                            $count = 0;
                                            if (isset($ingredientLog) && !empty($ingredientLog)) {
                                                foreach ($ingredientLog as $key => $val) {
                                            ?>
                                                    <tr id="ingredientTr<?php echo $count; ?>">
                                                        <td><?php echo ($count + 1); ?></td>
                                                        <td><?php echo $val->ingredient_name; ?></td>
                                                        <td><?php echo $val->unit_of_measure; ?></td>
                                                        <td><?php echo $val->calorie_per_gm; ?></td>
                                                        <td><?php echo $val->min_amt; ?> <?php echo $val->unit_of_measure; ?>.</td>
                                                        <td><?php echo $val->max_amt; ?> <?php echo $val->unit_of_measure; ?>.</td>
                                                        <td><?php echo $val->per_of_meal; ?></td>
                                                    </tr>
                                            <?php
                                                    $count++;
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><br>
                        <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">
                            <p class="title-text">Extra Ingradient</p>
                            <hr>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="table-info">
                                                <th>Order</th>
                                                <th>Ingredient</th>
                                                <th>Unit of<br>Measurement</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $count = 0;
                                            if (isset($mealExtraLog) && !empty($mealExtraLog)) {
                                                foreach ($mealExtraLog as $key => $val) {
                                            ?>
                                                    <tr id="ingredientTr<?php echo $count; ?>">
                                                        <td><?php echo ($count + 1); ?></td>
                                                        <td><?php echo $val->extra_ingredient_name; ?></td>
                                                        <td><?php echo $val->extra_measure; ?></td>
                                                        <td><?php echo $val->extra_amt; ?></td>
                                                    </tr>
                                            <?php
                                                    $count++;
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
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
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            if (isset($mealNutriLog) && !empty($mealNutriLog)) {
                                                foreach ($mealNutriLog as $key => $val) {
                                            ?>
                                                    <tr>
                                                        <td><?php echo $i; ?>
                                                        </td>
                                                        <td><?php echo $val->nutrition_name; ?></td>
                                                        <td><?php echo $val->nutrition_amount; ?></td>
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
                                    <?php
                                    echo (isset($resultLog->meal_type_name) && $resultLog->meal_type_name != "") ? $resultLog->meal_type_name : '';
                                    ?>
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
                                    echo (isset($resultLog->food_type) && $resultLog->food_type != "") ? $resultLog->food_type : '';
                                    ?>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">
                            <p class="title-text">Dietary Requirment</p>
                            <hr>
                            <div class="card-body">
                                <div class="form-group">
                                    <?php
                                    echo (isset($resultLog->diet_type) && $resultLog->diet_type != "") ? $resultLog->diet_type : '';
                                    ?>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">
                            <p class="title-text">Image</p>
                            <hr>
                            <div class="card-body">
                                <?php if (isset($resultLog->meal_image_thumb) && $resultLog->meal_image_thumb != '') { ?>
                                    <img src="<?php echo site_url($resultLog->meal_image_thumb) ?>">
                                <?php } ?>
                            </div>
                        </div>

                        <div class="card" style="border-radius: border: 2px; padding: 10px; border-radius: 10px;">
                            <p class="title-text">Video</p>
                            <hr>
                            <div class="card-body">
                                <?php if (isset($resultLog->video_path) && $resultLog->video_path != '') { ?>
                                    <?php echo $resultLog->video_path; ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>