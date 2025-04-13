'use client';

import React from 'react';
import { useQuery, useMutation } from 'convex/react';
import { api } from '../../../../convex/_generated/api';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { toast } from 'sonner';

export function SystemConfig() {
  const config = useQuery(api.settings.getSystemConfig);
  const updateConfig = useMutation(api.settings.updateSystemConfig);

  const [formData, setFormData] = React.useState({
    apiRateLimit: config?.apiRateLimit || 100,
    sessionTimeout: config?.sessionTimeout || 30,
    maxUploadSize: config?.maxUploadSize || 10,
    enableCaching: config?.enableCaching || true,
    cacheDuration: config?.cacheDuration || 3600,
    enableLogging: config?.enableLogging || true,
    logLevel: config?.logLevel || 'info',
    enableBackup: config?.enableBackup || true,
    backupFrequency: config?.backupFrequency || 'daily',
  });

  React.useEffect(() => {
    if (config) {
      setFormData({
        apiRateLimit: config.apiRateLimit,
        sessionTimeout: config.sessionTimeout,
        maxUploadSize: config.maxUploadSize,
        enableCaching: config.enableCaching,
        cacheDuration: config.cacheDuration,
        enableLogging: config.enableLogging,
        logLevel: config.logLevel,
        enableBackup: config.enableBackup,
        backupFrequency: config.backupFrequency,
      });
    }
  }, [config]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      await updateConfig(formData);
      toast({
        title: 'Configuration updated',
        description: 'System configuration has been saved successfully.',
      });
    } catch (error) {
      toast({
        title: 'Error',
        description: 'Failed to update configuration. Please try again.',
        variant: 'destructive',
      });
    }
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      <div className="space-y-4">
        <div className="space-y-2">
          <Label htmlFor="apiRateLimit">API Rate Limit (requests/minute)</Label>
          <Input
            id="apiRateLimit"
            type="number"
            value={formData.apiRateLimit}
            onChange={(e) => setFormData({ ...formData, apiRateLimit: parseInt(e.target.value) })}
          />
        </div>

        <div className="space-y-2">
          <Label htmlFor="sessionTimeout">Session Timeout (minutes)</Label>
          <Input
            id="sessionTimeout"
            type="number"
            value={formData.sessionTimeout}
            onChange={(e) => setFormData({ ...formData, sessionTimeout: parseInt(e.target.value) })}
          />
        </div>

        <div className="space-y-2">
          <Label htmlFor="maxUploadSize">Maximum Upload Size (MB)</Label>
          <Input
            id="maxUploadSize"
            type="number"
            value={formData.maxUploadSize}
            onChange={(e) => setFormData({ ...formData, maxUploadSize: parseInt(e.target.value) })}
          />
        </div>

        <div className="flex items-center justify-between">
          <Label htmlFor="enableCaching">Enable Caching</Label>
          <Switch
            id="enableCaching"
            checked={formData.enableCaching}
            onCheckedChange={(checked) => setFormData({ ...formData, enableCaching: checked })}
          />
        </div>

        <div className="space-y-2">
          <Label htmlFor="cacheDuration">Cache Duration (seconds)</Label>
          <Input
            id="cacheDuration"
            type="number"
            value={formData.cacheDuration}
            onChange={(e) => setFormData({ ...formData, cacheDuration: parseInt(e.target.value) })}
          />
        </div>

        <div className="flex items-center justify-between">
          <Label htmlFor="enableLogging">Enable Logging</Label>
          <Switch
            id="enableLogging"
            checked={formData.enableLogging}
            onCheckedChange={(checked) => setFormData({ ...formData, enableLogging: checked })}
          />
        </div>

        <div className="space-y-2">
          <Label htmlFor="logLevel">Log Level</Label>
          <Select
            value={formData.logLevel}
            onValueChange={(value) => setFormData({ ...formData, logLevel: value })}
          >
            <SelectTrigger>
              <SelectValue placeholder="Select log level" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="debug">Debug</SelectItem>
              <SelectItem value="info">Info</SelectItem>
              <SelectItem value="warn">Warning</SelectItem>
              <SelectItem value="error">Error</SelectItem>
            </SelectContent>
          </Select>
        </div>

        <div className="flex items-center justify-between">
          <Label htmlFor="enableBackup">Enable Automatic Backups</Label>
          <Switch
            id="enableBackup"
            checked={formData.enableBackup}
            onCheckedChange={(checked) => setFormData({ ...formData, enableBackup: checked })}
          />
        </div>

        <div className="space-y-2">
          <Label htmlFor="backupFrequency">Backup Frequency</Label>
          <Select
            value={formData.backupFrequency}
            onValueChange={(value) => setFormData({ ...formData, backupFrequency: value })}
          >
            <SelectTrigger>
              <SelectValue placeholder="Select backup frequency" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="hourly">Hourly</SelectItem>
              <SelectItem value="daily">Daily</SelectItem>
              <SelectItem value="weekly">Weekly</SelectItem>
              <SelectItem value="monthly">Monthly</SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>

      <Button type="submit">Save Changes</Button>
    </form>
  );
} 