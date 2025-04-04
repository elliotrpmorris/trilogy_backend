import { query } from "./_generated/server";
import { v } from "convex/values";

/**
 * Get dashboard statistics
 */
export const getStats = query({
  args: {},
  handler: async (ctx) => {
    // For production, these would be actual counts from the database
    
    // Get total users
    const users = await ctx.db.query("users").collect();
    const totalUsers = users.length;
    
    // Count active users (those with status = "active" or undefined status which we'll assume is active)
    const activeUsers = users.filter(user => 
      user.status === "active" || user.status === undefined
    ).length;
    
    // Count total orders
    const orders = await ctx.db.query("orders").collect();
    const totalOrders = orders.length;
    
    // Calculate total revenue
    const totalRevenue = orders.reduce((sum, order) => sum + order.amount, 0);
    
    return {
      totalUsers,
      activeUsers,
      totalOrders,
      totalRevenue
    };
  },
});

/**
 * Get recent users for dashboard
 */
export const getRecentUsers = query({
  args: {
    limit: v.optional(v.number()),
  },
  handler: async (ctx, args) => {
    const limit = args.limit || 5; // Default to 5 recent users
    
    // Get users ordered by creation date
    const users = await ctx.db
      .query("users")
      .order("desc")
      .take(limit);
    
    // Format for frontend display
    return users.map(user => ({
      id: user._id,
      cust_id: user._id, // For compatibility with older code
      name: user.name,
      email_id: user.email,
      registration_date: new Date(user.createdAt).toISOString().split('T')[0], // Format as YYYY-MM-DD
      status: user.status || "active"
    }));
  },
});

/**
 * Get recent orders for dashboard
 */
export const getRecentOrders = query({
  args: {
    limit: v.optional(v.number()),
  },
  handler: async (ctx, args) => {
    const limit = args.limit || 5; // Default to 5 recent orders
    
    // Get orders ordered by creation date
    const orders = await ctx.db
      .query("orders")
      .order("desc")
      .take(limit);
    
    // Get user data for each order
    const orderData = await Promise.all(
      orders.map(async (order) => {
        const user = await ctx.db.get(order.userId);
        
        return {
          id: order._id,
          order_id: order._id, // For compatibility with older code
          cust_id: order.userId,
          name: user?.name || "Unknown User",
          amount: order.amount,
          date: new Date(order.createdAt).toISOString().split('T')[0], // Format as YYYY-MM-DD
          status: order.status
        };
      })
    );
    
    return orderData;
  },
});

/**
 * Get full dashboard data (stats + recent users + recent orders)
 */
export const getDashboardData = query({
  args: {},
  handler: async (ctx) => {
    // In Convex, we need to implement these directly
    // Get stats
    const users = await ctx.db.query("users").collect();
    const totalUsers = users.length;
    const activeUsers = users.filter(user => 
      user.status === "active" || user.status === undefined
    ).length;
    const orders = await ctx.db.query("orders").collect();
    const totalOrders = orders.length;
    const totalRevenue = orders.reduce((sum, order) => sum + order.amount, 0);
    
    // Get recent users
    const recentUsersRaw = await ctx.db
      .query("users")
      .order("desc")
      .take(4);
    
    const recentUsers = recentUsersRaw.map(user => ({
      id: user._id,
      cust_id: user._id,
      name: user.name,
      email_id: user.email,
      registration_date: new Date(user.createdAt).toISOString().split('T')[0],
      status: user.status || "active"
    }));
    
    // Get recent orders
    const recentOrdersRaw = await ctx.db
      .query("orders")
      .order("desc")
      .take(4);
    
    const recentOrders = await Promise.all(
      recentOrdersRaw.map(async (order) => {
        const user = await ctx.db.get(order.userId);
        return {
          id: order._id,
          order_id: order._id,
          cust_id: order.userId,
          name: user?.name || "Unknown User",
          amount: order.amount,
          date: new Date(order.createdAt).toISOString().split('T')[0],
          status: order.status
        };
      })
    );
    
    return {
      totalUsers,
      activeUsers,
      totalOrders,
      totalRevenue,
      recentUsers,
      recentOrders
    };
  },
}); 