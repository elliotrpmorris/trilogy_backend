'use client';

import React from 'react';
import { useQuery, useMutation } from 'convex/react';
import { api } from '@/convex/_generated/api';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Button } from '@/components/ui/button';
import { UserNotifications } from './user-notifications';
import { SystemNotifications } from './system-notifications';

export default function NotificationsPage() {
  const notifications = useQuery(api.notifications.getNotifications);
  const createNotification = useMutation(api.notifications.createNotification);

  return (
    <div className="container mx-auto py-6">
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-3xl font-bold">Notifications Management</h1>
        <Button onClick={() => router.push('/admin/notifications/new')}>
          Create Notification
        </Button>
      </div>

      <Tabs defaultValue="user" className="w-full">
        <TabsList className="grid w-full grid-cols-2">
          <TabsTrigger value="user">User Notifications</TabsTrigger>
          <TabsTrigger value="system">System Notifications</TabsTrigger>
        </TabsList>

        <TabsContent value="user">
          <Card>
            <CardHeader>
              <CardTitle>User Notifications</CardTitle>
            </CardHeader>
            <CardContent>
              <UserNotifications
                notifications={notifications?.userNotifications || []}
              />
            </CardContent>
          </Card>
        </TabsContent>

        <TabsContent value="system">
          <Card>
            <CardHeader>
              <CardTitle>System Notifications</CardTitle>
            </CardHeader>
            <CardContent>
              <SystemNotifications
                notifications={notifications?.systemNotifications || []}
              />
            </CardContent>
          </Card>
        </TabsContent>
      </Tabs>
    </div>
  );
} 