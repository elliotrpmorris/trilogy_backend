import { mutation, query } from "./_generated/server";
import { v } from "convex/values";
import { ConvexError } from "convex/values";

/**
 * Get users with pagination, search, and filtering
 */
export const getUsers = query({
  args: {
    page: v.optional(v.number()),
    per_page: v.optional(v.number()),
    search: v.optional(v.string()),
    status: v.optional(v.string()),
  },
  handler: async (ctx, args) => {
    const page = args.page || 1;
    const per_page = args.per_page || 10;
    const search = args.search || "";
    const status = args.status || "all";
    
    // Get all users
    let users = await ctx.db.query("users").collect();
    
    // Apply status filter if provided
    if (status !== "all") {
      users = users.filter(user => user.status === status);
    }
    
    // Apply search filter if provided
    if (search) {
      const searchLower = search.toLowerCase();
      users = users.filter(user => {
        return (
          (user.name?.toLowerCase().includes(searchLower)) ||
          (user.email?.toLowerCase().includes(searchLower))
        );
      });
    }
    
    // Get total count
    const total = users.length;
    
    // Sort by creation date (most recent first)
    users.sort((a, b) => b.createdAt - a.createdAt);
    
    // Paginate
    const offset = (page - 1) * per_page;
    const paginatedUsers = users.slice(offset, offset + per_page);
    
    // Format users for frontend
    const formattedUsers = paginatedUsers.map(user => ({
      id: user._id,
      cust_id: user._id, // For compatibility with previous code
      name: user.name,
      email_id: user.email,
      user_code: user.user_code || `USR${Math.floor(Math.random() * 10000)}`,
      age: user.age || null,
      gender: user.gender || null,
      registration_date: new Date(user.createdAt).toISOString().split('T')[0], // Format as YYYY-MM-DD
      activity_lavel: user.activity_level || 1,
      status: user.status || "active",
    }));
    
    return {
      users: formattedUsers,
      total,
      page,
      per_page,
      total_pages: Math.ceil(total / per_page),
    };
  },
});

/**
 * Get a user by ID
 */
export const getUserById = query({
  args: {
    id: v.id("users"),
  },
  handler: async (ctx, args) => {
    const user = await ctx.db.get(args.id);
    
    if (!user) {
      throw new ConvexError({
        message: "User not found",
        code: 404,
      });
    }
    
    return {
      id: user._id,
      cust_id: user._id,
      name: user.name,
      email_id: user.email,
      user_code: user.user_code || `USR${Math.floor(Math.random() * 10000)}`,
      age: user.age || null,
      height: user.height || null,
      weight: user.weight || null,
      gender: user.gender || null,
      country: user.country || null,
      registration_date: new Date(user.createdAt).toISOString().split('T')[0],
      activity_lavel: user.activity_level || 1,
      allergies: "", // These are stored in separate tables
      health_problem: "", // These are stored in separate tables
      profile_picture: user.profile_picture || null,
      status: user.status || "active",
    };
  },
});

/**
 * Create a new user
 */
export const createUser = mutation({
  args: {
    name: v.string(),
    email: v.string(),
    password: v.optional(v.string()),
    age: v.optional(v.number()),
    gender: v.optional(v.string()),
    height: v.optional(v.number()),
    weight: v.optional(v.number()),
    country: v.optional(v.string()),
    activity_level: v.optional(v.number()),
    status: v.optional(v.string()),
  },
  handler: async (ctx, args) => {
    // Check if user with email already exists
    const existingUser = await ctx.db
      .query("users")
      .withIndex("by_email", q => q.eq("email", args.email))
      .unique();
    
    if (existingUser) {
      throw new ConvexError({
        message: "User with this email already exists",
        code: 400,
      });
    }
    
    // Create user
    const userId = await ctx.db.insert("users", {
      name: args.name,
      email: args.email,
      role: "user", // Default role is user
      age: args.age,
      gender: args.gender,
      height: args.height,
      weight: args.weight,
      country: args.country,
      activity_level: args.activity_level,
      status: args.status === "inactive" ? "inactive" : "active",
      createdAt: Date.now(),
      updatedAt: Date.now(),
    });
    
    return { id: userId };
  },
});

/**
 * Update a user
 */
export const updateUser = mutation({
  args: {
    id: v.id("users"),
    name: v.optional(v.string()),
    email: v.optional(v.string()),
    age: v.optional(v.number()),
    gender: v.optional(v.string()),
    height: v.optional(v.number()),
    weight: v.optional(v.number()),
    country: v.optional(v.string()),
    activity_level: v.optional(v.number()),
    profile_picture: v.optional(v.string()),
    status: v.optional(v.string()),
  },
  handler: async (ctx, args) => {
    const { id, ...updates } = args;
    
    // Get existing user
    const user = await ctx.db.get(id);
    
    if (!user) {
      throw new ConvexError({
        message: "User not found",
        code: 404,
      });
    }
    
    // Check if email is being updated and if it's already taken
    if (updates.email && updates.email !== user.email) {
      const existingUser = await ctx.db
        .query("users")
        .withIndex("by_email", q => q.eq("email", updates.email as string))
        .unique();
      
      if (existingUser) {
        throw new ConvexError({
          message: "Email is already taken",
          code: 400,
        });
      }
    }
    
    // Update user
    await ctx.db.patch(id, {
      ...((updates.name !== undefined) && { name: updates.name }),
      ...((updates.email !== undefined) && { email: updates.email }),
      ...((updates.age !== undefined) && { age: updates.age }),
      ...((updates.gender !== undefined) && { gender: updates.gender }),
      ...((updates.height !== undefined) && { height: updates.height }),
      ...((updates.weight !== undefined) && { weight: updates.weight }),
      ...((updates.country !== undefined) && { country: updates.country }),
      ...((updates.activity_level !== undefined) && { activity_level: updates.activity_level }),
      ...((updates.profile_picture !== undefined) && { profile_picture: updates.profile_picture }),
      ...((updates.status !== undefined) && { status: updates.status === "inactive" ? "inactive" : "active" }),
      updatedAt: Date.now(),
    });
    
    return { success: true };
  },
});

/**
 * Delete a user
 */
export const deleteUser = mutation({
  args: {
    id: v.id("users"),
  },
  handler: async (ctx, args) => {
    // Get existing user
    const user = await ctx.db.get(args.id);
    
    if (!user) {
      throw new ConvexError({
        message: "User not found",
        code: 404,
      });
    }
    
    // Delete user
    await ctx.db.delete(args.id);
    
    return { success: true };
  },
}); 