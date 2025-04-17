import React from 'react';
import { View, Text, StyleSheet, ScrollView, TouchableOpacity, Switch } from 'react-native';
import { FontAwesome } from '@expo/vector-icons';
import { router } from 'expo-router';

export default function SettingsScreen() {
  const [notificationsEnabled, setNotificationsEnabled] = React.useState(true);
  const [darkModeEnabled, setDarkModeEnabled] = React.useState(false);
  const [profileVisibility, setProfileVisibility] = React.useState(true);
  const [activitySharing, setActivitySharing] = React.useState(true);
  const [dataCollection, setDataCollection] = React.useState(true);
  const [personalizedAds, setPersonalizedAds] = React.useState(false);
  const [locationTracking, setLocationTracking] = React.useState(false);

  const SettingItem = ({ 
    title, 
    description, 
    value, 
    onValueChange,
    icon,
    color = '#007AFF'
  }: {
    title: string;
    description?: string;
    value: boolean;
    onValueChange: (value: boolean) => void;
    icon: string;
    color?: string;
  }) => (
    <View style={styles.menuItem}>
      <FontAwesome name={icon} size={24} color={color} />
      <View style={styles.menuTextContainer}>
        <Text style={styles.menuText}>{title}</Text>
        {description && <Text style={styles.menuDescription}>{description}</Text>}
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
        <Text style={styles.sectionTitle}>Profile</Text>
        <View style={styles.card}>
          <View style={styles.profileHeader}>
            <View style={styles.avatar}>
              <FontAwesome name="user" size={40} color="#007AFF" />
            </View>
            <View style={styles.profileInfo}>
              <Text style={styles.profileName}>John Doe</Text>
              <Text style={styles.profileEmail}>john.doe@example.com</Text>
            </View>
          </View>
          <TouchableOpacity style={styles.button}>
            <Text style={styles.buttonText}>Edit Profile</Text>
          </TouchableOpacity>
        </View>
      </View>

      <View style={styles.section}>
        <Text style={styles.sectionTitle}>Progress</Text>
        <View style={styles.card}>
          <TouchableOpacity style={styles.menuItem}>
            <FontAwesome name="bar-chart" size={24} color="#007AFF" />
            <Text style={styles.menuText}>View Progress</Text>
            <FontAwesome name="chevron-right" size={16} color="#666" />
          </TouchableOpacity>
          <TouchableOpacity style={styles.menuItem}>
            <FontAwesome name="trophy" size={24} color="#007AFF" />
            <Text style={styles.menuText}>Achievements</Text>
            <FontAwesome name="chevron-right" size={16} color="#666" />
          </TouchableOpacity>
        </View>
      </View>

      <View style={styles.section}>
        <Text style={styles.sectionTitle}>Preferences</Text>
        <View style={styles.card}>
          <SettingItem
            title="Notifications"
            description="Receive notifications about workouts and updates"
            value={notificationsEnabled}
            onValueChange={setNotificationsEnabled}
            icon="bell"
          />
          <SettingItem
            title="Dark Mode"
            description="Switch between light and dark theme"
            value={darkModeEnabled}
            onValueChange={setDarkModeEnabled}
            icon="moon-o"
          />
        </View>
      </View>

      <View style={styles.section}>
        <Text style={styles.sectionTitle}>Privacy</Text>
        <View style={styles.card}>
          <SettingItem
            title="Public Profile"
            description="Make your profile visible to other users"
            value={profileVisibility}
            onValueChange={setProfileVisibility}
            icon="globe"
          />
          <SettingItem
            title="Activity Sharing"
            description="Share your workout progress with friends"
            value={activitySharing}
            onValueChange={setActivitySharing}
            icon="share-alt"
          />
          <SettingItem
            title="Data Collection"
            description="Allow collection of usage data to improve the app"
            value={dataCollection}
            onValueChange={setDataCollection}
            icon="database"
          />
          <SettingItem
            title="Personalized Ads"
            description="Show personalized advertisements"
            value={personalizedAds}
            onValueChange={setPersonalizedAds}
            icon="ad"
          />
          <SettingItem
            title="Location Tracking"
            description="Allow the app to track your location for nearby features"
            value={locationTracking}
            onValueChange={setLocationTracking}
            icon="map-marker"
          />
        </View>
      </View>

      <View style={styles.section}>
        <Text style={styles.sectionTitle}>Account</Text>
        <View style={styles.card}>
          <TouchableOpacity style={styles.menuItem}>
            <FontAwesome name="lock" size={24} color="#007AFF" />
            <Text style={styles.menuText}>Change Password</Text>
            <FontAwesome name="chevron-right" size={16} color="#666" />
          </TouchableOpacity>
          <TouchableOpacity style={styles.menuItem}>
            <FontAwesome name="download" size={24} color="#007AFF" />
            <Text style={styles.menuText}>Download My Data</Text>
            <FontAwesome name="chevron-right" size={16} color="#666" />
          </TouchableOpacity>
          <TouchableOpacity style={styles.menuItem}>
            <FontAwesome name="trash" size={24} color="#FF3B30" />
            <Text style={[styles.menuText, styles.dangerText]}>Delete Account</Text>
          </TouchableOpacity>
          <TouchableOpacity style={styles.menuItem}>
            <FontAwesome name="sign-out" size={24} color="#FF3B30" />
            <Text style={[styles.menuText, styles.dangerText]}>Logout</Text>
          </TouchableOpacity>
        </View>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f8f8f8',
  },
  section: {
    padding: 20,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 15,
  },
  card: {
    backgroundColor: '#fff',
    borderRadius: 10,
    padding: 15,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 2,
  },
  profileHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 15,
  },
  avatar: {
    width: 60,
    height: 60,
    borderRadius: 30,
    backgroundColor: '#f0f0f0',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 15,
  },
  profileInfo: {
    flex: 1,
  },
  profileName: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
  },
  profileEmail: {
    fontSize: 14,
    color: '#666',
    marginTop: 2,
  },
  button: {
    backgroundColor: '#007AFF',
    padding: 12,
    borderRadius: 8,
    alignItems: 'center',
  },
  buttonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: '600',
  },
  menuItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#f0f0f0',
  },
  menuTextContainer: {
    flex: 1,
    marginLeft: 15,
  },
  menuText: {
    fontSize: 16,
    color: '#333',
  },
  menuDescription: {
    fontSize: 14,
    color: '#666',
    marginTop: 2,
  },
  dangerText: {
    color: '#FF3B30',
  },
}); 