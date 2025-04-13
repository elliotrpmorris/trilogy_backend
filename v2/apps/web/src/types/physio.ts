import { Id } from "@/convex/_generated/dataModel";

export interface PhysioProgram {
  _id: Id<"physioPrograms">;
  _creationTime: number;
  name: string;
  description?: string;
  weeks: number;
  createdAt: number;
  updatedAt: number;
} 