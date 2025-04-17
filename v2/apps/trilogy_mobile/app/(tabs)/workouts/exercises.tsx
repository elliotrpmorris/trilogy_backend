import { View, Text, FlatList, TouchableOpacity, StyleSheet } from 'react-native';
import { useState } from 'react';
import { router } from 'expo-router';

interface Exercise {
  id: string;
  name: string;
  description: string;
  muscleGroup: string;
  equipment: string;
  videoUrl?: string;
}

// TODO: Replace with actual data from Convex
const MOCK_EXERCISES: Exercise[] = [
  {
    id: '1',
    name: 'Push-ups',
    description: 'A classic bodyweight exercise that targets the chest, shoulders, and triceps',
    muscleGroup: 'Chest, Shoulders, Triceps',
    equipment: 'Bodyweight',
  },
  {
    id: '2',
    name: 'Squats',
    description: 'A fundamental lower body exercise that works the quadriceps, hamstrings, and glutes',
    muscleGroup: 'Legs',
    equipment: 'Bodyweight',
  },
];

export default function ExercisesScreen() {
  const [exercises, setExercises] = useState<Exercise[]>(MOCK_EXERCISES);

  const renderExerciseItem = ({ item }: { item: Exercise }) => (
    <TouchableOpacity
      style={styles.exerciseCard}
      onPress={() => router.push(`/(tabs)/exercise-details/${item.id}`)}
    >
      <Text style={styles.exerciseName}>{item.name}</Text>
      <Text style={styles.exerciseDescription}>{item.description}</Text>
      <View style={styles.exerciseMeta}>
        <Text style={styles.exerciseMetaText}>{item.muscleGroup}</Text>
        <Text style={styles.exerciseMetaText}>{item.equipment}</Text>
      </View>
    </TouchableOpacity>
  );

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Exercises</Text>
      <FlatList
        data={exercises}
        renderItem={renderExerciseItem}
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
  exerciseCard: {
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
  exerciseName: {
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 8,
  },
  exerciseDescription: {
    fontSize: 14,
    color: '#666',
    marginBottom: 12,
  },
  exerciseMeta: {
    flexDirection: 'row',
    justifyContent: 'space-between',
  },
  exerciseMetaText: {
    fontSize: 12,
    color: '#007AFF',
  },
}); 