import { anyApi } from "convex/server";
import { convex } from "./convexClient";
import { GenericId, v } from "convex/values";

/**
 * Convex Adapter
 * 
 * This adapter connects our frontend admin UI with Convex backend functions.
 * It provides a consistent interface that matches our existing API patterns.
 */

// Use anyApi to avoid type errors with functions that may not exist yet
const api = anyApi;

const convexAdapter = {
  admin: {
    /**
     * Admin login using Convex authentication
     */
    login: async (email: string, password: string) => {
      try {
        const result = await convex.mutation(api.auth.loginAdmin, { email, password });
        return {
          success: 1,
          message: "Login successful",
          data: {
            admin_id: result.id,
            name: result.name,
            email: result.email,
            role: result.role,
            token: "convex_session" // For compatibility with existing code
          }
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Login failed",
          data: null
        };
      }
    },

    /**
     * Get dashboard data from Convex
     */
    getDashboard: async () => {
      try {
        const data = await convex.query(api.dashboard.getDashboardData, {});
        return {
          success: 1,
          message: "Dashboard data retrieved successfully",
          data
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to retrieve dashboard data",
          data: null
        };
      }
    }
  },

  users: {
    /**
     * Get all users with pagination, search, and filtering
     */
    getAll: async (params: {
      page?: number;
      per_page?: number;
      search?: string;
      status?: string;
    }) => {
      try {
        const result = await convex.query(api.users.getUsers, {
          page: params.page,
          per_page: params.per_page,
          search: params.search,
          status: params.status
        });
        
        return {
          success: 1,
          message: "Users retrieved successfully",
          data: result
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to retrieve users",
          data: null
        };
      }
    },

    /**
     * Get a single user by ID
     */
    getById: async (userId: string) => {
      try {
        const data = await convex.query(api.users.getUserById, { id: userId });
        return {
          success: 1,
          message: "User retrieved successfully",
          data
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to retrieve user",
          data: null
        };
      }
    },

    /**
     * Create a new user
     */
    create: async (userData: any) => {
      try {
        const result = await convex.mutation(api.users.createUser, userData);
        return {
          success: 1,
          message: "User created successfully",
          data: { id: result.id }
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to create user",
          data: null
        };
      }
    },

    /**
     * Update an existing user
     */
    update: async (userId: string, userData: any) => {
      try {
        await convex.mutation(api.users.updateUser, {
          id: userId,
          ...userData
        });
        
        return {
          success: 1,
          message: "User updated successfully",
          data: { id: userId }
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to update user",
          data: null
        };
      }
    },

    /**
     * Delete a user
     */
    delete: async (userId: string) => {
      try {
        await convex.mutation(api.users.deleteUser, { id: userId });
        return {
          success: 1,
          message: "User deleted successfully",
          data: null
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to delete user",
          data: null
        };
      }
    },

    /**
     * Get user meals by type (week/month)
     */
    getMeals: async (userId: string, type: string) => {
      try {
        const data = await convex.query(api.users.getUserMeals, { id: userId, type });
        return {
          success: 1,
          message: "User meals retrieved successfully",
          data
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to retrieve user meals",
          data: null
        };
      }
    },

    /**
     * Get user workouts by type (week/month)
     */
    getWorkouts: async (userId: string, type: string) => {
      try {
        const data = await convex.query(api.users.getUserWorkouts, { id: userId, type });
        return {
          success: 1,
          message: "User workouts retrieved successfully",
          data
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to retrieve user workouts",
          data: null
        };
      }
    }
  },

  meals: {
    /**
     * Get all meal types
     */
    getTypes: async () => {
      try {
        const data = await convex.query(api.meals.getMealTypes, {});
        return {
          success: 1,
          message: "Meal types retrieved successfully",
          data
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to retrieve meal types",
          data: null
        };
      }
    },

    /**
     * Create a new meal type
     */
    createType: async (typeData: any) => {
      try {
        const result = await convex.mutation(api.meals.createMealType, typeData);
        return {
          success: 1,
          message: "Meal type created successfully",
          data: { id: result.id }
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to create meal type",
          data: null
        };
      }
    },

    /**
     * Update an existing meal type
     */
    updateType: async (typeId: string, typeData: any) => {
      try {
        await convex.mutation(api.meals.updateMealType, {
          id: typeId,
          ...typeData
        });
        
        return {
          success: 1,
          message: "Meal type updated successfully",
          data: { id: typeId }
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to update meal type",
          data: null
        };
      }
    },

    /**
     * Delete a meal type
     */
    deleteType: async (typeId: string) => {
      try {
        await convex.mutation(api.meals.deleteMealType, { id: typeId });
        return {
          success: 1,
          message: "Meal type deleted successfully",
          data: null
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to delete meal type",
          data: null
        };
      }
    },

    /**
     * Get all food types
     */
    getFoodTypes: async () => {
      try {
        const data = await convex.query(api.meals.getFoodTypes, {});
        return {
          success: 1,
          message: "Food types retrieved successfully",
          data
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to retrieve food types",
          data: null
        };
      }
    },

    /**
     * Get all diet types
     */
    getDietTypes: async () => {
      try {
        const data = await convex.query(api.meals.getDietTypes, {});
        return {
          success: 1,
          message: "Diet types retrieved successfully",
          data
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to retrieve diet types",
          data: null
        };
      }
    },

    /**
     * Get all meals with pagination, search, and filtering
     */
    getAll: async (params: {
      page?: number;
      per_page?: number;
      search?: string;
      meal_type_id?: string;
      diet_type_id?: string;
    }) => {
      try {
        // Pass the IDs directly to Convex
        const queryParams: any = {
          page: params.page,
          per_page: params.per_page,
          search: params.search
        };

        if (params.meal_type_id) {
          queryParams.meal_type_id = params.meal_type_id;
        }
        if (params.diet_type_id) {
          queryParams.diet_type_id = params.diet_type_id;
        }

        const result = await convex.query(api.meals.getMeals, queryParams);
        
        return {
          success: 1,
          message: "Meals retrieved successfully",
          data: result
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to retrieve meals",
          data: null
        };
      }
    },

    /**
     * Get a single meal by ID
     */
    getById: async (mealId: string) => {
      try {
        const data = await convex.query(api.meals.getMealById, { id: mealId });
        return {
          success: 1,
          message: "Meal retrieved successfully",
          data
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to retrieve meal",
          data: null
        };
      }
    },

    /**
     * Create a new meal
     */
    create: async (mealData: any) => {
      try {
        const result = await convex.mutation(api.meals.createMeal, mealData);
        return {
          success: 1,
          message: "Meal created successfully",
          data: { id: result.id }
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to create meal",
          data: null
        };
      }
    },

    /**
     * Update an existing meal
     */
    update: async (mealId: string, mealData: any) => {
      try {
        await convex.mutation(api.meals.updateMeal, {
          id: mealId,
          ...mealData
        });
        
        return {
          success: 1,
          message: "Meal updated successfully",
          data: { id: mealId }
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to update meal",
          data: null
        };
      }
    },

    /**
     * Delete a meal
     */
    delete: async (mealId: string) => {
      try {
        await convex.mutation(api.meals.deleteMeal, { id: mealId });
        return {
          success: 1,
          message: "Meal deleted successfully",
          data: null
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to delete meal",
          data: null
        };
      }
    },

    /**
     * Get all nutrition types
     */
    getNutritionTypes: async () => {
      try {
        const data = await convex.query(api.meals.getNutritionTypes, {});
        return {
          success: 1,
          message: "Nutrition types retrieved successfully",
          data
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to retrieve nutrition types",
          data: null
        };
      }
    },

    /**
     * Create a new nutrition type
     */
    createNutritionType: async (typeData: any) => {
      try {
        const result = await convex.mutation(api.meals.createNutritionType, typeData);
        return {
          success: 1,
          message: "Nutrition type created successfully",
          data: { id: result.id }
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to create nutrition type",
          data: null
        };
      }
    },

    /**
     * Update an existing nutrition type
     */
    updateNutritionType: async (typeId: string, typeData: any) => {
      try {
        await convex.mutation(api.meals.updateNutritionType, {
          id: typeId,
          ...typeData
        });
        
        return {
          success: 1,
          message: "Nutrition type updated successfully",
          data: { id: typeId }
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to update nutrition type",
          data: null
        };
      }
    },

    /**
     * Delete a nutrition type
     */
    deleteNutritionType: async (typeId: string) => {
      try {
        await convex.mutation(api.meals.deleteNutritionType, { id: typeId });
        return {
          success: 1,
          message: "Nutrition type deleted successfully",
          data: null
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to delete nutrition type",
          data: null
        };
      }
    },

    /**
     * Get nutrition information for a meal
     */
    getMealNutrition: async (mealId: string) => {
      try {
        const data = await convex.query(api.meals.getMealNutrition, { mealId });
        return {
          success: 1,
          message: "Meal nutrition retrieved successfully",
          data
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to retrieve meal nutrition",
          data: null
        };
      }
    },

    /**
     * Update nutrition information for a meal
     */
    updateMealNutrition: async (mealId: string, nutritionData: any) => {
      try {
        await convex.mutation(api.meals.updateMealNutrition, { 
          mealId,
          nutritionData 
        });
        return {
          success: 1,
          message: "Meal nutrition updated successfully",
          data: null
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to update meal nutrition",
          data: null
        };
      }
    }
  },

  workouts: {
    /**
     * Get all workout levels
     */
    getLevels: async () => {
      try {
        const data = await convex.query(api.workouts.getWorkoutLevels, {});
        return {
          success: 1,
          message: "Workout levels retrieved successfully",
          data
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to retrieve workout levels",
          data: null
        };
      }
    },

    /**
     * Get all workout coaches
     */
    getCoaches: async () => {
      try {
        const data = await convex.query(api.workouts.getWorkoutCoaches, {});
        return {
          success: 1,
          message: "Workout coaches retrieved successfully",
          data
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to retrieve workout coaches",
          data: null
        };
      }
    },

    /**
     * Get all muscle groups
     */
    getMuscleGroups: async () => {
      try {
        const data = await convex.query(api.workouts.getMuscleGroups, {});
        return {
          success: 1,
          message: "Muscle groups retrieved successfully",
          data
        };
      } catch (error: any) {
        return {
          success: 0,
          message: error.message || "Failed to retrieve muscle groups",
          data: null
        };
      }
    }
  }
};

export default convexAdapter; 