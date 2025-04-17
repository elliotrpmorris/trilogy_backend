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
import { Id } from '../../../../../convex/_generated/dataModel';

interface ProgramFormProps {
  programId?: Id<"physioPrograms">;
}

export function ProgramForm({ programId }: ProgramFormProps) {
  const router = useRouter();
  const [name, setName] = React.useState('');
  const [description, setDescription] = React.useState('');
  const [durationWeeks, setDurationWeeks] = React.useState('');
  const [isLoading, setIsLoading] = React.useState(false);

  const program = useQuery(
    api.physio.getProgram,
    programId ? { id: programId } : "skip"
  );

  const createProgram = useMutation(api.physio.createProgram);
  const updateProgram = useMutation(api.physio.updateProgram);

  React.useEffect(() => {
    if (program) {
      setName(program.name);
      setDescription(program.description || '');
      setDurationWeeks(program.durationWeeks.toString());
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
          durationWeeks: parseInt(durationWeeks),
        });
      } else {
        await createProgram({
          name,
          description,
          durationWeeks: parseInt(durationWeeks),
        });
      }
      toast.success(programId ? 'Program updated successfully' : 'Program created successfully');
      router.push('/admin/physio/programs');
    } catch (error) {
      toast.error('Failed to save program');
      console.error(error);
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      <div className="space-y-2">
        <Label htmlFor="name">Program Name</Label>
        <Input
          id="name"
          value={name}
          onChange={(e) => setName(e.target.value)}
          required
        />
      </div>
      <div className="space-y-2">
        <Label htmlFor="description">Description</Label>
        <Textarea
          id="description"
          value={description}
          onChange={(e) => setDescription(e.target.value)}
          required
        />
      </div>
      <div className="space-y-2">
        <Label htmlFor="durationWeeks">Duration (Weeks)</Label>
        <Input
          id="durationWeeks"
          type="number"
          value={durationWeeks}
          onChange={(e) => setDurationWeeks(e.target.value)}
          required
          min="1"
        />
      </div>
      <Button type="submit" disabled={isLoading}>
        {isLoading ? 'Saving...' : programId ? 'Update Program' : 'Create Program'}
      </Button>
    </form>
  );
} 