import { View, Text, FlatList, TouchableOpacity, StyleSheet } from 'react-native';
import { useState, useEffect } from 'react';
import { router } from 'expo-router';

interface Workout {
  id: string;
  name: string;
  description: string;
  weeks: number;
  level: string;
}

// TODO: Replace with actual data from Convex
const MOCK_WORKOUTS: Workout[] = [
  {
    id: '1',
    name: 'Beginner Full Body',
    description: 'Perfect for beginners, this routine focuses on full body movements',
    weeks: 4,
    level: 'Beginner',
  },
  {
    id: '2',
    name: 'Advanced Strength',
    description: 'Build strength with this challenging routine',
    weeks: 8,
    level: 'Advanced',
  },
];

export default function WorkoutsScreen() {
  const [workouts, setWorkouts] = useState<Workout[]>(MOCK_WORKOUTS);

  const renderWorkoutItem = ({ item }: { item: Workout }) => (
    <TouchableOpacity
      style={styles.workoutCard}
      onPress={() => router.push(`/(tabs)/workout-details/${item.id}`)}
    >
      <Text style={styles.workoutName}>{item.name}</Text>
      <Text style={styles.workoutDescription}>{item.description}</Text>
      <View style={styles.workoutMeta}>
        <Text style={styles.workoutMetaText}>{item.weeks} weeks</Text>
        <Text style={styles.workoutMetaText}>{item.level}</Text>
      </View>
    </TouchableOpacity>
  );

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Available Workouts</Text>
      <FlatList
        data={workouts}
        renderItem={renderWorkoutItem}
        keyExtractor={(item) => item.id}
        contentContainerStyle={styles.listContainer}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    padding: 16,
  },
  listContainer: {
    padding: 16,
  },
  workoutCard: {
    backgroundColor: '#f8f8f8',
    borderRadius: 8,
    padding: 16,
    marginBottom: 16,
    shadowColor: '#000',
    shadowOffset: {
      width: 0,
      height: 2,
    },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  workoutName: {
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 8,
  },
  workoutDescription: {
    fontSize: 14,
    color: '#666',
    marginBottom: 12,
  },
  workoutMeta: {
    flexDirection: 'row',
    justifyContent: 'space-between',
  },
  workoutMetaText: {
    fontSize: 12,
    color: '#007AFF',
  },
}); 