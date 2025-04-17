import { View, Text, FlatList, TouchableOpacity, StyleSheet } from 'react-native';
import { useState } from 'react';
import { router } from 'expo-router';

interface PhysioProgram {
  id: string;
  name: string;
  description: string;
  durationWeeks: number;
  type: string;
}

// TODO: Replace with actual data from Convex
const MOCK_PROGRAMS: PhysioProgram[] = [
  {
    id: '1',
    name: 'Back Pain Relief',
    description: 'A comprehensive program to help alleviate back pain and improve mobility',
    durationWeeks: 6,
    type: 'Rehabilitation',
  },
  {
    id: '2',
    name: 'Post-Surgery Recovery',
    description: 'Structured exercises to aid recovery after common surgeries',
    durationWeeks: 8,
    type: 'Recovery',
  },
];

export default function PhysioScreen() {
  const [programs, setPrograms] = useState<PhysioProgram[]>(MOCK_PROGRAMS);

  const renderProgramItem = ({ item }: { item: PhysioProgram }) => (
    <TouchableOpacity
      style={styles.programCard}
      onPress={() => router.push(`/(tabs)/physio-details/${item.id}`)}
    >
      <Text style={styles.programName}>{item.name}</Text>
      <Text style={styles.programDescription}>{item.description}</Text>
      <View style={styles.programMeta}>
        <Text style={styles.programMetaText}>{item.durationWeeks} weeks</Text>
        <Text style={styles.programMetaText}>{item.type}</Text>
      </View>
    </TouchableOpacity>
  );

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Physiotherapy Programs</Text>
      <FlatList
        data={programs}
        renderItem={renderProgramItem}
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
  programCard: {
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
  programName: {
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 8,
  },
  programDescription: {
    fontSize: 14,
    color: '#666',
    marginBottom: 12,
  },
  programMeta: {
    flexDirection: 'row',
    justifyContent: 'space-between',
  },
  programMetaText: {
    fontSize: 12,
    color: '#007AFF',
  },
}); 