import { query } from "./_generated/server";
import { v } from "convex/values";

export const getDashboardData = query({
  args: {
    startDate: v.optional(v.string()),
    endDate: v.optional(v.string()),
  },
  handler: async (ctx, args) => {
    const startDate = args.startDate ? new Date(args.startDate) : new Date(Date.now() - 30 * 24 * 60 * 60 * 1000); // Default to last 30 days
    const endDate = args.endDate ? new Date(args.endDate) : new Date();

    // Get total users
    const totalUsers = await ctx.db.query("users").collect();
    
    // Get active users (users with status "active")
    const activeUsers = totalUsers.filter(user => user.status === "active");
    
    // Get total orders
    const orders = await ctx.db.query("orders").collect();
    
    // Get total revenue
    const totalRevenue = orders.reduce((sum, order) => sum + order.amount, 0);
    
    // Get recent orders
    const recentOrders = orders
      .sort((a, b) => b.createdAt - a.createdAt)
      .slice(0, 5);

    return {
      totalUsers: totalUsers.length,
      activeUsers: activeUsers.length,
      totalOrders: orders.length,
      totalRevenue,
      recentOrders: recentOrders.map(order => ({
        id: order._id,
        userId: order.userId,
        amount: order.amount,
        status: order.status,
        orderDate: new Date(order.order_date).toISOString(),
      })),
      dateRange: {
        start: startDate.toISOString(),
        end: endDate.toISOString(),
      },
    };
  },
}); 