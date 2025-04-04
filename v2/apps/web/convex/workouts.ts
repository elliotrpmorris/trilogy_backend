import { mutation, query } from "./_generated/server";
import { v } from "convex/values";
import { ConvexError } from "convex/values";

/**
 * Get workout levels
 */
export const getWorkoutLevels = query({
  args: {},
  handler: async (ctx) => {
    const levels = await ctx.db.query("workoutLevels").collect();
    return levels.map(level => ({
      id: level._id,
      name: level.name,
      description: level.description || null,
    }));
  },
});

/**
 * Get workout coaches
 */
export const getWorkoutCoaches = query({
  args: {},
  handler: async (ctx) => {
    const coaches = await ctx.db.query("workoutCoaches").collect();
    return coaches.map(coach => ({
      id: coach._id,
      name: coach.name,
      bio: coach.bio || null,
      image: coach.image || null,
      specialization: coach.specialization || null,
    }));
  },
});

/**
 * Get muscle groups
 */
export const getMuscleGroups = query({
  args: {},
  handler: async (ctx) => {
    const muscleGroups = await ctx.db.query("muscleGroups").collect();
    return muscleGroups.map(group => ({
      id: group._id,
      name: group.name,
      description: group.description || null,
    }));
  },
});

/**
 * Get workout types
 */
export const getWorkoutTypes = query({
  args: {},
  handler: async (ctx) => {
    const types = await ctx.db.query("workoutTypes").collect();
    return types.map(type => ({
      id: type._id,
      name: type.name,
      description: type.description || null,
    }));
  },
});

/**
 * Get equipment types
 */
export const getEquipment = query({
  args: {},
  handler: async (ctx) => {
    const equipment = await ctx.db.query("equipments").collect();
    return equipment.map(item => ({
      id: item._id,
      name: item.name,
      description: item.description || null,
    }));
  },
});

// Typings for exercise groups
interface ExercisesByDay {
  [day: number]: any[];
}

interface ExercisesByWeekAndDay {
  [week: number]: ExercisesByDay;
}

/**
 * Get workout routines with pagination and filtering
 */
export const getWorkoutRoutines = query({
  args: {
    page: v.optional(v.number()),
    per_page: v.optional(v.number()),
    search: v.optional(v.string()),
    levelId: v.optional(v.id("workoutLevels")),
  },
  handler: async (ctx, args) => {
    const page = args.page || 1;
    const per_page = args.per_page || 10;
    const search = args.search || "";
    
    // Get all workout routines
    let routines = await ctx.db.query("workoutRoutines").collect();
    
    // Apply level filter if provided
    if (args.levelId) {
      routines = routines.filter(routine => routine.workoutLevelId === args.levelId);
    }
    
    // Apply search filter if provided
    if (search) {
      const searchLower = search.toLowerCase();
      routines = routines.filter(routine => {
        return (
          routine.name.toLowerCase().includes(searchLower) || 
          (routine.description?.toLowerCase() || "").includes(searchLower)
        );
      });
    }
    
    // Get total count
    const total = routines.length;
    
    // Sort by creation date (most recent first)
    routines.sort((a, b) => b.createdAt - a.createdAt);
    
    // Paginate
    const offset = (page - 1) * per_page;
    const paginatedRoutines = routines.slice(offset, offset + per_page);
    
    // Get level and coach data for each routine
    const enhancedRoutines = await Promise.all(
      paginatedRoutines.map(async (routine) => {
        const level = await ctx.db.get(routine.workoutLevelId);
        const coach = routine.coachId ? await ctx.db.get(routine.coachId) : null;
        
        return {
          id: routine._id,
          name: routine.name,
          description: routine.description || null,
          weeks: routine.weeks,
          level: level ? level.name : "Unknown",
          levelId: routine.workoutLevelId,
          coach: coach ? coach.name : null,
          coachId: routine.coachId || null,
          createdAt: new Date(routine.createdAt).toISOString().split('T')[0],
          updatedAt: new Date(routine.updatedAt).toISOString().split('T')[0],
        };
      })
    );
    
    return {
      routines: enhancedRoutines,
      total,
      page,
      per_page,
      total_pages: Math.ceil(total / per_page),
    };
  },
});

/**
 * Get a workout routine by ID with exercises
 */
export const getWorkoutRoutineById = query({
  args: {
    id: v.id("workoutRoutines"),
  },
  handler: async (ctx, args) => {
    const routine = await ctx.db.get(args.id);
    
    if (!routine) {
      throw new ConvexError({
        message: "Workout routine not found",
        code: 404,
      });
    }
    
    // Get level and coach data
    const level = await ctx.db.get(routine.workoutLevelId);
    const coach = routine.coachId ? await ctx.db.get(routine.coachId) : null;
    
    // Get exercises for this routine
    const routineExercises = await ctx.db
      .query("routineExercises")
      .withIndex("by_routine_week_day", q => q.eq("routineId", args.id))
      .collect();
    
    // Group exercises by week and day
    const exercisesByWeekAndDay: ExercisesByWeekAndDay = {};
    
    // Process each routine exercise
    await Promise.all(
      routineExercises.map(async (re) => {
        const exercise = await ctx.db.get(re.exerciseId);
        
        if (!exercise) return; // Skip if exercise not found
        
        // Get workout type and muscle group information
        const workoutType = await ctx.db.get(exercise.workoutTypeId);
        const muscleGroup = exercise.muscleGroupId ? await ctx.db.get(exercise.muscleGroupId) : null;
        const equipment = exercise.equipmentId ? await ctx.db.get(exercise.equipmentId) : null;
        
        if (!exercisesByWeekAndDay[re.week]) {
          exercisesByWeekAndDay[re.week] = {};
        }
        
        if (!exercisesByWeekAndDay[re.week][re.day]) {
          exercisesByWeekAndDay[re.week][re.day] = [];
        }
        
        exercisesByWeekAndDay[re.week][re.day].push({
          id: exercise._id,
          routineExerciseId: re._id,
          name: exercise.name,
          description: exercise.description || null,
          instructions: exercise.instructions || null,
          video_url: exercise.video_url || null,
          image_url: exercise.image_url || null,
          workoutType: workoutType ? workoutType.name : "Unknown",
          workoutTypeId: exercise.workoutTypeId,
          muscleGroup: muscleGroup ? muscleGroup.name : null,
          muscleGroupId: exercise.muscleGroupId || null,
          equipment: equipment ? equipment.name : null,
          equipmentId: exercise.equipmentId || null,
          sets: re.sets || null,
          reps: re.reps || null,
          duration: re.duration || null,
          order: re.order,
        });
      })
    );
    
    // Sort exercises by order within each day
    for (const week in exercisesByWeekAndDay) {
      for (const day in exercisesByWeekAndDay[week]) {
        exercisesByWeekAndDay[week][parseInt(day)].sort((a, b) => a.order - b.order);
      }
    }
    
    return {
      id: routine._id,
      name: routine.name,
      description: routine.description || null,
      weeks: routine.weeks,
      level: level ? level.name : "Unknown",
      levelId: routine.workoutLevelId,
      coach: coach ? coach.name : null,
      coachId: routine.coachId || null,
      createdAt: new Date(routine.createdAt).toISOString().split('T')[0],
      updatedAt: new Date(routine.updatedAt).toISOString().split('T')[0],
      exercises: exercisesByWeekAndDay,
    };
  },
});

/**
 * Create a new workout routine
 */
export const createWorkoutRoutine = mutation({
  args: {
    name: v.string(),
    description: v.optional(v.string()),
    weeks: v.number(),
    workoutLevelId: v.id("workoutLevels"),
    coachId: v.optional(v.id("workoutCoaches")),
  },
  handler: async (ctx, args) => {
    // Validate weeks (ensure it's at least 1)
    if (args.weeks < 1) {
      throw new ConvexError({
        message: "Weeks must be at least 1",
        code: 400,
      });
    }
    
    // Create routine
    const routineId = await ctx.db.insert("workoutRoutines", {
      name: args.name,
      description: args.description,
      weeks: args.weeks,
      workoutLevelId: args.workoutLevelId,
      coachId: args.coachId,
      createdAt: Date.now(),
      updatedAt: Date.now(),
    });
    
    return { id: routineId };
  },
});

/**
 * Update a workout routine
 */
export const updateWorkoutRoutine = mutation({
  args: {
    id: v.id("workoutRoutines"),
    name: v.optional(v.string()),
    description: v.optional(v.string()),
    weeks: v.optional(v.number()),
    workoutLevelId: v.optional(v.id("workoutLevels")),
    coachId: v.optional(v.id("workoutCoaches")),
  },
  handler: async (ctx, args) => {
    const { id, ...updates } = args;
    
    // Get existing routine
    const routine = await ctx.db.get(id);
    
    if (!routine) {
      throw new ConvexError({
        message: "Workout routine not found",
        code: 404,
      });
    }
    
    // Validate weeks (ensure it's at least 1)
    if (updates.weeks && updates.weeks < 1) {
      throw new ConvexError({
        message: "Weeks must be at least 1",
        code: 400,
      });
    }
    
    // Update routine
    await ctx.db.patch(id, {
      ...updates,
      updatedAt: Date.now(),
    });
    
    return { success: true };
  },
});

/**
 * Delete a workout routine
 */
export const deleteWorkoutRoutine = mutation({
  args: {
    id: v.id("workoutRoutines"),
  },
  handler: async (ctx, args) => {
    // Get existing routine
    const routine = await ctx.db.get(args.id);
    
    if (!routine) {
      throw new ConvexError({
        message: "Workout routine not found",
        code: 404,
      });
    }
    
    // First delete all routine exercises
    const routineExercises = await ctx.db
      .query("routineExercises")
      .withIndex("by_routine_week_day", q => q.eq("routineId", args.id))
      .collect();
    
    for (const re of routineExercises) {
      await ctx.db.delete(re._id);
    }
    
    // Then delete the routine
    await ctx.db.delete(args.id);
    
    return { success: true };
  },
});

/**
 * Get exercises with pagination and filtering
 */
export const getExercises = query({
  args: {
    page: v.optional(v.number()),
    per_page: v.optional(v.number()),
    search: v.optional(v.string()),
    workoutTypeId: v.optional(v.id("workoutTypes")),
    muscleGroupId: v.optional(v.id("muscleGroups")),
  },
  handler: async (ctx, args) => {
    const page = args.page || 1;
    const per_page = args.per_page || 10;
    const search = args.search || "";
    
    // Get all exercises
    let exercises = await ctx.db.query("exercises").collect();
    
    // Apply workout type filter if provided
    if (args.workoutTypeId) {
      exercises = exercises.filter(ex => ex.workoutTypeId === args.workoutTypeId);
    }
    
    // Apply muscle group filter if provided
    if (args.muscleGroupId) {
      exercises = exercises.filter(ex => 
        ex.muscleGroupId && ex.muscleGroupId === args.muscleGroupId
      );
    }
    
    // Apply search filter if provided
    if (search) {
      const searchLower = search.toLowerCase();
      exercises = exercises.filter(ex => {
        return (
          ex.name.toLowerCase().includes(searchLower) || 
          (ex.description?.toLowerCase() || "").includes(searchLower) ||
          (ex.instructions?.toLowerCase() || "").includes(searchLower)
        );
      });
    }
    
    // Get total count
    const total = exercises.length;
    
    // Sort by name
    exercises.sort((a, b) => a.name.localeCompare(b.name));
    
    // Paginate
    const offset = (page - 1) * per_page;
    const paginatedExercises = exercises.slice(offset, offset + per_page);
    
    // Get additional data for each exercise
    const enhancedExercises = await Promise.all(
      paginatedExercises.map(async (exercise) => {
        const workoutType = await ctx.db.get(exercise.workoutTypeId);
        const muscleGroup = exercise.muscleGroupId ? await ctx.db.get(exercise.muscleGroupId) : null;
        const equipment = exercise.equipmentId ? await ctx.db.get(exercise.equipmentId) : null;
        
        return {
          id: exercise._id,
          name: exercise.name,
          description: exercise.description || null,
          instructions: exercise.instructions || null,
          video_url: exercise.video_url || null,
          image_url: exercise.image_url || null,
          duration: exercise.duration || null,
          calories_burned: exercise.calories_burned || null,
          workoutType: workoutType ? workoutType.name : "Unknown",
          workoutTypeId: exercise.workoutTypeId,
          muscleGroup: muscleGroup ? muscleGroup.name : null,
          muscleGroupId: exercise.muscleGroupId || null,
          equipment: equipment ? equipment.name : null,
          equipmentId: exercise.equipmentId || null,
          createdAt: new Date(exercise.createdAt).toISOString().split('T')[0],
          updatedAt: new Date(exercise.updatedAt).toISOString().split('T')[0],
        };
      })
    );
    
    return {
      exercises: enhancedExercises,
      total,
      page,
      per_page,
      total_pages: Math.ceil(total / per_page),
    };
  },
});

/**
 * Get a single exercise by ID
 */
export const getExerciseById = query({
  args: {
    id: v.id("exercises"),
  },
  handler: async (ctx, args) => {
    const exercise = await ctx.db.get(args.id);
    
    if (!exercise) {
      throw new ConvexError({
        message: "Exercise not found",
        code: 404,
      });
    }
    
    // Get related entities
    const workoutType = await ctx.db.get(exercise.workoutTypeId);
    const muscleGroup = exercise.muscleGroupId ? await ctx.db.get(exercise.muscleGroupId) : null;
    const equipment = exercise.equipmentId ? await ctx.db.get(exercise.equipmentId) : null;
    
    return {
      id: exercise._id,
      name: exercise.name,
      description: exercise.description || null,
      instructions: exercise.instructions || null,
      video_url: exercise.video_url || null,
      image_url: exercise.image_url || null,
      duration: exercise.duration || null,
      calories_burned: exercise.calories_burned || null,
      workoutType: workoutType ? workoutType.name : "Unknown",
      workoutTypeId: exercise.workoutTypeId,
      muscleGroup: muscleGroup ? muscleGroup.name : null,
      muscleGroupId: exercise.muscleGroupId || null,
      equipment: equipment ? equipment.name : null,
      equipmentId: exercise.equipmentId || null,
      createdAt: new Date(exercise.createdAt).toISOString().split('T')[0],
      updatedAt: new Date(exercise.updatedAt).toISOString().split('T')[0],
    };
  },
});

/**
 * Create a new exercise
 */
export const createExercise = mutation({
  args: {
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
  },
  handler: async (ctx, args) => {
    // Create exercise
    const exerciseId = await ctx.db.insert("exercises", {
      name: args.name,
      description: args.description,
      instructions: args.instructions,
      video_url: args.video_url,
      image_url: args.image_url,
      duration: args.duration,
      calories_burned: args.calories_burned,
      workoutTypeId: args.workoutTypeId,
      muscleGroupId: args.muscleGroupId,
      equipmentId: args.equipmentId,
      createdAt: Date.now(),
      updatedAt: Date.now(),
    });
    
    return { id: exerciseId };
  },
});

/**
 * Update an exercise
 */
export const updateExercise = mutation({
  args: {
    id: v.id("exercises"),
    name: v.optional(v.string()),
    description: v.optional(v.string()),
    instructions: v.optional(v.string()),
    video_url: v.optional(v.string()),
    image_url: v.optional(v.string()),
    duration: v.optional(v.number()),
    calories_burned: v.optional(v.number()),
    workoutTypeId: v.optional(v.id("workoutTypes")),
    muscleGroupId: v.optional(v.id("muscleGroups")),
    equipmentId: v.optional(v.id("equipments")),
  },
  handler: async (ctx, args) => {
    const { id, ...updates } = args;
    
    // Get existing exercise
    const exercise = await ctx.db.get(id);
    
    if (!exercise) {
      throw new ConvexError({
        message: "Exercise not found",
        code: 404,
      });
    }
    
    // Update exercise
    await ctx.db.patch(id, {
      ...updates,
      updatedAt: Date.now(),
    });
    
    return { success: true };
  },
});

/**
 * Delete an exercise
 */
export const deleteExercise = mutation({
  args: {
    id: v.id("exercises"),
  },
  handler: async (ctx, args) => {
    // Get existing exercise
    const exercise = await ctx.db.get(args.id);
    
    if (!exercise) {
      throw new ConvexError({
        message: "Exercise not found",
        code: 404,
      });
    }
    
    // First check if this exercise is used in any routine
    const routineExercises = await ctx.db
      .query("routineExercises")
      .filter(q => q.eq(q.field("exerciseId"), args.id))
      .collect();
    
    if (routineExercises.length > 0) {
      throw new ConvexError({
        message: "Cannot delete exercise as it's used in workout routines",
        code: 400,
      });
    }
    
    // Delete the exercise
    await ctx.db.delete(args.id);
    
    return { success: true };
  },
});

/**
 * Add an exercise to a workout routine
 */
export const addExerciseToRoutine = mutation({
  args: {
    routineId: v.id("workoutRoutines"),
    exerciseId: v.id("exercises"),
    week: v.number(),
    day: v.number(),
    sets: v.optional(v.number()),
    reps: v.optional(v.number()),
    duration: v.optional(v.number()),
    order: v.number(),
  },
  handler: async (ctx, args) => {
    // Validate routine
    const routine = await ctx.db.get(args.routineId);
    if (!routine) {
      throw new ConvexError({
        message: "Workout routine not found",
        code: 404,
      });
    }
    
    // Validate exercise
    const exercise = await ctx.db.get(args.exerciseId);
    if (!exercise) {
      throw new ConvexError({
        message: "Exercise not found",
        code: 404,
      });
    }
    
    // Validate week/day
    if (args.week < 1 || args.week > routine.weeks) {
      throw new ConvexError({
        message: `Week must be between 1 and ${routine.weeks}`,
        code: 400,
      });
    }
    
    if (args.day < 1 || args.day > 7) {
      throw new ConvexError({
        message: "Day must be between 1 and 7",
        code: 400,
      });
    }
    
    // Add exercise to routine
    const routineExerciseId = await ctx.db.insert("routineExercises", {
      routineId: args.routineId,
      exerciseId: args.exerciseId,
      week: args.week,
      day: args.day,
      sets: args.sets,
      reps: args.reps,
      duration: args.duration,
      order: args.order,
      createdAt: Date.now(),
    });
    
    return { id: routineExerciseId };
  },
});

/**
 * Update a routine exercise
 */
export const updateRoutineExercise = mutation({
  args: {
    id: v.id("routineExercises"),
    sets: v.optional(v.number()),
    reps: v.optional(v.number()),
    duration: v.optional(v.number()),
    order: v.optional(v.number()),
  },
  handler: async (ctx, args) => {
    const { id, ...updates } = args;
    
    // Get existing routine exercise
    const routineExercise = await ctx.db.get(id);
    
    if (!routineExercise) {
      throw new ConvexError({
        message: "Routine exercise not found",
        code: 404,
      });
    }
    
    // Update routine exercise
    await ctx.db.patch(id, updates);
    
    return { success: true };
  },
});

/**
 * Remove an exercise from a routine
 */
export const removeExerciseFromRoutine = mutation({
  args: {
    id: v.id("routineExercises"),
  },
  handler: async (ctx, args) => {
    // Get existing routine exercise
    const routineExercise = await ctx.db.get(args.id);
    
    if (!routineExercise) {
      throw new ConvexError({
        message: "Routine exercise not found",
        code: 404,
      });
    }
    
    // Delete routine exercise
    await ctx.db.delete(args.id);
    
    return { success: true };
  },
});

/**
 * Assign a workout routine to a user
 */
export const assignWorkoutToUser = mutation({
  args: {
    userId: v.id("users"),
    routineId: v.id("workoutRoutines"),
    start_date: v.string(),
  },
  handler: async (ctx, args) => {
    // Validate user
    const user = await ctx.db.get(args.userId);
    if (!user) {
      throw new ConvexError({
        message: "User not found",
        code: 404,
      });
    }
    
    // Validate routine
    const routine = await ctx.db.get(args.routineId);
    if (!routine) {
      throw new ConvexError({
        message: "Workout routine not found",
        code: 404,
      });
    }
    
    // Check if user already has an active workout
    const existingWorkout = await ctx.db
      .query("userWorkouts")
      .withIndex("by_user", q => q.eq("userId", args.userId))
      .unique();
    
    if (existingWorkout) {
      // Update existing user workout
      await ctx.db.patch(existingWorkout._id, {
        routineId: args.routineId,
        start_date: args.start_date,
        current_week: 1,
        current_day: 1,
        updatedAt: Date.now(),
      });
      
      return { id: existingWorkout._id };
    }
    
    // Create new user workout
    const userWorkoutId = await ctx.db.insert("userWorkouts", {
      userId: args.userId,
      routineId: args.routineId,
      start_date: args.start_date,
      current_week: 1,
      current_day: 1,
      createdAt: Date.now(),
      updatedAt: Date.now(),
    });
    
    return { id: userWorkoutId };
  },
});

/**
 * Update user workout progress
 */
export const updateUserWorkoutProgress = mutation({
  args: {
    userId: v.id("users"),
    current_week: v.number(),
    current_day: v.number(),
  },
  handler: async (ctx, args) => {
    // Get user workout
    const userWorkout = await ctx.db
      .query("userWorkouts")
      .withIndex("by_user", q => q.eq("userId", args.userId))
      .unique();
    
    if (!userWorkout) {
      throw new ConvexError({
        message: "User workout not found",
        code: 404,
      });
    }
    
    // Get routine to validate week/day
    const routine = await ctx.db.get(userWorkout.routineId);
    
    if (!routine) {
      throw new ConvexError({
        message: "Workout routine not found",
        code: 404,
      });
    }
    
    // Validate week/day
    if (args.current_week < 1 || args.current_week > routine.weeks) {
      throw new ConvexError({
        message: `Week must be between 1 and ${routine.weeks}`,
        code: 400,
      });
    }
    
    if (args.current_day < 1 || args.current_day > 7) {
      throw new ConvexError({
        message: "Day must be between 1 and 7",
        code: 400,
      });
    }
    
    // Update user workout progress
    await ctx.db.patch(userWorkout._id, {
      current_week: args.current_week,
      current_day: args.current_day,
      updatedAt: Date.now(),
    });
    
    return { success: true };
  },
});

/**
 * Get user workout
 */
export const getUserWorkout = query({
  args: {
    userId: v.id("users"),
  },
  handler: async (ctx, args) => {
    // Get user workout
    const userWorkout = await ctx.db
      .query("userWorkouts")
      .withIndex("by_user", q => q.eq("userId", args.userId))
      .unique();
    
    if (!userWorkout) {
      return null; // User has no workout assigned
    }
    
    // Get routine
    const routine = await ctx.db.get(userWorkout.routineId);
    
    if (!routine) {
      return null; // Routine not found (shouldn't happen)
    }
    
    // Get workout level and coach
    const level = await ctx.db.get(routine.workoutLevelId);
    const coach = routine.coachId ? await ctx.db.get(routine.coachId) : null;
    
    // Get exercises for the current week and day
    const routineExercises = await ctx.db
      .query("routineExercises")
      .withIndex("by_routine_week_day", q => 
        q.eq("routineId", userWorkout.routineId)
          .eq("week", userWorkout.current_week)
          .eq("day", userWorkout.current_day)
      )
      .collect();
    
    // Process exercises
    const exercises = await Promise.all(
      routineExercises.sort((a, b) => a.order - b.order).map(async (re) => {
        const exercise = await ctx.db.get(re.exerciseId);
        
        if (!exercise) return null; // Skip if exercise not found
        
        // Get workout type and muscle group information
        const workoutType = await ctx.db.get(exercise.workoutTypeId);
        const muscleGroup = exercise.muscleGroupId ? await ctx.db.get(exercise.muscleGroupId) : null;
        const equipment = exercise.equipmentId ? await ctx.db.get(exercise.equipmentId) : null;
        
        return {
          id: exercise._id,
          routineExerciseId: re._id,
          name: exercise.name,
          description: exercise.description || null,
          instructions: exercise.instructions || null,
          video_url: exercise.video_url || null,
          image_url: exercise.image_url || null,
          workoutType: workoutType ? workoutType.name : "Unknown",
          workoutTypeId: exercise.workoutTypeId,
          muscleGroup: muscleGroup ? muscleGroup.name : null,
          muscleGroupId: exercise.muscleGroupId || null,
          equipment: equipment ? equipment.name : null,
          equipmentId: exercise.equipmentId || null,
          sets: re.sets || null,
          reps: re.reps || null,
          duration: re.duration || null,
          order: re.order,
        };
      })
    );
    
    // Filter out any null exercises
    const validExercises = exercises.filter(e => e !== null);
    
    return {
      id: userWorkout._id,
      routineId: userWorkout.routineId,
      routineName: routine.name,
      routineDescription: routine.description || null,
      level: level ? level.name : "Unknown",
      levelId: routine.workoutLevelId,
      coach: coach ? coach.name : null,
      coachId: routine.coachId || null,
      startDate: userWorkout.start_date,
      currentWeek: userWorkout.current_week,
      currentDay: userWorkout.current_day,
      totalWeeks: routine.weeks,
      exercises: validExercises,
    };
  },
}); 