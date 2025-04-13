'use client';

import React from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import ProgramForm from '../program-form';

export default function NewProgramPage() {
  return (
    <div className="container mx-auto py-6">
      <Card>
        <CardHeader>
          <CardTitle>Create New Physio Program</CardTitle>
        </CardHeader>
        <CardContent>
          <ProgramForm />
        </CardContent>
      </Card>
    </div>
  );
} 