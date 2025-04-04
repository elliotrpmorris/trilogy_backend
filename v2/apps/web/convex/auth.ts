import { v } from "convex/values";
import { mutation, query } from "./_generated/server";
import { ConvexError } from "convex/values";

/**
 * Authenticate an admin user with email and password
 */
export const loginAdmin = mutation({
  args: {
    email: v.string(),
    password: v.string(),
  },
  handler: async (ctx, args) => {
    // For demo purposes, we're using a simplified authentication
    // In production, you would:
    // 1. Check if the email exists
    // 2. Verify the password with bcrypt
    // 3. Use a proper auth token system

    // Get the user with the given email
    const user = await ctx.db
      .query("users")
      .withIndex("by_email", (q) => q.eq("email", args.email))
      .unique();

    if (!user) {
      throw new ConvexError({
        message: "Invalid email or password",
        code: 401,
      });
    }

    // In production, compare password hash with bcrypt
    // For demo, we're just checking admin@example.com/admin123
    if (args.email !== "admin@example.com" || args.password !== "admin123") {
      throw new ConvexError({
        message: "Invalid email or password",
        code: 401,
      });
    }

    // Check if the user has admin role
    if (user.role !== "admin") {
      throw new ConvexError({
        message: "Unauthorized. User is not an admin.",
        code: 403,
      });
    }

    // Return user info (in a real system, we'd return a token too)
    return {
      id: user._id,
      name: user.name,
      email: user.email,
      role: user.role,
    };
  },
});

/**
 * Get the current admin user
 */
export const getAdminUser = query({
  args: {
    userId: v.optional(v.id("users")),
  },
  handler: async (ctx, args) => {
    if (!args.userId) {
      return null;
    }

    const user = await ctx.db.get(args.userId);

    if (!user || user.role !== "admin") {
      return null;
    }

    return {
      id: user._id,
      name: user.name,
      email: user.email,
      role: user.role,
    };
  },
});

/**
 * Create a new admin user (for development purposes)
 */
export const createAdminUser = mutation({
  args: {
    name: v.string(),
    email: v.string(),
  },
  handler: async (ctx, args) => {
    // Check if the user already exists
    const existingUser = await ctx.db
      .query("users")
      .withIndex("by_email", (q) => q.eq("email", args.email))
      .unique();

    if (existingUser) {
      throw new ConvexError({
        message: "User with this email already exists",
        code: 400,
      });
    }

    // Create the user
    const userId = await ctx.db.insert("users", {
      name: args.name,
      email: args.email,
      role: "admin",
      createdAt: Date.now(),
      updatedAt: Date.now(),
    });

    return { id: userId };
  },
}); 