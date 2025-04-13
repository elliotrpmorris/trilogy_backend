'use client';

import React from 'react';
import {
  PieChart,
  Pie,
  BarChart,
  Bar,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  Legend,
  ResponsiveContainer,
  Cell,
} from 'recharts';

interface UsageData {
  date: string;
  workoutCompletions: number;
  mealPlanCompletions: number;
  workoutTypes: {
    name: string;
    count: number;
  }[];
  mealTypes: {
    name: string;
    count: number;
  }[];
}

interface UsageStatisticsProps {
  data: UsageData[];
}

const COLORS = ['#0088FE', '#00C49F', '#FFBB28', '#FF8042', '#8884d8'];

export function UsageStatistics({ data }: UsageStatisticsProps) {
  const latestData = data[data.length - 1];

  return (
    <div className="space-y-8">
      <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div className="h-[400px]">
          <h3 className="text-lg font-semibold mb-4">Workout Type Distribution</h3>
          <ResponsiveContainer width="100%" height="100%">
            <PieChart>
              <Pie
                data={latestData?.workoutTypes || []}
                cx="50%"
                cy="50%"
                labelLine={false}
                label={({ name, percent }) => `${name} ${(percent * 100).toFixed(0)}%`}
                outerRadius={150}
                fill="#8884d8"
                dataKey="count"
              >
                {latestData?.workoutTypes.map((entry, index) => (
                  <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                ))}
              </Pie>
              <Tooltip />
              <Legend />
            </PieChart>
          </ResponsiveContainer>
        </div>

        <div className="h-[400px]">
          <h3 className="text-lg font-semibold mb-4">Meal Type Distribution</h3>
          <ResponsiveContainer width="100%" height="100%">
            <PieChart>
              <Pie
                data={latestData?.mealTypes || []}
                cx="50%"
                cy="50%"
                labelLine={false}
                label={({ name, percent }) => `${name} ${(percent * 100).toFixed(0)}%`}
                outerRadius={150}
                fill="#8884d8"
                dataKey="count"
              >
                {latestData?.mealTypes.map((entry, index) => (
                  <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                ))}
              </Pie>
              <Tooltip />
              <Legend />
            </PieChart>
          </ResponsiveContainer>
        </div>
      </div>

      <div className="h-[400px]">
        <h3 className="text-lg font-semibold mb-4">Completion Trends</h3>
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
              dataKey="workoutCompletions"
              fill="#8884d8"
              name="Workout Completions"
            />
            <Bar
              dataKey="mealPlanCompletions"
              fill="#82ca9d"
              name="Meal Plan Completions"
            />
          </BarChart>
        </ResponsiveContainer>
      </div>
    </div>
  );
} 