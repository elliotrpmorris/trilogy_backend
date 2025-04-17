import { v } from "convex/values";
import { mutation, query } from "./_generated/server";

export const list = query({
  handler: async (ctx) => {
    return await ctx.db
      .query("physioPrograms")
      .order("desc")
      .collect();
  },
});

export const get = query({
  args: { id: v.union(v.string(), v.literal("skip")) },
  handler: async (ctx, args) => {
    if (args.id === "skip") return null;
    return await ctx.db
      .query("physioPrograms")
      .filter((q) => q.eq(q.field("_id"), args.id))
      .first();
  },
});

export const create = mutation({
  args: {
    name: v.string(),
    description: v.string(),
    durationWeeks: v.number(),
  },
  handler: async (ctx, args) => {
    const now = Date.now();
    return await ctx.db.insert("physioPrograms", {
      ...args,
      createdAt: now,
      updatedAt: now,
    });
  },
});

export const update = mutation({
  args: {
    id: v.string(),
    name: v.string(),
    description: v.string(),
    durationWeeks: v.number(),
  },
  handler: async (ctx, args) => {
    const { id, ...data } = args;
    const program = await ctx.db
      .query("physioPrograms")
      .filter((q) => q.eq(q.field("_id"), id))
      .first();
    
    if (!program) {
      throw new Error("Program not found");
    }

    return await ctx.db.patch(program._id, {
      ...data,
      updatedAt: Date.now(),
    });
  },
}); 