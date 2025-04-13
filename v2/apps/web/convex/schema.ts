import { defineSchema, defineTable } from "convex/server";
import { v } from "convex/values";

export default defineSchema({
  users: defineTable({
    name: v.string(),
    email: v.string(),
    role: v.union(v.literal("admin"), v.literal("user")),
    createdAt: v.number(),
    updatedAt: v.number(),
    // Additional user fields
    user_code: v.optional(v.string()),
    age: v.optional(v.number()),
    height: v.optional(v.number()),
    weight: v.optional(v.number()),
    gender: v.optional(v.string()),
    activity_level: v.optional(v.number()),
    country: v.optional(v.string()),
    profile_picture: v.optional(v.string()),
    status: v.optional(v.union(v.literal("active"), v.literal("inactive"))),
  }).index("by_email", ["email"]),

  userPreferences: defineTable({
    userId: v.id("users"),
    theme: v.union(v.literal("light"), v.literal("dark")),
    notifications: v.boolean(),
    language: v.string(),
    createdAt: v.number(),
    updatedAt: v.number(),
  }).index("by_user", ["userId"]),

  // Health data
  allergies: defineTable({
    name: v.string(),
    description: v.optional(v.string()),
    createdAt: v.number(),
    updatedAt: v.number(),
  }),

  userAllergies: defineTable({
    userId: v.id("users"),
    allergyId: v.id("allergies"),
    createdAt: v.number(),
  }).index("by_user", ["userId"]),

  healthProblems: defineTable({
    name: v.string(),
    description: v.optional(v.string()),
    createdAt: v.number(),
    updatedAt: v.number(),
  }),

  userHealthProblems: defineTable({
    userId: v.id("users"),
    healthProblemId: v.id("healthProblems"),
    createdAt: v.number(),
  }).index("by_user", ["userId"]),

  // Meal management
  mealTypes: defineTable({
    name: v.string(),
    description: v.optional(v.string()),
    order: v.optional(v.number()),
    createdAt: v.number(),
    updatedAt: v.number(),
  }),

  foodTypes: defineTable({
    name: v.string(),
    description: v.optional(v.string()),
    createdAt: v.number(),
    updatedAt: v.number(),
  }),

  dietTypes: defineTable({
    name: v.string(),
    description: v.optional(v.string()),
    createdAt: v.number(),
    updatedAt: v.number(),
  }),

  nutritionTypes: defineTable({
    name: v.string(),
    unit: v.string(),
    createdAt: v.number(),
    updatedAt: v.number(),
  }),

  meals: defineTable({
    name: v.string(),
    description: v.optional(v.string()),
    ingredients: v.optional(v.string()),
    recipe: v.optional(v.string()),
    preparation_time: v.optional(v.number()),
    cooking_time: v.optional(v.number()),
    calorie: v.optional(v.number()),
    image: v.optional(v.string()),
    mealTypeId: v.id("mealTypes"),
    foodTypeId: v.optional(v.id("foodTypes")),
    dietTypeId: v.optional(v.id("dietTypes")),
    created_by: v.id("users"),
    createdAt: v.number(),
    updatedAt: v.number(),
  }).index("by_meal_type", ["mealTypeId"]),

  mealNutrition: defineTable({
    mealId: v.id("meals"),
    nutritionTypeId: v.id("nutritionTypes"),
    value: v.number(),
    createdAt: v.number(),
  }).index("by_meal", ["mealId"]),

  userMeals: defineTable({
    userId: v.id("users"),
    mealId: v.id("meals"),
    date: v.string(),
    mealTypeId: v.id("mealTypes"),
    createdAt: v.number(),
  }).index("by_user_date", ["userId", "date"]),

  // Workout management
  workoutLevels: defineTable({
    name: v.string(),
    description: v.optional(v.string()),
    createdAt: v.number(),
    updatedAt: v.number(),
  }),

  workoutCoaches: defineTable({
    name: v.string(),
    bio: v.optional(v.string()),
    image: v.optional(v.string()),
    specialization: v.optional(v.string()),
    createdAt: v.number(),
    updatedAt: v.number(),
  }),

  muscleGroups: defineTable({
    name: v.string(),
    description: v.optional(v.string()),
    createdAt: v.number(),
    updatedAt: v.number(),
  }),

  workoutTypes: defineTable({
    name: v.string(),
    description: v.optional(v.string()),
    createdAt: v.number(),
    updatedAt: v.number(),
  }),

  equipments: defineTable({
    name: v.string(),
    description: v.optional(v.string()),
    createdAt: v.number(),
    updatedAt: v.number(),
  }),

  workoutRoutines: defineTable({
    name: v.string(),
    description: v.optional(v.string()),
    weeks: v.number(),
    workoutLevelId: v.id("workoutLevels"),
    coachId: v.optional(v.id("workoutCoaches")),
    createdAt: v.number(),
    updatedAt: v.number(),
  }).index("by_level", ["workoutLevelId"]),

  exercises: defineTable({
    name: v.string(),
    description: v.optional(v.string()),
    instructions: v.optional(v.string()),
    video_url: v.optional(v.string()),
    image_url: v.optional(v.string()),
    duration: v.optional(v.number()),
    calories_burned: v.optional(v.number()),
    workoutTypeId: v.id("workoutTypes"),
    muscleGroupId: v.optional(v.id("muscleGroups")),
    equipmentId: v.optional(v.id("equipments")),
    createdAt: v.number(),
    updatedAt: v.number(),
  }).index("by_workout_type", ["workoutTypeId"]),

  routineExercises: defineTable({
    routineId: v.id("workoutRoutines"),
    exerciseId: v.id("exercises"),
    week: v.number(),
    day: v.number(),
    sets: v.optional(v.number()),
    reps: v.optional(v.number()),
    duration: v.optional(v.number()),
    order: v.number(),
    createdAt: v.number(),
  }).index("by_routine_week_day", ["routineId", "week", "day"]),

  userWorkouts: defineTable({
    userId: v.id("users"),
    routineId: v.id("workoutRoutines"),
    start_date: v.string(),
    current_week: v.number(),
    current_day: v.number(),
    createdAt: v.number(),
    updatedAt: v.number(),
  }).index("by_user", ["userId"]),

  // Physio management
  physioPrograms: defineTable({
    name: v.string(),
    description: v.string(),
    durationWeeks: v.number(),
    createdAt: v.number(),
    updatedAt: v.number(),
  }),

  physioExercises: defineTable({
    title: v.string(),
    description: v.optional(v.string()),
    instructions: v.optional(v.string()),
    image: v.optional(v.string()),
    video_url: v.optional(v.string()),
    program_name: v.string(),
    week_no: v.number(),
    rep: v.optional(v.number()),
    sets: v.optional(v.number()),
    worktime: v.optional(v.number()),
    equipments: v.optional(v.string()),
    musclegroup: v.optional(v.string()),
    status: v.union(v.literal("Y"), v.literal("N")),
    created_by: v.id("users"),
    createdAt: v.number(),
    updatedAt: v.number(),
  }).index("by_program_week", ["program_name", "week_no"]),

  physioWorkoutTypes: defineTable({
    physio_type: v.string(),
    status: v.union(v.literal("Y"), v.literal("N")),
    createdAt: v.number(),
    updatedAt: v.number(),
  }),

  // Payment and orders
  packages: defineTable({
    name: v.string(),
    description: v.optional(v.string()),
    duration: v.number(), // in days
    price: v.number(),
    features: v.optional(v.array(v.string())),
    is_active: v.boolean(),
    createdAt: v.number(),
    updatedAt: v.number(),
  }),

  orders: defineTable({
    userId: v.id("users"),
    packageId: v.id("packages"),
    order_date: v.string(),
    amount: v.number(),
    discount: v.optional(v.number()),
    coupon_code: v.optional(v.string()),
    status: v.union(
      v.literal("pending"),
      v.literal("completed"),
      v.literal("failed"),
      v.literal("refunded")
    ),
    createdAt: v.number(),
    updatedAt: v.number(),
  }).index("by_user", ["userId"]),

  payments: defineTable({
    orderId: v.id("orders"),
    transaction_id: v.string(),
    payment_method: v.string(),
    payment_date: v.string(),
    amount: v.number(),
    status: v.union(
      v.literal("pending"),
      v.literal("completed"),
      v.literal("failed"),
      v.literal("refunded")
    ),
    createdAt: v.number(),
    updatedAt: v.number(),
  }).index("by_order", ["orderId"]),

  // Marketing
  coupons: defineTable({
    code: v.string(),
    discount_type: v.union(v.literal("percentage"), v.literal("fixed")),
    discount_value: v.number(),
    start_date: v.string(),
    end_date: v.string(),
    usage_limit: v.optional(v.number()),
    usage_count: v.number(),
    is_active: v.boolean(),
    createdAt: v.number(),
    updatedAt: v.number(),
  }).index("by_code", ["code"]),

  competitions: defineTable({
    title: v.string(),
    description: v.string(),
    start_date: v.string(),
    end_date: v.string(),
    prize: v.optional(v.string()),
    is_active: v.boolean(),
    createdAt: v.number(),
    updatedAt: v.number(),
  }),

  competitionEntries: defineTable({
    competitionId: v.id("competitions"),
    userId: v.id("users"),
    submission_text: v.optional(v.string()),
    submission_image: v.optional(v.string()),
    submission_date: v.string(),
    is_winner: v.boolean(),
    createdAt: v.number(),
    updatedAt: v.number(),
  }).index("by_competition", ["competitionId"]),

  // Messages for chat functionality
  messages: defineTable({
    author: v.string(),
    content: v.string(),
    timestamp: v.number(),
    recipientId: v.optional(v.id("users")),
    isRead: v.boolean(),
    createdAt: v.number(),
    updatedAt: v.number(),
  }).index("by_author", ["author"]),

  settings: defineTable({
    type: v.string(),
    siteName: v.string(),
    siteDescription: v.string(),
    maintenanceMode: v.boolean(),
    allowUserRegistration: v.boolean(),
    createdAt: v.number(),
    updatedAt: v.number(),
  }),
}); 