'use client';

import React from 'react';
import { useQuery, useMutation } from 'convex/react';
import { api } from '../../../../convex/_generated/api';
import { Button } from '@/components/ui/button';

import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { toast } from 'sonner';

export function UserPreferences() {
  const preferences = useQuery(api.settings.getUserPreferences);
  const updatePreferences = useMutation(api.settings.updateUserPreferences);

  const [formData, setFormData] = React.useState({
    defaultLanguage: preferences?.defaultLanguage || 'en',
    timezone: preferences?.timezone || 'UTC',
    dateFormat: preferences?.dateFormat || 'MM/DD/YYYY',
    enableNotifications: preferences?.enableNotifications || true,
    enableEmailNotifications: preferences?.enableEmailNotifications || true,
    enablePushNotifications: preferences?.enablePushNotifications || true,
  });

  React.useEffect(() => {
    if (preferences) {
      setFormData({
        defaultLanguage: preferences.defaultLanguage,
        timezone: preferences.timezone,
        dateFormat: preferences.dateFormat,
        enableNotifications: preferences.enableNotifications,
        enableEmailNotifications: preferences.enableEmailNotifications,
        enablePushNotifications: preferences.enablePushNotifications,
      });
    }
  }, [preferences]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      await updatePreferences(formData);
      toast({
        title: 'Preferences updated',
        description: 'User preferences have been saved successfully.',
      });
    } catch (error) {
      toast({
        title: 'Error',
        description: 'Failed to update preferences. Please try again.',
        variant: 'destructive',
      });
    }
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      <div className="space-y-4">
        <div className="space-y-2">
          <Label htmlFor="defaultLanguage">Default Language</Label>
          <Select
            value={formData.defaultLanguage}
            onValueChange={(value) => setFormData({ ...formData, defaultLanguage: value })}
          >
            <SelectTrigger>
              <SelectValue placeholder="Select language" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="en">English</SelectItem>
              <SelectItem value="es">Spanish</SelectItem>
              <SelectItem value="fr">French</SelectItem>
              <SelectItem value="de">German</SelectItem>
            </SelectContent>
          </Select>
        </div>

        <div className="space-y-2">
          <Label htmlFor="timezone">Default Timezone</Label>
          <Select
            value={formData.timezone}
            onValueChange={(value) => setFormData({ ...formData, timezone: value })}
          >
            <SelectTrigger>
              <SelectValue placeholder="Select timezone" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="UTC">UTC</SelectItem>
              <SelectItem value="EST">Eastern Time</SelectItem>
              <SelectItem value="PST">Pacific Time</SelectItem>
              <SelectItem value="GMT">Greenwich Mean Time</SelectItem>
            </SelectContent>
          </Select>
        </div>

        <div className="space-y-2">
          <Label htmlFor="dateFormat">Date Format</Label>
          <Select
            value={formData.dateFormat}
            onValueChange={(value) => setFormData({ ...formData, dateFormat: value })}
          >
            <SelectTrigger>
              <SelectValue placeholder="Select date format" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="MM/DD/YYYY">MM/DD/YYYY</SelectItem>
              <SelectItem value="DD/MM/YYYY">DD/MM/YYYY</SelectItem>
              <SelectItem value="YYYY-MM-DD">YYYY-MM-DD</SelectItem>
            </SelectContent>
          </Select>
        </div>

        <div className="space-y-2">
          <Label htmlFor="enableNotifications">Enable Notifications</Label>
          <Select
            value={formData.enableNotifications ? 'true' : 'false'}
            onValueChange={(value: string) => setFormData({ ...formData, enableNotifications: value === 'true' })}
          >
            <SelectTrigger>
              <SelectValue placeholder="Select notification preference" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="true">Enabled</SelectItem>
              <SelectItem value="false">Disabled</SelectItem>
            </SelectContent>
          </Select>
        </div>

        <div className="space-y-2">
          <Label htmlFor="enableEmailNotifications">Enable Email Notifications</Label>
          <Select
            value={formData.enableEmailNotifications ? 'true' : 'false'}
            onValueChange={(value: string) => setFormData({ ...formData, enableEmailNotifications: value === 'true' })}
          >
            <SelectTrigger>
              <SelectValue placeholder="Select email notification preference" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="true">Enabled</SelectItem>
              <SelectItem value="false">Disabled</SelectItem>
            </SelectContent>
          </Select>
        </div>

        <div className="space-y-2">
          <Label htmlFor="enablePushNotifications">Enable Push Notifications</Label>
          <Select
            value={formData.enablePushNotifications ? 'true' : 'false'}
            onValueChange={(value: string) => setFormData({ ...formData, enablePushNotifications: value === 'true' })}
          >
            <SelectTrigger>
              <SelectValue placeholder="Select push notification preference" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="true">Enabled</SelectItem>
              <SelectItem value="false">Disabled</SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>

      <Button type="submit">Save Changes</Button>
    </form>
  );
} 