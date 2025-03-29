# Trilogy V2

A monorepo containing the Trilogy mobile app and admin panel, built with Expo, Next.js, and Convex.

## Project Structure

```
v2/
├── apps/
│   ├── mobile/     # Expo mobile app
│   └── web/        # Next.js admin panel and website
├── packages/
│   ├── shared/     # Shared Convex backend and types
│   └── ui/         # Shared UI components
```

## Prerequisites

- Node.js 18 or later
- Yarn 4.1.1
- Expo CLI (`npm install -g expo-cli`)
- iOS development environment (for iOS development)
- Android development environment (for Android development)

## Setup

1. Install dependencies:
   ```bash
   yarn install
   ```

2. Set up Convex:
   - Create a new Convex project at https://dashboard.convex.dev
   - Copy your deployment URL
   - Create a `.env` file in the root directory with:
     ```
     CONVEX_URL=your_deployment_url
     ```

3. Start the development servers:

   For the web app:
   ```bash
   yarn workspace @trilogy/web dev
   ```

   For the mobile app:
   ```bash
   # Start the Expo development server
   yarn workspace @trilogy/mobile start
   
   # Then:
   # - Press 'i' to open iOS simulator
   # - Press 'a' to open Android emulator
   # - Scan QR code with Expo Go app on your physical device
   ```

## Development

- The shared Convex backend is in `packages/shared/src/convex/`
- The web app is in `apps/web/`
- The mobile app is in `apps/mobile/`
  - Uses Expo Router for navigation
  - Main app structure is in `apps/mobile/app/`

## Building for Production

```bash
# Build all packages and apps
yarn build

# Start the production web server
yarn workspace @trilogy/web start

# Build mobile app
cd apps/mobile
expo build:android  # For Android
expo build:ios      # For iOS
```

## Contributing

1. Create a new branch for your feature
2. Make your changes
3. Submit a pull request

## License

Private - All rights reserved 