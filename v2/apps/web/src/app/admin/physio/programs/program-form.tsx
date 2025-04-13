'use client';

import React from 'react';
import { useRouter } from 'next/navigation';
import { useMutation, useQuery } from 'convex/react';
import { api } from '../../../../../convex/_generated/api';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { toast } from 'sonner';

interface ProgramFormProps {
  programId?: string;
}

export function ProgramForm({ programId }: ProgramFormProps) {
  const router = useRouter();
  const [name, setName] = React.useState('');
  const [description, setDescription] = React.useState('');
  const [weeks, setWeeks] = React.useState('');
  const [isLoading, setIsLoading] = React.useState(false);

  const program = useQuery(
    api.physio.getProgram,
    programId ? { id: programId } : undefined
  );

  const createProgram = useMutation(api.physio.createProgram);
  const updateProgram = useMutation(api.physio.updateProgram);

  React.useEffect(() => {
    if (program) {
      setName(program.name);
      setDescription(program.description || '');
      setWeeks(program.weeks.toString());
    }
  }, [program]);

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setIsLoading(true);

    try {
      if (programId) {
        await updateProgram({
          id: programId,
          name,
          description,
          weeks: parseInt(weeks),
        });
        toast.success('Program updated successfully');
      } else {
        await createProgram({
          name,
          description,
          weeks: parseInt(weeks),
        });
        toast.success('Program created successfully');
      }
      router.push('/admin/physio/programs');
    } catch (error) {
      toast.error('Failed to save program');
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      <div className="space-y-2">
        <Label htmlFor="name">Program Name</Label>
        <Input
          id="name"
          value={name}
          onChange={(e: React.ChangeEvent<HTMLInputElement>) => setName(e.target.value)}
          required
        />
      </div>

      <div className="space-y-2">
        <Label htmlFor="description">Description</Label>
        <Textarea
          id="description"
          value={description}
          onChange={(e: React.ChangeEvent<HTMLTextAreaElement>) => setDescription(e.target.value)}
        />
      </div>

      <div className="space-y-2">
        <Label htmlFor="weeks">Number of Weeks</Label>
        <Input
          id="weeks"
          type="number"
          min="1"
          value={weeks}
          onChange={(e: React.ChangeEvent<HTMLInputElement>) => setWeeks(e.target.value)}
          required
        />
      </div>

      <div className="flex justify-end space-x-4">
        <Button
          type="button"
          variant="outline"
          onClick={() => router.push('/admin/physio/programs')}
        >
          Cancel
        </Button>
        <Button type="submit" disabled={isLoading}>
          {isLoading ? 'Saving...' : programId ? 'Update Program' : 'Create Program'}
        </Button>
      </div>
    </form>
  );
} 