import { mutation, query } from './_generated/server';
import { v } from 'convex/values';

export const getGeneralSettings = query({
  args: {},
  handler: async (ctx) => {
    const settings = await ctx.db
      .query('settings')
      .filter((q) => q.eq(q.field('type'), 'general'))
      .first();

    return settings || {
      siteName: 'My Site',
      siteDescription: 'Welcome to my site',
      maintenanceMode: false,
      allowUserRegistration: true,
    };
  },
});

export const updateGeneralSettings = mutation({
  args: {
    siteName: v.string(),
    siteDescription: v.string(),
    maintenanceMode: v.boolean(),
    allowUserRegistration: v.boolean(),
  },
  handler: async (ctx, args) => {
    const existing = await ctx.db
      .query('settings')
      .filter((q) => q.eq(q.field('type'), 'general'))
      .first();

    if (existing) {
      await ctx.db.patch(existing._id, {
        ...args,
        updatedAt: Date.now(),
      });
    } else {
      await ctx.db.insert('settings', {
        type: 'general',
        ...args,
        createdAt: Date.now(),
        updatedAt: Date.now(),
      });
    }

    return true;
  },
});

export const getUserPreferences = query({
  args: {
    userId: v.id("users"),
  },
  handler: async (ctx, args) => {
    // Get the user's preferences from the database
    const preferences = await ctx.db
      .query("userPreferences")
      .withIndex("by_user", (q) => q.eq("userId", args.userId))
      .first();

    return preferences || {
      userId: args.userId,
      theme: "light",
      notifications: true,
      language: "en",
      createdAt: Date.now(),
      updatedAt: Date.now(),
    };
  },
}); 