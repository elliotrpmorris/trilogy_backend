'use client';

import React from 'react';
import { useMutation } from 'convex/react';
import { api } from '../../../../../../convex/_generated/api';
import { ImageUpload } from '@/components/ui/image-upload';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import { toast } from 'sonner';
import { useRouter } from 'next/navigation';

export default function NewExercisePage() {
  const router = useRouter();
  const createExercise = useMutation(api.physio.createExercise);

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const formData = new FormData(e.currentTarget);
    
    try {
      await createExercise({
        title: formData.get('title') as string,
        description: formData.get('description') as string,
        instructions: formData.get('instructions') as string,
        image: formData.get('image') as string,
        video_url: formData.get('video_url') as string,
        program_name: formData.get('program_name') as string,
        week_no: parseInt(formData.get('week_no') as string),
        rep: parseInt(formData.get('rep') as string),
        sets: parseInt(formData.get('sets') as string),
        worktime: parseInt(formData.get('worktime') as string),
        equipments: formData.get('equipments') as string,
        musclegroup: formData.get('musclegroup') as string,
        status: formData.get('status') as 'Y' | 'N',
      });
      
      toast.success('Exercise created successfully');
      router.push('/admin/physio/exercises');
    } catch (error) {
      toast.error('Failed to create exercise');
    }
  };

  return (
    <div className="container mx-auto py-10">
      <h1 className="text-3xl font-bold mb-6">New Exercise</h1>
      
      <form onSubmit={handleSubmit} className="space-y-6">
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div className="space-y-2">
            <Label htmlFor="title">Title</Label>
            <Input
              id="title"
              name="title"
              required
            />
          </div>

          <div className="space-y-2">
            <Label htmlFor="program_name">Program Name</Label>
            <Input
              id="program_name"
              name="program_name"
              required
            />
          </div>

          <div className="space-y-2">
            <Label htmlFor="week_no">Week Number</Label>
            <Input
              id="week_no"
              name="week_no"
              type="number"
              required
            />
          </div>

          <div className="space-y-2">
            <Label htmlFor="image">Image</Label>
            <ImageUpload
              onChange={(url) => {
                const imageInput = document.getElementById('image') as HTMLInputElement;
                if (imageInput) {
                  imageInput.value = url;
                }
              }}
              onRemove={() => {
                const imageInput = document.getElementById('image') as HTMLInputElement;
                if (imageInput) {
                  imageInput.value = '';
                }
              }}
            />
            <Input
              id="image"
              name="image"
              type="hidden"
            />
          </div>

          <div className="space-y-2">
            <Label htmlFor="video_url">Video URL</Label>
            <Input
              id="video_url"
              name="video_url"
            />
          </div>

          <div className="space-y-2">
            <Label htmlFor="rep">Repetitions</Label>
            <Input
              id="rep"
              name="rep"
              type="number"
            />
          </div>

          <div className="space-y-2">
            <Label htmlFor="sets">Sets</Label>
            <Input
              id="sets"
              name="sets"
              type="number"
            />
          </div>

          <div className="space-y-2">
            <Label htmlFor="worktime">Work Time (minutes)</Label>
            <Input
              id="worktime"
              name="worktime"
              type="number"
            />
          </div>

          <div className="space-y-2">
            <Label htmlFor="equipments">Equipment</Label>
            <Input
              id="equipments"
              name="equipments"
            />
          </div>

          <div className="space-y-2">
            <Label htmlFor="musclegroup">Muscle Group</Label>
            <Input
              id="musclegroup"
              name="musclegroup"
            />
          </div>
        </div>

        <div className="space-y-2">
          <Label htmlFor="description">Description</Label>
          <Textarea
            id="description"
            name="description"
            rows={4}
          />
        </div>

        <div className="space-y-2">
          <Label htmlFor="instructions">Instructions</Label>
          <Textarea
            id="instructions"
            name="instructions"
            rows={4}
          />
        </div>

        <div className="space-y-2">
          <Label htmlFor="status">Status</Label>
          <select
            id="status"
            name="status"
            defaultValue="Y"
            className="w-full p-2 border rounded"
          >
            <option value="Y">Active</option>
            <option value="N">Inactive</option>
          </select>
        </div>

        <div className="flex justify-end space-x-4">
          <Button
            type="button"
            variant="outline"
            onClick={() => router.push('/admin/physio/exercises')}
          >
            Cancel
          </Button>
          <Button type="submit">Create Exercise</Button>
        </div>
      </form>
    </div>
  );
} 