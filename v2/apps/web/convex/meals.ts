import { v } from "convex/values";
import { mutation, query } from "./_generated/server";
import { Id } from "./_generated/dataModel";

/**
 * Get all meal types
 */
export const getMealTypes = query({
  handler: async (ctx) => {
    return await ctx.db.query("mealTypes").collect();
  },
});

/**
 * Get a specific meal type by ID
 */
export const getMealTypeById = query({
  args: { id: v.id("mealTypes") },
  handler: async (ctx, args) => {
    return await ctx.db.get(args.id);
  },
});

/**
 * Create a new meal type
 */
export const createMealType = mutation({
  args: {
    name: v.string(),
    description: v.optional(v.string()),
    order: v.optional(v.number()),
  },
  handler: async (ctx, args) => {
    const { name, description, order } = args;
    
    const mealTypeId = await ctx.db.insert("mealTypes", {
      name,
      description,
      order: order || 0,
      createdAt: Date.now(),
      updatedAt: Date.now(),
    });

    return { id: mealTypeId };
  },
});

/**
 * Update an existing meal type
 */
export const updateMealType = mutation({
  args: {
    id: v.id("mealTypes"),
    name: v.string(),
    description: v.optional(v.string()),
    order: v.optional(v.number()),
  },
  handler: async (ctx, args) => {
    const { id, name, description, order } = args;
    
    // Check if the meal type exists
    const existingMealType = await ctx.db.get(id);
    if (!existingMealType) {
      throw new Error("Meal type not found");
    }
    
    // Update the meal type
    await ctx.db.patch(id, {
      name,
      description,
      order: order !== undefined ? order : existingMealType.order,
      updatedAt: Date.now(),
    });

    return { id };
  },
});

/**
 * Delete a meal type
 */
export const deleteMealType = mutation({
  args: { id: v.id("mealTypes") },
  handler: async (ctx, args) => {
    const { id } = args;
    
    // Check if the meal type exists
    const existingMealType = await ctx.db.get(id);
    if (!existingMealType) {
      throw new Error("Meal type not found");
    }
    
    // Check if the meal type is in use by any meals
    const mealsUsingType = await ctx.db
      .query("meals")
      .withIndex("by_meal_type", (q) => q.eq("mealTypeId", id))
      .first();
    
    if (mealsUsingType) {
      throw new Error("Cannot delete a meal type that is in use by meals");
    }
    
    // Delete the meal type
    await ctx.db.delete(id);
    
    return { success: true };
  },
});

/**
 * Get all food types
 */
export const getFoodTypes = query({
  handler: async (ctx) => {
    return await ctx.db.query("foodTypes").collect();
  },
});

/**
 * Get all diet types
 */
export const getDietTypes = query({
  handler: async (ctx) => {
    return await ctx.db.query("dietTypes").collect();
  },
});

/**
 * Get all nutrition types
 */
export const getNutritionTypes = query({
  handler: async (ctx) => {
    return await ctx.db.query("nutritionTypes").collect();
  },
});

/**
 * Get all meals with pagination, search and filtering
 */
export const getMeals = query({
  args: {
    page: v.optional(v.number()),
    per_page: v.optional(v.number()),
    search: v.optional(v.string()),
    meal_type_id: v.optional(v.id("mealTypes")),
    diet_type_id: v.optional(v.id("dietTypes")),
  },
  handler: async (ctx, args) => {
    const { page = 1, per_page = 10, search, meal_type_id, diet_type_id } = args;
    
    // Build the query based on filters
    let mealsQuery;
    
    // Apply meal type filter if provided
    if (meal_type_id) {
      mealsQuery = ctx.db
        .query("meals")
        .withIndex("by_meal_type", (q) => q.eq("mealTypeId", meal_type_id));
    } else {
      // No meal type filter
      mealsQuery = ctx.db.query("meals");
    }
    
    // Collect results for further filtering
    let meals = await mealsQuery.collect();
    
    // Apply diet type filter if provided
    if (diet_type_id) {
      meals = meals.filter(meal => meal.dietTypeId === diet_type_id);
    }
    
    // Apply search filter if provided
    if (search && search.trim()) {
      const searchLower = search.toLowerCase();
      meals = meals.filter(meal => 
        meal.name.toLowerCase().includes(searchLower) || 
        (meal.description && meal.description.toLowerCase().includes(searchLower))
      );
    }
    
    // Calculate pagination
    const total = meals.length;
    const start = (page - 1) * per_page;
    const end = start + per_page;
    const paginatedMeals = meals.slice(start, end);
    
    // Gather additional data for each meal
    const mealsWithDetails = await Promise.all(
      paginatedMeals.map(async (meal) => {
        const mealType = meal.mealTypeId ? await ctx.db.get(meal.mealTypeId) : null;
        const dietType = meal.dietTypeId ? await ctx.db.get(meal.dietTypeId) : null;
        
        return {
          ...meal,
          meal_type_name: mealType?.name || "Unknown",
          diet_type_name: dietType?.name || "Unknown",
        };
      })
    );
    
    return {
      meals: mealsWithDetails,
      total,
      page,
      per_page,
      total_pages: Math.ceil(total / per_page),
    };
  },
});

/**
 * Get a single meal by ID
 */
export const getMealById = query({
  args: { id: v.id("meals") },
  handler: async (ctx, args) => {
    const meal = await ctx.db.get(args.id);
    if (!meal) {
      throw new Error("Meal not found");
    }
    
    // Get related data
    const mealType = meal.mealTypeId ? await ctx.db.get(meal.mealTypeId) : null;
    const dietType = meal.dietTypeId ? await ctx.db.get(meal.dietTypeId) : null;
    const foodType = meal.foodTypeId ? await ctx.db.get(meal.foodTypeId) : null;
    
    // Get nutrition information
    const nutritionInfo = await ctx.db
      .query("mealNutrition")
      .withIndex("by_meal", (q) => q.eq("mealId", args.id))
      .collect();
    
    const nutrition = await Promise.all(
      nutritionInfo.map(async (item) => {
        const nutritionType = await ctx.db.get(item.nutritionTypeId);
        return {
          id: item._id,
          name: nutritionType?.name || "Unknown",
          unit: nutritionType?.unit || "g",
          value: item.value,
        };
      })
    );
    
    return {
      ...meal,
      meal_type_name: mealType?.name || "Unknown",
      diet_type_name: dietType?.name || "Unknown",
      food_type_name: foodType?.name || "Unknown",
      nutrition,
    };
  },
}); 