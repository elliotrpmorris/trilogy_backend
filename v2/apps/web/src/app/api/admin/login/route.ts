import { NextResponse } from 'next/server';

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const { email_id, password } = body;

    // Validate required fields
    if (!email_id || !password) {
      return NextResponse.json(
        { success: 0, message: 'Email and password are required' },
        { status: 400 }
      );
    }

    // In a real implementation, you would make a request to your PHP backend
    // Example: 
    // const response = await fetch('https://your-backend-url/admin/login', {
    //   method: 'POST',
    //   headers: {
    //     'Content-Type': 'application/json',
    //     'API-Key': process.env.API_KEY || '',
    //   },
    //   body: JSON.stringify({ email_id, password }),
    // });
    // const data = await response.json();

    // For development purposes, we'll mock the response
    // Replace this with actual API call in production
    const mockSuccessResponse = {
      success: 1,
      message: 'Login successful',
      data: {
        admin_id: 1,
        name: 'Admin User',
        email: email_id,
        role: 'admin',
        token: 'mocktoken123456789'
      }
    };

    // Mock unauthorized response if not using admin credentials
    if (email_id !== 'admin@example.com' || password !== 'admin123') {
      return NextResponse.json(
        { success: 0, message: 'Invalid email or password' },
        { status: 401 }
      );
    }

    // Create a session cookie (would be JWT in production)
    const response = NextResponse.json(mockSuccessResponse);
    
    // Set a cookie that would be used for authentication
    // In production, this would be a secure, httpOnly JWT token
    response.cookies.set({
      name: 'admin_token',
      value: mockSuccessResponse.data.token,
      httpOnly: true,
      secure: process.env.NODE_ENV === 'production',
      sameSite: 'strict',
      path: '/',
      maxAge: 60 * 60 * 24 // 1 day
    });

    return response;
  } catch (error) {
    console.error('Admin login error:', error);
    return NextResponse.json(
      { success: 0, message: 'Server error' },
      { status: 500 }
    );
  }
} 