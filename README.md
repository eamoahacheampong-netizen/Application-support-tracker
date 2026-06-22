# App Support Tracker (SRE Technical Assessment)

## Overview
This application is a containerized Internal Daily Activities & Support Tracker. It provides a secure, isolated environment for Application Support Engineers to log system issues, track resolution states, and maintain chronological troubleshooting logs.

## Architectural & Infrastructure Choices

As an SRE, the primary focus of this build was **reliability, portability, and environment parity**. The application utilizes modern containerization and infrastructure-as-code principles.

### 1. Containerization (Docker)
* **Implementation:** The application is fully containerized using Docker and Laravel Sail.
* **Justification:** Defining the infrastructure (PHP, MySQL, Redis) in containers eliminates local dependency conflicts. The environment is perfectly reproducible across Dev, Staging, and Production deployments without requiring host OS modifications.

### 2. Database Management (Migrations)
* **Implementation:** Database schemas and relationships are managed strictly via code (Migrations).
* **Justification:** Treating the database schema as code ensures infrastructure is version-controlled, auditable, and easily reversible (`down()` functions) in the event of a critical deployment failure.

### 3. State Management & Data Integrity
* **Implementation:** State-based UI and strict Controller validation.
* **Justification:** The application utilizes an impact matrix (Low, Medium, High) and enforces strict state control on troubleshooting logs. Engineers cannot log steps on "Pending" tickets (forcing them to claim the ticket via "In Progress"), and logs are permanently archived and locked once marked "Completed."

## Core Features Implemented
- [x] **CRUD Operations:** Users can input, view, and update daily support activities.
- [x] **Status Lifecycle Management:** Automated tracking of ticket states (Pending ➡️ In Progress ➡️ Completed).
- [x] **Automated Metadata:** System securely captures authenticated User IDs and timestamps for every action.
- [x] **Metrics Dashboard:** Real-time calculation of Total Volume, Active Queue, and Resolved tickets.
- [x] **Internal Audit/Troubleshooting Log:** Threaded, timestamped comments chained to specific tickets and engineers.
- [x] **Security:** Protected by full user authentication, session management, and backend data validation.

## Local Deployment Instructions

To start up this environment locally, ensure Docker Desktop is running, then execute the following commands in the project root:

1. **Start the containerized environment:**
   ```bash
   ./vendor/bin/sail up -d
   ```
2. **Provision the database tables:**
   ```bash
   ./vendor/bin/sail artisan migrate
   ```
3. **Access the application:**
   Navigate to `http://localhost` in your web browser. Register a new user account to access the secure dashboard.

## Teardown
To safely halt the containers and preserve resources:
```bash
./vendor/bin/sail stop
```
