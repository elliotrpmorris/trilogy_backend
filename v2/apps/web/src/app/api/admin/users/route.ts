import { NextResponse } from 'next/server';
import { cookies } from 'next/headers';

export async function GET(request: Request) {
  try {
    // Check for admin token
    const cookieStore = cookies();
    const token = cookieStore.get('admin_token')?.value;

    if (!token) {
      return NextResponse.json(
        { success: 0, message: 'Unauthorized' },
        { status: 401 }
      );
    }

    // Get query parameters
    const { searchParams } = new URL(request.url);
    const page = parseInt(searchParams.get('page') || '1', 10);
    const perPage = parseInt(searchParams.get('per_page') || '10', 10);
    const search = searchParams.get('search') || '';
    const status = searchParams.get('status') || 'all';
    
    // Calculate pagination offsets
    const offset = (page - 1) * perPage;

    // In a real implementation, you would make a request to your backend
    // Example:
    // const response = await fetch(`https://your-backend-url/admin/users?page=${page}&per_page=${perPage}&search=${search}&status=${status}`, {
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
    const mockUsers = [
      { cust_id: 1, name: 'John Doe', email_id: 'john@example.com', user_code: 'USR001', age: 28, gender: 'male', registration_date: '2023-03-15', activity_lavel: 3, status: 'active' },
      { cust_id: 2, name: 'Jane Smith', email_id: 'jane@example.com', user_code: 'USR002', age: 32, gender: 'female', registration_date: '2023-03-14', activity_lavel: 2, status: 'active' },
      { cust_id: 3, name: 'Bob Johnson', email_id: 'bob@example.com', user_code: 'USR003', age: 41, gender: 'male', registration_date: '2023-03-13', activity_lavel: 1, status: 'inactive' },
      { cust_id: 4, name: 'Alice Brown', email_id: 'alice@example.com', user_code: 'USR004', age: 35, gender: 'female', registration_date: '2023-03-12', activity_lavel: 4, status: 'active' },
      { cust_id: 5, name: 'Charlie Wilson', email_id: 'charlie@example.com', user_code: 'USR005', age: 29, gender: 'male', registration_date: '2023-03-11', activity_lavel: 3, status: 'active' },
      { cust_id: 6, name: 'Diana Miller', email_id: 'diana@example.com', user_code: 'USR006', age: 27, gender: 'female', registration_date: '2023-03-10', activity_lavel: 2, status: 'inactive' },
      { cust_id: 7, name: 'Edward Davis', email_id: 'edward@example.com', user_code: 'USR007', age: 33, gender: 'male', registration_date: '2023-03-09', activity_lavel: 1, status: 'active' },
      { cust_id: 8, name: 'Fiona Clark', email_id: 'fiona@example.com', user_code: 'USR008', age: 30, gender: 'female', registration_date: '2023-03-08', activity_lavel: 4, status: 'active' },
      { cust_id: 9, name: 'George White', email_id: 'george@example.com', user_code: 'USR009', age: 38, gender: 'male', registration_date: '2023-03-07', activity_lavel: 3, status: 'inactive' },
      { cust_id: 10, name: 'Hannah Green', email_id: 'hannah@example.com', user_code: 'USR010', age: 25, gender: 'female', registration_date: '2023-03-06', activity_lavel: 2, status: 'active' },
      { cust_id: 11, name: 'Ian Black', email_id: 'ian@example.com', user_code: 'USR011', age: 42, gender: 'male', registration_date: '2023-03-05', activity_lavel: 1, status: 'active' },
      { cust_id: 12, name: 'Jessica Lee', email_id: 'jessica@example.com', user_code: 'USR012', age: 31, gender: 'female', registration_date: '2023-03-04', activity_lavel: 3, status: 'inactive' },
      { cust_id: 13, name: 'Kevin Chen', email_id: 'kevin@example.com', user_code: 'USR013', age: 29, gender: 'male', registration_date: '2023-03-03', activity_lavel: 2, status: 'active' },
      { cust_id: 14, name: 'Linda Wang', email_id: 'linda@example.com', user_code: 'USR014', age: 34, gender: 'female', registration_date: '2023-03-02', activity_lavel: 4, status: 'active' },
      { cust_id: 15, name: 'Michael Kim', email_id: 'michael@example.com', user_code: 'USR015', age: 40, gender: 'male', registration_date: '2023-03-01', activity_lavel: 3, status: 'inactive' },
      { cust_id: 16, name: 'Nancy Park', email_id: 'nancy@example.com', user_code: 'USR016', age: 27, gender: 'female', registration_date: '2023-02-28', activity_lavel: 2, status: 'active' },
      { cust_id: 17, name: 'Oscar Rodriguez', email_id: 'oscar@example.com', user_code: 'USR017', age: 36, gender: 'male', registration_date: '2023-02-27', activity_lavel: 1, status: 'active' },
      { cust_id: 18, name: 'Patty Smith', email_id: 'patty@example.com', user_code: 'USR018', age: 32, gender: 'female', registration_date: '2023-02-26', activity_lavel: 3, status: 'inactive' },
      { cust_id: 19, name: 'Quincy Jones', email_id: 'quincy@example.com', user_code: 'USR019', age: 45, gender: 'male', registration_date: '2023-02-25', activity_lavel: 2, status: 'active' },
      { cust_id: 20, name: 'Rachel Williams', email_id: 'rachel@example.com', user_code: 'USR020', age: 29, gender: 'female', registration_date: '2023-02-24', activity_lavel: 4, status: 'active' },
    ];

    // Filter users based on search and status
    let filteredUsers = [...mockUsers];
    
    if (search) {
      const searchLower = search.toLowerCase();
      filteredUsers = filteredUsers.filter(user => 
        user.name.toLowerCase().includes(searchLower) ||
        user.email_id.toLowerCase().includes(searchLower)
      );
    }
    
    if (status !== 'all') {
      filteredUsers = filteredUsers.filter(user => user.status === status);
    }
    
    // Calculate total for pagination
    const total = filteredUsers.length;
    
    // Paginate results
    const paginatedUsers = filteredUsers.slice(offset, offset + perPage);

    // Construct response
    const mockResponse = {
      success: 1,
      message: 'Users retrieved successfully',
      data: {
        users: paginatedUsers,
        total,
        page,
        per_page: perPage,
        total_pages: Math.ceil(total / perPage)
      }
    };

    return NextResponse.json(mockResponse);
  } catch (error) {
    console.error('Users API error:', error);
    return NextResponse.json(
      { success: 0, message: 'Server error' },
      { status: 500 }
    );
  }
} 