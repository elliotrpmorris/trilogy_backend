'use client';

import React from 'react';
import {
  BarChart,
  Bar,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  Legend,
  ResponsiveContainer,
} from 'recharts';

interface EngagementData {
  date: string;
  activeUsers: number;
  avgSessionDuration: number;
  pageViews: number;
  bounceRate: number;
}

interface EngagementMetricsProps {
  data: EngagementData[];
}

export function EngagementMetrics({ data }: EngagementMetricsProps) {
  return (
    <div className="space-y-8">
      <div className="h-[400px]">
        <ResponsiveContainer width="100%" height="100%">
          <BarChart
            data={data}
            margin={{
              top: 5,
              right: 30,
              left: 20,
              bottom: 5,
            }}
          >
            <CartesianGrid strokeDasharray="3 3" />
            <XAxis dataKey="date" />
            <YAxis />
            <Tooltip />
            <Legend />
            <Bar dataKey="activeUsers" fill="#8884d8" name="Active Users" />
            <Bar dataKey="pageViews" fill="#82ca9d" name="Page Views" />
          </BarChart>
        </ResponsiveContainer>
      </div>

      <div className="h-[400px]">
        <ResponsiveContainer width="100%" height="100%">
          <BarChart
            data={data}
            margin={{
              top: 5,
              right: 30,
              left: 20,
              bottom: 5,
            }}
          >
            <CartesianGrid strokeDasharray="3 3" />
            <XAxis dataKey="date" />
            <YAxis />
            <Tooltip />
            <Legend />
            <Bar
              dataKey="avgSessionDuration"
              fill="#ffc658"
              name="Avg Session Duration (min)"
            />
            <Bar
              dataKey="bounceRate"
              fill="#ff8042"
              name="Bounce Rate (%)"
            />
          </BarChart>
        </ResponsiveContainer>
      </div>
    </div>
  );
} 