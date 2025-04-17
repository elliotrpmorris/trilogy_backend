import { mutation } from "./_generated/server";
import { v } from "convex/values";

/**
 * Upload a file to Convex storage
 */
export const uploadFile = mutation({
  args: {
    name: v.string(),
    contentType: v.string(),
    data: v.string(), // base64 encoded data
  },
  handler: async (ctx, args) => {
    // Generate an upload URL
    const postUrl = await ctx.storage.generateUploadUrl();

    // Convert base64 to Uint8Array
    const binaryData = atob(args.data);
    const bytes = new Uint8Array(binaryData.length);
    for (let i = 0; i < binaryData.length; i++) {
      bytes[i] = binaryData.charCodeAt(i);
    }

    // Upload the file to the generated URL
    const result = await fetch(postUrl, {
      method: "POST",
      headers: { "Content-Type": args.contentType },
      body: new Blob([bytes], { type: args.contentType }),
    });

    if (!result.ok) {
      throw new Error("Failed to upload file");
    }

    const { storageId } = await result.json();

    // Get the URL for the stored file
    const url = await ctx.storage.getUrl(storageId);

    return {
      storageId,
      url,
    };
  },
}); 