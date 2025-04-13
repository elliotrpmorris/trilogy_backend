'use client';

import React from 'react';
import { useMutation } from 'convex/react';
import { api } from '../../../../../convex/_generated/api';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { toast } from 'sonner';

export default function SeedPage() {
  const seedDatabase = useMutation(api.seed.seedDatabase);

  const handleSeed = async () => {
    try {
      const result = await seedDatabase();
      toast({
        title: 'Success',
        description: result,
      });
    } catch (error) {
      toast({
        title: 'Error',
        description: 'Failed to seed database. Please try again.',
        variant: 'destructive',
      });
    }
  };

  return (
    <div className="container mx-auto py-6">
      <Card>
        <CardHeader>
          <CardTitle>Development Tools</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="space-y-4">
            <p className="text-gray-600">
              This page contains development tools for managing the database. Use with caution in production.
            </p>
            <div className="space-y-2">
              <h3 className="font-semibold">Database Seeding</h3>
              <p className="text-sm text-gray-500">
                Seed the database with sample data for development and testing.
              </p>
              <Button onClick={handleSeed} variant="destructive">
                Seed Database
              </Button>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  );
} 