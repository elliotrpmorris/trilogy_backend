'use client';

import React from 'react';
import { useQuery, useMutation } from 'convex/react';
import { api } from '../../../../convex/_generated/api';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { toast } from 'sonner';

export function GeneralSettings() {
  const settings = useQuery(api.settings.getGeneralSettings);
  const updateSettings = useMutation(api.settings.updateGeneralSettings);

  const [formData, setFormData] = React.useState({
    siteName: settings?.siteName || '',
    siteDescription: settings?.siteDescription || '',
    maintenanceMode: settings?.maintenanceMode || false,
    allowUserRegistration: settings?.allowUserRegistration || true,
  });

  React.useEffect(() => {
    if (settings) {
      setFormData({
        siteName: settings.siteName,
        siteDescription: settings.siteDescription,
        maintenanceMode: settings.maintenanceMode,
        allowUserRegistration: settings.allowUserRegistration,
      });
    }
  }, [settings]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      await updateSettings(formData);
      toast({
        title: 'Settings updated',
        description: 'Your general settings have been saved successfully.',
      });
    } catch (error) {
      toast({
        title: 'Error',
        description: 'Failed to update settings. Please try again.',
        variant: 'destructive',
      });
    }
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      <div className="space-y-4">
        <div className="space-y-2">
          <Label htmlFor="siteName">Site Name</Label>
          <Input
            id="siteName"
            value={formData.siteName}
            onChange={(e) => setFormData({ ...formData, siteName: e.target.value })}
          />
        </div>

        <div className="space-y-2">
          <Label htmlFor="siteDescription">Site Description</Label>
          <Input
            id="siteDescription"
            value={formData.siteDescription}
            onChange={(e) => setFormData({ ...formData, siteDescription: e.target.value })}
          />
        </div>

        <div className="flex items-center justify-between">
          <Label htmlFor="maintenanceMode">Maintenance Mode</Label>
          <Switch
            id="maintenanceMode"
            checked={formData.maintenanceMode}
            onCheckedChange={(checked) => setFormData({ ...formData, maintenanceMode: checked })}
          />
        </div>

        <div className="flex items-center justify-between">
          <Label htmlFor="allowUserRegistration">Allow User Registration</Label>
          <Switch
            id="allowUserRegistration"
            checked={formData.allowUserRegistration}
            onCheckedChange={(checked) => setFormData({ ...formData, allowUserRegistration: checked })}
          />
        </div>
      </div>

      <Button type="submit">Save Changes</Button>
    </form>
  );
} 