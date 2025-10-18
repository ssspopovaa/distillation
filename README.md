# Distillery Control System

A PHP-based control system for a distillery apparatus, designed to manage and monitor the distillation process. The system supports both simulation mode (using predefined data) and real-world hardware integration, with configurable storage backends (Redis) and a modular architecture for extensibility.

## Features
- **Modular Components**: Manages heater, mash tank, and condenser with separate classes.
- **Flexible Data Sources**: Supports simulation (JSON data) and real sensor (neet to be implemented) data via a pluggable `DataUpdaterInterface`.
- **Configurable Storage**: Persists state in Redis, abstracted via `StateRepositoryInterface`.
- **Real-time Monitoring**: A separate CLI script logs system state continuously.
- **Config-driven**: All thresholds and settings are defined in a single configuration file.

## Prerequisites
- PHP >= 8.2
- Composer
- Redis (optional, for `redis` storage type)

## Installation
### Using Docker (Recommended)
1. Clone the repository or copy the project files to a directory:
   ```bash
   git clone git@github.com:ssspopovaa/distillation.git distiller
   cd distiller
   ```
2. Start Docker containers:
   ```bash
   docker compose up --build -d
   ```
3. Install dependencies
   ```bash
   docker exec -it distillation_app composer install
   ```
4. Ensure Redis is running if using redis storage (default in config.php).

## Configuration
### Edit config/config.php to customize the system:

- Thresholds: Set heatOnThreshold, heatOffThreshold, stopAlcoholThreshold, stopMashThreshold for heater control and system shutdown conditions.
- Cycle Timing: Adjust cycleSleepSeconds for the control loop interval.
- Storage: Choose storageType (redis) and configure storageConfig (e.g., Redis host/port).
- Mode: Set simulationMode to true for simulation or false for real hardware.
- Simulation Data: Specify simDataFile path in updaterConfig for simulation mode.

## Usage
1. Run the Control System
   ```bash
   php run.php
   ```
   This starts the main control loop, which reads sensor data (simulated or real), manages the heater based on temperature thresholds, checks stop conditions (alcohol content or mash level), and persists state to the configured storage.
2. Run the Monitoring System (in a separate terminal)
   ```bash
   php monitor.php
   ```
   This continuously logs the system state (heater, mash tank, condenser) to the console. Monitoring persists after the system stops, displaying the last known state with a "System Stopped" message.

## License

MIT License
