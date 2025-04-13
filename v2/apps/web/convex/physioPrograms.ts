import { v } from "convex/values";
import { mutation, query } from "./_generated/server";
import { Id } from "./_generated/dataModel";

export const list = query({
  handler: async (ctx) => {
    return await ctx.db
      .query("physioPrograms")
      .order("desc")
      .collect();
  },
});

export const get = query({
  args: { id: v.union(v.id("physioPrograms"), v.literal("skip")) },
  handler: async (ctx, args) => {
    if (args.id === "skip") return null;
    return await ctx.db.get(args.id as Id<"physioPrograms">);
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
    id: v.id("physioPrograms"),
    name: v.string(),
    description: v.string(),
    durationWeeks: v.number(),
  },
  handler: async (ctx, args) => {
    const { id, ...data } = args;
    return await ctx.db.patch(id, {
      ...data,
      updatedAt: Date.now(),
    });
  },
}); 