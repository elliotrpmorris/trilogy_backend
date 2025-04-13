import { v } from "convex/values";
import { mutation, query } from "./_generated/server";
import { Id } from "./_generated/dataModel";

// Physio Exercise Functions
export const listExercises = query({
  args: {
    programName: v.optional(v.string()),
    weekNo: v.optional(v.number()),
  },
  handler: async (ctx, args) => {
    let query = ctx.db.query("physioExercises");
    
    if (args.programName) {
      query = query.filter((q) => q.eq(q.field("program_name"), args.programName));
    }
    
    if (args.weekNo !== undefined) {
      query = query.filter((q) => q.eq(q.field("week_no"), args.weekNo));
    }
    
    return await query.collect();
  },
});

export const getExercise = query({
  args: { id: v.id("physioExercises") },
  handler: async (ctx, args) => {
    return await ctx.db.get(args.id);
  },
});

export const createExercise = mutation({
  args: {
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
  },
  handler: async (ctx, args) => {
    const identity = await ctx.auth.getUserIdentity();
    if (!identity) {
      throw new Error("Unauthorized");
    }

    const userId = identity.subject as Id<"users">;
    const now = Date.now();

    return await ctx.db.insert("physioExercises", {
      ...args,
      created_by: userId,
      createdAt: now,
      updatedAt: now,
    });
  },
});

export const updateExercise = mutation({
  args: {
    id: v.id("physioExercises"),
    title: v.optional(v.string()),
    description: v.optional(v.string()),
    instructions: v.optional(v.string()),
    image: v.optional(v.string()),
    video_url: v.optional(v.string()),
    program_name: v.optional(v.string()),
    week_no: v.optional(v.number()),
    rep: v.optional(v.number()),
    sets: v.optional(v.number()),
    worktime: v.optional(v.number()),
    equipments: v.optional(v.string()),
    musclegroup: v.optional(v.string()),
    status: v.optional(v.union(v.literal("Y"), v.literal("N"))),
  },
  handler: async (ctx, args) => {
    const { id, ...rest } = args;
    const now = Date.now();

    return await ctx.db.patch(id, {
      ...rest,
      updatedAt: now,
    });
  },
});

export const deleteExercise = mutation({
  args: { id: v.id("physioExercises") },
  handler: async (ctx, args) => {
    await ctx.db.delete(args.id);
  },
});

// Physio Workout Type Functions
export const listWorkoutTypes = query({
  handler: async (ctx) => {
    return await ctx.db.query("physioWorkoutTypes").collect();
  },
});

export const getWorkoutType = query({
  args: { id: v.id("physioWorkoutTypes") },
  handler: async (ctx, args) => {
    return await ctx.db.get(args.id);
  },
});

export const createWorkoutType = mutation({
  args: {
    physio_type: v.string(),
    status: v.union(v.literal("Y"), v.literal("N")),
  },
  handler: async (ctx, args) => {
    const now = Date.now();
    return await ctx.db.insert("physioWorkoutTypes", {
      ...args,
      createdAt: now,
      updatedAt: now,
    });
  },
});

export const updateWorkoutType = mutation({
  args: {
    id: v.id("physioWorkoutTypes"),
    physio_type: v.optional(v.string()),
    status: v.optional(v.union(v.literal("Y"), v.literal("N"))),
  },
  handler: async (ctx, args) => {
    const { id, ...rest } = args;
    const now = Date.now();

    return await ctx.db.patch(id, {
      ...rest,
      updatedAt: now,
    });
  },
});

export const deleteWorkoutType = mutation({
  args: { id: v.id("physioWorkoutTypes") },
  handler: async (ctx, args) => {
    await ctx.db.delete(args.id);
  },
});

// Physio Program Functions
export const listPrograms = query({
  handler: async (ctx) => {
    return await ctx.db.query("physioPrograms").collect();
  },
});

export const getProgram = query({
  args: { id: v.id("physioPrograms") },
  handler: async (ctx, args) => {
    return await ctx.db.get(args.id);
  },
});

export const createProgram = mutation({
  args: {
    name: v.string(),
    description: v.string(),
    durationWeeks: v.number(),
  },
  handler: async (ctx, args) => {
    const identity = await ctx.auth.getUserIdentity();
    if (!identity) {
      throw new Error("Unauthorized");
    }

    const now = Date.now();
    return await ctx.db.insert("physioPrograms", {
      ...args,
      createdAt: now,
      updatedAt: now,
    });
  },
});

export const updateProgram = mutation({
  args: {
    id: v.id("physioPrograms"),
    name: v.optional(v.string()),
    description: v.optional(v.string()),
    durationWeeks: v.optional(v.number()),
  },
  handler: async (ctx, args) => {
    const { id, ...rest } = args;
    const now = Date.now();

    return await ctx.db.patch(id, {
      ...rest,
      updatedAt: now,
    });
  },
});

export const deleteProgram = mutation({
  args: { id: v.id("physioPrograms") },
  handler: async (ctx, args) => {
    // First check if there are any exercises associated with this program
    const exercises = await ctx.db
      .query("physioExercises")
      .filter((q) => q.eq(q.field("program_name"), args.id))
      .collect();

    if (exercises.length > 0) {
      throw new Error("Cannot delete program with associated exercises");
    }

    await ctx.db.delete(args.id);
  },
});

// Additional utility functions
export const duplicateExercise = mutation({
  args: { id: v.id("physioExercises") },
  handler: async (ctx, args) => {
    const exercise = await ctx.db.get(args.id);
    if (!exercise) {
      throw new Error("Exercise not found");
    }

    const identity = await ctx.auth.getUserIdentity();
    if (!identity) {
      throw new Error("Unauthorized");
    }

    const userId = identity.subject as Id<"users">;
    const now = Date.now();

    // Create a new exercise with the same data but new timestamps
    const { _id, _creationTime, createdAt, updatedAt, ...exerciseData } = exercise;
    return await ctx.db.insert("physioExercises", {
      ...exerciseData,
      created_by: userId,
      createdAt: now,
      updatedAt: now,
    });
  },
});

export const getProgramExercises = query({
  args: { programId: v.id("physioPrograms") },
  handler: async (ctx, args) => {
    const program = await ctx.db.get(args.programId);
    if (!program) {
      throw new Error("Program not found");
    }

    return await ctx.db
      .query("physioExercises")
      .filter((q) => q.eq(q.field("program_name"), program.name))
      .collect();
  },
});

export const getProgramWeekExercises = query({
  args: {
    programId: v.id("physioPrograms"),
    weekNo: v.number(),
  },
  handler: async (ctx, args) => {
    const program = await ctx.db.get(args.programId);
    if (!program) {
      throw new Error("Program not found");
    }

    return await ctx.db
      .query("physioExercises")
      .filter((q) => 
        q.and(
          q.eq(q.field("program_name"), program.name),
          q.eq(q.field("week_no"), args.weekNo)
        )
      )
      .collect();
  },
}); 