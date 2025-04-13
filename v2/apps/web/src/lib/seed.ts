
import { mutation } from '../../../../convex/_generated/server';

// Sample data for seeding
const sampleUsers = [
  {
    name: 'John Doe',
    email: 'john@example.com',
    role: 'user',
    status: 'active',
  },
  {
    name: 'Jane Smith',
    email: 'jane@example.com',
    role: 'admin',
    status: 'active',
  },
];

const sampleWorkouts = [
  {
    name: 'Beginner Full Body',
    description: 'A full body workout for beginners',
    duration: 45,
    level: 'beginner',
    exercises: [
      {
        name: 'Push-ups',
        sets: 3,
        reps: 10,
        rest: 60,
      },
      {
        name: 'Squats',
        sets: 3,
        reps: 15,
        rest: 60,
      },
    ],
  },
  {
    name: 'Advanced HIIT',
    description: 'High intensity interval training',
    duration: 30,
    level: 'advanced',
    exercises: [
      {
        name: 'Burpees',
        sets: 4,
        reps: 12,
        rest: 45,
      },
      {
        name: 'Mountain Climbers',
        sets: 4,
        reps: 20,
        rest: 45,
      },
    ],
  },
];

const sampleMeals = [
  {
    name: 'Chicken Salad',
    description: 'Healthy chicken salad with mixed greens',
    prepTime: 15,
    cookTime: 0,
    calories: 350,
    ingredients: [
      {
        name: 'Chicken Breast',
        amount: 200,
        unit: 'g',
      },
      {
        name: 'Mixed Greens',
        amount: 100,
        unit: 'g',
      },
    ],
  },
  {
    name: 'Vegetable Stir Fry',
    description: 'Quick and healthy vegetable stir fry',
    prepTime: 10,
    cookTime: 15,
    calories: 300,
    ingredients: [
      {
        name: 'Mixed Vegetables',
        amount: 300,
        unit: 'g',
      },
      {
        name: 'Soy Sauce',
        amount: 2,
        unit: 'tbsp',
      },
    ],
  },
];

export const seedDatabase = mutation({
  args: {},
  handler: async (ctx) => {
    // Check if we already have data
    const existingUsers = await ctx.db.query('users').collect();
    if (existingUsers.length > 0) {
      return 'Database already seeded';
    }

    // Seed users
    for (const user of sampleUsers) {
      await ctx.db.insert('users', user);
    }

    // Seed workouts
    for (const workout of sampleWorkouts) {
      await ctx.db.insert('workouts', workout);
    }

    // Seed meals
    for (const meal of sampleMeals) {
      await ctx.db.insert('meals', meal);
    }

    return 'Database seeded successfully';
  },
}); 