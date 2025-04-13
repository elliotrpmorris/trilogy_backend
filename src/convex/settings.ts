import { query } from "./_generated/server";
import { v } from "convex/values";

export const getUserPreferences = query({
  args: {
    userId: v.string(),
  },
  handler: async (ctx, args) => {
    // Get the user's preferences from the database
    const preferences = await ctx.db
      .query("userPreferences")
      .filter((q) => q.eq(q.field("userId"), args.userId))
      .first();

    return preferences || {
      theme: "light",
      notifications: true,
      language: "en",
    };
  },
}); 