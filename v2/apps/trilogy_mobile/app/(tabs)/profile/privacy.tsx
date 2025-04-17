import { View, Text, TouchableOpacity, StyleSheet, ScrollView, Switch } from 'react-native';
import { useState } from 'react';
import { router } from 'expo-router';

export default function PrivacyScreen() {
  const [profileVisibility, setProfileVisibility] = useState(true);
  const [activitySharing, setActivitySharing] = useState(true);
  const [dataCollection, setDataCollection] = useState(true);
  const [personalizedAds, setPersonalizedAds] = useState(false);
  const [locationTracking, setLocationTracking] = useState(false);

  const SettingItem = ({ 
    title, 
    description, 
    value, 
    onValueChange
  }: {
    title: string;
    description?: string;
    value: boolean;
    onValueChange: (value: boolean) => void;
  }) => (
    <View style={styles.settingItem}>
      <View style={styles.settingTextContainer}>
        <Text style={styles.settingTitle}>{title}</Text>
        {description && <Text style={styles.settingDescription}>{description}</Text>}
      </View>
      <Switch
        value={value}
        onValueChange={onValueChange}
        trackColor={{ false: '#767577', true: '#81b0ff' }}
        thumbColor={value ? '#007AFF' : '#f4f3f4'}
      />
    </View>
  );

  return (
    <ScrollView style={styles.container}>
      <View style={styles.section}>
        <Text style={styles.sectionTitle}>Profile Privacy</Text>
        <SettingItem
          title="Public Profile"
          description="Make your profile visible to other users"
          value={profileVisibility}
          onValueChange={setProfileVisibility}
        />
        <SettingItem
          title="Activity Sharing"
          description="Share your workout progress with friends"
          value={activitySharing}
          onValueChange={setActivitySharing}
        />
      </View>

      <View style={styles.section}>
        <Text style={styles.sectionTitle}>Data & Privacy</Text>
        <SettingItem
          title="Data Collection"
          description="Allow collection of usage data to improve the app"
          value={dataCollection}
          onValueChange={setDataCollection}
        />
        <SettingItem
          title="Personalized Ads"
          description="Show personalized advertisements"
          value={personalizedAds}
          onValueChange={setPersonalizedAds}
        />
        <SettingItem
          title="Location Tracking"
          description="Allow the app to track your location for nearby features"
          value={locationTracking}
          onValueChange={setLocationTracking}
        />
      </View>

      <View style={styles.section}>
        <Text style={styles.sectionTitle}>Data Management</Text>
        <TouchableOpacity style={styles.button}>
          <Text style={styles.buttonText}>Download My Data</Text>
        </TouchableOpacity>
        <TouchableOpacity style={styles.button}>
          <Text style={styles.buttonText}>Delete My Data</Text>
        </TouchableOpacity>
      </View>

      <View style={styles.section}>
        <Text style={styles.sectionTitle}>Contact Us</Text>
        <TouchableOpacity style={styles.button}>
          <Text style={styles.buttonText}>Privacy Questions</Text>
        </TouchableOpacity>
        <TouchableOpacity style={styles.button}>
          <Text style={styles.buttonText}>Report a Concern</Text>
        </TouchableOpacity>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
  section: {
    padding: 16,
    borderBottomWidth: 1,
    borderBottomColor: '#eee',
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 16,
    color: '#333',
  },
  settingItem: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#eee',
  },
  settingTextContainer: {
    flex: 1,
    marginRight: 16,
  },
  settingTitle: {
    fontSize: 16,
    color: '#333',
  },
  settingDescription: {
    fontSize: 14,
    color: '#666',
    marginTop: 4,
  },
  button: {
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#eee',
  },
  buttonText: {
    color: '#007AFF',
    fontSize: 16,
  },
}); 