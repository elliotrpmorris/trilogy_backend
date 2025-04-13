import { Id } from "../_generated/dataModel";

export interface WorkoutRoutine {
  _id: Id<"workoutRoutines">;
  _creationTime: number;
  name: string;
  description?: string;
  weeks: number;
  workoutLevelId: Id<"workoutLevels">;
  coachId?: Id<"workoutCoaches">;
  createdAt: number;
  updatedAt: number;
} 