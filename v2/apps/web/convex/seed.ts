import { mutation } from "./_generated/server";
import { v } from "convex/values";

export const seedDatabase = mutation({
  args: {},
  handler: async (ctx) => {
    // Create default meal types
    const mealTypes = [
      { name: "Breakfast", description: "Morning meal", order: 1 },
      { name: "Lunch", description: "Midday meal", order: 2 },
      { name: "Dinner", description: "Evening meal", order: 3 },
      { name: "Snack", description: "Light meal between main meals", order: 4 },
    ];

    for (const mealType of mealTypes) {
      await ctx.db.insert("mealTypes", {
        ...mealType,
        createdAt: Date.now(),
        updatedAt: Date.now(),
      });
    }

    // Create default food types
    const foodTypes = [
      { name: "Vegetarian", description: "Plant-based meals" },
      { name: "Vegan", description: "Strictly plant-based meals" },
      { name: "Gluten-Free", description: "Meals without gluten" },
      { name: "Dairy-Free", description: "Meals without dairy" },
    ];

    for (const foodType of foodTypes) {
      await ctx.db.insert("foodTypes", {
        ...foodType,
        createdAt: Date.now(),
        updatedAt: Date.now(),
      });
    }

    // Create default workout levels
    const workoutLevels = [
      { name: "Beginner", description: "For those new to fitness" },
      { name: "Intermediate", description: "For those with some fitness experience" },
      { name: "Advanced", description: "For experienced fitness enthusiasts" },
    ];

    for (const level of workoutLevels) {
      await ctx.db.insert("workoutLevels", {
        ...level,
        createdAt: Date.now(),
        updatedAt: Date.now(),
      });
    }

    // Create default workout types
    const workoutTypes = [
      { name: "Strength Training", description: "Focus on building muscle and strength" },
      { name: "Cardio", description: "Focus on cardiovascular fitness" },
      { name: "Flexibility", description: "Focus on mobility and flexibility" },
      { name: "HIIT", description: "High-intensity interval training" },
    ];

    for (const type of workoutTypes) {
      await ctx.db.insert("workoutTypes", {
        ...type,
        createdAt: Date.now(),
        updatedAt: Date.now(),
      });
    }

    // Create default muscle groups
    const muscleGroups = [
      { name: "Chest", description: "Pectoral muscles" },
      { name: "Back", description: "Back muscles" },
      { name: "Legs", description: "Lower body muscles" },
      { name: "Arms", description: "Arm muscles" },
      { name: "Core", description: "Abdominal and core muscles" },
    ];

    for (const group of muscleGroups) {
      await ctx.db.insert("muscleGroups", {
        ...group,
        createdAt: Date.now(),
        updatedAt: Date.now(),
      });
    }

    // Create default packages
    const packages = [
      {
        name: "Basic Plan",
        description: "Essential fitness and nutrition guidance",
        duration: 30,
        price: 29.99,
        features: ["Basic workout plans", "Nutrition guidelines", "Email support"],
        is_active: true,
      },
      {
        name: "Premium Plan",
        description: "Comprehensive fitness and nutrition program",
        duration: 30,
        price: 49.99,
        features: [
          "Custom workout plans",
          "Personalized nutrition",
          "Priority support",
          "Progress tracking",
        ],
        is_active: true,
      },
    ];

    for (const pkg of packages) {
      await ctx.db.insert("packages", {
        ...pkg,
        createdAt: Date.now(),
        updatedAt: Date.now(),
      });
    }

    return { success: true };
  },
}); 