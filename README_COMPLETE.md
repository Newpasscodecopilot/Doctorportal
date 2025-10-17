# Doctor Patient Portal - PHP MySQL Application

A comprehensive web-based medical appointment management system built with PHP and MySQL.

## 🏥 Features

### Multi-User System
- **Admin Panel**: Manage doctors, patients, and appointments
- **Doctor Dashboard**: View appointments, manage profile, communicate with patients
- **Patient Portal**: Book appointments, view medical history, send messages

### Core Functionality
- **User Authentication**: Secure login/registration with role-based access
- **Appointment Management**: Schedule, view, and manage medical appointments
- **Messaging System**: Communication between doctors and patients
- **Profile Management**: Update personal information and upload photos
- **Security Features**: Password protection, session management, CSRF protection
- **Feedback System**: Patient feedback and testimonials

### Modern Enhancements
- **Responsive Design**: Mobile-friendly interface
- **Modern UI**: Clean, professional styling with CSS3
- **Security Improvements**: SQL injection prevention, input sanitization
- **Database Abstraction**: Improved database handling with prepared statements
- **Session Management**: Secure session handling with timeout

## 🚀 Installation & Setup

### Prerequisites
- PHP 8.4+
- MySQL 8.0+
- Apache Web Server
- Web browser

### Quick Setup
1. **Clone/Download** the application files
2. **Start Services**:
   ```bash
   sudo service apache2 start
   sudo service mysql start
   ```

3. **Setup Database**:
   ```bash
   mysql -u root -p
   CREATE DATABASE dpp;
   USE dpp;
   SOURCE sqlfiles/dpp.sql;
   ```

4. **Configure Database Connection**:
   Edit `includes/conn.php` with your database credentials

5. **Setup Demo Data**:
   ```bash
   php setup_demo_data.php
   ```

6. **Access Application**:
   Open `http://localhost/index.php` in your browser

## 👥 Demo Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@test.com | 123456 |
| Doctor | doctor@demo.com | demo123 |
| Patient | patient@demo.com | demo123 |

## 📁 Project Structure

```
├── config/                 # Configuration files
│   ├── config.php         # Application configuration
│   └── database.php       # Database class
├── models/                # Data models
│   ├── User.php          # User authentication
│   ├── Doctor.php        # Doctor operations
│   └── Patient.php       # Patient operations
├── includes/              # Core includes
│   ├── conn.php          # Database connection
│   ├── Security.php      # Security utilities
│   └── Session.php       # Session management
├── assets/               # Static assets
│   └── css/
│       └── modern-dashboard.css
├── css/                  # Bootstrap & UI styles
├── js/                   # JavaScript files
├── img/                  # Images and media
├── sqlfiles/             # Database schema
│   └── dpp.sql
├── doctor/               # Doctor file uploads
├── patient/              # Patient file uploads
└── *.php                 # Application pages
```

## 🔧 Key Files

### Main Application Pages
- `index.php` - Homepage with login/registration
- `doctor.php` - Doctor dashboard
- `patient.php` - Patient dashboard  
- `adminpanel.php` - Admin control panel
- `doclist.php` - Doctor directory
- `makeap.php` - Appointment booking

### Authentication & Security
- `dp.php` - Registration processing
- `logout.php` - Session termination
- `verify.php` - Account verification

### Messaging System
- `sendmsg.php` - Send messages
- `rmsg.php` - Received messages
- `rmsg2.php` - Message management

## 🛡️ Security Features

### Implemented Security Measures
- **SQL Injection Prevention**: Prepared statements and input sanitization
- **XSS Protection**: HTML entity encoding for user inputs
- **CSRF Protection**: Token-based form validation
- **Session Security**: Secure session management with timeouts
- **File Upload Validation**: Type and size restrictions
- **Password Security**: Bcrypt hashing (in new models)

### Security Classes
- `Security.php`: Input validation, CSRF tokens, file upload security
- `Session.php`: Secure session management with flash messages

## 📊 Database Schema

### Core Tables
- **admin**: System administrators
- **doctor**: Medical professionals  
- **patient**: Patients/users
- **appointments**: Medical appointments
- **feedback**: Patient feedback
- **sentmsg/rmsg**: Messaging system

### Key Relationships
- Appointments link doctors and patients
- Messages connect users bidirectionally
- Feedback associates patients with doctors

## 🎨 Modern UI Features

### Styling Enhancements
- **Modern CSS**: Clean, professional design with CSS3
- **Responsive Layout**: Mobile-first approach
- **Interactive Elements**: Hover effects and animations
- **Color Scheme**: Professional medical theme
- **Typography**: Clean, readable fonts

### UI Components
- Modern cards with shadows and hover effects
- Gradient buttons with animations
- Responsive navigation
- Professional forms with validation styling
- Statistics dashboard with icons

## 🔄 Workflow

### Patient Journey
1. **Registration**: Create account with personal details
2. **Login**: Access patient dashboard
3. **Browse Doctors**: View verified medical professionals
4. **Book Appointment**: Schedule medical consultation
5. **Manage Appointments**: View, modify, or cancel bookings
6. **Communication**: Message doctors directly
7. **Feedback**: Rate and review medical services

### Doctor Journey
1. **Registration**: Submit credentials for verification
2. **Verification**: Admin approves doctor accounts
3. **Dashboard Access**: Manage appointments and profile
4. **Patient Communication**: Respond to messages
5. **Appointment Management**: View and update appointments
6. **Profile Management**: Update availability and information

### Admin Functions
1. **User Management**: Approve/reject doctor registrations
2. **System Monitoring**: View statistics and activity
3. **Content Management**: Manage system content
4. **Support**: Handle user issues and feedback

## 🚀 Advanced Features

### Enhanced Models (New)
- **Object-Oriented Design**: Clean, maintainable code structure
- **Database Abstraction**: Simplified database operations
- **Error Handling**: Comprehensive error management
- **Validation**: Input validation and sanitization

### Configuration Management
- **Centralized Config**: Single configuration file
- **Environment Settings**: Easy deployment configuration
- **Security Settings**: Configurable security parameters

## 📱 Mobile Responsiveness

The application is fully responsive and works seamlessly on:
- Desktop computers
- Tablets
- Mobile phones
- Various screen sizes and orientations

## 🔧 Customization

### Theming
- Modify `assets/css/modern-dashboard.css` for styling changes
- Update color variables in CSS root for theme changes
- Customize Bootstrap components in existing CSS files

### Configuration
- Database settings in `config/config.php`
- Security parameters in `includes/Security.php`
- File upload limits and types in configuration

### Extensions
- Add new user roles by extending User model
- Implement additional features using existing architecture
- Integrate third-party services via configuration

## 🐛 Troubleshooting

### Common Issues
1. **Database Connection**: Check credentials in `includes/conn.php`
2. **File Permissions**: Ensure web server can write to upload directories
3. **MySQL Socket**: Fix socket permissions if connection fails
4. **PHP Extensions**: Verify mysqli extension is loaded

### Debug Mode
Enable debugging by setting in `config/config.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 📈 Performance Optimization

### Implemented Optimizations
- **Database Indexing**: Proper indexes on frequently queried fields
- **Prepared Statements**: Efficient query execution
- **Session Management**: Optimized session handling
- **File Organization**: Structured file hierarchy

### Recommendations
- Enable PHP OPcache for production
- Use database connection pooling
- Implement caching for frequently accessed data
- Optimize images and static assets

## 🔮 Future Enhancements

### Planned Features
- **Email Notifications**: Appointment reminders and confirmations
- **SMS Integration**: Text message notifications
- **Payment Gateway**: Online payment processing
- **Telemedicine**: Video consultation features
- **Mobile App**: Native mobile applications
- **API Development**: RESTful API for third-party integrations

### Technical Improvements
- **Framework Migration**: Consider Laravel or CodeIgniter
- **Frontend Framework**: React or Vue.js integration
- **Microservices**: Service-oriented architecture
- **Cloud Deployment**: AWS/Azure deployment guides

## 📄 License

This project is open-source and available under the MIT License.

## 👨‍💻 Development

### Contributing
1. Fork the repository
2. Create feature branches
3. Follow coding standards
4. Submit pull requests

### Code Standards
- PSR-4 autoloading for new classes
- Consistent naming conventions
- Comprehensive error handling
- Security-first development

## 📞 Support

For issues, questions, or contributions:
- Check existing documentation
- Review troubleshooting section
- Create detailed issue reports
- Follow security reporting guidelines

---

**Doctor Patient Portal** - Bridging the gap between healthcare providers and patients through technology.