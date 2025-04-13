'use client';

import React from 'react';
import {
  LineChart,
  Line,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  Legend,
  ResponsiveContainer,
} from 'recharts';

interface UserGrowthData {
  date: string;
  totalUsers: number;
  newUsers: number;
}

interface UserGrowthChartProps {
  data: UserGrowthData[];
}

export function UserGrowthChart({ data }: UserGrowthChartProps) {
  return (
    <div className="h-[400px]">
      <ResponsiveContainer width="100%" height="100%">
        <LineChart
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
          <Line
            type="monotone"
            dataKey="totalUsers"
            stroke="#8884d8"
            name="Total Users"
            activeDot={{ r: 8 }}
          />
          <Line
            type="monotone"
            dataKey="newUsers"
            stroke="#82ca9d"
            name="New Users"
          />
        </LineChart>
      </ResponsiveContainer>
    </div>
  );
} 