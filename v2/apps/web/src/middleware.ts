import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';

// This function can be marked `async` if using `await` inside
export function middleware(request: NextRequest) {
  // Get the pathname of the request
  const path = request.nextUrl.pathname;

  // Check if the path is an admin path and not the login page
  const isAdminPath = path.startsWith('/admin');
  const isLoginPath = path === '/admin/login';

  // Public paths that don't require authentication
  const publicPaths = ['/admin/login'];

  // If it's an admin path that's not a public path
  if (isAdminPath && !publicPaths.includes(path)) {
    // Check if the user has a token
    const token = request.cookies.get('admin_token')?.value;

    // If there's no token, redirect to the login page
    if (!token) {
      const url = new URL('/admin/login', request.url);
      url.searchParams.set('redirectTo', path);
      return NextResponse.redirect(url);
    }
  }

  // If it's the login path and the user has a token, redirect to dashboard
  if (isLoginPath) {
    const token = request.cookies.get('admin_token')?.value;
    if (token) {
      const url = new URL('/admin/dashboard', request.url);
      return NextResponse.redirect(url);
    }
  }

  return NextResponse.next();
}

// See "Matching Paths" below to learn more
export const config = {
  matcher: ['/admin/:path*'],
}; 