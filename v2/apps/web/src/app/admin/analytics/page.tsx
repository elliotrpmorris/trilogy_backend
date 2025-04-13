'use client';

import React from 'react';
import { useQuery } from 'convex/react';
import { api } from '../../../../convex/_generated/api';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { UserGrowthChart } from '@/app/admin/analytics/user-growth-chart';
import { EngagementMetrics } from '@/app/admin/analytics/engagement-metrics';
import { UsageStatistics } from '@/app/admin/analytics/usage-statistics';

export default function AnalyticsPage() {
  const analytics = useQuery(api.analytics.getDashboardData);

  return (
    <div className="container mx-auto py-6">
      <h1 className="text-3xl font-bold mb-6">Analytics Dashboard</h1>

      <div className="grid gap-6">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          <Card>
            <CardHeader>
              <CardTitle>Total Users</CardTitle>
            </CardHeader>
            <CardContent>
              <p className="text-3xl font-bold">{analytics?.totalUsers || 0}</p>
              <p className="text-sm text-gray-500">+{analytics?.newUsersThisMonth || 0} this month</p>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle>Active Users</CardTitle>
            </CardHeader>
            <CardContent>
              <p className="text-3xl font-bold">{analytics?.activeUsers || 0}</p>
              <p className="text-sm text-gray-500">{analytics?.activeUserPercentage || 0}% of total</p>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle>Average Engagement</CardTitle>
            </CardHeader>
            <CardContent>
              <p className="text-3xl font-bold">{analytics?.avgEngagementTime || 0} min</p>
              <p className="text-sm text-gray-500">per user per day</p>
            </CardContent>
          </Card>
        </div>

        <Tabs defaultValue="users" className="w-full">
          <TabsList className="grid w-full grid-cols-3">
            <TabsTrigger value="users">User Growth</TabsTrigger>
            <TabsTrigger value="engagement">Engagement</TabsTrigger>
            <TabsTrigger value="usage">Usage Statistics</TabsTrigger>
          </TabsList>

          <TabsContent value="users">
            <Card>
              <CardHeader>
                <CardTitle>User Growth</CardTitle>
              </CardHeader>
              <CardContent>
                <UserGrowthChart data={analytics?.userGrowthData || []} />
              </CardContent>
            </Card>
          </TabsContent>

          <TabsContent value="engagement">
            <Card>
              <CardHeader>
                <CardTitle>Engagement Metrics</CardTitle>
              </CardHeader>
              <CardContent>
                <EngagementMetrics data={analytics?.engagementData || []} />
              </CardContent>
            </Card>
          </TabsContent>

          <TabsContent value="usage">
            <Card>
              <CardHeader>
                <CardTitle>Usage Statistics</CardTitle>
              </CardHeader>
              <CardContent>
                <UsageStatistics data={analytics?.usageData || []} />
              </CardContent>
            </Card>
          </TabsContent>
        </Tabs>
      </div>
    </div>
  );
} 