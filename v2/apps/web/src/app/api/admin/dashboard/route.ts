import { NextResponse } from 'next/server';
import { cookies } from 'next/headers';

export async function GET() {
  try {
    // Check for admin token in cookies
    const cookieStore = cookies();
    const token = cookieStore.get('admin_token')?.value;

    if (!token) {
      return NextResponse.json(
        { success: 0, message: 'Unauthorized' },
        { status: 401 }
      );
    }

    // In a real implementation, you would validate the token
    // and then make a request to your backend
    // Example:
    // const response = await fetch('https://your-backend-url/admin/dashboard', {
    //   method: 'GET',
    //   headers: {
    //     'Content-Type': 'application/json',
    //     'API-Key': process.env.API_KEY || '',
    //     'Authorization': `Bearer ${token}`
    //   }
    // });
    // const data = await response.json();

    // For development purposes, we'll mock the response
    // Replace this with actual API call in production
    const mockDashboardData = {
      success: 1,
      message: 'Dashboard data retrieved successfully',
      data: {
        totalUsers: 2456,
        activeUsers: 1872,
        totalOrders: 1256,
        totalRevenue: 85640,
        recentUsers: [
          { cust_id: 1, name: 'John Doe', email_id: 'john@example.com', registration_date: '2023-03-15' },
          { cust_id: 2, name: 'Jane Smith', email_id: 'jane@example.com', registration_date: '2023-03-14' },
          { cust_id: 3, name: 'Bob Johnson', email_id: 'bob@example.com', registration_date: '2023-03-13' },
          { cust_id: 4, name: 'Alice Brown', email_id: 'alice@example.com', registration_date: '2023-03-12' },
        ],
        recentOrders: [
          { order_id: 101, cust_id: 1, name: 'John Doe', amount: 99, date: '2023-03-15', status: 'Completed' },
          { order_id: 102, cust_id: 2, name: 'Jane Smith', amount: 149, date: '2023-03-14', status: 'Completed' },
          { order_id: 103, cust_id: 3, name: 'Bob Johnson', amount: 199, date: '2023-03-13', status: 'Processing' },
          { order_id: 104, cust_id: 4, name: 'Alice Brown', amount: 99, date: '2023-03-12', status: 'Completed' },
        ]
      }
    };

    return NextResponse.json(mockDashboardData);
  } catch (error) {
    console.error('Dashboard API error:', error);
    return NextResponse.json(
      { success: 0, message: 'Server error' },
      { status: 500 }
    );
  }
} 