import { ConvexReactClient } from "convex/react";
import { ConvexProviderWithClerk } from "convex/react-clerk";

/**
 * Create a Convex client for use in the frontend React application
 * This client provides a way to interact with the Convex backend
 */
export const convex = new ConvexReactClient(
  process.env.NEXT_PUBLIC_CONVEX_URL as string
);

/**
 * Convex client helper functions
 */
export const convexHelper = {
  /**
   * Format a Convex document by including the id in the document body
   * Useful for components that need to use the id property directly
   *
   * @param doc - The document to format
   * @returns The formatted document with id included in the document body
   */
  formatDoc: <T>(doc: { _id: string; _creationTime: number } & T) => {
    return {
      id: doc._id,
      creationTime: doc._creationTime,
      ...doc,
    };
  },

  /**
   * Format a list of Convex documents by including the id in each document body
   *
   * @param docs - The array of documents to format
   * @returns The formatted documents with ids included in each document body
   */
  formatDocs: <T>(
    docs: Array<{ _id: string; _creationTime: number } & T>
  ) => {
    return docs.map(convexHelper.formatDoc);
  },
};

export { ConvexProviderWithClerk };
export default convex; 