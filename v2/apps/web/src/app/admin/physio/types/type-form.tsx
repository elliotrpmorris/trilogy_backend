'use client';

import React, { useState, useEffect } from 'react';
import { useQuery, useMutation } from 'convex/react';
import { api } from '../../../../../convex/_generated/api';
import { useRouter } from 'next/navigation';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { toast } from 'sonner';

interface TypeFormProps {
  typeId?: string;
}

export default function TypeForm({ typeId }: TypeFormProps) {
  const router = useRouter();
  const [isLoading, setIsLoading] = useState(false);
  const [formData, setFormData] = useState({
    physio_type: '',
    status: 'Y' as 'Y' | 'N',
  });

  const workoutType = useQuery(
    typeId ? api.physio.getWorkoutType : undefined,
    typeId ? { id: typeId } : undefined
  );

  const createWorkoutType = useMutation(api.physio.createWorkoutType);
  const updateWorkoutType = useMutation(api.physio.updateWorkoutType);

  useEffect(() => {
    if (workoutType) {
      setFormData({
        physio_type: workoutType.physio_type,
        status: workoutType.status,
      });
    }
  }, [workoutType]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);

    try {
      if (typeId) {
        await updateWorkoutType({
          id: typeId,
          ...formData,
        });
        toast.success('Workout type updated successfully');
      } else {
        await createWorkoutType(formData);
        toast.success('Workout type created successfully');
      }
      router.push('/admin/physio/types');
    } catch (error) {
      toast.error('Failed to save workout type');
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div className="space-y-2">
          <Label htmlFor="physio_type">Type Name</Label>
          <Input
            id="physio_type"
            value={formData.physio_type}
            onChange={(e) => setFormData({ ...formData, physio_type: e.target.value })}
            required
          />
        </div>

        <div className="space-y-2">
          <Label htmlFor="status">Status</Label>
          <select
            id="status"
            value={formData.status}
            onChange={(e) => setFormData({ ...formData, status: e.target.value as 'Y' | 'N' })}
            className="w-full p-2 border rounded"
            required
          >
            <option value="Y">Active</option>
            <option value="N">Inactive</option>
          </select>
        </div>
      </div>

      <div className="flex justify-end space-x-4">
        <Button
          type="button"
          variant="outline"
          onClick={() => router.push('/admin/physio/types')}
        >
          Cancel
        </Button>
        <Button type="submit" disabled={isLoading}>
          {isLoading ? 'Saving...' : typeId ? 'Update Type' : 'Create Type'}
        </Button>
      </div>
    </form>
  );
} 