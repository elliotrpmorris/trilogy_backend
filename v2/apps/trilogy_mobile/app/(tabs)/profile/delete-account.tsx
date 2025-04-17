import { View, Text, TouchableOpacity, StyleSheet, Alert } from 'react-native';
import { useState } from 'react';
import { router } from 'expo-router';

export default function DeleteAccountScreen() {
  const [isConfirmed, setIsConfirmed] = useState(false);

  const handleDeleteAccount = async () => {
    if (!isConfirmed) {
      Alert.alert(
        'Confirmation Required',
        'Please confirm that you understand the consequences of deleting your account.',
        [{ text: 'OK' }]
      );
      return;
    }

    Alert.alert(
      'Delete Account',
      'Are you sure you want to delete your account? This action cannot be undone.',
      [
        {
          text: 'Cancel',
          style: 'cancel',
        },
        {
          text: 'Delete',
          style: 'destructive',
          onPress: async () => {
            // TODO: Implement account deletion logic with Convex
            console.log('Deleting account...');
            router.replace('/(auth)/login');
          },
        },
      ]
    );
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Delete Account</Text>
      
      <View style={styles.warningContainer}>
        <Text style={styles.warningTitle}>Warning</Text>
        <Text style={styles.warningText}>
          Deleting your account will permanently remove all your data, including:
        </Text>
        <View style={styles.bulletPoints}>
          <Text style={styles.bulletPoint}>• Your profile information</Text>
          <Text style={styles.bulletPoint}>• Workout history and progress</Text>
          <Text style={styles.bulletPoint}>• Saved exercises and routines</Text>
          <Text style={styles.bulletPoint}>• All personal settings</Text>
        </View>
        <Text style={styles.warningText}>
          This action cannot be undone. Please make sure you want to proceed.
        </Text>
      </View>

      <View style={styles.confirmationContainer}>
        <TouchableOpacity
          style={[styles.checkbox, isConfirmed && styles.checkboxChecked]}
          onPress={() => setIsConfirmed(!isConfirmed)}
        >
          <Text style={styles.checkboxText}>
            I understand that this action is permanent and cannot be undone
          </Text>
        </TouchableOpacity>
      </View>

      <TouchableOpacity
        style={[styles.deleteButton, !isConfirmed && styles.deleteButtonDisabled]}
        onPress={handleDeleteAccount}
        disabled={!isConfirmed}
      >
        <Text style={styles.deleteButtonText}>Delete My Account</Text>
      </TouchableOpacity>

      <TouchableOpacity style={styles.cancelButton} onPress={() => router.back()}>
        <Text style={styles.cancelButtonText}>Cancel</Text>
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 20,
    backgroundColor: '#fff',
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    marginBottom: 32,
    textAlign: 'center',
  },
  warningContainer: {
    backgroundColor: '#fff3f3',
    padding: 16,
    borderRadius: 8,
    marginBottom: 24,
  },
  warningTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#ff3b30',
    marginBottom: 8,
  },
  warningText: {
    fontSize: 16,
    color: '#666',
    marginBottom: 12,
  },
  bulletPoints: {
    marginLeft: 16,
    marginBottom: 12,
  },
  bulletPoint: {
    fontSize: 16,
    color: '#666',
    marginBottom: 4,
  },
  confirmationContainer: {
    marginBottom: 24,
  },
  checkbox: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 12,
    borderWidth: 1,
    borderColor: '#ddd',
    borderRadius: 8,
  },
  checkboxChecked: {
    borderColor: '#007AFF',
    backgroundColor: '#f0f7ff',
  },
  checkboxText: {
    fontSize: 16,
    color: '#333',
    marginLeft: 8,
  },
  deleteButton: {
    backgroundColor: '#ff3b30',
    height: 50,
    borderRadius: 8,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 16,
  },
  deleteButtonDisabled: {
    opacity: 0.5,
  },
  deleteButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
  cancelButton: {
    height: 50,
    borderRadius: 8,
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#ddd',
  },
  cancelButtonText: {
    color: '#666',
    fontSize: 16,
  },
}); 