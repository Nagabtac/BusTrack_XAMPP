# BusTrack - Fleet Management System

A comprehensive bus fleet management system with driver and vehicle tracking capabilities.

## Features

- **Dashboard**: Real-time statistics and overview
- **Bus Management**: Add, edit, delete buses with full CRUD operations
- **Driver Management**: Manage driver information and assignments
- **Live Tracking**: GPS tracking interface (ready for integration)
- **Reports**: Fleet analytics and reporting

## Installation

1. **Database Setup**
   ```sql
   -- Create database
   CREATE DATABASE bustrack;
   
   -- Import schemas
   mysql -u root -p bustrack < create__userSchema.sql
   mysql -u root -p bustrack < create_bus_driver_schema.sql
   ```

2. **Configure Database Connection**
   - Update `db.php` with your database credentials
   - Default settings: localhost, root, no password, database: bustrack

3. **File Structure**
   ```
   /
   ├── dashboard.php          # Main dashboard
   ├── Auth.php              # Authentication
   ├── db.php                # Database connection
   ├── logout.php            # Logout handler
   ├── api/
   │   ├── buses.php         # Bus CRUD API
   │   ├── drivers.php       # Driver CRUD API
   │   └── dashboard.php     # Dashboard stats API
   └── *.sql                 # Database schemas
   ```

## Usage

1. **Login**: Access through Auth.php
2. **Dashboard**: View fleet statistics
3. **Manage Buses**: 
   - Add new buses with capacity, route, and driver assignment
   - Edit existing bus information
   - Delete buses (with confirmation)
4. **Manage Drivers**:
   - Add drivers with license and contact information
   - Edit driver details and status
   - Delete drivers (prevents deletion if buses assigned)

## API Endpoints

### Buses
- `GET api/buses.php?action=list` - Get all buses
- `GET api/buses.php?action=get&id=X` - Get specific bus
- `POST api/buses.php?action=create` - Create new bus
- `PUT api/buses.php?action=update&id=X` - Update bus
- `DELETE api/buses.php?action=delete&id=X` - Delete bus

### Drivers
- `GET api/drivers.php?action=list` - Get all drivers
- `GET api/drivers.php?action=get&id=X` - Get specific driver
- `POST api/drivers.php?action=create` - Create new driver
- `PUT api/drivers.php?action=update&id=X` - Update driver
- `DELETE api/drivers.php?action=delete&id=X` - Delete driver

### Dashboard
- `GET api/dashboard.php` - Get dashboard statistics

## Database Schema

### Buses Table
- id, bus_number, bus_name, route, capacity, status, driver_id
- Status: running, stopped, maintenance

### Drivers Table  
- id, name, license_number, phone, email, status
- Status: active, inactive, on_leave

## Security Features

- Session-based authentication
- SQL injection prevention with prepared statements
- Input validation and sanitization
- Foreign key constraints for data integrity

## Browser Compatibility

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Responsive design for mobile and desktop
- No external dependencies required