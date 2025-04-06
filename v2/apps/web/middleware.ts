import { authMiddleware } from "@clerk/nextjs";
import { NextRequest } from "next/server";

// This example protects all routes including api/trpc routes
// Please edit this to allow other routes to be public as needed.
// See https://clerk.com/docs/references/nextjs/auth-middleware for more information about configuring your middleware
export default authMiddleware({
  // Routes that can be accessed without authentication
  publicRoutes: [
    "/",
    "/signin",
    "/signup",
  ],
  
  // Routes that can always be accessed, and have
  // no authentication information
  ignoredRoutes: [
    "/api/webhook"
  ],
  
  // Define custom authorizations for specific routes
  afterAuth(auth: AuthObject, req: NextRequest) {
    // Handle users who aren't authenticated
    if (!auth.userId && !auth.isPublicRoute) {
      const signInUrl = new URL('/signin', req.url);
      signInUrl.searchParams.set('redirect_url', req.nextUrl.pathname);
      return Response.redirect(signInUrl);
    }
  }
});

export const config = {
  matcher: ["/((?!.*\\..*|_next).*)", "/", "/(api|trpc)(.*)"],
};
