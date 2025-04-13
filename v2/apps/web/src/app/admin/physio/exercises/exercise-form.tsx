'use client';

import React, { useState, useEffect } from 'react';
import { useQuery, useMutation } from 'convex/react';
import { api } from '../../../../../convex/_generated/api';
import { useRouter } from 'next/navigation';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import { toast } from 'sonner';

interface ExerciseFormProps {
  exerciseId?: string;
}

export default function ExerciseForm({ exerciseId }: ExerciseFormProps) {
  const router = useRouter();
  const [isLoading, setIsLoading] = useState(false);
  const [formData, setFormData] = useState({
    title: '',
    description: '',
    instructions: '',
    image: '',
    video_url: '',
    program_name: '',
    week_no: 1,
    rep: 0,
    sets: 0,
    worktime: 0,
    equipments: '',
    musclegroup: '',
    status: 'Y' as 'Y' | 'N',
  });

  const exercise = useQuery(
    exerciseId ? api.physio.getExercise : undefined,
    exerciseId ? { id: exerciseId } : undefined
  );

  const programs = useQuery(api.physio.listPrograms);
  const createExercise = useMutation(api.physio.createExercise);
  const updateExercise = useMutation(api.physio.updateExercise);

  useEffect(() => {
    if (exercise) {
      setFormData({
        title: exercise.title,
        description: exercise.description || '',
        instructions: exercise.instructions || '',
        image: exercise.image || '',
        video_url: exercise.video_url || '',
        program_name: exercise.program_name,
        week_no: exercise.week_no,
        rep: exercise.rep || 0,
        sets: exercise.sets || 0,
        worktime: exercise.worktime || 0,
        equipments: exercise.equipments || '',
        musclegroup: exercise.musclegroup || '',
        status: exercise.status,
      });
    }
  }, [exercise]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);

    try {
      if (exerciseId) {
        await updateExercise({
          id: exerciseId,
          ...formData,
        });
        toast.success('Exercise updated successfully');
      } else {
        await createExercise(formData);
        toast.success('Exercise created successfully');
      }
      router.push('/admin/physio/exercises');
    } catch (error) {
      toast.error('Failed to save exercise');
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div className="space-y-2">
          <Label htmlFor="title">Title</Label>
          <Input
            id="title"
            value={formData.title}
            onChange={(e) => setFormData({ ...formData, title: e.target.value })}
            required
          />
        </div>

        <div className="space-y-2">
          <Label htmlFor="program_name">Program</Label>
          <select
            id="program_name"
            value={formData.program_name}
            onChange={(e) => setFormData({ ...formData, program_name: e.target.value })}
            className="w-full p-2 border rounded"
            required
          >
            <option value="">Select a program</option>
            {programs?.map((program) => (
              <option key={program._id} value={program.name}>
                {program.name}
              </option>
            ))}
          </select>
        </div>

        <div className="space-y-2">
          <Label htmlFor="week_no">Week Number</Label>
          <Input
            id="week_no"
            type="number"
            value={formData.week_no}
            onChange={(e) => setFormData({ ...formData, week_no: parseInt(e.target.value) })}
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

        <div className="space-y-2">
          <Label htmlFor="rep">Repetitions</Label>
          <Input
            id="rep"
            type="number"
            value={formData.rep}
            onChange={(e) => setFormData({ ...formData, rep: parseInt(e.target.value) })}
          />
        </div>

        <div className="space-y-2">
          <Label htmlFor="sets">Sets</Label>
          <Input
            id="sets"
            type="number"
            value={formData.sets}
            onChange={(e) => setFormData({ ...formData, sets: parseInt(e.target.value) })}
          />
        </div>

        <div className="space-y-2">
          <Label htmlFor="worktime">Duration (minutes)</Label>
          <Input
            id="worktime"
            type="number"
            value={formData.worktime}
            onChange={(e) => setFormData({ ...formData, worktime: parseInt(e.target.value) })}
          />
        </div>

        <div className="space-y-2">
          <Label htmlFor="equipments">Equipment</Label>
          <Input
            id="equipments"
            value={formData.equipments}
            onChange={(e) => setFormData({ ...formData, equipments: e.target.value })}
          />
        </div>

        <div className="space-y-2">
          <Label htmlFor="musclegroup">Muscle Group</Label>
          <Input
            id="musclegroup"
            value={formData.musclegroup}
            onChange={(e) => setFormData({ ...formData, musclegroup: e.target.value })}
          />
        </div>

        <div className="space-y-2">
          <Label htmlFor="image">Image URL</Label>
          <Input
            id="image"
            value={formData.image}
            onChange={(e) => setFormData({ ...formData, image: e.target.value })}
          />
        </div>

        <div className="space-y-2">
          <Label htmlFor="video_url">Video URL</Label>
          <Input
            id="video_url"
            value={formData.video_url}
            onChange={(e) => setFormData({ ...formData, video_url: e.target.value })}
          />
        </div>
      </div>

      <div className="space-y-2">
        <Label htmlFor="description">Description</Label>
        <Textarea
          id="description"
          value={formData.description}
          onChange={(e) => setFormData({ ...formData, description: e.target.value })}
        />
      </div>

      <div className="space-y-2">
        <Label htmlFor="instructions">Instructions</Label>
        <Textarea
          id="instructions"
          value={formData.instructions}
          onChange={(e) => setFormData({ ...formData, instructions: e.target.value })}
        />
      </div>

      <div className="flex justify-end space-x-4">
        <Button
          type="button"
          variant="outline"
          onClick={() => router.push('/admin/physio/exercises')}
        >
          Cancel
        </Button>
        <Button type="submit" disabled={isLoading}>
          {isLoading ? 'Saving...' : exerciseId ? 'Update Exercise' : 'Create Exercise'}
        </Button>
      </div>
    </form>
  );
} 