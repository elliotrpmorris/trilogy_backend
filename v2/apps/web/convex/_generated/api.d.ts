/* eslint-disable */
/**
 * Generated `api` utility.
 *
 * THIS CODE IS AUTOMATICALLY GENERATED.
 *
 * To regenerate, run `npx convex dev`.
 * @module
 */

import type {
  ApiFromModules,
  FilterApi,
  FunctionReference,
} from "convex/server";
import type * as analytics from "../analytics.js";
import type * as auth from "../auth.js";
import type * as dashboard from "../dashboard.js";
import type * as meals from "../meals.js";
import type * as messages from "../messages.js";
import type * as physio from "../physio.js";
import type * as physioPrograms from "../physioPrograms.js";
import type * as seed from "../seed.js";
import type * as settings from "../settings.js";
import type * as storage from "../storage.js";
import type * as types_workout from "../types/workout.js";
import type * as users from "../users.js";
import type * as workouts from "../workouts.js";

/**
 * A utility for referencing Convex functions in your app's API.
 *
 * Usage:
 * ```js
 * const myFunctionReference = api.myModule.myFunction;
 * ```
 */
declare const fullApi: ApiFromModules<{
  analytics: typeof analytics;
  auth: typeof auth;
  dashboard: typeof dashboard;
  meals: typeof meals;
  messages: typeof messages;
  physio: typeof physio;
  physioPrograms: typeof physioPrograms;
  seed: typeof seed;
  settings: typeof settings;
  storage: typeof storage;
  "types/workout": typeof types_workout;
  users: typeof users;
  workouts: typeof workouts;
}>;
export declare const api: FilterApi<
  typeof fullApi,
  FunctionReference<any, "public">
>;
export declare const internal: FilterApi<
  typeof fullApi,
  FunctionReference<any, "internal">
>;
